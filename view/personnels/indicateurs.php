 <div class="tiles clearfix">
    <div class="row">
        <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-compass"></i></div>
                <div class="stat"><?php echo round($tauxabsenteisme,2).' %' ?></div>
                <div class="title">Taux d'absentéisme</div>
                <div class="highlight bg-color-blue"></div>
            </a>
        </div>
 
        <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-recycle color-red"></i></div>
                <div class="stat"><?php echo $turn_over.' %'; ?></div>
                <div class="title">Turn over</div>
                <div class="highlight bg-color-red"></div>
            </a>
        </div>

          <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon "><i class="fa fa-fire color-gold"></i></div>
                <div class="stat"><?php echo $taux_delinquance.' %'; ?></div>
                <div class="title">Taux de délinquance</div>
                <div class="highlight bg-color-gold"></div>
            </a>
        </div>


          <div class="col-sm-3 col-xs-3 tile" onclick="">
            <a href="">
                <div class="icon"><i class="fa fa-compress color-asbestos"></i></div>
                <div class="stat"><?php echo $taux_retention.' %'; ?></div>
                <div class="title">Taux de rétention</div>
                <div class="highlight bg-color-asbestos"></div>
            </a>
        </div>
 </div>
 <div class="col-md-12">
    <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
</div>
 

        <script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'EFFECTIF PAR POSTE'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Effectif'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Nombre de personne <b>{point.y:.1f} </b>'
    },
    series: [{
        name: 'Poste',
        data: [
        <?php foreach ($listeposte as $key => $value) {
             ?>
            ['<?php echo $value->nom ?>', <?php echo $value->nombre ?>],
            <?php } ?>
            
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
        </script>