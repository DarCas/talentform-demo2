<?php
/**
 * @var array|null $errors
 */
?>
@if ($errors)
    <div
        class="alert alert-danger mx-auto shadow-lg"
        style="width: 100%"
        role="alert"
    >

        <h3>Si sono verificati errori:</h3>
        <ul>
            @foreach ($errors as $key => $value)
                <li>{{ $key }}: {{ $value }}</li>
            @endforeach
        </ul>

    </div>
@endif
