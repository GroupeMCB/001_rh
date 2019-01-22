<?php  
$title_for_layout = 'GESTION DES CERTIFICATS ET SANCTIONS';
$title_for_page_menu = 'GESTION DES CERTIFICATS ET SANCTIONS';
$current_menu = 'Tableau de Bord';
$controller = $this->request->controller;
?> 

  <div class="page-header" style="margin-top:30px; border-bottom: 2px solid #000">
    <span style="font-weight: bold; color:#A20606"> <i class="glyphicon glyphicon-chevron-right"></i> RECAPITULATIF DES CERTIFICATS</span>
    </div>
 <div class="tiles clearfix">
    <div class="row">
        <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-ticket"></i></div>
                <div class="stat"><?php echo $nombrecertificatmedical; ?></div>
                <div class="title">Nombre de certificats médicals</div>
                <div class="highlight bg-color-blue"></div>
            </a>
        </div>
 
        <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-paste color-red"></i></div>
                <div class="stat"><?php echo $nombrecertificatgrossesse ?></div>
                <div class="title">Certificats de grossesse</div>
                <div class="highlight bg-color-red"></div>
            </a>
        </div>

      <!--     <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon "><i class="fa fa-files-o color-gold"></i></div>
                <div class="stat">15%</div>
                <div class="title">CDD</div>
                <div class="highlight bg-color-gold"></div>
            </a>
        </div>


          <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-file-text color-asbestos"></i></div>
                <div class="stat">2</div>
                <div class="title">CDI</div>
                <div class="highlight bg-color-asbestos"></div>
            </a>
        </div>

        <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-edit color-asbestos"></i></div>
                <div class="stat">2</div>
                <div class="title">STAGE</div>
                <div class="highlight bg-color-asbestos"></div>
            </a>
        </div> -->
 </div>

    <div class="page-header" style="margin-top:30px; border-bottom: 2px solid #000">
    <span style="font-weight: bold; color:#A20606"> <i class="glyphicon glyphicon-chevron-right"></i> RECAPITULATIF DES SANCTIONS</span>
    <span class="pull-right"><a href="<?php echo BASE_URL ?>/sanctions/index" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Afficher les détails</a></span>
    </div>

         <div class="row clearfix" style="margin-top:0px;">
           <div class="col-sm-4 col-xs-4 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-files-o color-purple"></i></div>
                <div class="stat"><?php echo $nombremise; ?></div>
                <div class="title">mise à pied </div>
                <div class="highlight bg-color-purple"></div>
            </a>
        </div>
         <div class="col-sm-4 col-xs-4 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-list-alt color-pomegranate"></i></div>
                <div class="stat"><?php echo $nombreavertissement ?></div>
                <div class="title">Avertissement </div>
                <div class="highlight bg-color-pomegranate"></div>
            </a>
        </div>

        <div class="col-sm-4 col-xs-4 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-files-o color-pink"></i></div>
                <div class="stat"><?php echo $nombrelicenciement ?></div>
                <div class="title">Licenciement</div>
                <div class="highlight bg-color-pink"></div>
            </a>
        </div>

 <!--         <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-files-o color-purple"></i></div>
                <div class="stat">100</div>
                <div class="title">CDD</div>
                <div class="highlight bg-color-purple"></div>
            </a>
        </div>

         <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-file-text color-purple"></i></div>
                <div class="stat">100</div>
                <div class="title">CDI</div>
                <div class="highlight bg-color-purple"></div>
            </a>
        </div>
 
       

         <div class="col-sm-2 col-xs-2 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-edit color-magenta"></i></div>
                <div class="stat">2</div>
                <div class="title">STAGE</div>
                <div class="highlight bg-color-magenta"></div>
            </a>
        </div> -->
  
         

</div>

</div>
  