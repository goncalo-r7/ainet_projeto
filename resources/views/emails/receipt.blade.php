<!DOCTYPE html>
<html>

<head>
    <title>Receipt - Ticket(s) Purchase</title>
    <style>
      <?=file_get_contents(public_path('build/assets/app-BPpGcTh_.css')); ?>
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Here is your purchase details</h1>

        <p class="mb-2">Dear customer, please find your purchase receipt attached to this email.</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p><b>EMISSION no:</b> #{{$purchase->id}}</p>
                <p><b>EMISSION DATE:</b> {{$purchase->date}}</p>
            </div>
            <div>
                <p><b>CLIENT NAME:</b> {{$purchase->customer_name}}</p>
                <p><b>CLIENT EMAIL:</b> {{$purchase->customer_email}}</p>
                <p><b>CLIENT NIF:</b> {{$purchase->nif}}</p>
            </div>
        </div>
    </div>
</body>

</html>