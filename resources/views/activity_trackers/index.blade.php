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
                    <div class="panel-body padding-20">
                        @if($activities->count() == 0)
                            <h4 class="text-center padding-top-15 color-text-secondary">عذراً! لا توجد سجلات</h4>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th class="text-right" style="width: 15%;">التاريخ والوقت</th>
                                            <th class="text-right" style="width: 20%;">المستخدم</th>
                                            <th class="text-right" style="width: 65%;">الحدث</th>
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
