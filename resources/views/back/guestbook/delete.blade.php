<?php
/**
 * @var array $item
 */
?>
<div class="card mt-4 shadow mx-auto" style="width: 50%">
    <div class="card-body">
        <form action="{{ url()->current() }}" method="post" class="card-text">
            @csrf
            <div class="alert alert-danger" role="alert">
                Sei sicuro di voler cancellare il messaggio
                di <strong>{{ $item['cognome'] }}, {{ $item['nome'] }}</strong>?
            </div>

            <div class="row">
                <div class="col-4">
                    <a href="/backend" class="btn w-100 block btn-success">No</a>
                </div>
                <div class="col-8">
                    <button type="submit" class="btn w-100 block btn-danger">Sì</button>
                </div>
            </div>
        </form>
    </div>
</div>
