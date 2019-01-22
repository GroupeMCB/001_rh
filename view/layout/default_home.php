<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title_for_layout)?$title_for_layout: 'MCB APP'; ?></title>

    <?php  if(isset($header) && $header== 1) { 
      header("Content-disposition: attachment; filename=".$nom); 
      header("Content-Type: application/force-download"); 
      header("Content-Transfer-Encoding: $type\n"); // Surtout ne pas enlever le \n
      header("Pragma: no-cache"); 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public"); 
      header("Expires: 0"); 
      readfile($nom);  
    } 
      ?>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo  BASE_URL; ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <!-- Custom CSS -->
    <link href="<?php echo  BASE_URL; ?>/dist/css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?php echo  BASE_URL; ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo  BASE_URL; ?>/dist/css/sty.css">
    <link href="<?php echo  BASE_URL; ?>/dist/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo  BASE_URL; ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo  BASE_URL; ?>/dist/js/sb-admin.js"></script>
    <script src="<?php echo  BASE_URL; ?>/code/highcharts.js"></script>
    <script src="<?php echo  BASE_URL; ?>/code/highcharts-3d.js"></script>
    <script src="<?php echo  BASE_URL; ?>/vendor/datatables/dataTables.bootstrap4.css"></script> 

     <script src="<?php echo  BASE_URL; ?>/js/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
     <script src="<?php echo  BASE_URL; ?>/js/datepicker/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="<?php echo  BASE_URL; ?>/vendor/datatables/jquery.dataTables.js"></script> 
        <script src="<?php echo  BASE_URL; ?>/vendor/datatables/dataTables.bootstrap4.js"></script> 
     

</head>
  <body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark nav-fixed static-top">

      <a class="navbar-brand mr-1" href="#">Ressources Humaines</a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar Search -->
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <!-- <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div> -->
      </form>

      <!-- Navbar -->
      <ul class="navbar-nav fixed ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <span class="badge badge-danger">9+</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw"></i>
            <span class="badge badge-danger">7</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="#">Activity Log</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
          </div>
        </li>
      </ul>

    </nav>

    <div id="wrapper">

       <ul class="sidebar navbar-nav">
         <li class="nav-item dropdown <?php if($controller == 'personnels' || $controller == 'contrats' ) echo " show " ?>" >
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" id="#home-pills"  > 
            <i class="fa fa-users"></i> Personnel</a>
             <div  class="dropdown-menu <?php if($controller == 'personnels' || $controller == 'contrats' ) echo " show " ?>" aria-labelledby="home-pills">
               <a class="dropdown-item" href="<?php echo BASE_URL.DS?>personnels/dashboard"> Tableau de bord</a>
               <a class="dropdown-item " href="<?php echo BASE_URL.DS?>personnels/indicateurs"><i class="fa fa-lightbulb-o"></i> Indicateurs</a>
               <a class=" dropdown-item active" href="<?php echo BASE_URL.DS?>personnels/viewList/contractuels"> Employés</a> 
                <a class="dropdown-item" href="<?php echo BASE_URL.DS?>personnels/sortie"><i class="fa fa-sign-out"></i> 
                  Agents inactifs</a>
             </div>
            
          </li>

          <li class="nav-item dropdown <?php if($controller == 'conges') echo "active" ?>"  >
            <a  class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" id="#profile-pills">
           <i class="fa fa-tasks"></i> Gestion des Congés</a>
          </li>

          <li class="nav-item dropdown  <?php if($controller == 'paies') echo "active" ?>">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" id="#paie" data-toggle="tab"> 
          <i class="fa fa-book"></i> Paie</a>
          </li>

          <li class="nav-item dropdown <?php if($controller == 'certificats' || $controller == 'sanctions') echo "active" ?>"  >
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" id="#certificat" > <i class="fa fa-file"></i> Certificats et Sanctions</a>
          </li>
           <li class="nav-item  dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" id="#parametres" > <i class="fa fa-cogs"></i> Paramètres</a>
          </li>
      </ul>

      <div id="content-wrapper">

        <div class="container-fluid">
                        <?php  echo $content_for_layout;  ?>
             </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<!-- 
             <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Digit'All Solutions <?php //echo date("Y") ?></span>
            </div>
          </div>
        </footer>
 -->
        </div>

  

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Voulez vous quitter?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Cliquer sur Déconnexion si vous voulez terminer votre session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
            <a class="btn btn-primary" href="deconnect.php">Déconnexion</a>
          </div>
        </div>
      </div>
    </div>
      <!-- jQuery -->
    <script src="<?php echo  BASE_URL; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo  BASE_URL; ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo  BASE_URL; ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">

  $(".alert").delay(4000).slideUp(200, function() {
    $(this).alert('close');
});
</script>

</body>

</html>
