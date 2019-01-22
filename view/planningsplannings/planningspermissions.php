<?php
  setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Planning - Plannings speciaux et permissions';
	$title_for_page_menu = 'Planning';
	$current_menu = 'Plannings speciaux et permissions';
?>


<div class="col-md-8 costum-body" id="bigbody">
	<?php include(ROOT.'views/layout/breadcrumb.php') ?>
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

	<h3 class="sub-header">Gestion des permissions
		<div class="pull-right">
			<!-- Déclencheur de la suppression -->
			<a href="<?php echo WEBROOT.'permissions/viewAllPermissionsBy'; ?>" class="btn btn-default">Voir les permissions</a>
		</div>
	</h3>
	<br>

	<h5>Listes des agents</h5>
	<table class="table table-hover table-condensed table-bordered table-responsive" id="tablelog">
		<thead>
			<tr>
				<th>#</th>
				<th>Log</th>
				<th>Nom & Prénoms</th>
				<th class="actionth2">Action</th>
			</tr>
		</thead>
		<tbody>

			<?php
				if ($campAgents) {

					$count = 1;

					foreach ($campAgents as $key => $value)
					{
			?>

			<tr>
				<td><?php echo $count; ?></td>
				<td><?php echo $value['Agent_idAgent']['log']; ?></td>
				<td><?php echo $value['Agent_idAgent']['nomAgent'].' '.$value['Agent_idAgent']['prenomAgent']; ?></td>
				<td>
					<a href="<?php echo WEBROOT.'permissions/agentPermissionHistory/'.$value['Agent_idAgent']['id']; ?>"><span class="label label-default">Voir</span></a>
				</td>
			</tr>

			<?php
					$count++;
					}
				}
			?>

		</tbody>
	</table>
</div>
