{{-- @if ($item->Depth == '2') --}}
<tr @if ($item->BOMType === 'P') data-widget="expandable-table" aria-expanded="true" @endif>
    <td>
        @if ($item->BOMType === 'P')
            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
        @endif
        {{ $item->lineage . ' ' . $item->Item . ' - ' . $item->ItemDescription }}
    </td>

    {{-- <td>{{ $item->ItemDescription }}</td>
    <td>{{ $item->UoM }}</td>
    <td>{{ $item->qty }}</td>
    <td>{{ $item->Curr }}</td>
    <td>{{ $item->Price }}</td> --}}
    {{-- <td>{{ $item->Depth }}</td>
    <td>{{ $item->parent }}</td> --}}
</tr>


@foreach ($tree as $child)
    @if ($child->parent === $item->Item)
        <tr class="expandable-body">
            <td>
                <div class="p-0">
                    <table class="table table-hover">
                        <tbody>
                            @include('partials.tree_item_first', ['item' => $child])
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    @endif
@endforeach
