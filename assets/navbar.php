<header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?='/'.HTDOCS.'/'; ?>">Blog</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?='/'.HTDOCS.'/'; ?>">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?='/'.HTDOCS."/cards" ;?>">les derniers cards</a>
        </li> 
        <?php if(isset($_SESSION['auth']) && $_SESSION['role']=='admin' ): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?='/'.HTDOCS.'/admin/'; ?>">Administration des utilisateurs</a>
        </li> 
        <?php endif  ?>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['auth'])){
          ?>
             <li class="nav-item">
          <a class="nav-link btn btn-outline-success" href="<?='/'.HTDOCS.'/logout/'; ?>">Deconnecter</a>
        </li> 

          <?php
        }
        else{
          ?>
              <li class="nav-item">
          <a class="nav-link btn btn-outline-success" href="<?='/'.HTDOCS.'/login/'; ?>">Se Connecter</a>
        </li> 
        <?php
        } ?>
         
        
      </ul>
     
    </div>
  </div>
</nav>
</header>

