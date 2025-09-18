<?php

namespace App\Console\Commands;

use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Console\Command;
use CURLFile;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily backup db';

    public $myName = 'samosavd_db';

    public $token = "7811102143:AAGRxg7qh_M9z4zKoc0xR9rgINZPwkvrpU0";

    public $chat_id = '-1002366360088';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->myName = env('APP_NAME');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        /** @var string $filename */
        $filename = storage_path() . '/app/backup/' . $this->myName . '__' . date("Y_m_d__h_i_s");

//        dd($filename);
        /**
         * Dumping
         */
        echo "Start dumping...";
        $sql_file = $this->export($filename . '.sql');
        echo "Dumping finished.\n";

        /**
         * Zipping
         */
        echo "Start zipping...";
        $zip_file = $this->createZip($sql_file, $filename . '.zip');
        echo "Zipping finished.\n";

        /**
         * Sending via telegram
         */
        echo "Start sending via telegram...";
        $this->sendViaTelegram($zip_file);
        echo "Sending finished.\n";


        /**
         * Deleting files
         */
        echo "Start deleting files...";
        unlink($sql_file);
        unlink($zip_file);
        echo "Deleting finished.\n";
    }

    /**
     * @param $filename
     * @return string
     * @throws \Exception
     */
    protected function export($filename)
    {
        $dbName = env('DB_DATABASE');
        $userName = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $dump = new Mysqldump("mysql:host=127.0.0.1;dbname={$dbName}", "{$userName}", "{$password}");
        $dump->start($filename);
        return $filename;
    }

    /**
     * @param $filename
     * @return void
     */
    protected function sendViaTelegram($filename)
    {
        $url = 'https://api.telegram.org/bot' . $this->token . '/sendDocument?chat_id=' . $this->chat_id;
        $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        $cFile = new CURLFile($filename, $finfo);

        $curl = curl_init();

//        curl_setopt_array($curl, [
//            CURLOPT_URL => $url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_POST => true,
//            CURLOPT_TIMEOUT => 3 * 60, //3 minutes
//            CURLOPT_POSTFIELDS => [
//                "document" => $cFile
//            ],
//        ]);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            "document" => $cFile
        ]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * @param string $file
     * @param string $zip_file
     * @return string
     */
    public function createZip(string $file, string $zip_file)
    {
        $zip = new \ZipArchive();

        if ($zip->open($zip_file, \ZipArchive::CREATE) !== true) {
            echo "Cannot create zip file: {$zip_file}";
        }
        $zip->addFile($file, basename($file));
        if (!$zip->close()) {
            echo "Error in closing zip file: {$zip_file}";
        }
        return $zip_file;
    }
}
