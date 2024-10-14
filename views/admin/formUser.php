<h2><?=isset($params['user'])? "Modifier un Utilisateur":"Créer un Utilisateur"  ?> </h2>
<div class="form-group">
<form   action="<?= isset($params['user']) ? '/'.HTDOCS.'/admin/edit/'.$params['user']->id : '/'.HTDOCS.'/admin/create/' ; ?>" method="POST"  style="width: 80%;">
<?php if (isset($params['user']->id)): ?> 
            <input type="hidden" class="form-control" name="id" value="<?= $params['user']->id; ?>" >
        <?php endif ?>
<div class="form-group mb-2">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" value="<?=isset($params['user']->username) ? $params['user']->username:""; ?>">
        </div>
        <div class="form-group mb-2">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?=isset($params['user']->email) ? $params['user']->email:"";?>">
        </div>
        <div class="form-group mb-2">
            <label for="phone">Phone</label>
            <input type="tel" class="form-control" name="phone" id="phone"  value="<?=isset($params['user']->phone) ? $params['user']->phone:""; ?>">
        </div>
        <?php if (!isset($params['user']->password)): ?> 
            <div class="form-group mb-2">
        <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <?php endif ?>
        <div class="form-group mb-2">
        <label for="role">Role</label>
        <select name="role" id="role" class="text-center  form-control">
            <option value="staff" <?= (isset($params['user']->role) and $params['user']->role==='staff')   ? "selected" : "";?> >staff</option>
            <option value="admin"  <?= (isset($params['user']->role) and $params['user']->role==='admin')  ? "selected" : "";?> >admin</option>
            <option value="manager" <?= (isset($params['user']->role) and $params['user']->role==='manager')  ? "selected" : "";?> >manager</option>
        </select>
        </div>
    <button type="submit"class="btn btn-primary mt-1 mb-2 w-100" name=""><?= isset($params['user'])? "Enregistrer les modifications":"Enregistrer nouveau Utilisateur" ?></button>
</form>
</div>