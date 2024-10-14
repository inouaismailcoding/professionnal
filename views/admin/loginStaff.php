<?php if(isset($_SESSION['errors'])):  ?>
    <?php foreach($_SESSION['errors'] as $errorsArray):  ?>
        <?php foreach($errorsArray as $errors):  ?>
            
                <div class="alert alert-danger">
                <?php foreach($errors as $error):  ?>
                    <li><?=$error ;?></li>
                    <?php endforeach  ?> 
                </div>
            
        <?php endforeach  ?>  
    <?php endforeach  ?>    
<?php endif  ?>
<?php session_destroy();  ?>
<h1>Formulaire de connexion</h1>

<div class="form-connect" class="container w-auto" style="width: 50%;">
    <form action="<?='/'.HTDOCS.'/admin/loginStaff/'; ?>" method="post">
        <div class="form-group mb-2">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="form-group mb-2">
        <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <button type="submit" class="btn btn-primary mt-1">Se Connecter</button>
        <div class="text-decoration-none text-center">
        
        </div>
    </form>
</div>