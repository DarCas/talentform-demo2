<div class="card shadow mx-auto" style="width: 50%;">
    <div class="card-body">
        <form action="/backend/login" method="post" class="card-text">
            @csrf
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="inputUsernm" class="form-label">Username</label>
                    <input type="text" class="form-control" id="inputUsernm"
                           required name="usernm">
                </div>

                <div class="col-6 mb-3">
                    <label for="inputPasswd" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPasswd"
                           required name="passwd">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn w-100 block btn-success">Accedi</button>
                </div>

                <div class="col-12 mt-3">
                    <a href="/backend/recupera-password">Ho dimenticato la password</a>
                </div>
            </div>
        </form>
    </div>
</div>
