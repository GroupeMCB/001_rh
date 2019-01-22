<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Nouveau planning';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Nouveau planning';
?>

<div class="col-md-9 costum-body createPlanning">

  <?php if (!empty($succes)) { ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><?php echo $succes; ?></b>
    </div>
  <?php }elseif (!empty($echec)) { ?>
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><?php echo $echec; ?></b>
    </div>
  <?php } ?>

  <div class="page-header">
    <h4>Créer un Planning</h4>
  </div>

  <form class="form-horizontal" role="form" action="<?php echo WEBROOT.'plannings/generatePlanning'; ?>" method="post">
    <div class="col-md-5">
      <h5>Période</h5>
      <div class="form-group">
        <label for="inputPeriodeDebut" class="col-md-3 control-label">Du :</label>
        <div class="col-md-6">
          <input type="text" class="form-control" id="inputPeriodeDebut" name="periodeDebut" value="">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPeriodeFin" class="col-sm-3 control-label">Au :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="inputPeriodeFin" name="periodeFin" value="">
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <h5>cellule</h5>
      <div class="form-group">
        <label for="celluleSelect" class="col-sm-6 control-label">Selectionez la cellule :</label>
        <div class="col-sm-6">
          <select name="selectedCel" id="celluleSelect" onchange="showVacation(this.value)" class="form-control" data-selecter-options="{&quot;cover&quot;:&quot;true&quot;}" tabindex="-1">
            <?php
              if ($allCel) {
                echo '<option value="">Choisir une cellule</option>';
                foreach ($allCel as $value) {
                   echo '<option value="'.$value['id'].'">'.$value['libCellule'].'</option>';
                }
              }
             ?>
          </select>

        </div>
      </div>
    </div>

    <div class="col-md-12" id="vacationDiv">

    </div>

    <div class="form-group">
      <div class="col-sm-5 pull-right">
        <button type="submit" name="createBtn" id="createBtn" class="btn btn-success">Créer</button>
        <button type="reset" class="btn btn-danger">Annuler</button>
      </div>
    </div>

  </form>
</div>
