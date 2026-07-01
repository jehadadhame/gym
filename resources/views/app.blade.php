<!DOCTYPE html>
<html lang="ar" dir="rtl">
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <title>TargetOne</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- BEGIN CORE FRAMEWORK -->
    <link href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/ionicons/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!-- END CORE FRAMEWORK -->

    <!-- BEGIN PLUGIN STYLES -->
    <link href="{{ URL::asset('assets/plugins/animate/animate.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/morris/morris.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrap-slider/css/slider.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/rickshaw/rickshaw.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}"
        rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrapValidator/bootstrapValidator.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield.min.css') }}"
        rel="stylesheet" />
    <!-- END PLUGIN STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="{{ URL::asset('assets/css/material.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/plugins.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/helpers.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/responsive.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/mystyle.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/print.css') }}" media="print" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/rtl.css') }}" rel="stylesheet" />
    <!-- END THEME STYLES -->
    @include('_jsVariables')
    @yield('header_scripts')
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="fixed-leftside fixed-header">
    <!-- BEGIN HEADER -->
    <header class="hidden-print">
        <span class="logo">TargetOne</span>
        <nav class="navbar navbar-static-top">
            <a href="#" class="navbar-btn sidebar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
        </nav>
    </header>
    <!-- END HEADER -->

    <div class="wrapper">
        <!-- BEGIN LEFTSIDE -->
        <div class="leftside hidden-print">
            <div class="sidebar">
                <!-- BEGIN RPOFILE -->
                <div class="nav-profile">
                    <div class="thumb">
                        <?php
