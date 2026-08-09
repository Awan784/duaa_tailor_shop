<!DOCTYPE html>
<html lang="zxx">

<!-- Mirrored from demo.dashboardpack.com/sales-html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 May 2024 10:45:17 GMT -->
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Darzi Shop</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('admin-assets/img/logo.png')}}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('admin-assets/css/bootstrap1.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/themefy_icon/themify-icons.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/niceselect/css/nice-select.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/owl_carousel/css/owl.carousel.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/gijgo/gijgo.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/font_awesome/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/tagsinput/tagsinput.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/datepicker/date-picker.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/vectormap-home/vectormap-2.0.2.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/scroll/scrollable.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/datatable/css/jquery.dataTables.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/datatable/css/responsive.dataTables.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/datatable/css/buttons.dataTables.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/morris/morris.css')}}">
    <link rel="stylesheet" href="{{ asset('admin-assets/vendors/material_icon/material-icons.css')}}"/>
    <link rel="stylesheet" href="{{ asset('admin-assets/css/metisMenu.css')}}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style1.css')}}"/>
    <link rel="stylesheet" id="colorSkinCSS" href="{{ asset('admin-assets/css/colors/default.css')}}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/duaa-theme.css')}}"/>
    @yield('css')
</head>
<body class="crm_body_bg">
   @include('admin.layout.sidebar')
   <section class="main_content dashboard_part large_header_bg">
      <div class="container-fluid g-0">
         <div class="row">
            <div class="col-lg-12 p-0">
               <div class="header_iner d-flex justify-content-between align-items-center">
                  <div class="sidebar_icon d-lg-none">
                     <i class="ti-menu"></i>
                  </div>
                  <div class="serach_field-area d-flex align-items-center">
                     <div class="duaa-header-brand">
                        <img src="{{ asset('admin-assets/img/logo.png') }}" alt="Darzi Shop">
                        <div>
                           <p class="title">Darzi Shop</p>
                           <p class="subtitle">Operations Dashboard</p>
                        </div>
                     </div>
                  </div>
                  <div class="header_right d-flex justify-content-between align-items-center">
                     <div class="profile_info">
                        <img src="{{asset('admin-assets/img/client_img.png')}}" alt="#">
                        <div class="profile_info_iner">
                           <div class="profile_author_name">
                              <p>{{auth()->user()->name}} </p>
                              <h5>{{auth()->user()->email}}</h5>
                           </div>
                           <div class="profile_info_details">
                              <a href="{{route('password.change')}}">My Profile </a>
                              <a href="{{url('logout')}}">Log Out </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
