<?php
$dataTicketsCount = [];
foreach ($dailyStats as $day => $stats) {
    $dataTicketsCount[] = $stats['ticketsSold'];
}

$jsonDataTicketsCount = json_encode($dataTicketsCount);

$dataCombined = [];
foreach ($dailyStats as $day => $stats) {
    $dataCombined[] = $day . ' (' . $stats['percentage'] . '%)';
}

$jsonDataCombined = json_encode($dataCombined);

?>
@extends('layouts.main')

@section('header-title', 'Statistics')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('main')


    <div class="flex flex-row space-x-6">
        <div class="flex-1 flex flex-col p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="flex-1">
                <section>

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Daily Ticket Sales per Screening
                        </h2>
                        <p>
                            (Percentage of Seats Purchased)
                        </p>
                    </header>
                    <div>
                        <div class="pt-6 px-2 pb-0">
                            <div id="bar-chart"></div>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                    <script>
                        const jsonDataTicketsCount = <?php echo $jsonDataTicketsCount; ?>;
                        const jsonDataCombined = <?php echo $jsonDataCombined; ?>;




                        const chartConfig = {
                            series: [{
                                name: "Tickets Sold",
                                data: jsonDataTicketsCount, // Use the JSON data here
                            }],
                            chart: {
                                type: "bar",
                                height: 240,
                                toolbar: {
                                    show: false,
                                },
                            },
                            title: {
                                show: "",
                            },
                            dataLabels: {
                                enabled: false,
                            },
                            colors: ["#020617"],
                            plotOptions: {
                                bar: {
                                    columnWidth: "40%",
                                    borderRadius: 2,
                                },
                            },
                            xaxis: {
                                axisTicks: {
                                    show: false,
                                },
                                axisBorder: {
                                    show: false,
                                },
                                labels: {
                                    style: {
                                        colors: "#616161",
                                        fontSize: "12px",
                                        fontFamily: "inherit",
                                        fontWeight: 400,
                                    },
                                },
                                categories: jsonDataCombined,
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: "#616161",
                                        fontSize: "12px",
                                        fontFamily: "inherit",
                                        fontWeight: 400,
                                    },
                                },
                            },
                            grid: {
                                show: true,
                                borderColor: "#dddddd",
                                strokeDashArray: 5,
                                xaxis: {
                                    lines: {
                                        show: true,
                                    },
                                },
                                padding: {
                                    top: 5,
                                    right: 20,
                                },
                            },
                            fill: {
                                opacity: 0.8,
                            },
                            tooltip: {
                                theme: "dark",
                            },
                        };

                        const chart = new ApexCharts(document.querySelector("#bar-chart"), chartConfig);

                        chart.render();
                    </script>
                </section>
            </div>
        </div>


        <div class="flex-1 flex flex-col p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="flex-1">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Daily Ticket Sales per Screening
                        </h2>
                        <p>
                            (Percentage of Seats Purchased)
                        </p>
                    </header>
                    <div>
                        <div class="pt-6 px-2 pb-0">
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>
@endsection