$media = Auth::user()->getMedia();
$image = ($media->isEmpty() ? 'https://placeholdit.imgix.net/~text?txtsize=18&txt=NA&w=50&h=50' : url($media[0]->getUrl('thumb')));
                    ?>
                        <img src="{{ $image }}" class="img-circle" alt="" />
                    </div>
                    <div class="info">
                        <span class="color-grey-400">{{Utilities::getGreeting()}},</span><br />
                        <a>{{Auth::user()->name}}</a>
                    </div>
                    <a href="{{url('auth/logout')}}" class="button"><i class="ion-log-out"></i></a>
                </div>
                <!-- END RPOFILE -->
                <!-- BEGIN NAV -->
                <div class="title">القائمة الرئيسية</div>
                <ul class="nav-sidebar">
                    <li class="{{ Utilities::setActiveMenu('dashboard*') }}">
                        <a href="{{ action('DashboardController@index') }}">
                            <i class="ion-home"></i> <span>لوحة التحكم</span>
                        </a>
                    </li>

                    {{-- @permission(['manage-gymie','manage-enquiries','view-enquiry'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('enquiries*',true) }}">
                        <a href="#">
                            <i class="ion-ios-telephone"></i> <span>Enquiries</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('enquiries/all') }}"><a
                                    href="{{ action('EnquiriesController@index') }}">All Enquiries</a></li>
                            @permission(['manage-gymie','manage-enquiries','add-enquiry'])
                            <li class="{{ Utilities::setActiveMenu('enquiries/create') }}"><a
                                    href="{{ action('EnquiriesController@create') }}">Add Enquiry</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission --}}

                    <li class="nav-dropdown {{ Utilities::setActiveMenu('members*', true) }}">
                        <a href="#">
                            <i class="ion-person-add"></i> <span>الأعضاء</span>
                        </a>
                        <ul>
                            @permission(['manage-gymie', 'manage-members', 'view-member'])
                            <li class="{{ Utilities::setActiveMenu('members/all') }}"><a
                                    href="{{ action('MembersController@index') }}">كل الأعضاء</a></li>
                            @endpermission

                            @permission(['manage-gymie', 'manage-members', 'add-member'])
                            <li class="{{ Utilities::setActiveMenu('members/create') }}"><a
                                    href="{{ action('MembersController@create') }}">إضافة عضو</a></li>
                            @endpermission
                            @permission(['manage-gymie', 'manage-members', 'view-member'])
                            <li class="{{ Utilities::setActiveMenu('members/active') }}"><a
                                    href="{{ action('MembersController@active') }}">الأعضاء النشطون</a></li>
                            <li class="{{ Utilities::setActiveMenu('members/inactive') }}"><a
                                    href="{{ action('MembersController@inactive') }}">الأعضاء غير النشطين</a>
                                @endpermission
                            </li>
                        </ul>
                    </li>

                    @permission(['manage-gymie', 'manage-payments', 'view-payment'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('payments*', true) }}">
                        <a href="#">
                            <i class="ion-cash"></i> <span>المدفوعات</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('payments/all') }}"><a
                                    href="{{ action('PaymentsController@index') }}">كل المدفوعات</a></li>
                            @permission(['manage-gymie', 'manage-payments', 'add-payment'])
                            <li class="{{ Utilities::setActiveMenu('payments/create') }}"><a
                                    href="{{ action('PaymentsController@create') }}">إضافة دفعة</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-subscriptions', 'view-subscription'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('subscriptions*', true) }}">
                        <a href="#">
                            <i class="ion-android-checkbox-outline"></i> <span>الاشتراكات</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('subscriptions/all') }}"><a
                                    href="{{ action('SubscriptionsController@index') }}">كل الاشتراكات</a></li>
                            @permission(['manage-gymie', 'manage-subscriptions', 'add-subscription'])
                            <li class="{{ Utilities::setActiveMenu('subscriptions/create') }}"><a
                                    href="{{ action('SubscriptionsController@create') }}">إضافة اشتراك</a></li>
                            @endpermission
                            <li class="{{ Utilities::setActiveMenu('subscriptions/expiring') }}"><a
                                    href="{{ action('SubscriptionsController@expiring') }}">الاشتراكات التي ستنتهي قريباً</a></li>
                            <li class="{{ Utilities::setActiveMenu('subscriptions/expired') }}"><a
                                    href="{{ action('SubscriptionsController@expired') }}">الاشتراكات المنتهية</a></li>
                        </ul>
                    </li>
                    @endpermission

                    <!-- <li class="nav-dropdown {{-- Utilities::setActiveMenu('reports*',true) --}}">
                            <a href="#">
                                <i class="fa fa-file"></i> <span>Reports</span>
                            </a>
                            <ul>
                                <li class="{{-- Utilities::setActiveMenu('reports/members/*') --}}"><a href="{{-- action('ReportsController@gymMemberCharts') --}}">Members</a></li>
                                <li class="{{-- Utilities::setActiveMenu('reports/enquiries/*') --}}"><a href="{{-- action('ReportsController@enquiryCharts') --}}">Enquiries</a></li>
                                <li class="{{-- Utilities::setActiveMenu('reports/subscriptions/*') --}}"><a href="{{-- action('ReportsController@subscriptionCharts') --}}">Subscriptions</a></li>
                                <li class="{{-- Utilities::setActiveMenu('reports/payments/*') --}}"><a href="{{-- action('ReportsController@paymentCharts') --}}">Payments</a></li>                            
                                <li class="{{-- Utilities::setActiveMenu('reports/expenses/*') --}}"><a href="{{-- action('ReportsController@expenseCharts') --}}">Expenses</a></li>                            
                                <li class="{{-- Utilities::setActiveMenu('reports/invoices/*') --}}"><a href="{{-- action('ReportsController@invoiceCharts') --}}">Invoices</a></li>                            
                            </ul>
                        </li> -->

                    {{-- @permission(['manage-gymie','manage-sms'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('sms*',true) }}">
                        <a href="#">
                            <i class="ion-ios-paper"></i> <span>SMS</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('sms/triggers') }}"><a
                                    href="{{ action('SmsController@triggersIndex') }}">Triggers</a></li>
                            <li class="{{ Utilities::setActiveMenu('sms/events') }}"><a
                                    href="{{ action('SmsController@eventsIndex') }}">Schedule message</a></li>
                            <li class="{{ Utilities::setActiveMenu('sms/send') }}"><a
                                    href="{{ action('SmsController@send') }}">Send message</a></li>
                            <li class="{{ Utilities::setActiveMenu('sms/log') }}"><a
                                    href="{{ action('SmsController@logIndex') }}">Log</a></li>
                        </ul>
                    </li>
                    @endpermission --}}

                    @permission(['manage-gymie', 'manage-invoices', 'view-invoice'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('invoices*', true) }}">
                        <a href="#">
                            <i class="ion-ios-paper"></i> <span>الفواتير</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('invoices/all') }}"><a
                                    href="{{ action('InvoicesController@index') }}">كل الفواتير</a></li>
                            <li class="{{ Utilities::setActiveMenu('invoices/paid') }}"><a
                                    href="{{ action('InvoicesController@paid') }}">الفواتير المدفوعة</a></li>
                            <li class="{{ Utilities::setActiveMenu('invoices/unpaid') }}"><a
                                    href="{{ action('InvoicesController@unpaid') }}">الفواتير غير المدفوعة</a>
                            </li>
                            <li class="{{ Utilities::setActiveMenu('invoices/partial') }}"><a
                                    href="{{ action('InvoicesController@partial') }}">فواتير مدفوعة جزئياً</a>
                            </li>
                            <li class="{{ Utilities::setActiveMenu('invoices/overpaid') }}"><a
                                    href="{{ action('InvoicesController@overpaid') }}">فواتير زائدة الدفع</a></li>
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-expenses', 'view-expense'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('expenses*', true) }}">
                        <a href="#">
                            <i class="fa fa-inr"></i> <span>المصروفات</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('expenses/all') }}"><a
                                    href="{{ action('ExpensesController@index') }}">كل المصروفات</a></li>
                            @permission(['manage-gymie', 'manage-expenses', 'add-expense'])
                            <li class="{{ Utilities::setActiveMenu('expenses/create') }}"><a
                                    href="{{ action('ExpensesController@create') }}">إضافة مصروف</a></li>
                            @endpermission
                            @permission(['manage-gymie', 'manage-expenseCategories', 'view-expenseCategory'])
                            <li class="{{ Utilities::setActiveMenu('expenses/categories/all') }}"><a
                                    href="{{ action('ExpenseCategoriesController@index') }}">فئات المصروفات</a></li>
                            @endpermission
                            @permission(['manage-gymie', 'manage-expenseCategories', 'add-expenseCategory'])
                            <li class="{{ Utilities::setActiveMenu('expenses/categories/create') }}"><a
                                    href="{{ action('ExpenseCategoriesController@create') }}">إضافة فئة</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-plans', 'view-plan'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('plans*', true) }}">
                        <a href="#">
                            <i class="ion-compose"></i> <span>الخطط</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('plans/all') }}"><a
                                    href="{{ action('PlansController@index') }}">كل الخطط</a></li>
                            @permission(['manage-gymie', 'manage-plans', 'add-plan'])
                            <li class="{{ Utilities::setActiveMenu('plans/create') }}"><a
                                    href="{{ action('PlansController@create') }}">إضافة خطة</a></li>
                            @endpermission
                            @permission(['manage-gymie', 'manage-services', 'view-service'])
                            <li class="{{ Utilities::setActiveMenu('plans/services/all') }}"><a
                                    href="{{ action('ServicesController@index') }}">خدمات الصالة</a>
                            </li>
                            @endpermission
                            @permission(['manage-gymie', 'manage-services', 'add-service'])
                            <li class="{{ Utilities::setActiveMenu('plans/services/create') }}"><a
                                    href="{{ action('ServicesController@create') }}">إضافة خدمة</a>
                            </li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-products', 'view-product'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('products*', true) }}">
                        <a href="#">
                            <i class="ion-bag"></i> <span>المتجر</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('purchase/all') }}"><a
                                    href="{{ action('PurchaseController@index') }}">كل المشتريات</a></li>
                            @permission(['manage-gymie', 'manage-purchase', 'add-purchase'])
                            <li class="{{ Utilities::setActiveMenu('purchase/create') }}"><a
                                    href="{{ action('PurchaseController@create') }}">إضافة مشتريات</a></li>
                            @endpermission

                            <li class="{{ Utilities::setActiveMenu('products/all') }}"><a
                                    href="{{ action('ProductController@index') }}">كل المنتجات</a></li>
                            @permission(['manage-gymie', 'manage-products', 'add-product'])
                            <li class="{{ Utilities::setActiveMenu('products/create') }}"><a
                                    href="{{ action('ProductController@create') }}">إضافة منتج</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-users'])
                    <li class="nav-dropdown {{ Utilities::setActiveMenu('user*', true) }}">
                        <a href="#">
                            <i class="fa fa-users"></i> <span>المستخدمين</span>
                        </a>
                        <ul>
                            <li class="{{ Utilities::setActiveMenu('user') }}"><a
                                    href="{{ action('AclController@userIndex') }}"><i class="fa fa-upload"></i> كل المستخدمين</a></li>
                            <li class="{{ Utilities::setActiveMenu('user/create') }}"><a
                                    href="{{ action('AclController@createUser') }}"><i class="fa fa-list"></i>
                                    إضافة مستخدم جديد</a></li>
                            <li class="{{ Utilities::setActiveMenu('user/role') }}"><a
                                    href="{{ action('AclController@roleIndex') }}"><i class="fa fa-list"></i>
                                    الأدوار</a></li>
                            @role('Gymie')
                            <li class="{{ Utilities::setActiveMenu('user/permission') }}"><a
                                    href="{{ action('AclController@permissionIndex') }}"><i class="fa fa-list"></i>
                                    الصلاحيات</a></li>
                            @endrole
                        </ul>
                    </li>
                    @endpermission

                    @permission(['manage-gymie', 'manage-settings'])
                    <li class="{{ Utilities::setActiveMenu('settings*') }}">
                        <a href="{{ action('SettingsController@edit') }}">
                            <i class="fa fa-cogs fa-2x"></i> <span>الإعدادات</span>
                        </a>
                    </li>
                    @endpermission

                    <!-- Dummy Spacer -->
                    <li>
                        <a href=""></a>
                    </li>

                </ul>

            </div>
        </div>

        @yield('content')
    </div><!-- /.wrapper -->
    <!-- END CONTENT -->

    <!-- BEGIN JAVASCRIPTS -->

    <!-- BEGIN CORE PLUGINS -->
    <script src="{{ URL::asset('assets/plugins/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/js/core.js') }}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- datepicker -->
    <script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"
        type="text/javascript"></script>

    <!-- counter -->
    <script src="{{ URL::asset('assets/plugins/jquery-countTo/jquery.countTo.js') }}" type="text/javascript"></script>

    <!-- datepicker -->
    <script src="{{ URL::asset('assets/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"
        type="text/javascript"></script>

    <!--validator-->
    <script src="{{ URL::asset('assets/plugins/bootstrapValidator/bootstrapValidator.min.js') }}"
        type="text/javascript"></script>

    {{-- @include('_jsVariables') --}}

    <!--Footer scripts-->
    @yield('footer_scripts')

    <!-- gymie -->
    <script src="{{ URL::asset('assets/js/gymie.js') }}" type="text/javascript"></script>

    @yield('footer_script_init')

    <!-- dashboard -->
    <script type="text/javascript">

        $(document).ready(function () {
            gymie.loadcounter();
            gymie.loadprogress();
            gymie.loaddatepicker();
            gymie.loaddaterangepicker();
            gymie.loadbsselect();
        });
        let keyBuffer = '';
        let lastKeyTime = Date.now();

        $(document).on('keydown', function (e) {
            if ($(e.target).is('input, textarea')) return;

            const now = Date.now();

            if (now - lastKeyTime > 1000) {
                keyBuffer = '';
            }

            lastKeyTime = now;

            if (/^[a-z]$/i.test(e.key)) {
                keyBuffer += e.key.toLowerCase();
            } else {
                return;
            }

            // 🔥 NAVIGATION COMMANDS

            // Members
            if (keyBuffer === 'mem') window.location.href = "{{ action('MembersController@index') }}";
            if (keyBuffer === 'mema') window.location.href = "{{ action('MembersController@active') }}";
            if (keyBuffer === 'memi') window.location.href = "{{ action('MembersController@inactive') }}";
            if (keyBuffer === 'memc') window.location.href = "{{ action('MembersController@create') }}";

            // Payments
            if (keyBuffer === 'pay') window.location.href = "{{ action('PaymentsController@index') }}";
            if (keyBuffer === 'payc') window.location.href = "{{ action('PaymentsController@create') }}";

            // Subscriptions
            if (keyBuffer === 'sub') window.location.href = "{{ action('SubscriptionsController@index') }}";
            if (keyBuffer === 'subc') window.location.href = "{{ action('SubscriptionsController@create') }}";
            if (keyBuffer === 'sube') window.location.href = "{{ action('SubscriptionsController@expiring') }}";
            if (keyBuffer === 'subx') window.location.href = "{{ action('SubscriptionsController@expired') }}";

            // Invoices
            if (keyBuffer === 'inv') window.location.href = "{{ action('InvoicesController@index') }}";
            if (keyBuffer === 'invp') window.location.href = "{{ action('InvoicesController@paid') }}";
            if (keyBuffer === 'invu') window.location.href = "{{ action('InvoicesController@unpaid') }}";
            if (keyBuffer === 'invo') window.location.href = "{{ action('InvoicesController@overpaid') }}";

            // Expenses
            if (keyBuffer === 'exp') window.location.href = "{{ action('ExpensesController@index') }}";
            if (keyBuffer === 'expc') window.location.href = "{{ action('ExpensesController@create') }}";

            // Plans
            if (keyBuffer === 'pla') window.location.href = "{{ action('PlansController@index') }}";
            if (keyBuffer === 'plac') window.location.href = "{{ action('PlansController@create') }}";

            // Products / Shop
            if (keyBuffer === 'pro') window.location.href = "{{ action('ProductController@index') }}";
            if (keyBuffer === 'proc') window.location.href = "{{ action('ProductController@create') }}";
            if (keyBuffer === 'pur') window.location.href = "{{ action('PurchaseController@index') }}";
            if (keyBuffer === 'purc') window.location.href = "{{ action('PurchaseController@create') }}";

            // Users
            if (keyBuffer === 'usr') window.location.href = "{{ action('AclController@userIndex') }}";
            if (keyBuffer === 'usrc') window.location.href = "{{ action('AclController@createUser') }}";
            if (keyBuffer === 'rol') window.location.href = "{{ action('AclController@roleIndex') }}";

            // Settings
            if (keyBuffer === 'set') window.location.href = "{{ action('SettingsController@edit') }}";

            // Dashboard
            if (keyBuffer === 'dash') window.location.href = "{{ action('DashboardController@index') }}";
        });


        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();

            $(document).on('keydown', function (e) {

                // 🔴 ESC: exit search field
                if (e.key === 'Escape') {
                    if ($('#search').is(':focus')) {
                        $('#search').blur();   // remove focus
                        $('#search').val('');  // optional: clear input
                    }
                    return;
                }

                // Ignore other shortcuts if typing (except ESC handled above)
                if ($(e.target).is('input, textarea')) return;

                // 🔍 "/" → focus search
                if (e.key === '/') {
                    e.preventDefault();
                    $('#search').focus().select(); // focus + select text
                }
            });
        });
    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>