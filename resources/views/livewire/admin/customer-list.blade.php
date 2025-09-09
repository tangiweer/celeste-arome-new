<div>
    <h2 class="text-xl font-bold text-blue-300 mb-4">Customers</h2>
    <table class="w-full text-left bg-white/10 rounded-lg">
        <thead>
            <tr class="text-blue-300">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr class="border-b border-white/10">
                <td class="px-4 py-2">{{ $customer->name }}</td>
                <td class="px-4 py-2">{{ $customer->email }}</td>
                <td class="px-4 py-2">{{ $customer->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
