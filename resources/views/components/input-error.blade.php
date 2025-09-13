@props(['messages'])

@if ($messages)
    <div class="status negative error-list">
        <ul>
            @foreach ((array) $messages as $message)
                <li>
                    {{-- <x-paragraph-icon
                        :icon="'error'"
                    > --}}
                        <p>{{ $message }}</p>
                    {{-- </x-paragraph-icon> --}}
                </li>
            @endforeach
        </ul>
    </div>
@endif
