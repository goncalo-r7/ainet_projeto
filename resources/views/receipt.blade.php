<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CineMagic Cinemas</title>
    <style>
        <?=file_get_contents(public_path('build/assets/app-CAWSzT8F.css')); ?>
    </style>
</head>

<body>
    <div class="mx-auto bg-white p-28 shadow-lg px-auto">
        <div class="">
            <h2 class="text-2xl font-bold mb-6">CineMagic Cinemas Inc.</h2>
        </div>
        <div class="my-8">
            <div class="mt-8">
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
        <div class="mt-16 mb-10">
            <table style="width: 500px; border: 2px solid;">
                <thead>
                    <tr class="">
                        <th style="border: 2px solid;">TICKET ID</th>
                        <th style="border: 2px solid;">THEATER</th>
                        <th style="border: 2px solid;">SEAT</th>
                        <th style="border: 2px solid;">MOVIE</th>
                        <th style="border: 2px solid;">DATE</th>
                        <th style="border: 2px solid;">PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->tickets as $ticket)
                    <tr class="">
                        <td style="border: 2px solid;">{{$ticket->id ?? "UNKOWN ID"}}</td>
                        <td style="border: 2px solid;">{{$ticket->seat->theater->name ?? "UNKOWN THEATER"}}</td>
                        <td style="border: 2px solid;">{{$ticket->seat->row}}{{$ticket->seat->seat_number ?? "UNKOWN SEAT"}}</td>
                        <td style="border: 2px solid;">{{$ticket->screening->movie->title ?? "UNKOWN MOVIE"}}</td>
                        <td style="border: 2px solid;">{{$ticket->screening->date ?? "UNKOWN DATE"}}</td>
                        <td style="border: 2px solid;">{{$ticket->price ?? "UNKNOWN PRICE"}} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-8">
        @foreach($qr_codes as $qr_code)
            <div class="bg-white p-4 rounded shadow w-40 h-40">
            <img src="data:image/png;base64, {!! base64_encode($qr_code) !!}" alt="QR Code">
            </div>
        @endforeach
        </div>
        <div class="mb-6">
            <div class="w-1/4">
                <div class=" font-bold text-xl">
                    <p>TOTAL: {{$purchase->total_price}}€</p>
                </div>
            </div>
        </div>
        <div class="mb-8" style="width: 400px;">
            <hr>
            <h3 class="font-bold text-lg mb-3 mt-3">PAYMENT DETAILS</h3>
            <div class="mb-8">
                <p><b>PAYMENT METHOD:</b>&nbsp; {{$purchase->payment_type}}</p>
                <p><b>ENTITY REFERENCE:</b>&nbsp; {{$purchase->payment_ref}}</p>
            </div>
            <hr>
        </div>

    </div>
</body>

</html>
