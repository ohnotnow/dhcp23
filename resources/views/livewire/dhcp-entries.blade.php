<div>
    <div>
        <!-- filers -->
    </div>
    <table>
        <thead>
            <tr>
                <th wire:click="sortOn('ip_address')" class="cursor-pointer">IP</th>
                <th wire:click="sortOn('mac_addres')" class="cursor-pointer">MAC</th>
                <th
                    wire:click="sortOn('hostname')"
                    class="cursor-pointer">Hostname</th>
                <th
                    wire:click="sortOn('added_by')"
                    class="cursor-pointer">Added By</th>
                <th
                    wire:click="sortOn('owner')"
                    class="cursor-pointer">Owner</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dhcpEntries as $entry)
                <tr>
                    <td>{{ $entry->ip_address }}</td>
                    <td>{{ $entry->mac_address }}</td>
                    <td>{{ $entry->hostname }}</td>
                    <td>{{ $entry->added_by }}</td>
                    <td>{{ $entry->owner }}</td>
                </tr>
            @endforeach
    </table>
</div>
