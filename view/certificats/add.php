<?php  
$title_for_layout = 'GESTION DES CERTIFICATS';
$title_for_page_menu = 'CERTIFICATS';
$current_menu = 'AJOUTER UN CERTIFICAT';
$controller = $this->request->controller;
  
 ?>

 <form action="<?php echo BASE_URL; ?>/certificats/addcertificat" method="post">  

        <input type="hidden" name="personnel_id" value="<?= $datapersonnel->idpersonnel; ?>">
        <div class="form-group">
          <label for="" class="  ">Nom de l'agent:</label>
              <input type="text" class="form-control input-sm" id="inputPrenom" value="<?php echo $datapersonnel->nom.' '.$datapersonnel->prenom ?>" disabled />
        </div>
     

      <div class="form-group">
           <label class="">Choisir le type de certificat</label>
           <div class="input-control text">
          <select class="form-control" name="type_certificat_id" >
            <?php foreach ($listecertificat as $key => $value): ?>
              <option value="<?php echo $value->idtype_certificat ?>"><?php echo $value->libelle_certificat; ?></option>
              
            <?php endforeach ?>
          </select>
      </div></div>

    <div class="form-group" >
          <label>Numero</label>
            <input class="form-control"  name="numero">
        </div>

        <div class="form-group" >
          <label>Date de consultation</label>
            <input class="form-control" id="consultation" name="date_consultation">
        </div>

         <div class="form-group" >
          <label>Date de début</label>
            <input class="form-control" id="debut" name="date_debut">
        </div>

         <div class="form-group" >
          <label>Date de fin</label>
            <input class="form-control" id="fin" name="date_fin">
        </div>

        
        <div class="form-group" >
          <label>Total jour de repos</label>
              <input class="form-control" name="total_jour_repos" value="" >
        </div>

          <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-danger">Enregistrer</button>
    </div>
 </form>
    <script>
       
   $('#debut').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
     
     $('#fin').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });

      $('#consultation').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });

      </script>