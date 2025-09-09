<div>
    <!-- Customer Management Component -->
    <h2>Customer Management</h2>
    <ul>
        @foreach($customers as $customer)
            <li>{{ $customer->name }} ({{ $customer->email }})</li>
        @endforeach
    </ul>
</div>
