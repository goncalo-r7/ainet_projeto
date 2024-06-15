

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CineMagic Cinemas</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            display: flex;
        }

        body>nav {
            min-width: 150px;
            background-color: lightgray;
            margin-right: 20px;
        }

        body>nav ul {
            list-style-type: none;
            padding-left: 15px;
            margin-bottom: 10px;
        }

        body>nav li {
            margin-bottom: 10px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="mx-auto bg-white p-8 shadow-lg w-1/2">
        <h2 class="text-2xl font-bold mb-6">CineMagic Cinemas Inc.</h2>
        <div class="flex justify-between mb-8">
            <div>
                <p>1912 Harvest Lane</p>
                <p>New York, NY 12210</p>
            </div>
            <div>
                <h3 class="font-bold text-lg">RECEIPT</h3>
                <p>Receipt # US-001</p>
                <p>Receipt Date: 11/02/2019</p>
            </div>
        </div>

        <table class="w-full mb-8 border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-200 px-4 py-2">QTY</th>
                    <th class="border border-gray-200 px-4 py-2">DESCRIPTION</th>
                    <th class="border border-gray-200 px-4 py-2 text-right">UNIT PRICE</th>
                    <th class="border border-gray-200 px-4 py-2 text-right">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-200 px-4 py-2 text-center">1</td>
                    <td class="border border-gray-200 px-4 py-2">Front and rear brake cables</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$100.00</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$100.00</td>
                </tr>
                <tr>
                    <td class="border border-gray-200 px-4 py-2 text-center">2</td>
                    <td class="border border-gray-200 px-4 py-2">New set of pedal arms</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$15.00</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$30.00</td>
                </tr>
                <tr>
                    <td class="border border-gray-200 px-4 py-2 text-center">3</td>
                    <td class="border border-gray-200 px-4 py-2">Labor 3hrs</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$5.00</td>
                    <td class="border border-gray-200 px-4 py-2 text-right">$15.00</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between">
                    <p>Subtotal</p>
                    <p>$145.00</p>
                </div>
                <div class="flex justify-between">
                    <p>Sales Tax 6.25%</p>
                    <p>$9.06</p>
                </div>
                <div class="flex justify-between font-bold text-xl">
                    <p>TOTAL</p>
                    <p>$154.06</p>
                </div>
            </div>
        </div>
        <div class="mb-8">
            <p class="font-bold">Terms & Conditions</p>
            <p>Payment is due within 15 days</p>
            <p>Please make checks payable to: East Repair Inc.</p>
        </div>
        <div class="text-right">
            <p class="font-bold">John Smith</p>
        </div>
    </div>
</body>
</html>
