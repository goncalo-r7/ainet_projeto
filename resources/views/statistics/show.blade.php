@extends('layouts.main')

@section('header-title', 'Statistics')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('main')


    <div class="flex flex-row space-x-6">
        <div id="bar-chart" class="flex-1 flex flex-col p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="flex-1">
                <section>

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Number of Tickets Sold for Sessions per Day
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


        <div class="flex-initial flex flex-col p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="flex-initial">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Gender Distribution of Films                        </h2>
                            <p>
                                (Last 30 Days Sessions)
                            </p>
                    </header>
                    <div>

                        <div class="py-6 mt-4 grid place-items-center px-2">
                            <div id="pie-chart"></div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
        <div class="flex-1 flex flex-col p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg w-full">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 pb-3">
                    Top 3 most sold out movies  </h2>
            </header>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Movie ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Movie Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tickets sold
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topMovies as $movie)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $movie->movie_id }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $movie->movie_title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $movie->tickets_sold }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-button id="exportBtn" text="Export top 3 most sold out movies to Excel" type="primary" class="pt-5 text-center"/>
        </div>
    </div>
    <br>
    <div>
        <div class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
            <div class="relative mx-4 mt-4 flex flex-col gap-4 overflow-hidden rounded-none bg-transparent bg-clip-border text-gray-700 shadow-none md:flex-row md:items-center">

                <div>
                    <h2 class="block font-sans text-lg font-semibold leading-relaxed tracking-normal text-blue-gray-900 antialiased px-4 py-2">
                        Ticket Revenues Over This Year (€)
                    </h2>
                </div>
            </div>
            <div class="pt-6 px-2 pb-0">
                <div id="line-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

        <script>
            const revenues = <?php echo $jsonDataRevenues; ?>;
            const ticketsSold = <?php echo $jsonDataTicketsSold; ?>;
            const chartConfig = {
                series: [
                    {
                        name: "Sales",
                        data: revenues,
                    },
                ],
                chart: {
                    type: "line",
                    height: 240,
                    toolbar: {
                        show: false,
                    },
                },
                title: {
                    show: false,
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ["#020617"],
                stroke: {
                    lineCap: "round",
                    curve: "smooth",
                },
                markers: {
                    size: 0,
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
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
                    y: {
                        formatter: function(value) {
                            return "Revenue: " + value + "€";
                        }
                    }
                },
            };

            const chart3 = new ApexCharts(document.querySelector("#line-chart"), chartConfig);
            chart3.render();
        </script>




    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
    document.getElementById('exportBtn').addEventListener('click', function() {
        const movies = @json($topMovies);

        const worksheet = XLSX.utils.json_to_sheet(movies);
        console.log(movies, worksheet);

        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Top 3 Movies");

        const filename = "Top_3_Movies.xlsx";

        // Write the workbook and trigger the download
        XLSX.writeFile(workbook, filename);
    });
    </script>
    <script>
        const jsonDataTicketsCount = <?php echo $jsonDataTicketsCount; ?>;
        const jsonDataCombined = <?php echo $jsonDataCombined; ?>;
        const jsonDataGenreLabel = <?php echo $jsonDataGenreLabel; ?>;
        const jsonDataGenrePerc = <?php echo $jsonDataGenrePerc; ?>;




        const chartConfig1 = {
            series: [{
                name: "Tickets Sold",
                data: jsonDataTicketsCount,
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

        const chart = new ApexCharts(document.querySelector("#bar-chart"), chartConfig1);

        chart.render();

        const chartConfig2 = {
            series: jsonDataGenrePerc,
            chart: {
                type: "pie",
                width: 280,
                height: 280,
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
            colors: ["#020617", "#ff8f00", "#00897b", "#1e88e5", "#d81b60", "#993399"],
            legend: {
                show: false,
            },
            labels: jsonDataGenreLabel,
        };

        const chart2 = new ApexCharts(document.querySelector("#pie-chart"), chartConfig2);

        chart2.render();
    </script>
@endsection
