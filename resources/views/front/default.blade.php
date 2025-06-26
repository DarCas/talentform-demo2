<?php
/**
 * @var string $alertTemplate
 * @var array $css
 * @var array|null $errors
 * @var string $formTemplate
 * @var string $guestbookTemplate
 * @var array $js
 * @var string $year
 * @var string $title
 */
?><!DOCTYPE html>
<html lang="it">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @foreach($css as $href)
        <link rel="stylesheet" href="{{ $href }}">
    @endforeach
</head>

<body>
<header class="container-fluid bg-dark shadow-lg mb-5">
    <nav class="navbar">
        <a class="navbar-brand d-block mx-auto" href="https://casertano.name" target="_blank">
            <img src="https://gravatar.com/avatar/5b70cf3b9140485421b906e45f606648?s=50" alt=""
                 class="img-fluid rounded-circle">
        </a>
    </nav>
</header>
<main class="container">
    <div class="row">
        <div class="col-12 col-md-4">
            @include('partials.form.errors', ['errors' => $errors])

            {!! $formTemplate !!}
        </div>

        <div class="col-12 col-md-8">
            @if ($alertTemplate)
                {!! $alertTemplate !!}
            @endif

            {!! $guestbookTemplate !!}
        </div>
    </div>
</main>
<div class="container mt-5">
    <hr>

    <footer class="row">
        <div class="col-4 d-flex align-items-center">
            <div class="mb-3 text-body-secondary">
                <a class="text-body-secondary" href="https://getbootstrap.com/"
                   target="_blank" title="Bootstrap" aria-label="Bootstrap"><i class="bi bi-bootstrap"></i></a>
                &middot;&middot;&middot;
                <strong>DarCas Software &copy; {{ $year }}</strong>
            </div>
        </div>

        <ul class="nav col-8 justify-content-end list-unstyled d-flex">
            <li class="ms-3">
                <a class="text-body-secondary" href="https://www.instagram.com/darcas"
                   target="_blank" aria-label="Instagram" title="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-body-secondary" href="https://github.com/DarCas"
                   aria-label="GitHub" title="GitHub" target="_blank">
                    <i class="bi bi-github"></i>
                </a>
            </li>
        </ul>
    </footer>
</div>
@foreach($js as $src)
    <script src="{{ $src }}"></script>
@endforeach
</body>
</html>
