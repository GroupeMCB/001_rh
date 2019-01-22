<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Tous les plannings';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Tous les plannings';
?>

<div class="col-md-9 costum-body" id="bigbody">
  <?php include(ROOT.'views/layout/breadcrumb.php') ?>
  <div class="page-header">
    <h4>
      Liste de tous les plannings
      <div class="pull-right othersactions">
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span> Options <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li><a href="#">Voir les commentaires</a></li>
            <li class="divider"></li>
          </ul>
        </div>
      </div>
    </h4>
  </div>

  <?php
    if ($lesplanningsByCamp) {

      $lesplanningsByCamp2 =array();

      $lesplanningsByCamp2 = $lesplanningsByCamp;

      foreach ($lesplanningsByCamp2 as $key => $value) {
        $compteur = 0;

        if (sizeof($value) <= 3) {
          $compteur = sizeof($value);
        }else{
           $compteur = 3;
        }
  ?>
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $key; ?></h3>
            </div>
            <div class="panel-body">
              <?php

                if ($value) {

                  echo '<ul class="list-group">';
                  for ($i=0; $i < $compteur; $i++) {

                    if ($value[$i]['niveauPlanning'] == 2) {
                      $status = '<span class="label label-success pull-right"><span class="glyphicon glyphicon-ok"></span> Validé</span>';
                    } elseif ($value[$i]['niveauPlanning'] == 3) {
                      $status = '<span class="label label-danger pull-right"><span class="glyphicon glyphicon-remove"></span> Non validé</span>';
                    } else{
                      $status = "";
                    }

                    echo '<li class="list-group-item">
                      <a href="'.WEBROOT.'planifications/veiwPlanification/'.$value[$i]['id'].'/'.$value[0]['idCampagne'].'">Planning du '.$value[$i]['periodeDebut'].' au '.$value[$i]['periodeFin'].'</a>
                      '.$status.'
                    </li>';
                  }
                  echo '</ul>';
                  echo '<span class="list-group"><a href="'.WEBROOT.'plannings/listPlanning/'.$value[0]['idCampagne'].'" style="display:block" class="pull-right">Voir tous les plannings &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span></a> </span>';

                }else{
                  echo "Aucun planning ...";
                }
              ?>
            </div>
          </div>
        </div>
  <?php

      } // fin boucle foreach
    } // fin boucle if
  ?>

</div>
