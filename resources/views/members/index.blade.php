@extends('app')

@section('content')

<div class="rightside bg-grey-100">

    <!-- BEGIN PAGE HEADING -->
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">الأعضاء
            @permission(['manage-gymie', 'manage-members', 'add-member'])
            <a href="{{ action('MembersController@create') }}" class="page-head-btn btn-sm btn-primary active" role="button">إضافة جديد</a>
            @endpermission
            <small>تفاصيل جميع أعضاء الصالة</small>
        </h1>
        @permission(['manage-gymie', 'pagehead-stats'])
        <h1 class="font-size-30 text-right color-primary-500 animated fadeInDown total-count pull-right"><span
                data-toggle="counter" data-start="0" data-from="0" data-to="{{ $count }}" data-speed="600"
                data-refresh-interval="10"></span>
            <small class="color-text-secondary display-block margin-top-5 font-size-14">إجمالي الأعضاء</small>
        </h1>
        @endpermission
    </div><!-- / PageHead -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel bg-white">
                    <!-- Filter Bar -->
                    <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                        {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}
                        <div class="row" dir="rtl">
                            
                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('member-daterangepicker', 'نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <div id="member-daterangepicker" class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                    <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                    <span>{{$drp_placeholder}}</span>
                                    <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                </div>
                                {!! Form::text('drp_start', null, ['class' => 'hidden', 'id' => 'drp_start']) !!}
                                {!! Form::text('drp_end', null, ['class' => 'hidden', 'id' => 'drp_end']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_field', 'ترتيب حسب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_field', array('created_at' => 'التاريخ', 'name' => 'الاسم', 'member_code' => 'كود العضو', 'status' => 'الحالة'), old('sort_field'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_field', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10">
                                {!! Form::label('sort_direction', 'الترتيب', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('sort_direction', array('desc' => 'تنازلي', 'asc' => 'تصاعدي'), old('sort_direction'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'sort_direction', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-3 col-sm-6 margin-bottom-10">
                                {!! Form::label('search', 'كلمة البحث', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <input value="{{ old('search') }}" name="search" id="search" type="text" class="form-control" placeholder="ابحث..." style="width: 100%;">
                            </div>

                            <div class="col-md-2 col-sm-12 margin-bottom-10 text-left" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> بحث</button>
                            </div>
                        </div>
                        {!! Form::Close() !!}
                    </div>

                    <div class="panel-body padding-20">
                        @if($members->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else
                            <div class="table-responsive">
                                <table id="members" class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right">الصورة</th>
                                            <th class="text-right">الكود</th>
                                            <th class="text-right">الاسم</th>
                                            <th class="text-right">رقم التواصل</th>
                                            <th class="text-right">الخطة النشطة</th>
                                            <th class="text-right">ملاحظة</th>
                                            <th class="text-right">عضو منذ</th>
                                            <th class="text-right">المبلغ المتبقي</th>
                                            <th class="text-right">الحالة</th>
                                            <th class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($members as $member)
                                            <?php
                                            $subscriptions = $member->subscriptions;
                                            $plansArray = array();
                                            foreach ($subscriptions as $subscription) {
                                                if ($subscription->status == \constSubscription::onGoing) {
                                                    $plansArray[] = $subscription->plan->plan_name;
                                                }
                                            }
                                            $activePlanDisplay = empty($plansArray) ? 'لا توجد' : implode(", ", $plansArray);

                                            $images = $member->getMedia('profile');
                                            $profileImage = ($images->isEmpty() ? 'https://placeholdit.imgix.net/~text?txtsize=18&txt=NA&w=70&h=70' : url($images[0]->getUrl('form')));
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <a href="{{ action('MembersController@show',['id' => $member->id]) }}">
                                                        <img src="{{ $profileImage }}" class="img-circle" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--color-border-light);" />
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $member->id]) }}" class="font-weight-600 color-primary-500">{{ $member->member_code}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ action('MembersController@show',['id' => $member->id]) }}" class="font-weight-600 color-text-primary">{{ $member->name}}</a>
                                                </td>
                                                <td dir="ltr" class="text-right">{{ $member->contact}}</td>
                                                <td>{{ $activePlanDisplay }}</td>
                                                <td class="text-center">
                                                    @if($member->note)
                                                    <span data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ $member->note }}" style="cursor: pointer;" class="color-text-secondary">
                                                        <i class="fa fa-commenting-o font-size-18"></i>
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>{{ $member->created_at->format('Y-m-d')}}</td>
                                                <td>
                                                    @if($member->pending_sum > 0)
                                                        <span class="label label-danger font-size-13">{{ $member->pending_sum }}</span>
                                                    @else
                                                        <span class="label label-success font-size-13">{{ $member->pending_sum }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="{{ Utilities::getActiveInactive($member->status) }}">{{ Utilities::getStatusValue($member->status) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            الإجراءات <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-members', 'view-member'])
                                                                <a href="{{ action('MembersController@show',['id' => $member->id]) }}"><i class="fa fa-eye"></i> عرض التفاصيل</a>
                                                                @endpermission
                                                            </li>
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-members', 'edit-member'])
                                                                <a href="{{ action('MembersController@edit',['id' => $member->id]) }}"><i class="fa fa-pencil"></i> تعديل التفاصيل</a>
                                                                @endpermission
                                                            </li>
                                                            <li class="divider"></li>
                                                            <li>
                                                                @permission(['manage-gymie', 'manage-members', 'delete-member'])
                                                                <a href="#" class="delete-record color-error-text" data-delete-url="{{ url('members/' . $member->id . '/archive') }}" data-record-id="{{$member->id}}"><i class="fa fa-trash"></i> حذف العضو</a>
                                                                @endpermission
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row margin-top-20">
                                <div class="col-xs-6">
                                    <div class="gymie_paging_info color-text-secondary">
                                        عرض الصفحة {{ $members->currentPage() }} من {{ $members->lastPage() }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $members->appends(Input::all())->render()) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer_script_init')
<script type="text/javascript">
    $(document).ready(function () {
        gymie.deleterecord();
        $('[data-toggle="popover"]').popover();
    });
</script>
@stop
