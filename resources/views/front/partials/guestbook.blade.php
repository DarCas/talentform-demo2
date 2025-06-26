<?php
/**
 * @var array $items Contiene i messaggi da visualizzare (in base alla pagina)
 * @var string $pagination Contiene la navigazione della paginazione fatta in Bootstrap 5
 */
?>
<div class="row">
    <div class="col-12">
        <div class="card shadow p-2">
            <div class="card-body">
                <div class="card-text">
                    <div class="row">
                        @forelse($items as $row)
                            @if(!$loop->first)
                                <hr class="mt-2 mb-4">
                            @endif

                            <div class="col-12">
                                <h4>
                                    {{ $row['cognome'] }}, {{ $row['nome'] }}
                                    <a href="mailto:{{ $row['email'] }}">
                                        <i class="bi bi-envelope-at fs-6"></i>
                                    </a>
                                </h4>

                                <blockquote class="border-start border-3 py-1 ps-3 ms-2">
                                    {!! nl2br($row['messaggio']) !!}
                                </blockquote>

                                <p>
                                    <i class="bi bi-calendar-event"></i>
                                    <small>{{ $row->formatDataRicezione() }}</small>
                                </p>
                            </div>
                        @empty
                            <div class="col-12 py-5">
                                @include('partials.alert', [
                                    'message' => 'Non ci sono messaggi',
                                    'type' => 'info',
                                    'size' => '100%',
                                ])
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow p-2 mt-4">
            <div class="card-body">
                <div class="card-text">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    </div>
</div>
