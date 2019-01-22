<?php  
$title_for_layout = 'PERSONNEL';
$title_for_page_menu = 'PERSONNEL';
$current_menu = 'Mouvement du personnel';
$controller = $this->request->controller;

$month = array("01" => "Janvier", "02" => "Février","03" => "Mars","04" => "Avril","05" => "Mai","06" => "Juin","07" => "Juillet","08" => "Août","09" => "Septembre","10" => "Octobre","11" => "Novembre","12" => "Décembre");
 
$mois_en_cours = date("m");

$titrecol = count($titre)+1;
$sanctioncol = count($sanction)+1;
 ?> 
 <div class="clear-fix" ></div>
<ul class="nav nav-pills" >
	<?php foreach ($month as $keys => $values){ ?>

    <li <?php if(date("m") == $keys) echo 'class="active"';?>><a href="#tab<?php echo $values; ?>" data-toggle="tab" aria-expanded="true"><?php  echo $values;?></a>
    </li>
   <?php } ?>
</ul>

<div class="tab-content" style="padding:20px">
<?php 
 
 foreach ($this->Helper->TableauouvementPersonnel() as $k   => $value) {
    echo $value;
 } 
 ?>


       
    </div>

      <script>
       // popover demo
    $("[data-toggle=popover]")
        .popover()
     
    </script>