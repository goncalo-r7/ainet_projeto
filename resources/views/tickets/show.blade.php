<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Information</title>
    <style>
      <?=file_get_contents(public_path('build/assets/app-CbtsLLvJ.css')); ?>
    </style>
</head>
<body>
    <div class="container bg-white p-6 rounded-lg shadow-lg w-1/2">
        <h1 class="text-2xl font-bold mb-4">Ticket Information</h1>
        
        <div class="mb-4">
            <label for="ticket_id" class="block text-sm font-medium text-gray-700">ID</label>
            <input type="text" id="ticket_id" name="ticket_id" value='{{$ticket->id ?? "UNKOWN ID"}}' class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="theater_name" class="block text-sm font-medium text-gray-700">Theater</label>
            <input type="text" id="theater_name" name="theater_name" value='{{$ticket->seat->theater->name ?? "UNKOWN THEATER"}}'  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="seat_row" class="block text-sm font-medium text-gray-700">Seat</label>
            <input type="text" id="seat_row" name="seat_row" value='{{$ticket->seat->row}}{{$ticket->seat->seat_number ?? "UNKOWN SEAT"}}'  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="movie_title" class="block text-sm font-medium text-gray-700">Movie</label>
            <input type="text" id="movie_title" name="movie_title" value='{{$ticket->screening->movie->title ?? "UNKOWN MOVIE"}}'  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="text" id="date" name="date" value='{{$ticket->screening->date ?? "UNKOWN DATE"}}'  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="text" id="price" name="price" value='{{$ticket->price ?? "UNKNOWN PRICE"}} €'  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>        
    </div>
    @if(isset($qr_code))
    <div class="mb-8">
            <div class="bg-white p-4 rounded shadow w-40 h-40">
            <img src="data:image/png;base64, {!! base64_encode($qr_code) !!}" alt="QR Code">
            </div>
    </div>
    @endif
</body>
</html>