@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="d-flex justify-content-between box-header">
                    <h4 class="box-title fw-600">10 Pemakaian Kendaraan Teratas</h4>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-12 p-0">
                            <div id="topKendaraan" class="me-20"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets') }}/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
    <script>
        // function fetchDataAndUpdateChart() {
        //     $.ajax({
        //         url: "{{ route('dashboard.top-kendaraan') }}",
        //         method: 'POST',
        //         success: function(response) {
        //             var data = response.data.data;
        //             var categories = response.data.categories;

        //             var options = {
        //                 series: [{
        //                     name: '10 Pemakaian Kendaraan Teratas',
        //                     data: data
        //                 }],
        //                 xaxis: {
        //                     categories: categories
        //                 }
        //             };
        //             chart.updateOptions(options);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching data:', error);
        //         }
        //     });
        // }

        // // Initialize the chart with initial options
        // var options = {
        //     // Initial options (can be static or based on initial data)
        // };

        // var chart = new ApexCharts(document.querySelector("#topKendaraan"), options);
        // chart.render();

        // // Call the function to fetch data and update the chart
        // fetchDataAndUpdateChart();

        function getDataChart() {
            $.ajax({
                url: "{{ route('dashboard.top-kendaraan') }}",
                method: 'POST',
                success: function(response) {
                    var data = response.data.data;
                    var categories = response.data.categories;
                    renderChart(data, categories);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        function renderChart(data, categories) {
            var options = {
                series: [{
                    name: '10 Pemakaian Kendaraan Teratas',
                    data: data
                }],
                chart: {
                    type: 'bar',
                    height: 270,
                    stacked: false,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '35%',
                        endingShape: 'rounded'
                    },
                },
                legend: {
                    show: false,
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: categories
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                colors: ['#00baff', '#3762EA'],
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "" + val + ""
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#topKendaraan"), options);
            chart.render();
        }

        getDataChart();
    </script>
@endsection
