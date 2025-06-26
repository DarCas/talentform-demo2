<?php
/**
 * @var array $data
 * @var array $item
 */
?>
<div class="card mt-4 shadow mx-auto" style="width: 50%">
    <div class="card-body">
        <h5 class="card-title">Modifica messaggio</h5>

        <form action="{{ url()->current() }}" method="post" class="card-text">
            @csrf
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="inputNome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="inputNome"
                           value="{{ $data['nome'] ?? $item['nome'] ?? '' }}" required
                           name="nome" placeholder="Il tuo nome">
                </div>

                <div class="col-6 mb-3">
                    <label for="inputCognome" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="inputCognome"
                           value="{{ $data['cognome'] ?? $item['cognome'] ?? '' }}" required
                           name="cognome" placeholder="Il tuo cognome">
                </div>

                <div class="col-12 mb-3">
                    <label for="inputEmail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="inputEmail"
                           value="{{ $data['email'] ?? $item['email'] ?? '' }}" required
                           name="email" placeholder="La tua e-mail">
                </div>

                <div class="col-12 mb-3">
                    <label for="inputMessage" class="form-label">Messaggio</label>
                    <textarea class="form-control" id="inputMessage" rows="5"
                              name="messaggio" placeholder="Il tuo messaggio"
                              style="min-height: 150px">{{ $data['messaggio'] ?? $item['messaggio'] ?? '' }}</textarea>
                </div>

                <div class="col-4">
                    <a href="/backend/" class="btn w-100 btn-secondary">Chiudi</a>
                </div>
                <div class="col-8">
                    <button type="submit" class="btn w-100 block btn-success">Invia</button>
                </div>
            </div>
        </form>
    </div>
</div>
