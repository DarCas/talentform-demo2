<?php
/**
 * @var array $items Contiene i messaggi da visualizzare (in base alla pagina)
 * @var string $pagination Contiene la navigazione della paginazione fatta in Bootstrap 5
 */

/**
 * Ordinamento ASC/DESC per colonna ID di default
 */
$idDesc = false;

/**
 * Ordinamento ASC/DESC per colonna Fullname di default
 */
$fullnameDesc = false;

/**
 * Ordinamento ASC/DESC per colonna Data Ricezione di default
 */
$dataRicezioneDesc = false;

if (!request()->query->has('sort')) {
    /**
     * Imposto un valore di default se non ho selezionato alcun ordinamento.
     */
    request()->query->set('sort', 'id');
}

if (request()->query('sort') === 'id') {
    /**
     * Se sto ordinando per questa colonna, imposto l'ordinamento DESC
     * al contrario rispetto a quello attuale. Quindi, se è FALSE lo cambio in TRUE
     * e viceversa.
     */
    $idDesc = !request()->query('desc');
}

if (request()->query('sort') === 'fullname') {
    /**
     * Se sto ordinando per questa colonna, imposto l'ordinamento DESC
     * al contrario rispetto a quello attuale. Quindi, se è FALSE lo cambio in TRUE
     * e viceversa.
     */
    $fullnameDesc = !request()->query('desc');
}

if (request()->query('sort') === 'data_ricezione') {
    /**
     * Se sto ordinando per questa colonna, imposto l'ordinamento DESC
     * al contrario rispetto a quello attuale. Quindi, se è FALSE lo cambio in TRUE
     * e viceversa.
     */
    $dataRicezioneDesc = !request()->query('desc');
}
?>
<table class="table table-bordered shadow table-striped align-middle">
    <thead class="table-dark">
    <tr>
        <th class="text-end" scope="col">
            <a href="{{ request()->fullUrlWithQuery(['page' => 1, 'sort' => 'id', 'desc' => $idDesc]) }}"
               class="text-white">#</a>

            <x-sort-icon
                column="id"
                :desc="$idDesc"
            />
        </th>
        <th scope="col">
            <a href="{{ request()->fullUrlWithQuery(['page' => 1, 'sort' => 'fullname', 'desc' => $fullnameDesc]) }}"
               class="text-white">Cognome, Nome</a>

            <x-sort-icon
                column="fullname"
                :desc="$fullnameDesc"
            />
        </th>
        <th scope="col">E-mail</th>
        <th scope="col">Messaggio</th>
        <th class="text-end" scope="col">
            <a href="{{ request()->fullUrlWithQuery(['page' => 1, 'sort' => 'data_ricezione', 'desc' => $dataRicezioneDesc]) }}"
               class="text-white">Data inserimento</a>

            <x-sort-icon
                column="data_ricezione"
                :desc="$dataRicezioneDesc"
            />
        </th>
        <th colspan="2" scope="col" style="width: 110px">&nbsp;</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <td colspan="7" class="px-4 pt-4">
            <div class="row">
                <div class="col-3">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            @if(request()->query('perPage', 10) == -1)
                                Tutti
                            @else
                                {{ request()->query('perPage', 10) }}
                            @endif
                        </button>
                        <ul class="dropdown-menu shadow">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['perPage' => 10]) }}">10</a>
                                <a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['perPage' => 25]) }}">25</a>
                                <a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['perPage' => 50]) }}">50</a>
                                <a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['perPage' => -1]) }}">Tutti</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-9">
                    {!! $pagination !!}
                </div>
            </div>
        </td>
    </tr>
    </tfoot>

    <tbody class="table-group-divider">
    @forelse($items as $row)
        <tr>
            <th class="text-end" scope="row">{{ $row['id'] }}</th>
            <td>{{ $row['cognome'] }}, {{ $row['nome'] }}</td>
            <td>{{ $row['email'] }}</td>
            <td>{{ $row['messaggio'] }}</td>
            <td class="text-end">{{ $row->formatDataRicezione('d/m/Y H:i:s') }}</td>
            <td style="width: 20px" class="text-center">
                <a class="btn btn-sm btn-info"
                   href="/backend/edit/{{ $row['id'] }}"
                >
                    <i class="bi bi-pencil"></i>
                </a>
            </td>
            <td style="width: 20px" class="text-center">
                <a class="btn btn-sm btn-danger"
                   href="/backend/delete/{{ $row['id'] }}"
                >
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="py-5">
                @include('partials.alert', [
                    'message' => 'Non ci sono messaggi',
                    'type' => 'info',
                    'size' => '50%',
                ])
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
