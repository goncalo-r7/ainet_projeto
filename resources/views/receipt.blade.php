<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CineMagic Cinemas</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="mx-auto bg-white p-8 shadow-lg w-1/2">
        <div class="flex justify-between">
        <h2 class="text-2xl font-bold mb-6">CineMagic Cinemas Inc.</h2>
        <div class="w-1/3 ">
                <div class="h-32 bg-cover bg-[url('../img/CineMagic-black.png')] dark:bg-[url('../img/CineMagic-white.png')]">
                </div>
            </div>
        </div>
        <div class="flex justify-between mb-8">
            <div>
                <h3 class="font-bold text-lg pb-3">ENTITY DETAILS</h3>
                <p>P5MH+MJ Campus 2 - Morro do Lena, R. do Alto Vieiro Apt 4163</p>
                <p>2411-901 Leiria</p>
            </div>
            <div class="pt-24">
                <h3 class="font-bold text-lg pb-3">RECEIPT</h3>
                <p><b>EMISSION no:</b> #{{$purchase->id}}</p>
                <p><b>EMISSION DATE:</b> {{$purchase->date}}</p>
                <p><b>CLIENT NAME:</b> {{$purchase->customer_name}}</p>
                <p><b>CLIENT EMAIL:</b> {{$purchase->customer_email}}</p>
                <p><b>CLIENT NIF:</b> {{$purchase->nif}}</p>
            </div>
        </div>
        <div class="mt-16 mb-28">
            <table class="table-auto border-collapse w-full">
                <thead>
                    <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                        <th class="px-2 py-2 text-center hidden lg:table-cell">TICKET ID</th>
                        <th class="px-2 py-2 text-center">THEATER</th>
                        <th class="px-2 py-2 text-center hidden sm:table-cell">SEAT</th>
                        <th class="px-2 py-2 text-center hidden sm:table-cell">MOVIE</th>
                        <th class="px-2 py-2 text-center hidden sm:table-cell">DATE</th>
                        <th class="px-2 py-2 text-center hidden sm:table-cell">PRICE</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($purchase->tickets as $ticket)
                    <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                        <td class="px-2 py-2 text-center hidden lg:table-cell">{{$ticket->id}}</td>
                        <td class="px-2 py-2 text-center">{{$ticket->seat->theater->name}}</td>
                        <td class="px-2 py-2 text-center">{{$ticket->seat->row}}{{$ticket->seat->seat_number}}</td>
                        <td class="px-2 py-2 text-center hidden sm:table-cell">{{$ticket->screening->movie->title}}</td>
                        <td class="px-2 py-2 text-center hidden sm:table-cell">{{$ticket->screening->date}}</td>
                        <td class="px-2 py-2 text-center hidden sm:table-cell">{{$ticket->price}} €</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between font-bold text-xl">
                    <p>TOTAL</p>
                    <p>{{$purchase->total_price}}€</p>
                </div>
            </div>
        </div>
        <div class="mb-8">
            <hr>
            <h3 class="font-bold text-lg mb-3 mt-3">PAYMENT DETAILS</h3>
            <div class="flex justify-between">
                <p><b>PAYMENT METHOD:</b>&nbsp; {{$purchase->payment_type}}</p>
                <p><b>ENTITY REFERENCE:</b>&nbsp; {{$purchase->payment_ref}}</p>
            </div>
        </div>
        <hr>
    </div>
</body>

</html>