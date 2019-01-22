<?php 

  $title_for_layout = 'Personnel';
  $title_for_page_menu = 'Personnel';
  $current_menu = 'Ajoute';
?>
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo BASE_URL.DS?>personnels/viewList/contractuels">Personnels</a>
  </li>
  <li class="breadcrumb-item active">Nouveau</li>
</ol>
<form class="form-horizontal" role="form" action="<?php echo BASE_URL; ?>/personnels/save/<?= $typepersonnel; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" value="1" name="active"> 
  <div class="row container">
    <div class="col-md-4"> 
      <a href="#" class="thumbnail">
        <img src="<?php echo BASE_URL; ?>/images/user/default-picture.png" alt="Picture" width="172px">
      </a> 
      <div class=" form-group">
        <label for="employephoto" class="control-label" style="text-align: center;">Ajouter une photo</label>
        <input type="file" class="form-control" id="employephoto" name="employephoto">
      </div>
    </div>

    <div class="col-md-8 row">
      <div class="form-group col-md-6">
          <label for="nomcomplet" class="control-label">Nom complet: </label>
          <div class="input-control text">
              <input type="text" class="form-control input-sm" id="nomcomplet" name="nom" placeholder="Nom" autocomplete="off"/>
          </div>
        </div>
      <div class="form-group col-md-6">
           <div class="input-control text">
              <label for="prenom" class="control-label">Prénom: </label>
              <input type="text" class="form-control input-sm" name="prenom" id="prenom" placeholder="Prenom" autocomplete="off"/>
          </div>

        </div>

        <div class="form-group col-md-12">
          <label for="autreprenom" class="control-label">Autres Prénoms :</label>
          <div class="input-control text">
              <input type="text" class="form-control input-sm" id="autreprenom" name="autres_prenom" autocomplete="off"/>
          </div>

        </div>

        <div class="form-group col-md-6" id="div">
          <label for="sexe" class=" control-label">Sexe :</label>

          <div class="input-control text  ">
            <select name="sexe" id="sexe" class="form-control input-sm">
              <option value="F">Féminin</option>
              <option value="M">Masculin</option>
            </select>
          </div>
        </div>

        <div class="form-group col-md-6" id="div">
          <label for="date_naissance" class=" control-label">Date de naissance :</label>
          <div class="input-control text  ">
            <input type="text" class="form-control input-sm" id="date_naissance" name="date_naissance" placeholder="Date de naissance" autocomplete="off"/>
          </div>
          
        </div>
        </div>
        <div class="form-group col-md-12 row">

             <div class="form-group  col-md-6">
          <label for="" class="control-label">Situation matrimoniale :</label>
          <div class="input-control select ">
            <select class="form-control input-sm" name="situation_matrimoniale">
             <option value="">Selectionnez votre situation matrimoniale</option>
             <option value="Célibataire">Célibataire</option>
             <option value="Marié(e)">Marié(e)</option>
            </select>
          </div>
          </div>

        <div class="form-group  col-md-6">   
          <label for="nombre_enfant_charge" class="control-label">Nombre d'enfant à charges :</label>  
          <div class="input-control select ">
            <input type="number" name="nombre_enfant_charge" id="nombre_enfant_charge" class="form-control input-sm" placeholder="Nbre d'enfants à charge">
          </div>

        </div>
        <div class="form-group col-md-6">
          <label for="domaine_etude" class="  control-label">Domaine d'étude et niveau d'étude:</label>
          <div class="input-control text  ">
            <input type="text" name="domaine_etude" id="domaine_etude" class="form-control input-sm">
          </div>
          </div>

          <div class="input-control text col-md-6">
          <label for="domaine_etude" class="  control-label">Domaine d'étude et niveau d'étude:</label>
            <select name="niveau_etude" class="form-control input-sm">
              <option value="">Selectionnez un niveau d'étude</option>
              <option value="BTS">BTS</option>
              <option value="Licence">Licence</option>
              <option value="Maîtrise">Maitrise</option>
              <option value="Master">Master</option>
              <option value="Doctorat">Doctorat</option>
            </select>
          </div>

        <div class="form-group col-md-12">
          <label for="adresse_complete" class=" control-label">Adresse complète :</label>
          <div class="input-control select ">
            <textarea name="adresse_complete" class="form-control input-sm" id="adresse_complete" cols="30" rows="3"></textarea>
          </div>
        </div>

        <div class="form-group col-md-12">
          <label for="numero_cnss" class="control-label ">Numéro CNSS :</label>
          <div class="input-control text ">
            <input type="text" class="form-control input-sm" id="numero_cnss" name="numero_cnss" autocomplete="off"/>
          </div>
        </div>

        <div class="form-group col-md-12">
          <label for="date_entree" class="control-label ">Date d'entrée dans l'entreprise :</label>

          <div class="input-control text ">
              <input type="text" class="form-control input-sm" id="date_entree" name="date_entree" placeholder="Date d'entrée" autocomplete="off"/>
          </div>

        </div>

        <div class="form-group col-md-6">
          <label for="departements_id" class="control-label">Département </label>

          <div class="input-control text">
            <select name="departements_id" id="departements_id" class="form-control input-sm">
              <option value="">Selectionnez le département</option>
              <?php foreach ($departements as $value): ?>
                <option value="<?= $value->iddepartement; ?>"><?= $value->libelle_departement; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          </div>
        <div class="form-group col-md-6">
          <label for="departements_id" class="control-label">Profil </label> 
           <div class="input-control text">
            <select name="titres_id" id="" class="form-control input-sm">
              <option value="">Selectionnez le profil</option>
              <?php foreach ($titres as $value): ?>
                <option value="<?= $value->idtitre; ?>"><?= $value->nom; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="hidden" name="categorie_id" value="1">
        </div>

        <div class="form-group col-md-6">
          <label for="" class="control-label  ">Catégorie :</label>
          <div class="input-control text ">
            <select name="categorie_id" id="" class="form-control input-sm">
              <option value="">Selectionnez la catégorie de contrat</option>
              <?php foreach ($categories as $value): ?>
                <option value="<?= $value->idcategorie; ?>"><?= $value->nom; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
          
        <div class="form-group col-md-6">
          <label for="" class="control-label  ">Type de contrat :</label>
          <div class="input-control text ">
            <select name="type_contrat" class="form-control input-sm">
              <option value="">Selectionnez le type de contrat</option>
              <?php foreach ($typecontrats as $contrat): ?>
                <option value="<?= $contrat->idtype_contrat; ?>"><?= $contrat->nom; ?></option>
              <?php endforeach ?>
            </select>
          </div>  
        </div>

        <div class="form-group col-md-12">
          <label for="type_personnel_id" class="control-label ">Type de personnel</label>
          <div class="input-control text  ">
            <select name="type_personnel_id" id="type_personnel_id" class="form-control input-sm">
              <option value="">Selectionnez le type de personnel</option>
              <?php foreach ($type_personnel as $value): ?>
                <option value="<?= $value->idtype_personnel; ?>"><?= $value->libelle; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group col-md-12">
          <label for="" class="control-label">Emplacement du dossier physique : </label>
          <div class="input-control text ">
            <input type="text" class="form-control input-sm" name="emplacement" placeholder="Emplacement du dossier">
          </div>
        </div>

        <hr>
        <div class="form-group col-md-12 mt-20">
          <div class=" ">
            <button type="submit" id="validation" class="btn btn-success">Enregistrer</button>
            <a href="#" class="btn btn-danger">Annuler</a>
          </div>
        </div>	
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