
     @props(['disabled' => false, 'errors', 'type' => 'text', 'label' => false])

     <?php
         /** 
          * These PHPDoc comments provide type hints for IDEs:
          * @var \Illuminate\Support\ViewErrorBag $errors
          * @var \Illuminate\View\ComponentAttributeBag  $attributes
          */
     ?>
     
     <?php
         // Define CSS classes for different input states:
         $errorClasses = 'border-red-600 focus:border-red-600 ring-1 ring-red-600 focus:ring-red-600';
         $defaultClasses = '';
         $successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';
     
         // This is used for error checking and retrieving old input values.
         $attributeName = preg_replace('/(\w+)\[(\w+)]/', '$1.$2', $attributes['name']);
     ?>
     
     <div>
         {{-- If a label is provided, render it inside a <label> tag --}}
         @if ($label)
             <label>{{$label}}</label>
         @endif
     
         {{-- Check if the input type is 'select' to render a dropdown --}}
         @if ($type === 'select')
             <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
                 'class' => 'border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full ' .
                  ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses : $defaultClasses))
             ]) !!}>
                 {{-- Render inner content passed into the component --}}
                 {{ $slot }}
             </select>
         @else
             {{-- For non-select input types. --}}
             <input {{ $disabled ? 'disabled' : '' }} type="{{$type}}" {!! $attributes->merge([
                 // Merge the same base and conditional CSS classes as above.
                 'class' => 'border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full ' .
                  ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses : $defaultClasses))
             ]) !!}>
         @endif
     
         {{-- If there is a validation error for the attribute, display the error message --}}
         @error($attributeName)
             <small class="text-red-600"> {{ $message }}</small>
         @enderror
     </div>
     