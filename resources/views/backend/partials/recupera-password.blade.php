<div class="card shadow mx-auto" style="width: 50%;">
    <div class="card-body">
        <form action="/backend/recupera-password" method="post" class="card-text">
            @csrf
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="inputUsernm" class="form-label">Username</label>
                    <input type="text" class="form-control" id="inputUsernm"
                           required name="usernm">
                </div>

                <div class="col-4">
                    <a href="/backend/" class="btn w-100 block btn-success">Torna indietro</a>
                </div>
                <div class="col-8">
                    <button type="submit" class="btn w-100 block btn-warning">Recupera password</button>
                </div>
            </div>
        </form>
    </div>
</div>
