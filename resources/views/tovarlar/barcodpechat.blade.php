@extends('layouts.almas_site')
@section('content')
    <script src="/js/JsBarcode.all.js"></script>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Штрих-код чоп этиш бўлими
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Танлаб олинган товарлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button id="btnPrint" name="btnPrint" class="btn btn-primary btn-sm ms-2"><i
                                                class="fa fa-print"></i> Чоп этиш </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="pechprint" style="max-width: 14%; background: white; margin-left: 0%; ">
                                    @foreach ($arrayid as $arrayi)
                                        <center>
                                            <div style="width: 180px; height: 108px; margin-top: 5px; margin-bottom: 0px; margin-left: 5%;">
                                                <p style="text-align: center; font-size: 9px; font-weight: bold;">
                                                    {{ $arrayi['tur_name'] . ' ' . $arrayi['brend_name'] }}
                                                </p>
                                                <p
                                                    style="text-align: center; font-size: 9px; font-weight: bold; margin-top: -22px;">
                                                    {{ $arrayi['model_name'] }}
                                                </p>

                                                <p style="margin-top: -15px"><img style="width: 160px; height: 70;"
                                                        id="barcode{{ $arrayi['id'] }}"></p>
                                                <script>
                                                    JsBarcode("#barcode{{ $arrayi['id'] }}", "{{ $arrayi['shtrix_kod'] }}");
                                                </script>
                                                <p
                                                    style="text-align: center; font-size: 9px; font-weight: bold; margin-top: -15pt;">
                                                    ID - {{ $arrayi['tmodel_id'] }} -- {{ $arrayi['kun'] }}й
                                                </p>
                                            </div>
                                        </center>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script src="/js/jquery.PrintArea.js"></script>


        <script>
            $("#btnPrint").click(function() {
                var mode = 'iframe';
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("div#pechprint").printArea(options);
            });
        </script>
    @endsection
