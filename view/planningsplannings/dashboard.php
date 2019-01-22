<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Tableau de Bord';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Tableau de Bord';
?>

<div class="col-md-9 costum-body">
  <div class="page-header">
    <h3>Bienvenue</h3>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Racourcis</h3>
    </div>
    <div class="panel-body">
      <div class="shortcuts">

          <a href="<?= BASE_URL.DS; ?>plannings/createPlanningForm" class="shortcut">
            <span class="glyphicon glyphicon-floppy-disk"></span><br>
            Créer un planning
          </a>

          <a href="<?= BASE_URL.DS; ?>plannings/listAllPlanning" class="shortcut">
            <span class="glyphicon glyphicon-calendar"></span><br>
            Consulter un planning
          </a>

          <a href="<?= BASE_URL.DS; ?>permissions" class="shortcut">
            <span class="glyphicon glyphicon-random"></span><br>
            Plannings spéciaux & permissions
          </a>

          <a href="<?= BASE_URL.DS; ?>profils/listprofil" class="shortcut">
            <span class="glyphicon glyphicon-user"></span><br>
            Gestion des profils
          </a>

          <a href="<?= BASE_URL.DS; ?>statistiques" class="shortcut">
            <span class="glyphicon glyphicon-stats"></span><br>
            Statistiques
          </a>

          <a href="<?= BASE_URL.DS; ?>logs" class="shortcut">
            <span class="glyphicon glyphicon-list-alt"></span><br>
            Journal
          </a>

          <a href="<?= BASE_URL.DS; ?>configurations" class="shortcut">
            <span class="glyphicon glyphicon-cog"></span><br>
            Configuration
          </a>

        <a href="<?= BASE_URL.DS; ?>aides" class="shortcut">
          <span class="glyphicon glyphicon-exclamation-sign"></span><br>
          Aide
        </a>

      </div>

    </div>
  </div>
</div>
