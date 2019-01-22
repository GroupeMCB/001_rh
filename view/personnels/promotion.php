<?php  
$title_for_layout = 'PERSONNELS';
$title_for_page_menu = 'PROMOTION';
$current_menu = 'Tableau de Bord des promotions';
$controller = $this->request->controller;

$month = array("01-02-03" => "Jan - Fev - Mars", "04-05-06" => "Avr - Mai - Juin","07-08-09" => "Jui - Aou - Sep","10-11-12" => "Oct - Nov - Dec");
 
$mois_en_cours = date("m");

$titrecol = count($titre)+1;
 
 ?> 
 <div class="clear-fix" ></div>
<ul class="nav nav-pills" >
  <?php foreach ($month as $keys => $values){
      $cle = explode('-', $keys);
        
          if(in_array(date("m"), $cle)) $act ="active"; else $act = '';
   ?>

    <li class="<?php echo $act ?>" ><a href="#tab<?php echo $keys; ?>" data-toggle="tab" aria-expanded="true"><?php  echo $values;?></a>
    </li>
   <?php } ?>
</ul>

<div class="tab-content" style="padding:20px">
<?php 
 //debug($this->Helper->contratpartitre());
 
  foreach ($this->Helper->NombrePersonnePartitre() as $k   => $value) {
     echo $value;
  } 
 ?>


       
    </div>

      <script>
       // popover demo
    $("[data-toggle=popover]")
        .popover()
     
    </script>