  <?php 
 // $pdo = new PDO ('mysql:dbname=mcb_rh;host=localhost', 'root', '',
 // array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); ?>

<label>Choisir un agent</label>
    <select name="assignLevelIDb" class="form-control selectpicker" data-width="300px" data-live-search="true" data-size="10" class="form-control input-sm">

      <option value="">&nbsp;</option>
     
      <?php 


        // $req1 = $pdo->prepare('SELECT * FROM personnel WHERE titre_id = ?');

        // $req1->execute(array($_GET['q']));
        // $databases = $req1->fetchAll();

        // if ($databases) {
          
        //   foreach ($databases as $key => $value) {
      ?>
        <option value="<?php //echo $value['idpersonnel']; ?>"><?php //echo $value['nom']; ?></option>
      <?php
         // } // #foreach
       // } // #if
      ?>
    </select>


 