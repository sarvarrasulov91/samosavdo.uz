<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ifsnop\Mysqldump\Mysqldump;
use CURLFile;
use Throwable;

class DbBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Daily optimized database backup and send to Telegram';

    protected string $appName;
    protected string $token;
    protected string $chatId;

    public function __construct()
    {
        parent::__construct();

        $this->appName = config('app.name');
        $this->token   = config('services.telegram.token');
        $this->chatId  = config('services.telegram.chat_id');
    }

    public function handle(): int
    {
        $backupPath = storage_path('app/backup');

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $baseFile = $backupPath . '/' . $this->appName . '__' . now()->format('Y_m_d__H_i_s');

        $sqlFile = null;
        $zipFile = null;

        try {
            $this->info('🔹 Dump started...');
            $sqlFile = $this->export($baseFile . '.sql');
            $this->info('✅ Dump finished');

            $this->info('🔹 Zipping...');
            $zipFile = $this->createZip($sqlFile, $baseFile . '.zip');
            $this->info('✅ Zip finished');

            $this->info('🔹 Sending to Telegram...');
            $this->sendViaTelegram($zipFile);
            $this->info('✅ Sent successfully');

            return Command::SUCCESS;

        } catch (Throwable $e) {

            logger()->error('DB Backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('❌ Backup failed: ' . $e->getMessage());
            return Command::FAILURE;

        } finally {
            @unlink($sqlFile);
            @unlink($zipFile);
        }
    }

    /**
     * Export database to SQL
     */
    protected function export(string $filename): string
    {
        $dump = new Mysqldump(
            "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            [
                'single-transaction' => true,
                'lock-tables' => false,
                'add-drop-table' => true,
                'skip-triggers' => false,
                'skip-comments' => true,
            ]
        );

        $dump->start($filename);

        return $filename;
    }

    /**
     * Create ZIP archive
     */
    protected function createZip(string $file, string $zipFile): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            throw new \Exception("Zip yaratib bo‘lmadi: {$zipFile}");
        }

        $zip->addFile($file, basename($file));
        $zip->close();

        return $zipFile;
    }

    /**
     * Send ZIP via Telegram
     */
    protected function sendViaTelegram(string $file): void
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendDocument";

        $caption = "📦 <b>DB Backup</b>\n"
            . "📛 App: {$this->appName}\n"
            . "🕒 Time: " . now()->toDateTimeString();

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_POSTFIELDS => [
                'chat_id' => $this->chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML',
                'document' => new CURLFile($file),
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception('Telegram error: ' . curl_error($curl));
        }

        curl_close($curl);
    }
}
