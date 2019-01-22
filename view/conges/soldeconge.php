<?php  
$title_for_layout = 'GESTION DES CONGES';
$title_for_page_menu = 'GESTION DES CONGES';
$current_menu = 'Solde des Congés';
$controller = $this->request->controller;
?> 
<div class="col-md-3">  

<form method="post" id="form" name="form">
<div class="form-group">
     <label>Choisir le titre</label>
    <select multiple class="form-control selectpicker" id="listeagent" onchange="document.getElementById('form').submit()"  name="titre" style="height:232px"  >
        <?php foreach ($titre as $key => $value): ?>
              <option <?php if(isset($letitre) && $letitre == $value->idtitre) echo "selected" ?> value="<?php echo $value->idtitre ?>"><?php echo $value->nom; ?></option>
      <?php endforeach ?>
    </select>
</div>

</form>


 </div>
<div class="col-md-9">  
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
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h5 class="modal-title" id="myModalLabel">MODIFIER LE SOLDE DE 
                                                <span style="text-transform: uppercase"><?php echo $value['nom'].' '.$value['prenom'] ?></span></h5>
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

  
  <?php } }?>
  </tbody>
</table> 
</div>

<?php  if (isset($listeagent)) {
  foreach ($listeagent as $key => $value) { ?>
   <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="detailsconge<?php echo  $value['idpersonnel'] ?>" >
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">POINT DES CONGES PRIS de 
                                                <span style="text-transform: uppercase"><?php echo $value['nom'].' '.$value['prenom'] ?></span></h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                            
                                          <table  class="table table-bordered">
                                              <thead>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                                <th>Date de la demande</th>
                                                <th>Nbre de jour</th>
                                              </thead>
                                              <tbody>
                                                 <?php 
                                                    if (isset($planningconge)) {
                                              foreach ($planningconge[$key] as $k => $value)  {
                                                if($value->etat == 1) {
                                               ?>
                                                <tr>
                                                  <td><?php echo date("d-m-Y",strtotime($value->date_debut)); ?></td>
                                                  <td><?php echo date("d-m-Y",strtotime($value->date_fin)); ?></td>
                                                  <td><?php echo date("d-m-Y",strtotime($value->date_demande)); ?></td>
                                                  <td><?php echo date("d-m-Y",strtotime($value->nombre_jour)); ?></td>
                                                </tr>
                                                <?php } }} ?>
                                              </tbody>
                                            </table> 
                                            
                                            
                                              
                                      </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                        </div>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>
                            <?php }} ?>
   <script> 
$(document).ready( function() {
  
 
      // on envoie la valeur recherch� en GET au fichier de traitement
      $.ajax({
    type : 'POST', // envoi des donn�es en GET ou POST
  url : '' , // url du fichier de traitement
  data : 'q='+document.getElementById('listeagent').value , // donn�es � envoyer en  GET ou POST
  beforeSend : function() { // traitements JS � faire AVANT l'envoi
    //$field.after('<img src="images/loading.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
  },
  success : function(data){ // traitements JS � faire APRES le retour d'ajax-search.php
    $('#ajax-loader').remove(); // on enleve le loader
    $('#results').html(data); // affichage des r�sultats dans le bloc
  }
      });
     
  
});

</script>

                             
 <script>
    $(document).ready(function() {
        $('#example1').DataTable({
    
                responsive: true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher un agent",
           "sEmptyTable": "Aucun agent ajout&eacute;e",
           "sInfo": "Nombre Total de agent: _TOTAL_ ",
           "sInfoEmpty": "Aucun agent ajout&eacute;e",
           "sLengthMenu": " _MENU_ Agents",
                "sZeroRecords": "Aucun Agent ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ Congé)"
               }
        });
    });

    
    </script>