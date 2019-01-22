<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Campagne Planning';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Campagne Planning';
?>

<div class="col-md-9 costum-body" id="bigbody">
  <?php include(ROOT.'views/layout/breadcrumb.php') ?>

  <?php if (!empty($succes)) { ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><?php echo $succes; ?></b>
  </div>
  <?php } ?>
<?php //var_dump($plannigsCampagne); die(); ?>
  <div class="page-header">
    <h4>Liste plannings / <?php echo $infosCampagne['nomCampagne']; ?></h4>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">Liste des plannings</div>
    <div class="panel">
      <ul id="myTab1" class="nav nav-tabs nav-justified">
        <?php
          if ($infosCel) {
            $k=1; $isActive = "";
            foreach ($infosCel as $key1 => $value1) {
              if ($k == 1) {
                $isActive = "active";
               }else{
                $isActive = "";
              }

        ?>
            <li class="<?php echo $isActive; ?>"><a href="#cellule<?php echo $value1['id']; ?>" data-toggle="tab"><?php echo $value1['libCellule']; ?></a></li>
        <?php
              $k++;

            } // Fin boucle foreach
          } // Fin boucle if
        ?>

      </ul>

      <div id="myTabContent" class="tab-content">
        <?php
          if ($infosCel) { // on vérifie si la campagne à des cellules

            $m=1; $hasClass = "";

            foreach ($infosCel as $key2 => $value2) { //var_dump($value2); die();

              if ($m == 1) {
                $hasClass = "active in";
               }else{
                $hasClass = "";
              }
              /* Pour chaque cellule de la campagne on fait le traitement suivant */
        ?>
        <div class="tab-pane fade <?php echo $hasClass; ?>" id="cellule<?php echo $value2['id']; ?>">

          <table class="table table-hover table-condensed table-responsive" id="tableListPlanning<?php echo $value2['id']; ?>">
            <thead>
              <tr>
                <th>#</th>
                <th>Période</th>
                <th>Auteur</th>
                <th>Date de création</th>
                <th>Etat</th>
                <th class="actionth3">Action</th>
              </tr>
            </thead>
            <tbody>

        <?php
          if ($value2['has_planning']) { // On vérifie si la cellule courante à au moins un planning
        ?>
            <?php
              if ($plannigsCampagne) {
                $i = 1;
                foreach ($plannigsCampagne as $value) {
                  // echo "<br><br>";
                  // var_dump($value['celPlanning'][0]);
                  // die();
                  if ((isset($value['celPlanning'][0])) AND ($value['celPlanning'][0] == $value2['id'])) {
            ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['periodeDebut'].' - '.$value['periodeFin']; ?></td>
                <td><?php echo $value['idAuteur']['NOM'].' '.$value['idAuteur']['PRENOM']; ?></td>
                <td><?php echo $value['dateEdition']; ?></td>
                <td>
                  <?php
                    switch ($value['niveauPlanning']) {
                      case 0:
                        echo "Planning non enregistré";
                        break;

                      case 1:
                        echo "Planning en cours de validation";
                        break;

                      case 2:
                        echo "Planning validé";
                        break;

                      case 3:
                        echo "Planning non validé";
                        break;

                      default:
                        # code...
                        break;
                    }
                  ?>
                </td>
                <td>

                  <?php if ($_SESSION['userpermission']['permissions_planning']['view']) { ?>
                    <!-- Bouton pour consulter le planning -->
                    <a href="<?php echo WEBROOT.'planifications/veiwPlanification/'.$value['id'].'/'.$value['idCampagne']; ?>"><span class="label label-default">Voir</span></a>
                  <?php } ?>

                  <?php if ($_SESSION['userpermission']['permissions_planning']['del']) { ?>
                    <!-- Déclencheur de la fenêtre modale de suppression d'un planning -->
                    <a href="#" data-toggle="modal" data-target="#confirmModal<?php echo $value['id']; ?>"><span class="label label-danger">Supprimer</span></a>
                  <?php } ?>

                  <!-- La fenetre modal de confirmation de la suppression -->
                  <div class="modal fade" id="confirmModal<?php echo $value['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h3>Suppression</h3>
                        </div>
                        <div class="modal-body">
                          <p>Voulez-vous vraiment supprimer ce planning  : <b> </b>?</p>
                        </div>
                        <div class="modal-footer">
                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
                          <a href="<?php echo WEBROOT.'plannings/deletePlanning/'.$value['id'].'/'.$value['idCampagne']; ?>" class="btn btn-primary">OUI</a>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog  -->
                  </div> <!-- /.modal-fade -->

                </td>
              </tr>

              <?php
                  $i++;
                     }
                  } // Fin bloc foreach ($plannigsCampagne as $value)
                } // Fin bloc if($plannigsCampagne)
               ?>

        <?php
              } // Fin boucle de Vérification de planning de la cellule if($value2['has_planning'])
        ?>

            </tbody>
          </table>
        </div>

        <?php
          $m++;
            } // Fin boucle foreach de parcours des différentes cellule de la campagne
          } // Fin boucle if de Vérification de cellule de la campagne
        ?>

      </div>
    </div>

  </div>

</div>
