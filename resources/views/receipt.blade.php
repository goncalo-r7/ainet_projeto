<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CineMagic Cinemas</title>
    <style>
      <?=file_get_contents(public_path('build/assets/app-BPpGcTh_.css')); ?>
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
            <table class="border border-stone-950 w-90">
                <thead>
                    <tr>
                        <th class="border border-stone-950">TICKET ID</th>
                        <th class="border border-stone-950">THEATER</th>
                        <th class="border border-stone-950">SEAT</th>
                        <th class="border border-stone-950">MOVIE</th>
                        <th class="border border-stone-950">DATE</th>
                        <th class="border border-stone-950">PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->tickets as $ticket)
                    <tr >
                        <td class="border border-stone-950">{{$ticket->id ?? "UNKOWN ID"}}</td>
                        <td class="border border-stone-950">{{$ticket->seat->theater->name ?? "UNKOWN THEATER"}}</td>
                        <td class="border border-stone-950">{{$ticket->seat->row}}{{$ticket->seat->seat_number ?? "UNKOWN SEAT"}}</td>
                        <td class="border border-stone-950">{{$ticket->screening->movie->title ?? "UNKOWN MOVIE"}}</td>
                        <td class="border border-stone-950">{{$ticket->screening->date ?? "UNKOWN DATE"}}</td>
                        <td class="border border-stone-950">{{$ticket->screening->start_time ?? "UNKOWN TIME"}}</td>
                        <td class="border border-stone-950">{{$ticket->price ?? "UNKNOWN PRICE"}} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(isset($qr_codes))
        <div class="mb-8">
        @foreach($qr_codes as $qr_code)
            <div class="bg-white p-4 rounded shadow w-40 h-40">
            <img src="data:image/png;base64, {!! base64_encode($qr_code) !!}" alt="QR Code">
            </div>
        @endforeach
        </div>
        @endif
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
            padding: 24px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 24px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .border-stone-950 {
            border-color: #78716c;
        }
    </style>
</body>

</html>
