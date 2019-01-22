<?php  
$title_for_layout = 'GESTION DES CONGES';
$title_for_page_menu = 'GESTION DES CONGES';
$current_menu = 'Tableau de bord';

 
 ?> 
 <!-- onclick="window.location='conges/dashboard'" -->
 <div class="tiles clearfix">
    <div class="row">
        <div class="col-sm-3 col-xs-6 tile" >        
                <div class="icon"><i class="fa fa-cube color-blue"></i></div>
                <div class="stat"><?php echo $congeprevu; ?></div>
                <div class="title">Congés prévus pour ce mois</div>
                <div class="highlight bg-color-blue"></div>        
        </div>
                    <div class="col-sm-3 col-xs-6 tile" onclick="">
                    <div class="icon"><i class="fa fa-globe color-green"></i></div>
                    <div class="stat"><?php echo $congepris; ?></div>
                    <div class="title">Congés pris ce mois</div>
                    <div class="highlight bg-color-green"></div>
            </div>
                <div class="col-sm-3 col-xs-6 tile" onclick="">
           
                <div class="icon"><i class="fa fa-comments color-red"></i></div>
                <div class="stat"><?php echo $congeprisan; ?></div>
                <div class="title">Congés pris sur l'année</div>
                <div class="highlight bg-color-red"></div>
          
        </div>
        <div class="col-sm-3 col-xs-6 tile" onclick="">
           
                <div class="icon"><i class="fa fa-credit-card color-gold"></i></div>
                <div class="stat"><?php echo $congeprisrestant; ?></div>
                <div class="title">Congés restant</div>
                <div class="highlight bg-color-gold"></div>
           
        </div>
    </div>
</div>
  
<div id="container" style="height: 400px"></div>
 <div class="client-home-panels">
    <div class="row">
        <div class="col-sm-6">
            <div menuitemname="Active Products/Services" class="panel panel-default panel-accent-gold">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                             <div class="pull-right">
                                    <a href=" " class="btn btn-default bg-color-gold btn-xs">
                                        <i class="fa fa-plus"></i>  
                                    </a>
                                </div>
                                    <i class="fa fa-cube"></i>&nbsp;  Personnes prévues pour aller en congé ce mois
                        </h3>
    </div>
                                    <div class="list-group">
                                <?php foreach ($personnesprevues as $key => $value) {
                                  ?>
                                          <a menuitemname="0" href=" " class="list-group-item"
                                           id="ClientAreaHomePagePanels-Active_Products_Services-0">
                             <?php echo $value->nom.' '.$value->prenom ?><br><span class="text-domain"><?php echo $value->nombre_jour; ?> jours</span>
                                          </a>
                                          <?php } ?>
                                </div>
            <div class="panel-footer">
                </div>
                </div>
            
                                                        
        </div>
        <div class="col-sm-6">

                <div menuitemname="Recent Support Tickets" class="panel panel-default panel-accent-blue">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                                  <i class="fa fa-comments"></i>&nbsp;  Retour de congés <= à 7jours
                        </h3>
                    </div>
                       <div class="list-group">
                                <?php 
                                if($personnesretour !=0){
                                foreach ($personnesretour as $key => $value) {
                                  ?>
                                          <a menuitemname="0" href=" " class="list-group-item"
                                           id="ClientAreaHomePagePanels-Active_Products_Services-0">
                             <?php echo $value['nom'] ?><br><span class="text-domain">Retour prévu dans :<?php echo $value['jour']; ?> jours</span>
                                          </a>
                                          <?php } } ?>
                                </div>
                    <div class="panel-footer">
                    </div>
                </div>
            
                            
        </div>
    </div>
</div>

        <script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            viewDistance: 65,
            depth: 40
        }
    },

    title: {
        text: 'POINT DES CONGES DUS POUR TOUT LE PERSONNEL'
    },

    xAxis: {
        categories: ['<= 24 ', '>24 et <= 50', '>50 et <= 80', '> 80' ]
    },

    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: 'Nombre de Jours'
        }
    },

    tooltip: {
        headerFormat: '<b>{point.key}</b><br>',
        pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
    },
 
    plotOptions: {
        column: {
            stacking: 'normal',
            depth: 40,
             colorByPoint: true
        }
    },

    colors: [
        '#5BC0DE',
        '#5CB85C',
        '#D9534F',
        '#F0AD4E'
    ],
    series: [{
        name: 'Intervalle',
        data: [<?php echo $nombre24->nombre.','.$nombre50->nombre.','.$nombre80->nombre.','.$nombreplus->nombre ?>],
        stack: 'male'
    } ]
});


        </script>