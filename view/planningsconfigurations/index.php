<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Configuration';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Configuration';
?>

<div class="col-md-9 costum-body" id="bigbody">
  <?php if (!empty($succes)) { ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><?php echo $succes; ?></b>
    </div>
  <?php }elseif(!empty($echec)) { ?>
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><?php echo $echec; ?></b>
    </div>
  <?php } ?>

  <div class="page-header">
    <h3>Configuration
      <span class="pull-right"><a href="<?php echo WEBROOT.'configurations/updateTableAgent'; ?>" class="btn btn-default">MAJ Table Agent</a></span>
    </h3>
  </div>

  <?php

    // On vérifie si le tableau est vide
    if ($campAndCel) {
      $campAndCel2 =array();
      $campAndCel2 = $campAndCel;
      foreach($campAndCel2 as $key => $value){
  ?>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              <?php echo $value[0]['nomCampagne']; ?>
                <!-- Déclencheur de la fenêtre modale de suppression d'une campagne -->
                <a href="#" class="pull-right" data-toggle="modal" data-target="#confirmModal<?php echo $value[0]['id']; ?>"><span class="label label-danger">Supprimer</span></a>
            </h3>

            <!-- La fenetre modal de confirmation de la suppression d'une campagne-->
            <div class="modal fade" id="confirmModal<?php echo $value[0]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Suppression</h3>
                  </div>
                  <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer cette campagne  : <b><?php echo $value[0]['nomCampagne']; ?> </b>?</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
                    <a href="<?php echo WEBROOT.'configurations/delCampagne/'.$value[0]['id']; ?>" class="btn btn-primary">OUI</a>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog  -->
            </div> <!-- /.modal-fade -->

          </div> <!-- fin .panel-heading -->

          <div class="panel-body">
            <div class="shortcuts2">
            <?php

              // On vérifie si le tableau est vide
              if ($value[1]) {
                 // var_dump($campagnes);
                foreach($value[1] as $key => $subvalue){ // pour parcourir les éléments différentes cellules ?>

                <span class="celAndAction">
                  <a href="<?php echo WEBROOT.'configurations/viewCellule/'.$value[0]['id'].'/'.$subvalue['id']; ?>" class="shortcutcel">
                     <?php echo $subvalue['libCellule']; ?>
                  </a>
                    <!-- Déclencheur fenêtre modale de modification d'une celllule -->
                    <a href="#" class="celAction" data-toggle="modal" data-target="#celModif<?php echo $subvalue['id']; ?>" title="Modifier">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                  &nbsp;&nbsp;&nbsp;
                  <!-- Fenêtre modale de modification -->
                  <form role="form" class="celActionForm" action="<?php echo WEBROOT.'configurations/updateCellule'; ?>" method="post">
                    <div class="modal fade" id="celModif<?php echo $subvalue['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3>Modification</h3>
                          </div>
                          <div class="modal-body">

                            <div class="form-group col-md-12">
                              <label for="newCelInput" class="col-md-12 control-label">Libellé cellule :</label>
                              <div class="col-sm-12">
                                <input class="form-control" id="newCelInput" type="text" name="libCellule" value="<?php echo $subvalue['libCellule']; ?>">
                                <input type="hidden" name="id" value="<?php echo $subvalue['id']; ?>">
                              </div>
                            </div>

                            <?php if ($value[0]["typeCampagne"] != 1): ?>
                            <div class="form-group col-md-12">
                              <label for="newCelInput" class="col-md-12 control-label">Numéro hermes cellule :</label>
                              <div class="col-sm-12">
                                <input class="form-control" id="newCelInput" type="text" name="hermes_num" value="<?php echo $subvalue['hermes_num']; ?>">
                              </div>
                            </div>
                            <?php endif ?>

                          </div>
                          <br><br>
                          <div class="modal-footer">
                            <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Annuler</a>
                            <button type="submit" class="btn btn-primary">Modifier</button>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog  -->
                    </div> <!-- /.modal-fade -->
                  </form>

                    <!-- Déclencheur fenêtre modale de suppression d'une celllule -->
                    <a href="#" class="celAction" data-toggle="modal" data-target="#celDel<?php echo $subvalue['id']; ?>" title="Supprimer"><span class="glyphicon glyphicon-trash" style="color:#DA4453;"></span></a>

                  <!-- Fenêtre modale de suppression d'une cellule-->
                  <div class="modal fade" id="celDel<?php echo $subvalue['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h3>Suppression</h3>
                        </div>
                        <div class="modal-body">
                          <p>Voulez-vous vraiment supprimer cette cellule  : <b><?php echo $subvalue['libCellule']; ?> </b>?</p>
                        </div>
                        <div class="modal-footer">
                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
                          <a href="<?php echo WEBROOT.'configurations/delCellule/'.$subvalue['id']; ?>" class="btn btn-primary">OUI</a>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog  -->
                  </div> <!-- /.modal-fade -->

                </span>

              <?php
               }
                // Fin blic Forearch
              } // Fin du bloc If
            ?>
            </div>

            <br>

            <div class="col-md-12"> <!-- Bloc ajout cellule -->
              <div class="form-group">

                  <!-- Déclencheur fénetre modal AJOUT nouvelle cellule -->
                  <a href="#" class="btn btn-success col-md-12" data-toggle="modal" data-target="#addCellule<?php echo $value[0]['id']; ?>"> <span class="glyphicon glyphicon-plus"></span> Ajouter cellule</a>
              </div>

              <form action="<?php echo WEBROOT."configurations/addCellule"; ?>" method="post">
                <!-- La fenetre modal ajout dans une cellule -->
                <div class="modal fade" id="addCellule<?php echo $value[0]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Exportation" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Ajout cellule</h3>
                      </div>
                      <div class="modal-body">
                        <div class="form-group col-md-12">
                          <label for="newCelInput" class="col-sm-12 control-label">Nom :</label>
                          <div class="col-sm-12">
                            <input class="form-control" id="newCelInput" type="text" name="libCellule">
                            <input class="form-control" id="Campagne_idCampagne" type="hidden" name="Campagne_idCampagne" value="<?php echo $value[0]['id'] ?>">
                          </div>
                        </div>

                        <?php if ($value[0]["typeCampagne"] != 1): ?>
                          <div class="form-group col-md-12">
                            <label for="newCelInput" class="col-md-12 control-label">Numéro hermes cellule :</label>
                            <div class="col-sm-12">
                              <input class="form-control" id="newCelInput" type="text" name="hermes_num" value="">
                            </div>
                          </div>
                        <?php endif ?>

                      </div>
                      <br><br>
                      <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Annuler</a>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog  -->
                </div> <!-- /.modal-fade -->
              </form>

            </div> <!-- #Fin Bloc ajout cellule -->

          </div> <!-- #fin panel-body Bloc ajout cellule -->
        </div> <!-- #fin panel Bloc ajout cellule -->
      </div> <!-- .col-md-4 -->

  <?php

     } // Fin bloc premier Forearch avec ($campAndCel as $key => $value)

    } // Fin du bloc premier bloc if avce la condition ($campAndCel)
  ?>

    <!-- Bloc ajout d'une nouvelle campagne -->
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Ajout campagne</h3>
        </div>
        <div class="panel-body">
          <div class="shortcuts">
            <!-- Déclencheur fénetre modal AJOUT nouvelle campagne -->
            <a href="#" class="shortcutadd" data-toggle="modal" data-target="#addCampagne">
              <span class="glyphicon glyphicon-plus"></span><br>
              Ajouter une Campagne
            </a>
          </div>

          <form action="<?php echo WEBROOT.'configurations/addCampagne'; ?>" method="post">
            <!-- La fenetre modal ajout affectation dans une cellule -->
            <div class="modal fade" id="addCampagne" tabindex="-1" role="dialog" aria-labelledby="Exportation" aria-hidden="true">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Ajout campagne</h3>
                  </div>
                  <div class="modal-body">
                    <div class="form-group col-md-12">
                      <label for="newCampInput" class="col-sm-12 control-label">Nom :</label>
                      <div class="col-sm-12">
                        <input class="form-control" id="newCampInput" name="nomCampagne">
                      </div>
                    </div>
                    <div class="form-group col-md-12">
                      <label for="newCampInput1" class="col-sm-12 control-label">Type :</label>
                      <div class="col-sm-12">
                        <select class="form-control" name="typeCampagne" id="newCampInput1">
                          <option value="1">Administration</option>
                          <option value="2">Production</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <br><br>
                  <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Annuler</a>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog  -->
            </div> <!-- /.modal-fade -->
          </form>

        </div>
      </div>
    </div>

</div>
