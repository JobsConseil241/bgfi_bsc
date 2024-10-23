<html
    lang="en"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/backend/"
    data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - {{ $title }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{url('public/assets/frontend/img/bgfi.jpg')}}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/fonts/flag-icons.css')}}" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"  />
    <link rel="stylesheet" href="{{url('public/assets/backend/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/apex-charts/apex-charts.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/backend/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{url('public/assets/backend/vendor/js/helpers.js')}}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{--<script src="assets/backend/vendor/js/template-customizer.js"></script>--}}
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{url('public/assets/backend/js/config.js')}}"></script>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="/dashboard" class="app-brand-link">
                  <span class="app-brand-logo demo">
                    <img src="{{url('public/assets/frontend/img/bgfi.jpg')}}" alt="" srcset="" width="100" height="70">
                  </span>
                    <span class="app-brand-text demo menu-text fw-bold">CornerDash</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboards -->
                <li class="menu-item">
                    <a href="/dashboard" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-smart-home"></i>
                        <div data-i18n="Dashboard">Dashboard</div>
{{--                        <div class="badge bg-primary rounded-pill ms-auto">5</div>--}}
                    </a>
                </li>
                <li class="menu-item {{ $ags ?? '' }}">
                    <a href="/dashboard/agences"  class="menu-link">
                        <i class="menu-icon tf-icons ti ti-building"></i>
                        <div data-i18n="Vue des Agences"></div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                        <div data-i18n="Recapitulatif"></div>
                        {{--                        <div class="badge bg-primary rounded-pill ms-auto">5</div>--}}
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ $rcprcl ?? '' }}">
                            <a href="/dashboard/recapitulatifs/reclamations" class="menu-link">
                                <div data-i18n="Avis"></div>
                            </a>
                        </li>
                        <li class="menu-item {{ $rcpavis ?? '' }}">
                            <a href="/dashboard/recapitulatifs/avis" class="menu-link">
                                <div data-i18n="Reclamations"></div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-chart-donut"></i>
                        <div data-i18n="Pertinence"></div>
                        {{--                        <div class="badge bg-primary rounded-pill ms-auto">5</div>--}}
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ $ptnrcl ?? '' }}">
                            <a href="/dashboard/pertinence/reclamations" class="menu-link">
                                <div data-i18n="Avis"></div>
                            </a>
                        </li>
                        <li class="menu-item {{ $ptnavis ?? '' }}">
                            <a href="/dashboard/pertinence/avis" class="menu-link">
                                <div data-i18n="Reclamations"></div>
                            </a>
                        </li>
                        <li class="menu-item {{ $ptnec ?? '' }}">
                            <a href="/dashboard/pertinence/espace-client" class="menu-link">
                                <div data-i18n="Espace Client"></div>
                            </a>
                        </li>
                        <li class="menu-item {{ $ptnfaq ?? '' }}">
                            <a href="/dashboard/pertinence/faq" class="menu-link">
                                <div data-i18n="FAQ"></div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item {{ $form ?? '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-clipboard-text"></i>
                        <div data-i18n="Formulaires"></div>
                        {{--                        <div class="badge bg-primary rounded-pill ms-auto">5</div>--}}
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ $formavis ?? '' }}">
                            <a href="/dashboard/formulaire/avis" class="menu-link">
                                <div data-i18n="Avis"></div>
                            </a>
                        </li>
                        <li class="menu-item {{ $formrecla ?? '' }}">
                            <a href="/dashboard/formulaire/reclamation" class="menu-link">
                                <div data-i18n="Reclamations"></div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item {{ $pagema ?? '' }}">
                    <a href="/dashboard/page-manager"  class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-settings"></i>
                        <div data-i18n="Page Manager"></div>
                    </a>
                </li>

                <li class="menu-item {{ $faqmngr ?? '' }}">
                    <a href="/dashboard/faq-manage"  class="menu-link">
                        <i class="menu-icon tf-icons ti ti-help-octagon"></i>
                        <div data-i18n="Gestion de FAQ"></div>
                    </a>
                </li>

                <li class="menu-item {{ $usermng ?? '' }}">
                    <a href="/dashboard/users-manage"  class="menu-link">
                        <i class="menu-icon tf-icons ti ti-users"></i>
                        <div data-i18n="Gestion de Utilisateurs"></div>
                    </a>
                </li>
                <!-- Misc -->
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text" data-i18n="Misc"></span>
                </li>
                <li class="menu-item">
                    <a href="https://pixinvent.ticksy.com/"  class="menu-link">
                        <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                        <div data-i18n="Support">Support</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a
                        href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"

                        class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div data-i18n="Documentation">Documentation</div>
                    </a>
                </li>
            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            <nav
                class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="ti ti-menu-2 ti-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item navbar-search-wrapper mb-0">
                            <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                                <i class="ti ti-search ti-md me-2"></i>
                                <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                            </a>
                        </div>
                    </div>
                    <!-- /Search -->

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- Language -->
                        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <i class="ti ti-language rounded-circle ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-language="en" data-text-direction="ltr">
                                        <span class="align-middle">English</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-language="fr" data-text-direction="ltr">
                                        <span class="align-middle">French</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-language="ar" data-text-direction="rtl">
                                        <span class="align-middle">Arabic</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-language="de" data-text-direction="ltr">
                                        <span class="align-middle">German</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ Language -->

                        <!-- Style Switcher -->
                        <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <i class="ti ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                        <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                        <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                        <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- / Style Switcher-->

                        <!-- Notification -->
                        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                            <a
                                class="nav-link dropdown-toggle hide-arrow"
                                href="javascript:void(0);"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false">
                                <i class="ti ti-bell ti-md"></i>
                                <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end py-0">
                                <li class="dropdown-menu-header border-bottom">
                                    <div class="dropdown-header d-flex align-items-center py-3">
                                        <h5 class="text-body mb-0 me-auto">Notification</h5>
                                        <a
                                            href="javascript:void(0)"
                                            class="dropdown-notifications-all text-body"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Mark all as read"
                                        ><i class="ti ti-mail-opened fs-4"></i
                                            ></a>
                                    </div>
                                </li>
                                <li class="dropdown-notifications-list scrollable-container">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{url('public/assets/backend/img/avatars/1.png')}}" alt class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                                    <p class="mb-0">Won the monthly best seller gold badge</p>
                                                    <small class="text-muted">1h ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Charles Franklin</h6>
                                                    <p class="mb-0">Accepted your connection</p>
                                                    <small class="text-muted">12hr ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{url('public/assets/backend/img/avatars/2.png')}}" alt class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">New Message ✉️</h6>
                                                    <p class="mb-0">You have new message from Natalie</p>
                                                    <small class="text-muted">1h ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"
                                ><i class="ti ti-shopping-cart"></i
                                    ></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                                                    <p class="mb-0">ACME Inc. made new order $1,154</p>
                                                    <small class="text-muted">1 day ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{url('public/assets/backend/img/avatars/9.png')}}" alt class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Application has been approved 🚀</h6>
                                                    <p class="mb-0">Your ABC project application has been approved.</p>
                                                    <small class="text-muted">2 days ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"
                                ><i class="ti ti-chart-pie"></i
                                    ></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Monthly report is generated</h6>
                                                    <p class="mb-0">July monthly financial report is generated</p>
                                                    <small class="text-muted">3 days ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{url('public/assets/backend/img/avatars/5.png')}}" alt class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Send connection request</h6>
                                                    <p class="mb-0">Peter sent you connection request</p>
                                                    <small class="text-muted">4 days ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{url('public/assets/backend/img/avatars/6.png')}}" alt class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">New message from Jane</h6>
                                                    <p class="mb-0">Your have new message from Jane</p>
                                                    <small class="text-muted">5 days ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-warning"
                                ><i class="ti ti-alert-triangle"></i
                                    ></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">CPU is running high</h6>
                                                    <p class="mb-0">CPU Utilization Percent is currently at 88.63%,</p>
                                                    <small class="text-muted">5 days ago</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                    ><span class="badge badge-dot"></span
                                                        ></a>
                                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    ><span class="ti ti-x"></span
                                                        ></a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-menu-footer border-top">
                                    <a
                                        href="javascript:void(0);"
                                        class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                                        View all notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ Notification -->

                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="{{url('public/assets/backend/img/avatars/1.png')}}" alt class="h-auto rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="pages-account-settings-account.html">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="{{url('public/assets/backend/img/avatars/1.png')}}" alt class="h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                                <small class="text-muted">{{ Auth::user()->role }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/dashboard/profile">
                                        <i class="ti ti-user-check me-2 ti-sm"></i>
                                        <span class="align-middle">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="ti ti-logout me-2 ti-sm"></i>
                                        <span class="align-middle">Deconnexion</span>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>

                <!-- Search Small Screens -->
                <div class="navbar-search-wrapper search-input-wrapper d-none">
                    <input
                        type="text"
                        class="form-control search-input container-xxl border-0"
                        placeholder="Search..."
                        aria-label="Search..." />
                    <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
                </div>
            </nav>

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                @yield('content')

                  <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div
                            class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                            <div>
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://jobs-conseil.com" target="_blank" class="footer-link text-primary fw-medium"
                                >JOBS</a
                                >
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

{{--                <div class="content-backdrop fade"></div>--}}
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
{{--    <div class="layout-overlay layout-menu-toggle"></div>--}}

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
{{--    <div class="drag-target"></div>--}}
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="{{url('public/assets/backend/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/popper/popper.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/js/bootstrap.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/node-waves/node-waves.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/js/menu.js')}}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{url('public/assets/backend/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{url('public/assets/backend/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Main JS -->
<script src="{{url('public/assets/backend/js/main.js')}}"></script>

<!-- Page JS -->
<script src="{{url('public/assets/backend/js/app-ecommerce-dashboard.js')}}"></script>

<!-- Page JS -->
@stack('scripts')
</body>
</html>
