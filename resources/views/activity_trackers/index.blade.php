@extends('app')

@section('content')
<div class="rightside bg-grey-100">
    <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
        @include('flash::message')
        <h1 class="page-title no-line-height">سجل النشاطات
            <small>تتبع كافة العمليات في النظام</small>
        </h1>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel bg-white">
                    <div class="panel-heading bg-white" style="border-bottom: 1px solid var(--color-border-light); padding: 15px 20px;">
                        <div class="row" dir="rtl">
                            {!! Form::Open(['method' => 'GET', 'class' => 'form-inline']) !!}

                            <div class="col-md-3 col-sm-6 margin-bottom-10 pull-right">
                                {!! Form::label('user_id','المستخدم', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                {!! Form::select('user_id', ['' => 'الكل'] + (isset($users) ? $users->toArray() : []), old('user_id'), ['class' => 'form-control selectpicker show-tick show-menu-arrow', 'id' => 'user_id', 'style' => 'width: 100%;']) !!}
                            </div>

                            <div class="col-md-4 col-sm-6 margin-bottom-10 pull-right">
                                {!! Form::label('activity-daterangepicker','نطاق التاريخ', ['class' => 'color-text-secondary font-weight-600 display-block margin-bottom-5']) !!}
                                <div id="activity-daterangepicker" class="form-control gymie-daterangepicker" style="cursor: pointer; width: 100%;">
                                    <i class="ion-calendar margin-right-10 color-primary-500"></i>
                                    <span>{{ $drp_placeholder ?? 'تحديد نطاق التاريخ' }}</span>
                                    <i class="ion-ios-arrow-down margin-left-5 pull-left"></i>
                                </div>
                                {!! Form::text('drp_start', old('drp_start'), ['class'=>'hidden', 'id' => 'drp_start']) !!}
                                {!! Form::text('drp_end', old('drp_end'), ['class'=>'hidden', 'id' => 'drp_end']) !!}
                            </div>

                            <div class="col-md-2 col-sm-6 margin-bottom-10 text-right pull-right" style="margin-top: 25px;">
                                <label class="color-text-secondary font-weight-600 margin-right-10" style="cursor: pointer;">
                                    {!! Form::checkbox('today', 1, old('today') == 1, ['id' => 'today-checkbox']) !!} نشاطات اليوم فقط
                                </label>
                            </div>

                            <div class="col-md-1 col-sm-12 margin-bottom-10 text-left pull-left" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> بحث</button>
                            </div>
                            
                            {!! Form::Close() !!}
                        </div>
                    </div>
                    
                    <div class="panel-body padding-20">
                        @if($activities->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right" style="width: 15%;">التاريخ والوقت</th>
                                            <th class="text-right" style="width: 15%;">المستخدم</th>
                                            <th class="text-right" style="width: 10%;">العملية</th>
                                            <th class="text-right" style="width: 15%;">العضو المتأثر</th>
                                            <th class="text-right" style="width: 45%;">التفاصيل (الحدث)</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($activities as $activity)
                                            <tr>
                                                <td>{{ $activity->created_at ? $activity->created_at->format('Y-m-d H:i') : '' }}</td>
                                                <td>
                                                    @if($activity->user)
                                                        <span class="font-weight-600 color-primary-500">{{ $activity->user->name }}</span>
                                                    @else
                                                        <span class="color-text-secondary">النظام</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($activity->action_type == 'إنشاء')
                                                        <span class="label label-success">{{ $activity->action_type }}</span>
                                                    @elseif($activity->action_type == 'تعديل')
                                                        <span class="label label-warning">{{ $activity->action_type }}</span>
                                                    @elseif($activity->action_type == 'حذف')
                                                        <span class="label label-danger">{{ $activity->action_type }}</span>
                                                    @else
                                                        <span class="label label-info">{{ $activity->action_type ?? 'غير محدد' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($activity->affected_member_name)
                                                        <span class="font-weight-600">{{ $activity->affected_member_name }}</span>
                                                    @else
                                                        <span class="color-text-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $activity->story }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row margin-top-20">
                                <div class="col-xs-6">
                                    <div class="gymie_paging_info color-text-secondary">
                                        عرض الصفحة {{ $activities->currentPage() }} من {{ $activities->lastPage() }}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="gymie_paging pull-left">
                                        {!! str_replace('/?', '?', $activities->render()) !!}
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
