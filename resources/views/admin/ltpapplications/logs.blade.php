
{{-- <div class="">
    <x-bladewind::timelines position="left" stacked="true">
        @foreach ($logs as $key => $log)
            <x-bladewind::timeline
                completed="true"
                date="{{ $log->created_at->format('F d, Y h:i A') }}"
                content="{!! $_helper->formatTimelineDescription($log) !!}"
                last="{{ $key == count($logs) - 1 ? true : false }}" />
        @endforeach
    </x-bladewind::timelines>
</div> --}}
<div class="">
    <table class="table ">
        <tbody>
            @foreach ($logs as $key => $log)
                <tr class="border-0 ">
                    <td class="text-nowrap border-bottom-0 align-middle ">
                        <div class="fw-bold text-right">{{ $log->created_at->format('F d, Y') }}</div>
                        <div class="fw-bold text-right">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="text-nowrap border-bottom-0 align-middle">
                         <i class="fas fa-check-circle text-success text-xl"></i>
                    </td>
                    <td class="text-nowrap border-bottom-0 align-middle">
                        {!! $_helper->formatTimelineDescription($log) !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>