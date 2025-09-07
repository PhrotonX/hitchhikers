@props(
    [
        'disabled' => false,
        'autocomplete' => null,
        'id' => null,
        'name' => null,
        'label' => null,
        'placeholder' => null,
        'required' => false,
        'type' => 'text',
        'autofocus' => false,
        'value' => null,
    ]
)
{{-- <input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}> --}}

<span class="form-input">
    <label
        for="{{$id}}"
        @if ($required)
            class="required"
        @endif
    >
        <p>
            {{$label}}
        </p>
    </label>
    <input 
        type="{{$type}}"
        @if($name != null)
            name="{{$name}}"
        @endif
        @if($id != null)
            id="{{$id}}"
        @endif
        @if($placeholder != null)
            placeholder="{{$placeholder}}"
        @endif
        @disabled($disabled)
        @if ($autocomplete != null)
            autocomplete="{{$autocomplete}}"
        @endif
        @required($required)
        @if($autofocus)
            autofocus
        @endif
        @if($value !== null)
            value="{{ $value }}"
        @endif
        
        >
</span>