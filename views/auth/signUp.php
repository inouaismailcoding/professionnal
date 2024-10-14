<h1>Formulaire d'inscription</h1>

<div class="form-connect" class="container w-auto" style="width: 50%;">
    <form action="<?= '/'.HTDOCS.'/signUp/'?>" method="post">
       
        <div class="form-group mb-2">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="form-group mb-2">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>
        <div class="form-group mb-2">
        <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <button type="submit" class="btn btn-primary mt-1 mb-2">S'inscrire</button>
        <div class="text-decoration-none text-center">
        <small >si disposer déja d'un compte <a href="<?= '/'.HTDOCS.'login/'?>">cliquer Ici</a></small>
        </div>
    </form>
</div>