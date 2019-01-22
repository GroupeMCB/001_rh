<?php  
$title_for_layout = 'Paie ';
$title_for_page_menu = 'Paie';
$current_menu = 'Liste des retenue sur salaires';
$controller = $this->request->controller;
$button_option = ''.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#planningconge"><i class="fa fa-download"></i> Exporter</a>';
echo  $this->Session->flash();
?> 

 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="25%" class="text-center">Nom et Prénom <br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Montant<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
          <th width="10%" class="text-center">Motif<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
          <th width="15%" class="text-center">Date <br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="15%" class="text-center">  </th>
         
        </tr>
  </thead>
 <tbody>
 <?php 

 foreach ($liste_retenue as $key => $value) {
   ?>
        <tr>
          <td> <?php echo $value->nom.' '.$value->prenom ?></td>
          <td><?php echo $value->montant_retenue ?></td>
          <td><?php echo $value->motif_retenue ?></td>
          <td><?php echo date("d-m-Y",strtotime($value->date_retenue)) ?></td>  
            <td width="15%" class="text-center"> <a href="#" class="btn btn-danger btn-sm "  data-toggle="modal" data-target="#del<?php echo $value->idpersonnel_retenue ?>" ><span class="fa fa-trash"></span> supprimer </a></td>
      

          
                      <div class="modal fade" id="del<?php echo $value->idpersonnel_retenue ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4><i class="fa fa-trash"></i> Suppresion d'une retenue sur salaire de: <?= $value->nom.' '.$value->prenom; ?></h4>
                            </div>
                            <form action="" method="post">
                            <div class="modal-body">
                                <input type="hidden" value="<?php echo $value->idpersonnel_retenue;  ?>" name="idpersonnel_retenue">                             
                                <input type="hidden" value="0" name="delete">
                                Cette action est irreverssible. Prière générer à nouveau la paie de cet agent afin que ces nouveaux éléments soient prises en compte.

                             </div>
                            <div class="modal-footer">
                              <a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
                             <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</button>  
                            </div>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog  -->
                      </div> <!-- /.modal-fade -->
                      </form>
        </tr>
        <?php } ?>
     </tbody>
     </table>
     <script>
    $(document).ready(function() {
 var table =       $('#example1').DataTable({
     "ordering": false,
     "sorting" : false,
     "searching": true,
     "bfilter":false,
     "bLengthChange": false,
                responsive: true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher une retenue",
           "sEmptyTable": "Aucune retenue ajout&eacute;e",
           "sInfo": "Nombre Total de retenue: _TOTAL_ ",
           "sInfoEmpty": "Aucune retenue ajout&eacute;e",
           "sLengthMenu": " _MENU_ retenue",
                "sZeroRecords": "Aucune retenue ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ retenue)"
               }
        });


    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.header() ).on( 'keyup change', function () {

            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    });   
    </script>