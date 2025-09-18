@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Мижозларни қарздорликларини
                        кўриб бўлими
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
                                    <h5 class="bc-title text-primary">
                                        Шартномалар рўйхати
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                                <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i class="fa fa-file-excel"></i> Excel </button>    
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div class="table-responsive" id="tabpros" style="overflow: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="pechat" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 60%; font-size: 15px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-pechat" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="kvitpechat">
                    </div>
                    <div class="modal-footer">
                        <!-- <button onclick="printcertificate()" class="btn btn-primary"><i class="fa fa-print"></i>Чоп этиш</button> -->
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>
        

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('Portfel.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }

            $(document).ready(function() {
                tabyuklash();
                
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "Portfel"
                        });
                    })
                });
            })
            
            $(document).on('click', '#kivitpechat', function() {
            var id = $(this).data('id');
            var fio = $(this).data('fio');
            var savdounix_id = $('#savdounix_id').val();
            $('#modal-title-pechat').html(id + ' - ' + fio);
            $.ajax({
                url: "{{ route('Portfel.index') }}/" + id,
                method: "GET",
                data: {
                    id: id,
                    fio: fio,
                    savdounix_id: savdounix_id
                },
                success: function(data) {
                    $("#kvitpechat").html(data);
                }
            })
        });
        </script>
    @endsection
