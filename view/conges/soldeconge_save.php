 <?php  
$title_for_layout = 'GESTION DES CONGES';
$title_for_page_menu = 'GESTION DES CONGES';
$current_menu = 'Solde des Congés';
$controller = $this->request->controller;
?> 
<form method="post" id="form" name="form">
<div class="form-group">
     <label>Choisir le titre</label>
    <select class="form-control" id="listeagent" onchange="document.getElementById('form').submit()" name="titre" >
             <option></option>
        <?php foreach ($titre as $key => $value): ?>
              <option value="<?php echo $value->idtitre ?>"><?php echo $value->nom; ?></option>
      <?php endforeach ?>
    </select>
</div>
</form>
 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="30%" class="text-left">Nom et Prénom</th>
          <th width="15%" class="text-left">Compteur de congé</th>
          <th width="17%" class="text-left">Solde</th>
          <th width="15%" class="text-left" ></th>
          <th width="15%" class="text-left" ></th>
        </tr>
  </thead>
  <tbody>
  <?php if (isset($listeagent)) {
  foreach ($listeagent as $key => $value) { ?>
  <tr>
    <td><?php echo $value['nom'].' '.$value['prenom'] ?></td>
    <td><?php echo $value['compteur_conge']->nbre_jour; ?></td>
    <td><?php echo $value['solde_conge']; ?></td>
    <td><a href="" data-toggle="modal" data-target="#detailsconge<?php echo $value['idpersonnel'] ?>" class="btn btn-primary btn-xs">Détails</a></td>
    <td><a href="" data-toggle="modal" data-target="#editsolde<?php echo $value['idpersonnel'] ?>" class="btn btn-primary btn-xs">Modifier le solde</a></td>
  </tr>

  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="editsolde<?php echo  $value['idpersonnel'] ?>" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">MODIFIER LE SOLDE DE 
                                                <span style="text-transform: uppercase"><?php echo $value['nom'].' '.$value['prenom'] ?></span></h4>
                                        </div>
                                        <form method="post">
                                        <div class="modal-body">
                                           <input type="hidden" value="<?php echo $value['idpersonnel'] ?>" name="idpersonnel">
                                            
                                            <div class="form-group" >
                                              <label>Solde de congé</label>
                                                <input class="form-control"   name="solde_conge" value="<?php echo $value['solde_conge'] ?>">
                                            </div>
                                              
                                      </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary"  >Modifier</button>
                                        </div></form>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>

     <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="detailsconge<?php echo  $value['idpersonnel'] ?>" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">POINT DES CONGES PRIS de 
                                                <span style="text-transform: uppercase"><?php echo $value['nom'].' '.$value['prenom'] ?></span></h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="col-md-3">Date de début</div>
                                            <div class="col-md-3">Date de fin</div>
                                            <div class="col-md-3">Date de demande</div>
                                            <div class="col-md-3">Nbre de jour</div>
                                           
                                           <?php 
                                                    if (isset($planningconge)) {
                                              foreach ($planningconge[$key] as $k => $value)  {
                                                if($value->etat == 1) {
                                               ?>

                                            <div class="col-md-4"><?php echo $value->date_debut; ?></div>
                                            <div class="col-md-4"><?php echo $value->date_fin; ?></div>
                                            <div class="col-md-3"><?php echo $value->date_demande; ?></div>
                                            <div class="col-md-1"><?php echo $value->nombre_jour; ?></div>

                                                <?php } }}?>
                                            
                                              
                                      </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                        </div>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>
  <?php } }?>
  </tbody>
</table> 

  

                             
 <script>
    $(document).ready(function() {
        $('#example1').DataTable({
    
                responsive: true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher un conge",
           "sEmptyTable": "Aucun congé ajout&eacute;e",
           "sInfo": "Nombre Total de conge: _TOTAL_ ",
           "sInfoEmpty": "Aucun congé ajout&eacute;e",
           "sLengthMenu": " _MENU_ Congé",
                "sZeroRecords": "Aucun congé ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ Congé)"
               }
        });
    });

    
    </script>