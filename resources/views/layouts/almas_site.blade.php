<!DOCTYPE html>
<html lang="en">

@php
    $xis_oyi = \App\Models\xissobotoy::latest('id')->value('xis_oy');
    $lavozim_name = App\Models\lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
    $filial_name = App\Models\filial::where('id', Auth::user()->filial_id)->value('fil_name');
    $user = Auth::user()->lavozim_id;
@endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- PAGE TITLE HERE -->
    <title>SAMO</title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="/images/favicon.png">
    <link href="/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.css"> --}}
    <link href="/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" rel="stylesheet"> --}}
    <link href="/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/toastr/css/toastr.min.css">
    <link rel="stylesheet" href="/vendor/select2/css/select2.min.css">
    <!-- tagify-css -->
    <link href="/vendor/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Style css -->
    <link rel="stylesheet" href="/vendor/select2/css/select2.min.css">
    <link href="/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black"
    data-headerbg="color_1">

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <img src="/images/favicon.png" class="avatar avatar-md" alt=" ">
                <span class="brand-title text-white">SAMO</span>

                {{-- <a href="{{ route('dashboard') }}" class="brand-logo">


            </a> --}}

            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="input-group">

                                <input type="text" id="qidirish" class="form-control" placeholder="Qidiruv" style="width: 300px">
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item ps-3">
                                <div class="dropdown header-profile2">
                                    <a class="nav-link" aria-expanded="false">
                                        <div class="header-info">
                                            <p>Хисобот ойи</p>
                                            <h6>
                                                {{ $xis_oyi }}
                                            </h6>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item ps-3">
                                <div class="dropdown header-profile2">
                                    <a class="nav-link" aria-expanded="false">
                                        <div class="header-info">
                                            <p>Филиал</p>
                                            <h6>
                                                {{ $filial_name }}
                                            </h6>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item ps-3">
                                <div class="dropdown header-profile2">
                                    <a class="nav-link" aria-expanded="false">
                                        <div class="header-info">
                                            <p>Лавозим</p>
                                            <h6>
                                                {{ $lavozim_name }}
                                            </h6>
                                        </div>
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link " href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.8067 7.62358L20.1842 6.54349C19.6577 5.62957 18.4907 5.31429 17.5755 5.83869V5.83869C17.1399 6.09531 16.6201 6.16812 16.1307 6.04106C15.6413 5.91399 15.2226 5.59749 14.9668 5.16134C14.8023 4.88412 14.7139 4.56836 14.7105 4.24601V4.24601C14.7254 3.72919 14.5304 3.22837 14.17 2.85764C13.8096 2.48691 13.3145 2.27783 12.7975 2.27805H11.5435C11.037 2.27804 10.5513 2.47988 10.194 2.83891C9.83669 3.19795 9.63717 3.68456 9.63961 4.19109V4.19109C9.6246 5.23689 8.77248 6.07678 7.72657 6.07667C7.40421 6.07332 7.08846 5.98491 6.81123 5.82038V5.82038C5.89606 5.29598 4.72911 5.61126 4.20254 6.52519L3.53435 7.62358C3.00841 8.53636 3.3194 9.70258 4.23 10.2323V10.2323C4.8219 10.574 5.18653 11.2056 5.18653 11.889C5.18653 12.5725 4.8219 13.204 4.23 13.5458V13.5458C3.32056 14.0719 3.00923 15.2353 3.53435 16.1453V16.1453L4.16593 17.2346C4.41265 17.6798 4.8266 18.0083 5.31619 18.1474C5.80578 18.2866 6.33064 18.2249 6.77462 17.976V17.976C7.21108 17.7213 7.73119 17.6515 8.21934 17.7822C8.70749 17.9128 9.12324 18.233 9.37416 18.6716C9.5387 18.9489 9.62711 19.2646 9.63046 19.587V19.587C9.63046 20.6435 10.487 21.5 11.5435 21.5H12.7975C13.8505 21.5 14.7055 20.6491 14.7105 19.5961V19.5961C14.7081 19.088 14.9089 18.6 15.2682 18.2407C15.6275 17.8814 16.1155 17.6806 16.6236 17.6831C16.9452 17.6917 17.2596 17.7797 17.5389 17.9394V17.9394C18.4517 18.4653 19.6179 18.1543 20.1476 17.2437V17.2437L20.8067 16.1453C21.0618 15.7075 21.1318 15.186 21.0012 14.6963C20.8706 14.2067 20.5502 13.7893 20.111 13.5366V13.5366C19.6718 13.2839 19.3514 12.8665 19.2208 12.3769C19.0902 11.8873 19.1603 11.3658 19.4154 10.9279C19.5812 10.6383 19.8214 10.3982 20.111 10.2323V10.2323C21.0161 9.70286 21.3264 8.54346 20.8067 7.63274V7.63274V7.62358Z"
                                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12.1751" cy="11.889" r="2.63616" stroke="white"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                </div>
                            </li>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="javascript:void(0);" role="button"
                                    data-bs-toggle="dropdown">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z"
                                            stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path
                                            d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21"
                                            stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                </div>
                            </li>

                            <li class="nav-item ps-3">
                                <div class="dropdown header-profile2">
                                    <a class="nav-link" href="javascript:void(0);" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="header-info2 d-flex align-items-center">
                                            <div class="header-media">
                                                <img src="/images/1.png" alt="">
                                            </div>
                                            <div class="header-info">
                                                <h6>{{ Auth::user()->name }}</h6>
                                                <p>{{ Auth::user()->email }}</p>
                                            </div>

                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <div class="card border-0 mb-0">
                                            <div class="card-body px-0 py-2">
                                                <a href="" class="dropdown-item ai-icon ">
                                                    <svg width="20" height="20" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z"
                                                            stroke="var(--primary)" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94779 16.58 7.40969C16.58 4.8716 14.5229 2.81445 11.9848 2.81445C9.44667 2.81445 7.38857 4.8716 7.38857 7.40969C7.38 9.93922 9.42381 11.9973 11.9524 12.0059H11.9848Z"
                                                            stroke="var(--primary)" stroke-width="1.42857"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <span class="ms-2">Паролни алмаштириш</span>
                                                </a>
                                            </div>
                                            <div class="card-footer px-0 py-2">
                                                <!-- Authentication -->
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <a href="{{ route('logout') }}" class="dropdown-item ai-icon"
                                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                            height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="var(--primary)" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                            <polyline points="16 17 21 12 16 7"></polyline>
                                                            <line x1="21" y1="12" x2="9"
                                                                y2="12"></line>
                                                        </svg>
                                                        <span class="ms-2">Дастурдан чиқиш </span>
                                                    </a>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </li>


                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.8381 11.7317C15.4566 11.7317 15.9757 12.2422 15.8811 12.853C15.3263 16.4463 12.2502 19.1143 8.54009 19.1143C4.43536 19.1143 1.10834 15.7873 1.10834 11.6835C1.10834 8.30245 3.67693 5.15297 6.56878 4.44087C7.19018 4.28745 7.82702 4.72455 7.82702 5.36429C7.82702 9.69868 7.97272 10.8199 8.79579 11.4297C9.61886 12.0396 10.5867 11.7317 14.8381 11.7317Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M18.8848 8.12229C18.934 5.33755 15.5134 0.848777 11.345 0.92597C11.0208 0.93176 10.7612 1.20194 10.7468 1.52518C10.6416 3.81492 10.7834 6.78202 10.8626 8.12711C10.8867 8.54588 11.2157 8.87492 11.6335 8.89904C13.0162 8.97816 16.0914 9.08623 18.3483 8.74465C18.6552 8.69834 18.88 8.43202 18.8848 8.12229Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Тахлил</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('dashboard') }}">Умумий тахлил</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.5 7.49999L10 1.66666L17.5 7.49999V16.6667C17.5 17.1087 17.3244 17.5326 17.0118 17.8452C16.6993 18.1577 16.2754 18.3333 15.8333 18.3333H4.16667C3.72464 18.3333 3.30072 18.1577 2.98816 17.8452C2.67559 17.5326 2.5 17.1087 2.5 16.6667V7.49999Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.5 18.3333V10H12.5V18.3333" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Шартномалар</span>
                        </a>
                        <ul aria-expanded="false">
                            @if (in_array($user, [1]))
                            <li><a href="{{ route('OfficeSHartnoma.index') }}"> Шартномалар </a></li>
                            <li><a href="{{ route('ShartnomaEdit.index') }}"> Шартнома тахрирлаш </a></li>
                            
                            <li><a href="{{ route('OfficePortfel.index') }}"> Офис Портфел </a></li>
                            
                            <li><a href="{{ route('SHartTahlil.index') }}"> Шартномалар тахлили </a></li>
                            <li><a href="{{ route('MfyBriktirish.index') }}"> МФЙ бириктириш </a></li>
                            @endif
                            
                            @if (in_array($user, [2]))
                            <li><a href="{{ route('ShartnomaNew.index') }}"> New Шартнома </a></li>
                            <li><a href="{{ route('shartnomalar.index') }}"> Шартномалар </a></li>
                            
                            <li><a href="{{ route('Portfel.index') }}"> Портфел </a></li>
                            
                            @endif
                            <li><a href="{{ route('PortfelExpired.index') }}"> Портфел Муддати тугаган </a></li>
                            <li><a href="{{ route('yopilganshartnomalar.index') }}"> Ёпилган шартномалар </a></li>
                        </ul>
                    </li>
                    
                    @if (in_array($user, [2]))
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.64111 13.5497L9.38482 9.9837L12.5145 12.4421L15.1995 8.97684"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <ellipse cx="18.3291" cy="3.85021" rx="1.76201" ry="1.76201"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M13.6808 2.86012H7.01867C4.25818 2.86012 2.54651 4.81512 2.54651 7.57561V14.9845C2.54651 17.7449 4.22462 19.6915 7.01867 19.6915H14.9058C17.6663 19.6915 19.3779 17.7449 19.3779 14.9845V8.53213"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Савдо</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('savdolar.index') }}">Савдолар</a></li>
                        </ul>
                    </li>
                    @endif
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.79222 13.9396C12.1738 13.9396 15.0641 14.452 15.0641 16.4989C15.0641 18.5458 12.1931 19.0729 8.79222 19.0729C5.40972 19.0729 2.52039 18.5651 2.52039 16.5172C2.52039 14.4694 5.39047 13.9396 8.79222 13.9396Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.79223 11.0182C6.57206 11.0182 4.77173 9.21874 4.77173 6.99857C4.77173 4.7784 6.57206 2.97898 8.79223 2.97898C11.0115 2.97898 12.8118 4.7784 12.8118 6.99857C12.8201 9.21049 11.0326 11.0099 8.82064 11.0182H8.79223Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M15.1095 9.9748C16.5771 9.76855 17.7073 8.50905 17.7101 6.98464C17.7101 5.48222 16.6147 4.23555 15.1782 3.99997"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M17.0458 13.5045C18.4675 13.7163 19.4603 14.2149 19.4603 15.2416C19.4603 15.9483 18.9928 16.4067 18.2374 16.6936"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <span class="nav-text">Мижозлар</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('clients.index') }}">New Mijoz </a></li>
                            <li><a href="{{ route('newmijoz.index') }}">Мижозларни рўйхатга олиш</a></li>
                            <li><a href="{{ route('mijoztaxlil.index') }}">Мижозлар тахлили</a></li>
                            <li><a href="{{ route('BlackListClient.index') }}">Black List</a></li>
                            @if (in_array($user, [2]))
                            <li><a href="{{ route('TugilganKun.index') }}">Мижозлар туғилган кунлари</a></li>
                            @endif
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10.0122 1.82893L11.6874 5.17545C11.8515 5.50399 12.1684 5.73179 12.5359 5.78451L16.2832 6.32391C17.2091 6.45758 17.5775 7.57968 16.9075 8.22262L14.1977 10.8264C13.9314 11.0825 13.8101 11.4505 13.8731 11.812L14.5126 15.488C14.6701 16.3974 13.7023 17.0911 12.8747 16.6609L9.52545 14.9241C9.1971 14.7537 8.80385 14.7537 8.47455 14.9241L5.12525 16.6609C4.29771 17.0911 3.32986 16.3974 3.48831 15.488L4.12686 11.812C4.18986 11.4505 4.06864 11.0825 3.80233 10.8264L1.09254 8.22262C0.422489 7.57968 0.790922 6.45758 1.71678 6.32391L5.4641 5.78451C5.83158 5.73179 6.14942 5.50399 6.31359 5.17545L7.98776 1.82893C8.40201 1.00148 9.59799 1.00148 10.0122 1.82893Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Омбор</span>
                        </a>
                        <ul aria-expanded="false">
                            @if (in_array($user, [1]))
                            <li><a href="{{ route('kirimtovar.index') }}"> Товарларни рўйхатга олиш</a></li>
                            <li><a href="{{ route('OfficeJamiTovarlar.index') }}"> Жами товарлар офис</a></li>
                            <li><a href="{{ route('tovartaminotqaytarish.index') }}"> Товарларни қайтариш</a></li>
                            <li><a href="{{ route('xatlov.index') }}"> Товарларни хатловдан ўтказиш</a></li>
                            @endif
                            
                            @if (in_array($user, [2]))
                            <li><a href="{{ route('barcod.index') }}"> Шитрих-код чоп этиш </a></li>
                            <li><a href="{{ route('omborkirim.index') }}"> Товарларни кирим қилиб олиш</a></li>
                            <li><a href="{{ route('narx.index') }}"> Нарх чиқариш</a></li>
                            <li><a href="{{ route('tovarqarz.index') }}"> Товар бириктириш </a></li>
                            <li><a href="{{ route('jamitovarlar.index') }}"> Жами товарлар</a></li>
                            <li><a href="{{ route('tovaralmashish.index') }}"> Товарлар алмашиш</a></li>
                            @endif
                    
                            <li><a href="{{ route('OfficeSotilmaganTovarlar.index') }}"> Сотилмаган товарлар</a></li>
                            <li><a href="{{ route('chiqimtovarombor.index') }}"> Чиқим булган товарлар </a></li>
                            <li><a href="{{ route('AsosiyVosita.index') }}"> Асосий восита товарлар</a></li>
                        </ul>
                        
                    </li>
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M18.634 13.4211C18.634 16.7009 16.7007 18.6342 13.4209 18.6342H6.28738C2.99929 18.6342 1.06238 16.7009 1.06238 13.4211V6.27109C1.06238 2.99584 2.26688 1.06259 5.54763 1.06259H7.38096C8.03913 1.06351 8.65879 1.37242 9.05296 1.89951L9.88988 3.01234C10.2859 3.53851 10.9055 3.84834 11.5637 3.84926H14.1579C17.446 3.84926 18.6596 5.52309 18.6596 8.86984L18.634 13.4211Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5.85754 12.2577H13.8646" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Касса</span>
                        </a>
                        <ul aria-expanded="false">
                            @if (in_array($user, [1]))
                            <li><a href="{{ route('officekassa.index') }}"> Касса қолдиғи </a></li>
                            <li><a href="{{ route('OfficeKassaKirim.index') }}"> Кирим касса </a></li>
                            <li><a href="{{ route('officekassachiqtamin.index') }}"> Чиқим таъминотчи </a></li>
                            <li><a href="{{ route('officekassachiqbosh.index') }}"> Чиқим харажатлар </a></li>
                            <!--<li><a href="{{ route('boshqaxarajat.index') }}"> Бошқа харажартар </a></li>-->
                            <li><a href="{{ route('ValyutaAlmashish.index') }}"> Валюта алмаштириш </a></li>
                            <li><a href="{{ route('NaqdSavdoOffice.index') }}"> Нақд савдо </a></li>
                            @endif
                            
                            @if (in_array($user, [2]))
                            <li><a href="{{ route('naqdsavdo.index') }}"> Нақд савдо </a></li>
                            <li><a href="{{ route('fondsavdo.index') }}"> Фонд савдо </a></li>
                            <li><a href="{{ route('shartnomatulov.index') }}"> График тўлов </a></li>
                            <li><a href="{{ route('savdopuli.index') }}"> Савдо пули </a></li>
                            <li><a href="{{ route('boshqaxarajat.index') }}"> Бошқа харажартар </a></li>
                            @endif
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M18.634 13.4211C18.634 16.7009 16.7007 18.6342 13.4209 18.6342H6.28738C2.99929 18.6342 1.06238 16.7009 1.06238 13.4211V6.27109C1.06238 2.99584 2.26688 1.06259 5.54763 1.06259H7.38096C8.03913 1.06351 8.65879 1.37242 9.05296 1.89951L9.88988 3.01234C10.2859 3.53851 10.9055 3.84834 11.5637 3.84926H14.1579C17.446 3.84926 18.6596 5.52309 18.6596 8.86984L18.634 13.4211Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" fill="#2ECC71"/>
                                        
                                    <path d="M5.85754 12.2577H13.8646" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" fill="#2ECC71"/>
                                </svg>
                            </div>
                            <span class="nav-text">Туловлар</span>
                        </a>
                        <ul aria-expanded="false">
                            @if (in_array($user, [1]))
                            <li><a href="{{ route('officeizmenittulov.index') }}"> Туловларни тахрирлаш </a></li>
                            @endif
                            <li><a href="{{ route('officegrafiktulov.index') }}"> График туловлар </a></li>
                            <li><a href="{{ route('officeudalittulov.index') }}"> Учирилган туловлар </a></li>
                            <li><a href="{{ route('officebrontulov.index') }}"> Брон туловлар </a></li>
                            <li><a href="{{ route('officeavtotulov.index') }}"> Авто туловлар </a></li>
                            <li><a href="{{ route('OfficeJamiTulovlar.index') }}"> Жами туловлар </a></li>
                        </ul>
                    </li>
                    
                    @if (in_array($user, [2]))
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="16" height="15" viewBox="0 0 16 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.46932 12.2102H0.693665" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.04547 3.32535H14.8211" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.99912 3.27573C4.99912 2.08805 4.02914 1.125 2.8329 1.125C1.63667 1.125 0.666687 2.08805 0.666687 3.27573C0.666687 4.46342 1.63667 5.42647 2.8329 5.42647C4.02914 5.42647 4.99912 4.46342 4.99912 3.27573Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.3333 12.1743C15.3333 10.9866 14.3641 10.0235 13.1679 10.0235C11.9709 10.0235 11.0009 10.9866 11.0009 12.1743C11.0009 13.3619 11.9709 14.325 13.1679 14.325C14.3641 14.325 15.3333 13.3619 15.3333 12.1743Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Бонус</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('bonus.index') }}">Бонус савдолари </a></li>
                        </ul>
                    </li>
                    @endif
                    
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.4065 14.8714H7.78821" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14.4065 11.0338H7.78821" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.3137 7.2051H7.78827" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.5829 2.52066C14.5829 2.52066 7.54563 2.52433 7.53463 2.52433C5.00463 2.53991 3.43805 4.20458 3.43805 6.74374V15.1734C3.43805 17.7254 5.01655 19.3965 7.56855 19.3965C7.56855 19.3965 14.6049 19.3937 14.6168 19.3937C17.1468 19.3782 18.7143 17.7126 18.7143 15.1734V6.74374C18.7143 4.19174 17.1349 2.52066 14.5829 2.52066Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Хисоботлар</span>
                        </a>
                        <ul aria-expanded="false">
                            @if (in_array($user, [1]))
                            <li><a href="{{ route('xisobottaminot.index') }}">Таъминотчилар хисоботи</a></li>
                            <li><a href="{{ route('SavdolarTahlili.index') }}">Савдолар тахлил</a></li>
                            <li><a href="{{ route('TovarQoldigi.index') }}"> Товарлар тахлили</a></li>
                            <li><a href="{{ route('KunlikOfficeXarajatlar.index') }}">Харажатлар хисоботи Офис</a></li>
                            @endif
                            <li><a href="{{ route('kunlik.index') }}">Кунлик хисоботи</a></li>
                            <li><a href="{{ route('kunliktaxlil.index') }}">Кунлик тахлил учун</a></li>
                            <li><a href="{{ route('KunlikXarajatlar.index') }}">Харажатлар хисоботи Филиал</a></li>
                            <li><a href="{{ route('XisobotInvestor.index') }}">Хисобот Инвестор</a></li>
                            <li><a href="{{ route('TovarXisobot.index') }}"> Товарлар хисоботи ИНВ</a></li>
                        </ul>
                    </li>
                    
                    @if (in_array($user, [1]))
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="16" height="15" viewBox="0 0 16 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.46932 12.2102H0.693665" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.04547 3.32535H14.8211" stroke="#888888" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.99912 3.27573C4.99912 2.08805 4.02914 1.125 2.8329 1.125C1.63667 1.125 0.666687 2.08805 0.666687 3.27573C0.666687 4.46342 1.63667 5.42647 2.8329 5.42647C4.02914 5.42647 4.99912 4.46342 4.99912 3.27573Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.3333 12.1743C15.3333 10.9866 14.3641 10.0235 13.1679 10.0235C11.9709 10.0235 11.0009 10.9866 11.0009 12.1743C11.0009 13.3619 11.9709 14.325 13.1679 14.325C14.3641 14.325 15.3333 13.3619 15.3333 12.1743Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Фонд</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('newfond.index') }}">Фондларни рўйхатга олиш </a></li>
                            <li><a href="{{ route('FondSavdoOffice.index') }}"> Фонд савдо </a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <div class="menu-icon">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.3691 18.7157C18.086 18.7157 19.4784 17.3242 19.4793 15.6073V15.6055V13.1305C18.3454 13.1305 17.4269 12.212 17.426 11.078C17.426 9.94504 18.3445 9.02562 19.4784 9.02562H19.4793V6.55062C19.4812 4.83279 18.0906 3.43946 16.3737 3.43762H16.3682H5.63216C3.91433 3.43762 2.52191 4.82912 2.521 6.54696V6.54787V9.10537C3.6155 9.06687 4.53308 9.92304 4.57158 11.0175C4.5725 11.0377 4.57341 11.0579 4.57341 11.078C4.57433 12.2101 3.65858 13.1286 2.5265 13.1305H2.521V15.6055C2.52008 17.3224 3.9125 18.7157 5.62941 18.7157H5.63033H16.3691Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.3403 8.30788L11.905 9.45096C11.96 9.5628 12.0663 9.64071 12.1901 9.65905L13.4523 9.8433C13.7649 9.88913 13.8887 10.2723 13.6632 10.4914L12.7502 11.3805C12.6603 11.4676 12.62 11.5932 12.6402 11.717L12.8556 12.9728C12.9087 13.2835 12.5833 13.52 12.3047 13.3734L11.1762 12.7803C11.0653 12.7216 10.9333 12.7216 10.8224 12.7803L9.69491 13.3734C9.41533 13.52 9.08991 13.2835 9.14308 12.9728L9.3585 11.717C9.37958 11.5932 9.33833 11.4676 9.2485 11.3805L8.33641 10.4914C8.11091 10.2723 8.23466 9.88913 8.54633 9.8433L9.80858 9.65905C9.93233 9.64071 10.0396 9.5628 10.0946 9.45096L10.6583 8.30788C10.7977 8.02555 11.201 8.02555 11.3403 8.30788Z"
                                        stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="nav-text">Қўшимчалар</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('user.index') }}">Дастурдан фойдаланувчилар</a></li>
                            <li><a href="{{ route('bashqaruv.index') }}">Дастур қўшимча созламалари</a></li>
                            <li><a href="{{ route('chegirma.index') }}">Чегирма белгилаш</a></li>
                            <li><a href="{{ route('BonusTur.index') }}">Турга Бонус белгилаш</a></li>
                            <li><a href="{{ route('IPNazorati.index') }}">IP назорати</a></li>
                            <li><a href="{{ route('DasturNazorati.index') }}">Дастур назорати</a></li>
                        </ul>
                    </li>
                    @endif

                </ul>
            </div>
        </div>

        <!--**********************************
            Sidebar end
        ***********************************-->

        @yield('content')

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Samo 2024-2025 йил</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->



    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    {{-- <script src="/vendor/global/global.min.js"></script> --}}
    {{-- <script src="/vendor/chart.js/Chart.bundle.min.js"></script> --}}
    <script src="/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/vendor/select2/js/select2.full.min.js"></script>
    <script src="/js/plugins-init/select2-init.js"></script>
    {{-- <script src="/vendor/apexchart/apexchart.js"></script> --}}

    <!-- Dashboard 1 -->
    {{-- <script src="/js/dashboard/dashboard-1.js"></script> --}}
    {{-- <script src="/vendor/draggable/draggable.js"></script> --}}


    <!-- tagify -->
    {{-- <script src="/vendor/tagify/dist/tagify.js"></script> --}}
    {{-- <script src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/js/dataTables.buttons.min.js"></script> --}}
    {{-- <script src="/vendor/datatables/js/buttons.html5.min.js"></script> --}}
    {{-- <script src="/vendor/datatables/js/jszip.min.js"></script> --}}
    {{-- <script src="/js/plugins-init/datatables.init.js"></script> --}}

    <!-- Apex Chart -->

    {{-- <script src="/vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> --}}


    <!-- Vectormap -->
    {{-- <script src="/vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="/vendor/jqvmap/js/jquery.vmap.world.js"></script>
    <script src="/vendor/jqvmap/js/jquery.vmap.usa.js"></script> --}}
    <script src="/js/custom.js"></script>
    <script src="/js/deznav-init.js"></script>
    <script src="/js/demo.js"></script>
    <script src="/js/styleSwitcher.js"></script>
    <script src="/js/table2excel.js"></script>
    <script src="/vendor/toastr/js/toastr.min.js"></script>
    <script src="/js/plugins-init/toastr-init.js"></script>
    <script src="/js/jquery.PrintArea.js"></script>




    {{-- <script>
        jQuery(document).ready(function() {
            setTimeout(function() {
                dzSettingsOptions.version = 'dark';
                new dzSettings(dzSettingsOptions);
            }, 1500)
        });

    </script> --}}


</body>

</html>
