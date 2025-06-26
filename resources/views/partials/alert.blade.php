<?php
/**
 * @var string $message
 * @var string $size
 * @var string $type
 */
?>
<div
    class="alert alert-{{ $type }} mx-auto shadow-lg"
    style="width: {{ $size }}"
    role="alert"
>
    {{ $message }}
</div>
