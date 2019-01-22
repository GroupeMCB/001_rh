<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title_for_layout)?$title_for_layout: 'MCB APPSS'; ?></title>

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

    <!-- MetisMenu CSS -->
    <!-- <link href="<?php echo  BASE_URL; ?>/vendor/metisMenu/metisMenu.min.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="<?php echo  BASE_URL; ?>/dist/css/sb-admin-2.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="<?php echo  BASE_URL; ?>/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


      <link href="<?php echo  BASE_URL; ?>/dist/css/button.css" rel="stylesheet">

    <link href="<?php echo  BASE_URL; ?>/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="<?php echo  BASE_URL; ?>/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- <link href="<?php //echo  BASE_URL; ?>/dist/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" type="text/css" href="<?php echo  BASE_URL; ?>/dist/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  BASE_URL; ?>/dist/css/sty.css">
    <!-- jQuery -->

    <script src="<?php echo  BASE_URL; ?>/vendor/jquery/jquery.min.js"></script>



    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo  BASE_URL; ?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?php echo  BASE_URL; ?>/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo  BASE_URL; ?>/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo  BASE_URL; ?>/vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- <script src="<?php ////echo  BASE_URL; ?>/vendor/data.css"></script> -->
    <!-- <script src="<?php //echo  BASE_URL; ?>/vendor/data.js"></script> -->
    <!-- <script src="<?php //echo  BASE_URL; ?>/vendor/fixedcolums.js"></script> -->

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo  BASE_URL; ?>/vendor/metisMenu/metisMenu.min.js"></script>

     <script src="<?php echo  BASE_URL; ?>/js/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
     <script src="<?php echo  BASE_URL; ?>/js/datepicker/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo  BASE_URL; ?>/dist/js/sb-admin-2.js"></script>
    <script src="<?php echo  BASE_URL; ?>/code/highcharts.js"></script>
    <script src="<?php echo  BASE_URL; ?>/code/highcharts-3d.js"></script>
    <script src="<?php echo  BASE_URL; ?>/code/dark-blue.js.js"></script>
 


