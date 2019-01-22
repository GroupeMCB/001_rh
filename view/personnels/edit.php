<?php
  $title_for_layout = 'Personnel';
  $title_for_page_menu = 'Personnel';
  $current_menu = 'Modification';

  $diplomes = array('bts', 'licence', 'maitrise', 'master', 'doctorat');
?>
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo BASE_URL.DS?>personnels/viewList/contractuels">Personnels</a>
  </li>
  <li class="breadcrumb-item active"><?= $datapersonnel->nom." ".$datapersonnel->prenom; ?></li>
</ol>
<form class="form-horizontal" role="form" action="<?php echo BASE_URL; ?>/personnels/save/<?= $typepersonnel; ?>" method="post" enctype="multipart/form-data">
  <div class="row container">
    <div class="col-md-4">  
        <a href="#" class="thumbnail">
          <img src="<?php echo BASE_URL; ?>/webroot/images/user/<?php echo  $datapersonnel->photo?>"  width="172px" title="<?= $datapersonnel->nom." ".$datapersonnel->prenom; ?>">
        </a>  
      <div class=" form-group">
        <label for="employephoto" class="control-label" style="text-align: center;">Ajouter une photo</label>
        <input type="file" class="form-control" id="employephoto" name="employephoto">
      </div> 
  </div>

 <div class="col-md-8 row">
      <div class="form-group col-md-6">
          <label for="" class="control-label">Nom complet </label>
          <div class="input-control text">
            <input type="hidden" name="idpersonnel" value="<?= $datapersonnel->idpersonnel; ?>">
            <input type="text" class="form-control input-sm" id="" name="nom" placeholder="Nom" value="<?= $datapersonnel->nom; ?>" autocomplete="off"/>
          </div>
      </div>
      <div class="form-group col-md-6">
          <label for="" class="control-label">Prénom</label>
           <div class="input-control text">
              <input type="text" class="form-control input-sm" id="" name="prenom" placeholder="Prenom" value="<?= $datapersonnel->prenom; ?>" autocomplete="off"/>
          </div>
      </div>

        <div class="form-group col-md-12">
          <label for="textareaDescription" class="col-md-4 control-label">Autres Prénoms</label>
          <div class="input-control text">
              <input type="text" class="form-control input-sm" id="" name="autres_prenom" value="<?= $datapersonnel->autres_prenom; ?>" autocomplete="off"/>
          </div>
        </div>

        <div class="form-group col-md-6" id="identifiantdiv">
          <label for="" class="control-label">Sexe</label>
          <div class="input-control text">
            <select name="sexe" id="" class="form-control input-sm">
              <option value="F" <?= ($datapersonnel->sexe == 'F') ? 'selected' : '' ; ?>>Féminin</option>
              <option value="M" <?= ($datapersonnel->sexe == 'M') ? 'selected' : '' ; ?>>Masculin</option>
            </select>
          </div>
        </div>
          
        <div class="form-group col-md-6" id="">
          <label for="" class="control-label">Date de naissance</label>
          <div class="input-control text ">
            <input type="text" class="form-control input-sm" id="date_naissance" name="date_naissance" placeholder="Date de naissance" value="<?= $datapersonnel->date_naissance; ?>" autocomplete="off"/>
          </div> 
        </div>
        </div>

        <div class="form-group col-md-12 row">
       
        <div class="form-group col-md-4">
          <label for="" class="control-label">Domaine d'étude et niveau d'étude:</label>
          <div class="input-control text">
            <input type="text" name="domaine_etude" class="form-control input-sm" value="<?= $datapersonnel->domaine_etude?>" >
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="" class="control-label">Niveau d'étude:</label>
          <div class="input-control text">
            <select name="niveau_etude" id="" class="form-control input-sm">
              <option value="">Selectionnez un niveau d'étude</option>
              
              <?php foreach ($diplomes as $diplome): ?>
                <?php if ($diplome == $datapersonnel->niveau_etude): ?>
                  <option value="<?= $diplome; ?>" selected ><?= strtoupper($diplome); ?></option>
                <?php else: ?>
                  <option value="<?= $diplome; ?>"><?= strtoupper($diplome); ?></option>
                <?php endif ?>              
              <?php endforeach ?>
            </select>
          </div>
          </div>

        
        <div class="form-group col-md-4">
          <label for="" class="control-label">Situation matrimoniale :</label>
          <div class="input-control select">
            <select class="form-control input-sm" name="situation_matrimoniale">
             <option value="">Selectionnez votre situation matrimoniale</option>
             <option value="Célibataire" <?= ($datapersonnel->situation_matrimoniale == 'Célibataire') ? 'selected' : '' ; ?>>Célibataire</option>
             <option value="Marié(e)" <?= ($datapersonnel->situation_matrimoniale == 'Marié(e)') ? 'selected' : '' ; ?>>Marié(e)</option>
            </select>
          </div>
        </div>

        <div class="form-group col-md-4">
          <label for="" class="control-label">Nombre d'enfant à charge</label>
           <div class="input-control select ">
            <input type="number" name="nombre_enfant_charge" class="form-control input-sm" value="<?= $datapersonnel->nombre_enfant_charge; ?>" placeholder="Nbre d'enfants à charge">
          </div>
        </div>

          <div class="form-group col-md-4">
          <label for="" class="control-label">Numéro CNSS :</label>
          <div class="input-control text">
              <input type="text" class="form-control input-sm" id="" name="numero_cnss" value="<?= $datapersonnel->numero_cnss; ?>" autocomplete="off"/>
          </div>
        </div>
        <div class="form-group col-md-4 ">
          <label for="" class="control-label">Date d'entrée dans l'entreprise</label>
          <div class="input-control text">
              <input type="text" class="form-control input-sm" id="date_entree" value="<?= $datapersonnel->date_entree; ?>" name="date_entree" placeholder="Date d'entrée" value="" autocomplete="off"/>
          </div>
        </div>
        <div class="form-group col-md-12">
          <label for="identifiant" class="control-label">Adresse complète :</label>
          <div class="input-control select">
            <textarea name="adresse_complete" class="form-control input-sm" id="" cols="30" rows="3"><?= $datapersonnel->adresse_complete; ?></textarea>
          </div>
        </div>

        <div class="form-group col-md-4">
          <label for="" class="control-label">Departement </label>
          <div class="input-control text ">
            <select name="departements_id" id="" class="form-control input-sm">
              <option value="">Selectionnez le département</option>
              <?php foreach ($departements as $value): ?>
                <?php if ($value->iddepartement == $datapersonnel->departements_id): ?>
                  <option value="<?= $value->iddepartement; ?>" selected><?= $value->libelle_departement; ?></option>
                <?php else: ?>
                  <option value="<?= $value->iddepartement; ?>"><?= $value->libelle_departement; ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          </div>

          <div class="input-control text col-md-4">
          <label for="" class="control-label">Profil </label>
            <select name="titres_id" id="" class="form-control input-sm">
              <option value="">Profil</option>
              <?php foreach ($titres as $value): ?>
                <?php if ($value->idtitre == $datapersonnel->titres_id): ?>
                  <option value="<?= $value->idtitre; ?>" selected><?= $value->nom; ?></option>
                <?php else: ?>
                  <option value="<?= $value->idtitre; ?>"><?= $value->nom; ?></option>
                <?php endif ?>
              <?php endforeach; ?>
            </select>
          </div>
         
        <div class="form-group col-md-4">
          <label for="" class="control-label ">Catégorie </label>
          <div class="input-control text ">
            <select name="" id="" class="form-control input-sm">
              <option value="">Selectionnez la catégorie de contrat</option>
              <?php foreach ($categories as $value): ?>
                <?php if ($datapersonnel->categorie_id == $value->idcategorie): ?>
                  <option value="<?= $value->idcategorie; ?>" selected><?= $value->nom; ?></option>
                <?php else: ?>
                  <option value="<?= $value->idcategorie; ?>"><?= $value->nom; ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </div>
          </div>

        <div class="form-group col-md-4">
          <label for="" class="control-label ">Type de contrat </label>
           <div class="input-control text ">
            <select name="type_contrat_id" class="form-control input-sm">
              <option value="">Selectionnez le type de contrat</option>
              <?php foreach ($typecontrats as $contrat): ?>
                <?php if ($contrat->idtype_contrat == $personnelcontrat->idtype_contrat): ?>
                  <option value="<?= $contrat->idtype_contrat; ?>" selected ><?= $contrat->nom; ?></option>
                <?php else: ?>
                  <option value="<?= $contrat->idtype_contrat; ?>"><?= $contrat->nom; ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </div>
        </div>

         <div class="form-group col-md-4">
          <label for="" class="control-label ">Type de personnel </label>
          <div class="input-control text  ">
           <select name="type_personnel_id" id="" class="form-control input-sm">
              <option value="">Selectionnez le type de personnel</option>
              <?php foreach ($type_personnel as $value): ?>
                <?php if ($value->idtype_personnel == $datapersonnel->type_personnel_id): ?>
                  <option value="<?= $value->idtype_personnel; ?>" selected ><?= $value->libelle; ?></option>
                <?php else: ?>
                  <option value="<?= $value->idtype_personnel; ?>"><?= $value->libelle; ?></option>
                <?php endif ?>
              <?php endforeach ?>
            </select>
          </div>
          </div>

         <div class="form-group col-md-4">
          <label for="" class="control-label ">Statut </label>
           <div class="input-control text ">
            <select name="statut" class="form-control input-sm">
              <option></option>
              <option value="ABFPA" <?php if($datapersonnel->statut == "ABFPA") echo "selected" ?>>ABFPA</option>
              <option value="DRH" <?php if($datapersonnel->statut == "DRH") echo "selected" ?>>DRH</option>
              <option value="ASSISTANT DSI" <?php if($datapersonnel->statut == "ASSISTANT DSI") echo "selected" ?>>ASSISTANT DSI</option>
              <option value="FORMATION" <?php if($datapersonnel->statut == "FORMATION") echo "selected" ?>>FORMATION</option>
              <option value="FTY" <?php if($datapersonnel->statut == "FTY") echo "selected" ?>>FTY</option>
              <option value="MOOV" <?php if($datapersonnel->statut == "MOOV") echo "selected" ?>>MOOV</option>
              <option value="MTN" <?php if($datapersonnel->statut == "MTN") echo "selected" ?>>MTN</option>
              <option value="TEAM LEADER" <?php if($datapersonnel->statut == "TEAM LEADER") echo "selected" ?>>TEAM LEADER</option>
              <option value="SUPERVISEUR" <?php if($datapersonnel->statut == "SUPERVISEUR") echo "selected" ?>>SUPERVISEUR</option>
              <option value="RESPONSABLE QUALITY" <?php if($datapersonnel->statut == "RESPONSABLE QUALITY") echo "selected" ?>>RESPONSABLE QUALITY</option>
              <option value="RESPONSABLE OPERATION" <?php if($datapersonnel->statut == "RESPONSABLE OPERATION") echo "selected" ?>>RESPONSABLE OPERATION</option>
              <option value="RESPONSABLE DSI" <?php if($datapersonnel->statut == "RESPONSABLE DSI") echo "selected" ?>>RESPONSABLE DSI</option>
               
              <option value="RECRUTEMENT" <?php if($datapersonnel->statut == "RECRUTEMENT") echo "selected" ?>>RECRUTEMENT</option>
              <option value="RBU MTN" <?php if($datapersonnel->statut == "RBU MTN") echo "selected" ?>>RBU MTN</option>
              <option value="RBU MOOV" <?php if($datapersonnel->statut == "RBU MOOV") echo "selected" ?>>RBU MOOV</option>
              <option value="QUALITY ADVISOR" <?php if($datapersonnel->statut == "QUALITY ADVISOR") echo "selected" ?>>QUALITY ADVISOR</option>
              <option value="PLANNIFICATEUR" <?php if($datapersonnel->statut == "PLANNIFICATEUR") echo "selected" ?>>PLANNIFICATEUR</option>
            </select>
          </div>
        </div>
 

         <div class="form-group col-md-12">
          <label for="" class="control-label ">Emplacement du dossier physique : </label>
          <div class="input-control text ">
            <input type="text" class="form-control input-sm" name="emplacement" value="<?= $datapersonnel->emplacement; ?>">
          </div>
        </div>

        <hr>
         <div class="form-group col-md-12"> 
            <button type="submit" id="validation" class="btn btn-success">Enregistrer</button>
            <a href="#" class="btn btn-danger">Annuler</a> 
        </div>
  </div>
</form>

<script>
    $('#date_naissance').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

    $('#date_entree').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

    $('#date_sortie').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });
</script>