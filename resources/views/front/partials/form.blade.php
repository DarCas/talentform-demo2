<?php
/**
 * @var array|null $data
 */
?>
<div class="card shadow p-2">
    <form
        action="/sendmail"
        method="post"
        class="card-body"
    >
        @csrf
        <h2 class="card-title">Guestbook</h2>
        <div class="card-text">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <label for="inputNome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="inputNome"
                           value="{{ $data['nome'] ?? '' }}" required
                           name="nome" placeholder="Il tuo nome">
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <label for="inputCognome" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="inputCognome"
                           value="{{ $data['cognome'] ?? '' }}" required
                           name="cognome" placeholder="Il tuo cognome">
                </div>

                <div class="col-12 mb-3">
                    <label for="inputEmail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="inputEmail"
                           value="{{ $data['email'] ?? '' }}" required
                           name="email" placeholder="La tua e-mail">
                </div>

                <div class="col-12 mb-3">
                    <label for="inputMessage" class="form-label">Messaggio</label>
                    <textarea class="form-control" id="inputMessage" rows="5"
                              name="messaggio" placeholder="Il tuo messaggio"
                              style="min-height: 150px">{{ $data['messaggio'] ?? '' }}</textarea>
                </div>

                <div class="col-4">
                    <button type="reset" class="btn w-100 btn-secondary">Reset</button>
                </div>
                <div class="col-8">
                    <button type="submit" class="btn w-100 block btn-success">Invia</button>
                </div>
            </div>
        </div>
    </form>
</div>
