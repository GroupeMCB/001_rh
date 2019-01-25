<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title_for_layout)?$title_for_layout: 'MCB APP'; ?></title>

    <?php
      if(isset($header) && $header== 1) { 
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/vendor/bootstrap/css/bootstrap.min.css" type="text/css"> 


    <!-- Custom Fonts -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/vendor/font-awesome/css/all.min.css" type="text/css">

    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/dist/css/sty.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/dist/css/sb-admin.css" type="text/css">

    <!-- <link href="<?php //echo BASE_URL; ?>/dist/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> -->
    
    <link rel="stylesheet" src="<?php echo BASE_URL; ?>/vendor/datatables/css/datatables.min.css" type="text/css">

    <!-- jQuery -->
    <script src="<?php echo BASE_URL; ?>/vendor/jquery/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo BASE_URL; ?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- script src="<?php //echo BASE_URL; ?>/code/highcharts.js"></script>
    <script src="<?php //echo BASE_URL; ?>/code/highcharts-3d.js"></script>
    <script src="<?php //echo BASE_URL; ?>/dist/js/sb-admin.js"></script>

    <script src="<?php //echo BASE_URL; ?>/js/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php //echo BASE_URL; ?>/js/datepicker/bootstrap-datepicker.min.js" type="text/javascript"></script> -->
    <!-- <script src="<?php //echo BASE_URL; ?>/vendor/datatables/jquery.dataTables.js"></script> -->
    <script src="<?php echo BASE_URL; ?>/vendor/datatables/js/datatables.min.js"></script>
   
  </head>
  <body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark nav-fixed static-top">

      <a class="navbar-brand mr-1" href="#">Ressources Humaines</a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
      </button>

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
          		<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="collapse" data-target="#personnels-menu" aria-expanded="false" aria-controls="personnels-menu"> 
            		<i class="fa fa-users"></i> Personnel
            	</a>

             	<div id="personnels-menu" class="collapse <?php if($controller == 'personnels' || $controller == 'contrats') echo "show"; ?>" aria-labelledby="personnels-menu" data-parent="">

               		<a class="dropdown-item text-white" href="<?php echo BASE_URL.DS; ?>personnels/dashboard"> 
               			Vue d'ensemble
               		</a>
               		<a class="dropdown-item text-white" href="<?php echo BASE_URL.DS; ?>personnels/indicateurs">
               			<i class=""></i> Indicateurs
               		</a>
               		<a class="dropdown-item text-white" href="<?php echo BASE_URL.DS; ?>personnels/viewList/contractuels"> Employés
               		</a> 
                	<a class="dropdown-item text-white" href="<?php echo BASE_URL.DS; ?>personnels/sortie">
                		<i class=""></i> Agents inactifs
              		</a>
             	</div>
            
          	</li>

          	<li class="nav-item dropdown <?php if($controller == 'conges') echo "active"; ?>">
            	<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="collapse" data-target="#gestion-conges" aria-expanded="false" aria-controle="gestion-conges">
           			<i class="fa fa-tasks"></i> Gestion des Congés
           		</a>
           		<div id="gestion-conges" class="collapse <?php if($controller == 'conges') echo "show"; ?>" aria-labelledby="gestion-conges" data-parent="">
           			<a href="<?= BASE_URL.DS; ?>conges/dashboard" class="dropdown-item text-white">Vue d'ensemble</a>
           			<a href="<?= BASE_URL.DS; ?>conges/planning" class="dropdown-item text-white">Planning des congés</a>
           			<a href="<?= BASE_URL.DS; ?>conges/soldeconge" class="dropdown-item text-white">Solde des congés</a>
           		</div>
          	</li>

          	<li class="nav-item dropdown <?php if($controller == 'paies') echo "active"; ?>">
            	<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="collapse" data-target="#paie" aria-expanded="false" aria-controls="paie"> 
          			<i class="fa fa-book"></i> Paie
          		</a>
          		<div id="paie" class="collapse <?php if($controller == 'paies') echo "show"; ?>" aria-labelledby="paie" data-parent="">
          			<a href="<?= BASE_URL.DS; ?>paies/configpaie" class="dropdown-item text-white">Période de paie</a>

          			<a href="#" class="nav-link dropdown-toggle text-white" role="button" data-toggle="collapse" data-target="#indicateurs-perf" aria-expended="false" aria-controls="indicateurs-perf">
          				Indicateurs de perf.
          			</a>
          			<div id="indicateurs-perf" class="collapse" aria-labelledby="indicateurs-perf" data-parent="#paie">
          				<a href="<?= BASE_URL.DS; ?>paies/performance_emission" class="dropdown-item text-white">CRCD Emission</a>
          				<a href="<?= BASE_URL.DS; ?>paies/performance/4" class="dropdown-item text-white">CRCD Réception MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies/performance/9" class="dropdown-item text-white">CRCD Réception MOOV</a>
          				<a href="<?= BASE_URL.DS; ?>paies" class="dropdown-item text-white">CRCD Backoffice MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies" class="dropdown-item text-white">CRCD Commerciaux Terrains</a>
          				<a href="<?= BASE_URL.DS; ?>paies" class="dropdown-item text-white">Digital MOOV</a>
          				<a href="<?= BASE_URL.DS; ?>paies" class="dropdown-item text-white">Digital MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies" class="dropdown-item text-white">Emission locale</a>
          			</div>

          			<a href="#" class="nav-link dropdown-toggle text-white" role="button" data-toggle="collapse" data-target="#elements-paie" aria-expended="false" aria-controls="elements-paie">
						Eléments de paie
          			</a>
          			<div id="elements-paie" class="collapse" aria-labelledby="elements-paie" data-parent="#paie">
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/2" class="dropdown-item text-white">Administratif Direct</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/1" class="dropdown-item text-white">Administratif Indirect</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/3" class="dropdown-item text-white">Emission Offshore</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/7" class="dropdown-item text-white">Emission Locale</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/4" class="dropdown-item text-white">Réception MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/9" class="dropdown-item text-white">Réception MOOV</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/10" class="dropdown-item text-white">Digital MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/11" class="dropdown-item text-white">Digital MOOV</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/12" class="dropdown-item text-white">Commerciaux Terrains</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/13" class="dropdown-item text-white">BackOffice MOOV</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/14" class="dropdown-item text-white">BackOffice MTN</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/5" class="dropdown-item text-white">Entretien</a>
          				<a href="<?= BASE_URL.DS; ?>paies/personnel/6" class="dropdown-item text-white">ABFPA</a>
          			</div>

          			<a href="#" class="nav-link dropdown-toggle text-white" role="button" data-toggle="collapse" data-target="#recapitulatif" aria-expended="false" aria-controls="recapitulatif">
          				Récapitulatif
          			</a>
          			<div id="recapitulatif" class="collapse" aria-labelledby="recapitulatif" data-parent="#paie">
          				<a href="<?= BASE_URL.DS; ?>/paies/viewfraismission" class="dropdown-item text-white">Frais de mission</a>
          				<a href="<?= BASE_URL.DS; ?>/paies/viewavance" class="dropdown-item text-white">Avance sur salaire</a>
          				<a href="<?= BASE_URL.DS; ?>/paies/viewretenue" class="dropdown-item text-white">Retenue sur salaire</a>
          				<a href="<?= BASE_URL.DS; ?>/paies/viewregularisation" class="dropdown-item text-white">Régularisation</a>	
          			</div>


          			<a href="<?= BASE_URL.DS; ?>/paies/etat" class="dropdown-item text-white">Exporter les états</a>
          			<a href="<?= BASE_URL.DS; ?>/paies/etatfinancier" class="dropdown-item text-white">Exporter les états financiers</a>
          		</div>
          	</li>

          	<li class="nav-item dropdown <?php if($controller == 'certificats' || $controller == 'sanctions') echo "active"; ?>">
            	<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="collapse" data-target="#certificat" aria-expanded="false" aria-controls="certificat">
          			<i class="fa fa-file"></i> Certificats et Sanctions
          		</a>
          		<div id="certificat" class="collapse <?php if($controller == 'certificats' || $controller == 'sanctions') echo "show"; ?>" aria-labelledby="certificat" data-parent="">
          			<a href="<?= BASE_URL.DS; ?>certificats/index" class="dropdown-item text-white">
          				Vue d'ensemble
          			</a>
          			<a href="<?= BASE_URL.DS; ?>certificats/listview" class="dropdown-item text-white">
          				Liste des certificats
          			</a>
          			<a href="<?= BASE_URL.DS; ?>sanctions/listview" class="dropdown-item text-white">
          				Liste des sanctions
          			</a>
          		</div>
          	</li>

           	<li class="nav-item dropdown">
            	<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="collapse" data-target="#parametres" aria-expanded="false" aria-controls="parametres">
            		<i class="fa fa-cogs"></i> Paramètres
            	</a>
            	<div id="parametres" class="collapse" aria-labelledby="parametres" data-parent="">
            		<a href="<?= BASE_URL.DS; ?>parametres/annee" class="dropdown-item text-white">
            			Gestion des années
            		</a>
            	</div>
          	</li>
      	</ul>

      	<div id="content-wrapper">

	        <div class="container-fluid">
	          	<?php  echo $content_for_layout;  ?>
	        </div>
	                <!-- /.row -->
	      	</div>
	      <!-- /.container-fluid -->

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
    
  </body>

</html>
