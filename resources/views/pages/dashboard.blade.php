@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-md-6 col-xl-4">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <?php
                                $borrow = \App\Borrow::whereMonth('borrow_date', date('m'))->get();

                                $total = 0;
                                foreach ($borrow as $key => $value) {
                                    $borrowDate = $value->borrow_date;
                                    $total += $borrowDate->day;
                                }
                                ?>
                                <h4 class="mb-0">{{ __($total) }}</h4>
                                <p class="mb-0">{{ __('Total Borrow on this month') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>

                        </div>
                        <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <?php
                                $startDate = now()->subMonths(6);
                                $endDate = now();

                                $borrow = \App\Borrow::whereBetween('borrow_date', [$startDate, $endDate])->get();

                                $total = 0;
                                foreach ($borrow as $key => $value) {
                                    $borrowDate = $value->borrow_date;
                                    $total += $borrowDate->day;
                                }
                                ?>
                                <h4 class="mb-0">{{ __($total) }}</h4>
                                <p class="mb-0">{{ __('Total Borrow on 6 month') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart2" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <?php
                                $topUsers = \App\Borrow::select('user_id', \DB::raw('count(*) as borrow_count'))
                                    ->groupBy('user_id')
                                    ->orderByDesc('borrow_count')
                                    ->take(5)
                                    ->get();

                                ?>
                                <h5 class="mb-0">{{ __('Top 5 Users with Most Books') }}</h5>
                                <ul>
                                    @foreach ($topUsers as $topUser)
                                        <li>{{ $topUser->user->name }} - {{ $topUser->borrow_count }} books</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>
                        </div>
                         <div id="Widget-line-chart2" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
        <!-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


        <script src="{{ asset('js/widget-statistic.js') }}"></script>
        <script src="{{ asset('js/widget-data.js') }}"></script>
        <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    @endpush
@endsection
