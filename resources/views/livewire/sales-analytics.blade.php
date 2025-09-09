<div>
    <!-- Sales Analytics Component -->
    <h2>Sales Analytics</h2>
    <ul>
        @foreach($analytics as $item)
            <li>{{ $item['label'] }}: ${{ $item['value'] }}</li>
        @endforeach
    </ul>
</div>
