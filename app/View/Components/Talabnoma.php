<?php

namespace App\View\Components;

use App\Models\filial;
use App\Models\Savdo;
use App\Models\Shartnoma;
use App\Models\Tulovlar;
use App\Models\xissobotoy;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Talabnoma extends Component
{
    public $shartnom;
    public $filial;
    public $mchjName;
    public $clientName;
    public $shartnomaSumma;
    public $grafikTulov;
    public $qarzdorlik;
    public $qoldiqQarz;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $filial)
    {
        $filialModel = filial::find($filial);
        $this->filial = $filialModel;
        $this->mchjName = $filialModel->ytt;

        $this->shartnom = Shartnoma::where('id', $id)->where('filial_id', $filial)->first();

        $oldindantulov = Tulovlar::where('filial_id', $filial)->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnoma_id', $id)->sum('umumiysumma');
        $chegirma = Tulovlar::where('filial_id', $filial)->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnoma_id', $id)->sum('chegirma');
        $tulov = Tulovlar::where('filial_id', $filial)->where('tulovturi', 'Шартнома')->where('status', 'Актив')->where('shartnoma_id', $id)->sum('umumiysumma');

        $savdosumma = Savdo::where('filial_id', $filial)->where('status', 'Шартнома')->where('shartnoma_id', $this->shartnom->id)->sum('msumma');

        $foiz = xissobotoy::where('xis_oy', $this->shartnom->xis_oyi)->value('foiz');
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        if ($this->shartnom->fstatus == 0) {
            $foiz = 0;
        }

        $foiz = (($foiz / 12) * $this->shartnom->muddat);

        $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

        $umumiySumma = $savdosumma - $oldindantulov - $chegirma + $xis_foiz;

        if ($this->shartnom->tug_sana >= date("Y-m-d")) {
            $date11 = new DateTime($this->shartnom->kun);
            $xisKun = date('Y-m', strtotime($xis_oyi)) . '-' . date('d', strtotime($this->shartnom->kun));
            $date22 = new DateTime(date($xisKun));
            $interval = $date11->diff($date22);
            $months = ($interval->y * 12) + $interval->m;

            $joqarzm = ($umumiySumma / $this->shartnom->muddat) * $months - $tulov;
            $prSumma = $joqarzm - ($umumiySumma / $this->shartnom->muddat);

            $tkun = date('Y-m', strtotime($xis_oyi)) . '-' . date('d', strtotime($this->shartnom->t_kun));

            if ($tkun >= date("Y-m-d")) {
                $joqarzm = $prSumma;
            }

            if ($joqarzm < 1000) {
                $joqarzm = 0;
            }

        } else {
            $joqarzm = $umumiySumma - $tulov;
        }

        $this->shartnomaSumma = round($umumiySumma, 0);
        $this->grafikTulov = round($umumiySumma / $this->shartnom->muddat, -2);
        $this->qarzdorlik = round($joqarzm, 0);
        $this->qoldiqQarz = round($umumiySumma - $tulov, 0);

        $this->clientName = $this->shartnom->mijozlar->full_name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.talabnoma');
    }
}
