{{-- Define the component property "status".--}}
@props(['status'])

{{-- Check if the "status" property exists and is not empty. --}}
@if ($status)

    <div {{ $attributes->merge(['class' => 'font-medium text-sm bg-emerald-500 py-3 px-4 text-white rounded']) }}>
        {{-- Display the content of the "status" variable. --}}
        {{ $status }}
    </div>
@endif 
