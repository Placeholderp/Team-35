{{-- Define the component property "errors". --}}
@props(['errors'])

{{-- Check if there are any error messages in the "errors" collection --}}
@if ($errors->any())

    <div {{ $attributes->merge(['class' => 'p-3 rounded-md bg-red-600 text-white']) }}>
        {{-- Display a bold header message notifying the user that something went wrong --}}
        <div class="font-medium">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm">
            {{-- Loop through all error messages and display each one in a list item --}}
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
