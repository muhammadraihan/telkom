@if (!empty($result))
<table>
    <thead>
        <tr>
            <th>NAMA MODULE</th>
            @foreach ($result['module'][0]["witel"] as $item) <th>{{$item["witel_name"]}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($result['module'] as $module)
        <tr>
            <td>{{$module['module_name']}}</td>
            @foreach ($module['witel'] as $count_witel)
            <td>{{$count_witel['module_count']}}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@else
<table>
    <thead>
        <tr>
            <th>NAMA MODULE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>EMPTY DATA</td>
        </tr>
    </tbody>
</table>
@endif