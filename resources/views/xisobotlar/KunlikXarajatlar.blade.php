@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Кунлик харажатлар хисоботларини олиш бўлими
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row">
                                <div class="col-xl-3 select_div">
                                    <input type="date" name="boshkun" class="form-control form-control-sm text-center" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-3 select_div">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm text-center"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                <div class="col-xl-3">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value="">Филиал...</option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <style>
                                        #select_div {
                                            width: 150px !important;
                                        }
                                    </style>

                                </div>
                                <div class="col-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="tabpros">
                                    <table class="table table-bordered table-responsive-sm text-center align-middle">
                                        <thead>
                                            <tr class="text-bold text-primary align-middle">
                                                <th>ID</th>
                                                <th>Харажатлар</th>
                                                <th>Нақд</th>
                                                <th>Пластик</th>
                                                <th>Х-р</th>
                                                <th>Сlick</th>
                                                <th>Автотўлов</th>
                                                <th>Жами</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tab1">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>

            // function tabyuklash(model) {
            //     const tab1 = $('#tab1');
            //     let naqd=0;
            //     let pastik=0;
            //     let hr=0;
            //     let click=0;
            //     let avtot=0;
            //     let jsummasi=0;
            //     tab1.empty();
            //     if (model.length > 0) {
            //         model.forEach(item => {
            //             const { id, har_name, boshqaharajat1 } = item;
            //             naqd+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_naqd) : 0;
            //             pastik+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_pastik) : 0;
            //             hr+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_hr) : 0;
            //             click+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_click) : 0;
            //             avtot+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_avtot) : 0;
            //             jsummasi+=boshqaharajat1 ? parseFloat(boshqaharajat1.total_summasi) : 0;

            //             const tr = `
            //                 <tr>
            //                     <td>${id}</td>
            //                     <td>${har_name}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_naqd : 0}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_pastik : 0}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_hr : 0}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_click : 0}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_avtot : 0}</td>
            //                     <td>${boshqaharajat1 ? boshqaharajat1.total_summasi : 0}</td>
            //                 </tr>
            //             `;
            //             tab1.append(tr);
            //         });

            //     }
            //     const tr = `
            //         <tr class="fw-bolder">
            //             <td></td>
            //             <td>ЖАМИ</td>
            //             <td>${ naqd }</td>
            //             <td>${ pastik }</td>
            //             <td>${ hr }</td>
            //             <td>${ click }</td>
            //             <td>${ avtot }</td>
            //             <td>${ jsummasi }</td>
            //         </tr>
            //     `;
            //     tab1.append(tr);
            // }

            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#filial").select2({
                    placeholder: "Филиал...",
                });


                $("#yakunkun").val(new Date().toISOString().substring(0, 10));
                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');

                $('#saqlash').on('click', function() {
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    if (boshkun > yakunkun && filial>0) {
                        alert("Кунни киритишла хатолик ёки филиални танланг !!!");
                    } else {
                        $.ajax({
                            url: "{{ route('KunlikXarajatlar.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                            success: function(data) {
                                $("#tab1").html(data);

//                                tabyuklash(response.turharajat);
                            }
                        })
                    }
                })

            })
        </script>
    @endsection