</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">MCB PORTAIL RH</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links  nav-pills" style="background:">

                                <li <?php if($controller == 'personnels' || $controller == 'contrats' ) echo " class ='active' " ?>><a href="#home-pills" data-toggle="tab"> <i class="fa fa-users"></i> Personnel</a>
                                </li>
                                <li <?php if($controller == 'conges') echo " class ='active' " ?>><a href="#profile-pills" data-toggle="tab"> <i class="fa fa-tasks"></i> Gestion des Congés</a>
                                </li>
                                <li <?php if($controller == 'paies') echo " class ='active' " ?>><a href="#paie" data-toggle="tab"> <i class="fa fa-book"></i> Paie</a>
                                </li>
                                <li <?php if($controller == 'certificats' || $controller == 'sanctions') echo " class ='active' " ?> ><a href="#certificat" data-toggle="tab"> <i class="fa fa-file"></i> Certificats et Sanctions</a>
                                </li>
                                 <!-- <li <?php //if($controller == 'plannings') echo " class ='active' " ?> ><a href="#planning" data-toggle="tab"> <i class="fa fa-calendar"></i> <?php //echo $actions; ?>Planning</a>
                                </li> -->
                                 <li><a href="#parametres" data-toggle="tab"> <i class="fa fa-cogs"></i> Paramètres</a>
                                </li>

                <li class="dropdown navbar-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->


         <!--    <div class="navbar-default sidebar fixed" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                      <div class="col-md-4">
                          <span style="writing-mode:tb-rl; white-space: nowrap; margin-top:25px; font-size: 10px;color:#a20606">
                            Copyright &copy; GROUPE MEDIA CONTACT -2017.Powered By Recherche et Innovation.
                            </span>
                        <i class="glyphicon glyphicon-chevron-down" style="color:#3c23c6" ></i>
                        <i class="glyphicon glyphicon-chevron-down" style="color:#f23009"></i>
                        <i class="glyphicon glyphicon-chevron-down" style="color:#f7de00"></i>
                      </div>


                    </ul>
                </div>

            </div> -->
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" style="height: auto">
            <div class="col-md-12" style=" background: #e0e0e0; border-bottom: 2px solid #3bafda;">
                     <div class="tab-content" style="border-bottom:0px solid #d8d2d2; min-height: 45px; padding: 10px">
                                <div class="tab-pane fade in <?php if($controller == 'personnels' || $controller == 'contrats' ) echo " active " ?> " id="home-pills">
                                   <a class="btn btn-danger btn-outline" href="<?php echo BASE_URL.DS?>personnels/dashboard"><i class="fa fa-dashboard fa-fw"></i> Tableau de bord</a>
                                   <a class="btn btn-success " href="<?php echo BASE_URL.DS?>personnels/indicateurs"><i class="fa fa-lightbulb-o"></i> Indicateurs</a>

                                   <a class="btn btn-success  " href="<?php echo BASE_URL.DS?>personnels/profil"><i class="fa fa-user-md"></i> Stat profil</a>
                                   <a class="btn  btn-success" href="<?php echo BASE_URL.DS?>personnels/statut"><i class="fa fa-user-md"></i> Stat status</a>
                                   <a class="btn <?php if(isset($this->request->params[0]) && $this->request->params[0] == "contractuels" ) echo 'btn-defaults'; else echo 'btn-default'  ?> " href="<?php echo BASE_URL.DS?>personnels/viewList/contractuels"><i class="fa fa-user fa-fw"></i> Contractuels</a>
                                   <a class="btn  <?php if(isset($this->request->params[0]) && $this->request->params[0] == "stagiaires" ) echo 'btn-defaults'; else echo 'btn-default'  ?> " href="<?php echo BASE_URL.DS?>personnels/viewList/stagiaires"><i class="fa fa-user-md"></i> Stagiares</a>
                                   <!-- <a class="btn  <?php //if($this->request->action == "mouvement" ) echo 'btn-defaults'; else echo 'btn-default'  ?> " href="<?php //echo BASE_URL.DS?>personnels/mouvement"><i class="fa fa-sliders"></i> Mouvements</a> -->

                                  <a class="btn  <?php if($this->request->action == "presence" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>personnels/presence">
                                    <i class="fa fa-sliders"></i> Absence
                                  </a>
                                   <a class="btn <?php if($this->request->action == "promotion" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>personnels/promotion"><i class="fa fa-plus-circle"></i> Promotions</a>
                                   <!-- <a class="btn <?php //if($this->request->action == "contrat" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php //echo BASE_URL.DS?>personnels/contrat"><i class="fa fa-files-o"></i> Contrats</a> -->

                                    <a class="btn  <?php if($this->request->action == "sortie" ) echo 'btn-defaults'; else echo 'btn-default'  ?> " href="<?php echo BASE_URL.DS?>personnels/sortie"><i class="fa fa-sign-out"></i> Agents inactifs</a>

                                    

                                </div>

                                 <div class="tab-pane fade in <?php if($controller == 'conges') echo " active " ?>" id="profile-pills">

                                   <a class="btn btn-success" href="<?php echo BASE_URL.DS?>conges/dashboard"><i class="fa fa-dashboard"></i> Tableau de bord</a>
                                   <a class="btn <?php if($this->request->action == "planning" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo  BASE_URL.DS ?>conges/planning"><i class="fa fa-calendar"></i> Planning des congés</a>
                                   <a class="btn  <?php if($this->request->action == "soldeconge" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo  BASE_URL.DS ?>conges/soldeconge"><i class="fa fa-list-ul"></i> Solde des congés</a>
                                </div>

                                 <div class="tab-pane fade in <?php if($controller == 'paies') echo " active " ?>" id="paie">

                                   <a class="btn <?php if($this->request->action == "configpaie" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo  BASE_URL.DS ?>paies/configpaie"><span class="fa fa-calendar"></span> Période de paie</a>

                                            <div class="btn-group dropdown">
                                  <button type="button" class="btn btn-default "><span class="fa fa-list-alt"></span> Indicateurs de perf.</button>
                                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-right">
                                    </li>
                                     <li>
                                      <a href="<?php echo  BASE_URL.DS ?>paies/performance_emission"><span class="fa fa-"></span> CRCD Emission</a>
                                    </li>
                                     <li>
                                      <a href="<?php echo  BASE_URL.DS ?>paies/performance/4"><span class="fa fa-"></span> CRCD Réception</a>
                                    </li>
                                  </ul>
                                </div>

                                  <div class="btn-group dropdown">
						  <button type="button" class="btn btn-default "><span class="fa fa-list-alt"></span> Eléments de paie</button>
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu dropdown-menu-right">
						    <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/2"><span class="fa fa-"></span> Administratif Direct</a>
						    </li>
					 		 <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/1"><span class="fa fa-"></span> Administratif Indirect</a>
						    </li>
						     <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/3"><span class="fa fa-"></span> Emission offshore</a>
						    </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/7"><span class="fa fa-"></span> Emission locale</a>
                </li>
						     <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/4"><span class="fa fa-"></span> Réception MTN</a>
						    </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/9"><span class="fa fa-"></span> Réception MOOV</a>
                </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/10"><span class="fa fa-"></span> Digital Moov</a>
                </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/11"><span class="fa fa-"></span> Digital MTN</a>
                </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/12"><span class="fa fa-"></span> Commerciaux Terrain</a>
                </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/13"><span class="fa fa-"></span> Backoffice Moov</a>
                </li>
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/14"><span class="fa fa-"></span> Backoffice MTN</a>
                </li>
						     <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/5"><span class="fa fa-"></span> Entretien</a>
						    </li>
						     <li>
						    	<a href="<?php echo  BASE_URL.DS ?>paies/personnel/6"><span class="fa fa-"></span> ABFPA</a>
						    </li>

						  </ul>
						</div>

               <div class="btn-group dropdown">
              <button type="button" class="btn btn-default "><span class="fa fa-list"></span> Récapitulatif</button>
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/viewfraismission"><span class="fa fa-"></span> Frais de mission</a>
                </li>
               <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/viewavance"><span class="fa fa-"></span> Avance sur salaire</a>
                </li>
                 <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/viewretenue"><span class="fa fa-"></span> Retenue sur salaire</a>
                </li>
                 <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/viewregularisation"><span class="fa fa-"></span> Régularisation</a>
                </li>
                 <!-- <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/viewprime"><span class="fa fa-"></span> Prime DG</a>
                </li> -->

              </ul>
            </div>

             <a class="btn  <?php if($this->request->action == "etat" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>paies/etat">
                                    <i class="fa fa-download"></i> Exporter les états
                                  </a>
               <a class="btn  <?php if($this->request->action == "etatfinancier" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>paies/etatfinancier">
                                    <i class="fa fa-download"></i> Exporter les états financiers
                                  </a>
                 <!--  <a class="btn  <?php if($this->request->action == "archives" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>paies/archives">
                                    <i class="fa fa-archive"></i> Archives
                                  </a> -->
                                  <!--  <a class="btn btn-default" href="">Période de paie</a>
                                   <a class="btn btn-default" href="">Période de paie</a>
                                   <a class="btn btn-default" href="">Période de paie</a>
                                   <a class="btn btn-default" href="">Gestion de la paie</a> -->
                                </div>

                                <div class="tab-pane fade in <?php if($controller == 'certificats' || $controller == 'sanctions') echo " active " ?>" id="certificat">
                                   <a class="btn btn-warning" href="<?php echo BASE_URL.DS?>certificats/index"><i class="fa fa-dashboard"></i> Tableau de Bord</a>
                                   <a class="btn <?php if($this->request->action == "listview" && $this->request->controller == "certificats"  ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>certificats/listview"><i class="fa fa-file"></i> Liste des certificats</a>
                                   <a class="btn <?php if($this->request->action == "listview"  && $this->request->controller == "sanctions" ) echo 'btn-defaults'; else echo 'btn-default'  ?>" href="<?php echo BASE_URL.DS?>sanctions/listview"><i class="fa fa-file-text"></i> Liste des sanctions</a>

                                </div>
                                <div class="tab-pane fade in <?php if($controller == 'plannings') echo " active " ?>" id="planning">
                                  <a class="btn btn-default" href="<?= BASE_URL.DS; ?>planningsplannings/dashboard">Tableau de Bord</a>
                                  <a class="btn btn-default" href="<?= BASE_URL.DS; ?>planningsplannings/newPlanning">Nouveau planning</a>
                                  <a class="btn btn-default" href="<?= BASE_URL.DS; ?>plannings/getAllPlannings">Consulter planning</a>
                                  <a class="btn btn-default" href="<?= BASE_URL.DS; ?>plannings/planningsPermissions">Plannings speciaux et permissions</a>
                                  <a class="btn btn-default" href="<?= BASE_URL.DS; ?>planningsconfigurations/index">Configuration</a>
                                </div>
                                 <div class="tab-pane fade in <?php if($controller == 'parametres') echo " active " ?>" id="parametres">
                                  <a class="btn btn-default" href="<?php echo  BASE_URL.DS ?>/parametres/annee "><i class="fa fa-dashboard"></i> Gestion des années</a>
              <a class="btn btn-default" href=" "><i class="fa fa-dashboard"></i> Gestion des mois</a>
                                      <div class="btn-group dropdown">
              <button type="button" class="btn btn-default "><span class="fa fa-user"></span> Type</button>
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/2"><span class="fa fa-"></span> Type d'abscence</a>
                </li>
               <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/1"><span class="fa fa-"></span> Type de contrat</a>
                </li>
                 <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/3"><span class="fa fa-"></span> Type de sanction</a>
                </li>
                 <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/5"><span class="fa fa-"></span> Type de certificat</a>
                </li>
                 <li>
                  <a href="<?php echo  BASE_URL.DS ?>paies/personnel/5"><span class="fa fa-"></span> Motif de sortie</a>
                </li>
              </ul>
            </div>

                                </div>
                            </div>
                </div>
            <div class="container-fluid" style=" border-bottom: 2px solid #dcdcdc;">
                <?php if(isset($title_for_page_menu)){ ?>
            <div class="row">
            <div class="col-md-6" style="padding: 10px;">
                    <ol class="breadcrumb ">
                    <li class="breadcrumb-item"><a href="#"><?php echo $title_for_page_menu; ?></a></li>
                    <li class="breadcrumb-item active"><a href="#"><?php echo $current_menu; ?></a></li>
                  </ol></div>
        <div class="col-md-6" style="padding: 15px;">  <span class="pull-right"> <?php if(isset($button_option)) echo $button_option;?></span>
 </div>
              </div>

              <?php }  ?>

                    <!-- /.col-lg-12 -->
                </div>
                    <div class="row col-md-12" style="padding-left: 50px;margin-top: 20px;" >
                        <?php  echo $content_for_layout;  ?>
                    </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    <!-- /#wrapper -->
<!--     <div class="footer">
       Copyright &copy; GROUPE MEDIA CONTACT -2017.Powered By Recherche et Innovation.
        <i class="glyphicon glyphicon-chevron-right" style="color:#3c23c6" ></i>
                        <i class="glyphicon glyphicon-chevron-right" style="color:#f23009"></i>
                        <i class="glyphicon glyphicon-chevron-right" style="color:#f7de00"></i>
    </div> -->
<script type="text/javascript">

  $(".alert").delay(4000).slideUp(200, function() {
    $(this).alert('close');
});
</script>

</body>

</html>
