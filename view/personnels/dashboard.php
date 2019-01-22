  <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Personnels</a>
            </li>
            <li class="breadcrumb-item active">Tableau de bord</li> 
          </ol>
 <div class="tiles clearfix">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-users"></i>
                  </div>
                  <div class="mr-5"><?php echo $effectif; ?> </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">Effectif Total</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-male"></i>
                  </div>
                  <div class="mr-5"><?php echo $effectifhomme; ?>  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">Hommes</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

               <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-female"></i>
                  </div>
                  <div class="mr-5"><?php echo $effectiffemme; ?> </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">Femmes</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

             <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-male"></i>
                  </div>
                  <div class="mr-5"><?php echo $moyennegeneral; ?> ans</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left"> Moyenne d'âge</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-male"></i>
                  </div>
                  <div class="mr-5"><?php echo $cdi; ?>  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">CDI</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-male"></i>
                  </div>
                  <div class="mr-5"><?php echo $cdd; ?>  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">CDD</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-male"></i>
                  </div>
                  <div class="mr-5"><?php echo $stage; ?>  </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">STAGE</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
 
 </div>
          

</div>
<div class="row">
<div class="col-md-6">
     <div id="container" style=" height: 400px; margin: 0 auto"></div>

</div>
<div class="col-md-6">
     <div id="container1" style="height: 400px; margin: 0 auto"></div>

</div>
</div>
<!-- <div class="col-sm-6">
            <div menuitemname="Active Products/Services" class="panel panel-default panel-accent-red">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                                    <i class="fa fa-cube"></i>&nbsp;  Demandes spéciales en attente
                        </h3>
    </div>
                                    <div class="list-group">
                                      <a menuitemname="0" href=" " class="list-group-item" id="ClientAreaHomePagePanels-Active_Products_Services-0">
                                       <b>HOUESSOU Alban</b><br><span class="text-domain">Changement de domiciliation de salaire</span>
                                            <div class="pull-right">     
                                               <small class="btn btn-warning btn-xs">voir</small>
                                           </div>
                                      </a>

                                        <a menuitemname="0" href=" " class="list-group-item" id="ClientAreaHomePagePanels-Active_Products_Services-0">
                                       <b>Léa BATOSSI</b><br><span class="text-domain">Demande d'attestation de travail</span>
                                            <div class="pull-right">     
                                               <small class="btn btn-warning btn-xs">voir</small>
                                           </div>
                                      </a>
                                     
                                 </div>
            <div class="panel-footer">
                </div>
                </div>
                                                      
        </div> -->

<script type="text/javascript">


// Radialize the colors
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: {
            cx: 0.5,
            cy: 0.3,
            r: 0.7
        },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
        ]
    };
});

// Build the chart
Highcharts.chart('container1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Répartition par ancienneté'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                
                 enabled: false
                },
                showInLegend: true
        }
    },
     colors: [
        '#5BC0DE',
        '#5CB85C',
        '#D9534F',
        '#F0AD4E'
         ],
    series: [{
        name: 'Pourcentage',
        data: [
            { name: '<=3', y: <?php echo $age3 ?> },
            {
                name: '>3 et <=5',
                y: <?php echo $age5 ?>,
                sliced: true,
                selected: true
            },
            { name: '>5 et <=8', y: <?php echo $age8 ?> },
            { name: '>=9', y: <?php echo $age9 ?> }
        ]
    }]
});
        </script>


        <script type="text/javascript">
// Data gathered from http://populationpyramid.net/germany/2015/

// Age categories
var categories = ['0-30', '30-35', '35-39', '40 +'];
$(document).ready(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Pyramide des âges et sexes'
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: categories,
            reversed: false,
            labels: {
                step: 1
            }
        }, { // mirror axis on right side
            opposite: true,
            reversed: false,
            categories: categories,
            linkedTo: 0,
            labels: {
                step: 1
            }
        }],
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return Math.abs(this.value) + '%';
                }
            }
        },

        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
         colors: [
        '#5BC0DE',
        '#F0AD4E'
         ],

        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + ', age compris ' + this.point.category + '</b><br/>' +
                    'Nombre de personnes: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
            }
        },

        series: [{
            name: 'Homme',
            data: [-<?php echo $homme30->nombre  ?>, -<?php echo $homme35->nombre  ?>, -<?php echo $homme39->nombre  ?>, -<?php echo $homme40->nombre  ?>]
        }, {
            name: 'Femme',
            data: [<?php echo $femme30->nombre  ?>, <?php echo $femme35->nombre  ?>, <?php echo $femme39->nombre  ?>, <?php echo $femme40->nombre  ?>]
        }]
    });
});

        </script>