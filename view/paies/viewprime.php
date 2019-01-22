<?php  
$title_for_layout = 'Paie ';
$title_for_page_menu = 'Paie';
$current_menu = 'Liste des Primes DG';
$controller = $this->request->controller;
$button_option = ''.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#planningconge"><i class="fa fa-download"></i> Exporter</a>';
echo  $this->Session->flash();
?> 

 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="25%" class="text-center">Nom et Prénom <br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Montant Prime<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
           
        </tr>
  </thead>
 <tbody>
 <?php 

 foreach ($liste_prime as $key => $value) {
   ?>
        <tr>
          <td> <?php echo $value->nom.' '.$value->prenom ?></td>
          <td><?php echo $value->prime_dg ?></td>
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
           "sSearch": "Rechercher une prime",
           "sEmptyTable": "Aucune prime ajout&eacute;e",
           "sInfo": "Nombre Total de prime: _TOTAL_ ",
           "sInfoEmpty": "Aucune prime ajout&eacute;e",
           "sLengthMenu": " _MENU_ prime",
                "sZeroRecords": "Aucune prime ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ prime)"
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