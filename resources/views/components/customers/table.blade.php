<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">Name</th>
            <th class="px-2 py-2 text-left hidden lg:table-cell">Email</th>
            <th class="px-2 py-2 text-center hidden xl:table-cell">Blocked</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $customer->name }}</td>
                <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $customer->email }}</td>
                <td class="px-2 py-2 text-center hidden xl:table-cell">{{ $customer->blocked}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
