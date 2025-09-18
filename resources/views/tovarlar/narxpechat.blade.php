@extends('layouts.almas_site')
@section('content')
    <script src="/js/JsBarcode.all.js"></script>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Танлаб олинган товарларни
                        нархларини чоп этиш бўлими
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
                                <div id="pechprint">
                                    @foreach ($arrayid as $arrayi)
                                        <div class="ml-3"
                                            style="width: 360px; height: 540px; display: inline-block; margin-right: 3px; margin-top: 0px; font-family: sans-serif;">
                                            <style>
                                                * {
                                                    margin: 0;
                                                    padding: 0;
                                                    box-sizing: border-box;
                                                }
                                            </style>
                                            <img style="width: 360px; height: 540px; position: relative; z-index: -9999;"
                                                src="/images/kattanarh.jpg" alt="">

                                            <div style="width: 100%; margin-top: -540px;z-index: 1;">
                                                <h1
                                                    style="text-align: center; z-index: 5; margin-top: 20px; color: #000080; font-weight: bold; font-size: 22px; ">
                                                    SAMO
                                                </h1>
                                                <div style="height: 95px;">

                                                </div>
                                                <div style="height: 70px; width: 100%;">
                                                    <h1
                                                        style="text-align: center; z-index: 5; margin-top: -90px; color: 	#000080; font-weight: bold; font-size: 22px; ">
                                                        {{ $arrayi['tur_name'] . ' ' . $arrayi['brend_name'] }} <br>
                                                        {{ $arrayi['model_name'] }}
                                                    </h1>
                                                </div>
                                                <div style="height: 50px; width: 100%;">
                                                    <h1
                                                        style="text-align: center; z-index: 5; margin-top: 27px; color: 	#000080; font-weight: bold; font-size: 20px; ">
                                                        <?= number_format($arrayi['jamsotnarx'], 0, ',', ' ') ?> сўм
                                                    </h1>
                                                </div>

                                                <div style="padding: 15px 0; margin-top: -4px; display: inline-block;">
                                                    <div style="width: 200px; float: left; padding-left: 30px;">
                                                        <h1
                                                            style="text-align: left; color: #DC143C; font-size: 16px; font-weight: bolder;">
                                                            Олдиндан тўлов
                                                        </h1>
                                                    </div>
                                                    <div style="width: 154px;  float: right;  padding-right: 30px;">
                                                        <h1
                                                            style="text-align: right; color: #DC143C; font-size: 20px; font-weight: bolder;">
                                                            <?= number_format($arrayi['oldindantulov'], 0, ',', ' ') ?> сўм
                                                        </h1>
                                                    </div>
                                                </div>

                                                <div style="padding: 15px 0; margin-top: -23px; display: inline-block;">
                                                    <div style="width: 150px; float: left; padding-left: 32px;">
                                                        <h1
                                                            style="text-align: left; color: #000080; font-size: 16px; font-weight: bolder;">
                                                            12 ойга
                                                        </h1>
                                                    </div>
                                                    <div style="width: 200px;  float: right;  padding-right: 25px;">
                                                        <h1
                                                            style="text-align: right; color: #000080; font-size: 20px; font-weight: bolder;">
                                                            <span><?= number_format($arrayi['sotuv_narx12oy'] / 12, 0, ',', ' ') ?>
                                                                сўм</span>
                                                        </h1>
                                                    </div>
                                                </div>
                                                <div style="padding: 15px 0; margin-top: -23px; display: inline-block;">
                                                    <div style="width: 150px; float: left; padding-left: 32px;">
                                                        <h1
                                                            style="text-align: left; color: #000080; font-size: 16px; font-weight: bolder;">
                                                            9 ойга
                                                        </h1>
                                                    </div>
                                                    <div style="width: 200px;  float: right;  padding-right: 25px;">
                                                        <h1
                                                            style="text-align: right; color: #000080; font-size: 20px; font-weight: bolder;">
                                                            <span><?= number_format($arrayi['sotuv_narx9oy'] / 9, 0, ',', ' ') ?>
                                                                сўм</span>
                                                        </h1>
                                                    </div>
                                                </div>
                                                <div style="padding: 15px 0; margin-top: -23px; display: inline-block;">
                                                    <div style="width: 150px; float: left; padding-left: 32px;">
                                                        <h1
                                                            style="text-align: left; color: #000080; font-size: 16px; font-weight: bolder;">
                                                            6 ойга
                                                        </h1>
                                                    </div>
                                                    <div style="width: 200px;  float: right;  padding-right: 25px;">
                                                        <h1
                                                            style="text-align: right; color: #000080; font-size: 20px; font-weight: bolder;">
                                                            <span><?= number_format($arrayi['sotuv_narx6oy'] / 6, 0, ',', ' ') ?>
                                                                сўм</span>
                                                        </h1>
                                                    </div>
                                                </div>
                                                <div style="padding: 15px 0; margin-top: -23px; display: inline-block;">
                                                    <div style="width: 150px; float: left; padding-left: 32px;">
                                                        <h1
                                                            style="text-align: left; color: #000080; font-size: 16px; font-weight: bolder;">
                                                            3 ойга
                                                        </h1>
                                                    </div>
                                                    <div style="width: 200px;  float: right;  padding-right: 25px;">
                                                        <h1
                                                            style="text-align: right; color: #000080; font-size: 20px; font-weight: bolder;">
                                                            <span><?= number_format($arrayi['sotuv_narx3oy'] / 3, 0, ',', ' ') ?>
                                                                сўм</span>
                                                        </h1>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 30px; display: inline-block;">
                                                    <div style="width: 100px; float: left; padding-left: 20px;">
                                                        <h1
                                                            style="text-align: center; color: #000080; font-size: 18px; font-weight: bolder;">
                                                            <?= $arrayi['tmodel_id']?>
                                                        </h1>
                                                    </div>
                                                    <div style="width: 250; float: left;  padding-right: 35px;">
                                                        <h1
                                                            style="text-align: center; color: #000080; font-size: 18px; font-weight: bolder; padding-left: 115px;">
                                                            <?= date('d.m.Y', strtotime($arrayi['kun'])) ?>
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
