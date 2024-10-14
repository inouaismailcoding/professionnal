<?php if(isset($_SESSION['auth']) && $_SESSION['role']=='admin'):  ?>
<div class="container">
<h2>Gestion des utilisateurs</h2>
<a class="btn btn-primary" href="/<?= HTDOCS;?>/admin/create">Add User</a>
<h4>Liste Utilisateurs</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>username</th>
            <th>email</th>
            <th>Phone</th>
            <th>role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($params['users'] as $user):  ?>
            <tr>
                <td><?=$user->id ;?></td><td><?=$user->username ;?></td>
                <td><?=$user->email ;?></td>
                <td><?=$user->phone ;?></td><td><?=$user->role ;?></td>
                <td><a href="/<?=HTDOCS;?>/admin/view/<?=$user->id ;?>" class="btn btn-info"><i class="fa fa-info text-light">info</a><a href="/<?=HTDOCS;?>/admin/edit/<?=$user->id ;?>" class="btn btn-success"><i class="fa fa-edit text-light">edit</a>
                <form action="/<?=HTDOCS;?>/admin/delete/<?=$user->id ;?>" method="POST" class="d-inline">
                    <button type="submit"class="border-white btn btn-danger "><i class="fa fa-trash text-light"></i>delete</button>
               </form>
            </td>
            </tr>
        <?php endforeach  ?> 
    </tbody>
</table>  

</div>













<?php endif  ?>