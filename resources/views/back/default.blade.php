<?php
/**
 * @var string $alertTemplate
 * @var array $css
 * @var array|null $errors
 * @var string $content
 * @var array $js
 * @var string $year
 * @var string $title
 * @var array $user
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
<header class="container bg-dark rounded shadow-lg mt-1 mb-5 px-3 py-3">
    <div class="row">
        <div class="col-6">
            <a href="https://casertano.name" target="_blank">
                <img src="https://gravatar.com/avatar/5b70cf3b9140485421b906e45f606648?s=50" alt=""
                     class="rounded">
            </a>
            @if($user)
                <div class="btn-group" role="group" aria-label="">
                    <a href="/backend"
                       class="btn btn-secondary">Guestbook</a>
                    <a href="/backend/users"
                       class="btn btn-secondary">Utenti</a>
                </div>
            @endif
        </div>
        <div class="col-6 text-end">
            @if($user)
                <div class="btn-group-lg">
                    <button type="button" class="btn btn-info dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $user['fullname'] }}
                    </button>
                    <ul class="dropdown-menu bg-danger dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item bg-danger text-white"
                               href="/backend/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</header>
<main class="container">
    @include('partials.form.errors', ['errors' => $errors])

    {!! $alertTemplate !!}

    {!! $content !!}
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
