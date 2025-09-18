<?php

// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TovarTurController;
use App\Http\Controllers\TovarBrendController;
use App\Http\Controllers\TovarModelController;
use App\Http\Controllers\PastavshikController;
use App\Http\Controllers\KirimTovarController;
use App\Http\Controllers\XisobotTaminotchiController;
use App\Http\Controllers\BarcodTaminotchiController;
use App\Http\Controllers\KirimTovarOmborController;
use App\Http\Controllers\FondController;
use App\Http\Controllers\FondSavdoController;
use App\Http\Controllers\NewMijozController;
use App\Http\Controllers\SavdolarController;
use App\Http\Controllers\BoshqaXarajatlarController;
use App\Http\Controllers\NaqdSavdoController;
use App\Http\Controllers\ShartnomalarController;
use App\Http\Controllers\XisobotKunlikController;
use App\Http\Controllers\ShartnomaTulovController;
use App\Http\Controllers\DasturBoshqaruvController;
use App\Http\Controllers\TovarlarJamiController;
use App\Http\Controllers\TovarlarNarxController;
use App\Http\Controllers\SavdoPuliController;
use App\Http\Controllers\TovarQarzController;
use App\Http\Controllers\YopilganShartnomalarController;
use App\Http\Controllers\OfficeKassaController;
use App\Http\Controllers\OfficeKassaChiqimTaminotController;
use App\Http\Controllers\OfficeKassaChiqimBoshController;
use App\Http\Controllers\TovarTaminotQaytarishController;
use App\Http\Controllers\TovarXatlovController;
use App\Http\Controllers\SHartnomaOfficeController;
use App\Http\Controllers\ShartnomaEditController;
use App\Http\Controllers\TovarlarJamiOfficeController;
use App\Http\Controllers\TovarAlmashishController;
use App\Http\Controllers\BonusSavdoController;
use App\Http\Controllers\ChegirmaController;
use App\Http\Controllers\NaqdSavdoOfficeController;
use App\Http\Controllers\FondSavdoOfficeController;
use App\Http\Controllers\PortfelController;
use App\Http\Controllers\MfyBriktirishOfficeController;
use App\Http\Controllers\TovarQoldigiOfficeController;
use App\Http\Controllers\SavdolarTahliliController;
use App\Http\Controllers\OfficeKassaKirimController;
use App\Http\Controllers\OfficeKassaValyutaAlmashishController;
use App\Http\Controllers\XisobotXarajatlarController;
use App\Http\Controllers\XisobotOfficeXarajatlarController;
use App\Http\Controllers\OfficePortfelController;
use App\Http\Controllers\SHartTahlilOfficeController;
use App\Http\Controllers\TugilganKunController;
use App\Http\Controllers\IPNazoratiController;
use App\Http\Controllers\DasturNazoratiController;
use App\Http\Controllers\AsosiyVositaController;
use App\Http\Controllers\ChiqimTovarOmborController;
use App\Http\Controllers\MijozTaxlilController;
use App\Http\Controllers\OfficeIzmenitTulovController;
use App\Http\Controllers\OfficeGrafikTulovController;
use App\Http\Controllers\OfficeUdalitTulovController;
use App\Http\Controllers\OfficeBronTulovController;
use App\Http\Controllers\OfficeAvtoTulovController;
use App\Http\Controllers\OfficeJamiTulovlarController;
use App\Http\Controllers\KunlikTaxlilController;
use App\Http\Controllers\BonusTurController;
use App\Http\Controllers\XisobotInvestorController;
use App\Http\Controllers\TovarlarSotilmaganOfficeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BlackListClientController;
use App\Http\Controllers\ShartnomaNewController;
use App\Http\Controllers\PortfelExpiredController;
use App\Http\Controllers\TovarXisobotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
        Route::resource('user', UserController::class);
        Route::resource('tur', TovarTurController::class);
        Route::resource('brend', TovarBrendController::class);
        Route::resource('model', TovarModelController::class);
        Route::resource('pastavshik', PastavshikController::class);
        Route::resource('kirimtovar', KirimTovarController::class);
        Route::post('/filbaza', [KirimTovarController::class, 'filbaza'])->name('filbaza');
        Route::post('/sungimodel', [KirimTovarController::class, 'sungimodel'])->name('sungimodel');
        Route::resource('xisobottaminot', XisobotTaminotchiController::class);
        Route::post('/storekunlar', [XisobotTaminotchiController::class, 'storekunlar'])->name('storekunlar');
        Route::post('/storename', [XisobotTaminotchiController::class, 'storename'])->name('storename');
        Route::resource('barcod', BarcodTaminotchiController::class);
        Route::resource('omborkirim', KirimTovarOmborController::class);
        Route::resource('newfond', FondController::class);
        Route::resource('fondsavdo', FondSavdoController::class);
        Route::resource('newmijoz', NewMijozController::class);
        Route::resource('savdolar', SavdolarController::class);
        Route::resource('boshqaxarajat', BoshqaXarajatlarController::class);
        Route::resource('naqdsavdo', NaqdSavdoController::class);
        Route::resource('shartnomalar', ShartnomalarController::class);
        Route::resource('kunlik', XisobotKunlikController::class);
        Route::resource('shartnomatulov', ShartnomaTulovController::class);
        Route::resource('bashqaruv', DasturBoshqaruvController::class);
        Route::resource('jamitovarlar', TovarlarJamiController::class);
        Route::resource('narx', TovarlarNarxController::class);
        Route::resource('savdopuli', SavdoPuliController::class);
        Route::resource('tovarqarz', TovarQarzController::class);
        Route::resource('yopilganshartnomalar', YopilganShartnomalarController::class);

        Route::resource('officekassa', OfficeKassaController::class);
        Route::resource('officekassachiqtamin', OfficeKassaChiqimTaminotController::class);
        Route::resource('officekassachiqbosh', OfficeKassaChiqimBoshController::class);
        Route::resource('OfficeKassaKirim', OfficeKassaKirimController::class);
        Route::resource('ValyutaAlmashish', OfficeKassaValyutaAlmashishController::class);

        Route::resource('tovartaminotqaytarish', TovarTaminotQaytarishController::class);
        Route::resource('xatlov', TovarXatlovController::class);
        Route::resource('OfficeSHartnoma', SHartnomaOfficeController::class);
        Route::resource('ShartnomaEdit', ShartnomaEditController::class);
        Route::resource('OfficeJamiTovarlar', TovarlarJamiOfficeController::class);
        Route::resource('tovaralmashish', TovarAlmashishController::class);
        Route::resource('bonus', BonusSavdoController::class);
        Route::resource('chegirma', ChegirmaController::class);
        Route::resource('NaqdSavdoOffice', NaqdSavdoOfficeController::class);
        Route::resource('FondSavdoOffice', FondSavdoOfficeController::class);
        Route::resource('Portfel', PortfelController::class);
        Route::resource('MfyBriktirish', MfyBriktirishOfficeController::class);
        Route::resource('TovarQoldigi', TovarQoldigiOfficeController::class);
        Route::resource('SavdolarTahlili', SavdolarTahliliController::class);
        Route::resource('KunlikXarajatlar', XisobotXarajatlarController::class);
        Route::resource('KunlikOfficeXarajatlar', XisobotOfficeXarajatlarController::class);
        Route::resource('OfficePortfel', OfficePortfelController::class);
        Route::resource('SHartTahlil', SHartTahlilOfficeController::class);
        Route::resource('TugilganKun', TugilganKunController::class);
        Route::resource('IPNazorati', IPNazoratiController::class);
        Route::resource('DasturNazorati', DasturNazoratiController::class);
        Route::resource('AsosiyVosita', AsosiyVositaController::class);
        Route::resource('chiqimtovarombor', ChiqimTovarOmborController::class);
        Route::resource('mijoztaxlil', MijozTaxlilController::class);
        Route::resource('officeizmenittulov', OfficeIzmenitTulovController::class);
        Route::resource('officegrafiktulov', OfficeGrafikTulovController::class);
        Route::resource('officeudalittulov', OfficeUdalitTulovController::class);
        Route::resource('officebrontulov', OfficeBronTulovController::class);
        Route::resource('officeavtotulov', OfficeAvtoTulovController::class);
        Route::resource('OfficeJamiTulovlar', OfficeJamiTulovlarController::class);
        Route::resource('kunliktaxlil', KunlikTaxlilController::class);
        Route::resource('BonusTur', BonusTurController::class);
        Route::resource('XisobotInvestor', XisobotInvestorController::class);
        Route::resource('OfficeSotilmaganTovarlar', TovarlarSotilmaganOfficeController::class);
        Route::resource('clients', ClientController::class);
        Route::get('clients/showClient/{id}', [ClientController::class, 'showClient'])->name('showClient');
        Route::get('clients/blackListClient/{client}', [ClientController::class, 'blackListClient'])->name('blackListClient');
        Route::resource('BlackListClient', BlackListClientController::class);
        Route::resource('ShartnomaNew', ShartnomaNewController::class);
        Route::resource('PortfelExpired', PortfelExpiredController::class);
        Route::resource('TovarXisobot', TovarXisobotController::class);

});
require __DIR__ . '/auth.php';
