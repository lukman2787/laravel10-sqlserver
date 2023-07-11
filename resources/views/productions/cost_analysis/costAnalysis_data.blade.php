<table>
    <thead>

        <tr>
            <th>Code Ass</th>
            <th>Code Sub Ass</th>
            <th>Code Sub Ass</th>
            <th>Code Sub Ass</th>
            <th>Code Sub Ass</th>
            <th>Code Sub Ass</th>
            <th>Code Item</th>
            <th>Item Description</th>
            <th>Qty</th>
            <th>UoM</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>

            <td>{{ $induk->depth }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $induk->ItemCode }}</td>
            <td>{{ $induk->ItemName }}</td>
            <td>{{ $induk->Quantity }}</td>
            <td>{{ $induk->UoM }}</td>
            <td>{{ $induk->Price }}</td>
        </tr>
        @foreach ($data as $Item)
            <tr>
                <td></td>
                <td>
                    @if ($Item->Depth == '2')
                        {{ $Item->lineage }}
                    @endif
                </td>
                <td>
                    @if ($Item->Depth == '3')
                        {{ $Item->lineage }}
                    @endif
                </td>
                <td>
                    @if ($Item->Depth == '4')
                        {{ $Item->lineage }}
                    @endif
                </td>
                <td>
                    @if ($Item->Depth == '5')
                        {{ $Item->lineage }}
                    @endif
                </td>
                <td>
                    @if ($Item->Depth == '6')
                        {{ $Item->lineage }}
                    @endif
                </td>
                <td>{{ $Item->Item }}</td>
                <td>{{ $Item->ItemDescription }}</td>
                <td>{{ $Item->qty }}</td>
                <td>{{ $Item->UoM }}</td>
                <td>{{ $Item->Price }}</td>
            </tr>


            {{-- @foreach ($data as $childItem)
                @if ($childItem->parent === $Item->Item)
                    <tr>
                        <td></td> <!-- Empty column for indentation -->
                        <td></td>
                        <td>{{ $childItem->lineage }}</td>
                    </tr>
                    <!-- Add more nested loops if required for deeper hierarchy levels -->
                    @foreach ($data as $childItemTwo)
                        @if ($childItemTwo->parent === $childItem->Item)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $childItemTwo->lineage }}</td>
                            </tr>
                            <!-- Add more nested loops if required for deeper hierarchy levels -->
                            @foreach ($data as $childItemThree)
                                @if ($childItemThree->parent === $childItemTwo->Item)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $childItemThree->lineage }}</td>
                                    </tr>
                                    <!-- Add more nested loops if required for deeper hierarchy levels -->
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach --}}
        @endforeach
    </tbody>
</table>
