<?php 
include 'mis_nav.php';
include 'travel_side.php';

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='FA' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$q1[] = 0;
$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%TRAVEL%' GROUP BY product_class");
while($yr =$ach->fetch_array()){

$xxx= $yr['sum']/1000000;
 $q1[] = $xxx;
 
 $polc=$yr['count'];
$rAmount = $yr['sum'];
}

//Travel Production
$agcy_ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%TRAVEL%'");
$ro =$agcy_ach->fetch_array();
$agency= $ro['sum'];
$agencyPol= $ro['count'];
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">
                            <?php 
                            // if($rol =='FA'){echo "Bancassurance";}else{ echo $rol;}
                            ?>
                        </h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <?php 
                            /*
                            if($rol =='FA'){echo "<li class='active'><a href='bancca.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                            if($rol =='Agency'){echo "<li class='active'><a href='agency.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                           */ ?>
                            
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="row row-in">
                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-briefcase"></i>
                                            <h5 class="text-muted vb">BUDGET</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="counter text-right m-t-10 text-danger" style="font-size: 22px;"><?php echo number_format($tBud); ?></h4>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-pencil-alt"></i>
                                            <h5 class="text-muted vb">ACHIEVED</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="counter text-right m-t-10 text-info"><?php echo number_format($rAmount); ?></h4>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 row-in-br">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-list-ol"></i>
                                            <h5 class="text-muted vb">NoP Budget</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="counter text-right m-t-15 text-warning"><?php echo number_format($tPol); ?></h4>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 b-0">
                                    <div class="col-in row">
                                        <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-receipt"></i>
                                            <h5 class="text-muted vb">ACHIEVED</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="counter text-right m-t-15 text-success"><?php echo number_format($polc); ?></h4>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <!-- row -->
                 <div class="row">
                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">
                                New Business vs Renewal
                                <span class="pull-right">
                                    <i class="fa fa-circle m-r-5" style="color: #03A9F4;"></i>New Business
                                     <i class="fa fa-circle m-r-5" style="color: rgba(180,193,215,0.8);"></i>Renewal
                                </span>
                            </h3>
                            <div>
                                <canvas id="chart2" height="225"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">
                                Budget vs Target
                            </h3>
                            <div id="myChart2" style="height: 450px;"></div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
                 <div class="row">
                    
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (Branch)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        //Top branch performance
                                        $per=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, c_area FROM `myrecord` where c_area LIKE '%TRAVEL%' AND year='$y' 
                                                        AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') GROUP BY c_area ORDER BY sum DESC");
                                        while($r=$per->fetch_array()){
                                        $count=$r['count'];
                                        ?>
                                        <tr>
                                            <th><a href="" class="btn-link" style="font-size: 16px;"> <?php echo $r['c_area']; ?></a></th>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($r['sum']); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($count); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                   
                    
                    
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (RSE)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>RSEs</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Agent Performer
                                        //$agtPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where c_area LIKE '%TRAVEL%' AND year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE role='Agent' AND ( branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')) GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
                                        $agtPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where c_area LIKE '%TRAVEL%' AND year='$y' AND agent_name !='' GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
                                        while($srr=$agtPer->fetch_array()){
                                            $count3=$srr['count'];
                                        ?>
                                        <tr>
                                            <td><a href="" class="btn-link" style="font-size: 15px;"> <?php echo $srr['agent_name']; ?></a></td>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($srr['sum']); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($count3); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
            
            <!-- /.container-fluid -->
           
        </div>
        <!-- /#page-wrapper -->
    </div>
<?php
            $p=$con->query("SELECT product_class FROM myrecord GROUP BY product_class ");
            while($pr=$p->fetch_array()){
               $produt=$pr['product_class'];  
               $prDD[] = $produt; 
               
               
               // $q1[] = 0;
            $ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             product_class= '$produt' AND year='$y' AND type='NB' AND c_area LIKE '%TRAVEL%' ");
            while($yr =$ach->fetch_array()){

            $xxx= $yr['sum']/1000000;
             $q1[] = $xxx;
            }
            
            
             $ach2= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             product_class= '$produt' AND year='$y' AND type='RN' AND c_area LIKE '%TRAVEL%' ");
            while($yr2 =$ach2->fetch_array()){

            $xxx2= $yr2['sum']/1000000;
             $q2[] = $xxx2;
            }
            
            }
            
           
            
            ?>
    <!-- /#wrapper -->
    <!-- jQuery -->
   <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="../js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="../js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../js/custom.min.js"></script>
    <!-- Chart JS -->
    <script src="../scripts/charts/chartjs/bar/bar.js" type="text/javascript"></script>
    <script src="../scripts/charts/chartjs/bar/bar-stacked.js" type="text/javascript"></script>
    <script src="../scripts/charts/chartjs/bar/column.js" type="text/javascript"></script>
    <script src="../scripts/charts/chartjs/bar/column-stacked.js" type="text/javascript"></script>
    
    <!-- Line Charts-->
    <script src="..scripts/charts/chartjs/line/line.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-area.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-stacked-area.js" type="text/javascript"></script>

<!--    <script src="../plugins/bower_components/Chart.js/chartjs.init.js"></script>-->
    <script src="../plugins/bower_components/Chart.js/Chart.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- Zing chart  -->
    <script src="../plugins/bower_components/zing/zingchart.min.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.jquery.min.js"></script>
    
    <script>
        $( document ).ready(function() {
          
          var ctx2 = document.getElementById("chart2").getContext("2d");
    var data2 = {
        //labels: ["January", "February", "March", "April", "May", "June", "July"],
        labels: <?php echo json_encode($prDD); ?>,
        datasets: [
            {
                label: "New Business",
                fillColor: "#03A9F4", //"rgba(252,201,186,0.8)",
                strokeColor: "rgba(252,201,186,0.8)",
                highlightFill: "#03A9F4", //"rgba(252,201,186,1)",
                highlightStroke: "rgba(252,201,186,1)",
                data: <?php echo json_encode($q1); ?>
                //data: [10, 30, 80, 61, 26, 75, 40]
            },
            {
                label: "My Second dataset",
                fillColor: "rgba(180,193,215,0.8)",
                strokeColor: "rgba(180,193,215,0.8)",
                highlightFill: "rgba(180,193,215,1)",
                highlightStroke: "rgba(180,193,215,1)",
                data: <?php echo json_encode($q2); ?>
               // data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };
    
    var chart2 = new Chart(ctx2).Bar(data2, {
        scaleBeginAtZero : true,
        scaleShowGridLines : true,
        scaleGridLineColor : "rgba(0,0,0,.005)",
        scaleGridLineWidth : 0,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        barShowStroke : true,
        barStrokeWidth : 0,
		tooltipCornerRadius: 2,
        barDatasetSpacing : 3,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true
    });
    
  
    });
    </script>
    
      
    <?php 
    $agy=$tBud /1000000; //Agency budget
    if($agy <= 200){
        $agy = 200; $d= 10;
        $VL = 20;
        $L = 50;
        $M = 100;
        $H = 160;
        $VH = 160;
        
    }
    elseif($agy >= 200 && $agy < 500){
        $agy = 500; $d = 50;
        $VL = 100;
        $L = 200;
        $M = 300;
        $H = 400;
        $VH = 400;
    }
    elseif($agy >= 500 && $agy < 1000){
        $agy = 1000; $d = 100;
        $VL = 100;
        $L = 300;
        $M = 500;
        $H = 700;
        $VH = 700;
    }
    elseif($agy >= 1000 && $agy < 2000){
        $agy = 2000; $d = 200;
        $VL = 300;
        $L = 500;
        $M = 800;
        $H = 1000;
        $VH = 1000;
    }
    elseif($agy > 2000 && $agy < 5000){
        $agy = 3000; $d = 500;
        $VL = 800;
        $L = 1500;
        $M = 2500;
        $H = 3500;
        $VH = 3500;
    }
    
    $xcy=$agency /1000000; //agency production
    
    
    
    ?>
  
   <script>
    zingchart.THEME = "classic";
    var myConfig = {
      "graphset": [{
        "type": "gauge",
        "background-color": "#fff #eee",
        "plot": {
          "background-color": "#666"
        },
        "plotarea": {
          "margin": "40 40 40 40"
        },
        "scale": {
          "size-factor": 1.25,
          "offset-y": 120
        },
        "tooltip": {
          "background-color": "black"
        },
        "scale-r": {
          "values": "0:<?php echo $agy; ?>:<?php echo $d; ?>",
          "border-color": "#b3b3b3",
          "border-width": "2",
          "background-color": "#eeeeee,#b3b3b3",
          "ring": {
            "size": 10,
            "offset-r": "130px",
            "rules": [{
              "rule": "%v >=0 && %v < <?php echo $VL; ?>",
              "background-color": "#FB0A02"
            }, {
              "rule": "%v >= <?php echo $VL; ?> && %v < <?php echo $L; ?>",
              "background-color": "#EC7928"
            }, {
              "rule": "%v >= <?php echo $L; ?> && %v < <?php echo $M; ?>",
              "background-color": "#FAC100"
            }, {
              "rule": "%v >= <?php echo $M; ?> && %v < <?php echo $H; ?>",
              "background-color": "#B1AD00"
            }, {
              "rule": "%v >= <?php echo $H; ?> && %v >= <?php echo $VH; ?>",
              "background-color": "#348D00"
            }]
          }
        },
        "images": [
          // {
          //     "src":"gaugle_scale_mini.png",
          //     "position":"50% 80%"
          // }
        ],
        "labels": [{
          "id": "lbl1",
          "x": "50%",
          "y": "90%",
          "width": 80,
          "offsetX": 160,
          "textAlign": "center",
          "padding": 10,
          "anchor": "c",
          "text": "Very High",
          "backgroundColor": "#237b00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#237b00",
            "text": "<= <?php echo $VH; ?> <br>Units",
            "shadow": 0
          }
        }, {
          "id": "lbl2",
          "x": "50%",
          "y": "90%",
          "width": 80,
          "offsetX": 80,
          "textAlign": "center",
          "padding": 10,
          "anchor": "c",
          "text": "High",
           "backgroundColor": "#B1AD00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#a09c00",
            "text": ">= <?php echo $M; ?> <= <?php echo $H; ?><br>Units",
            "shadow": 0
          }
        }, {
          "id": "lbl3",
          "x": "50%",
          "y": "90%",
          "width": 80,
          "offsetX": 0,
          "textAlign": "center",
          "padding": 10,
          "anchor": "c",
          "text": "Medium",
          "backgroundColor": "#FAC100",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#e9b000",
            "text": ">= <?php echo $L; ?> <= <?php echo $M; ?><br>Units",
            "shadow": 0
          }
        }, {
          "id": "lbl4",
          "x": "50%",
          "y": "90%",
          "width": 80,
          "offsetX": -80,
          "textAlign": "center",
          "padding": 10,
          "anchor": "c",
          "text": "Low",
          "backgroundColor": "#EC7928",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#da6817",
            "text": ">= <?php echo $VL; ?> < <?php echo $L; ?><br>Units",
            "shadow": 0
          }
        }, {
          "id": "lbl5",
          "x": "50%",
          "y": "90%",
          "width": 80,
          "offsetX": -160,
          "textAlign": "center",
          "padding": 10,
          "anchor": "c",
          "text": "Very Low",
          "backgroundColor": "#ea0901",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#ea0901 ",
            "text": " <= <?php echo $VL; ?><br>Units",
            "shadow": 0
          }
        }],
        "series": [{
          "values": [<?php  echo $xcy ?>],
          "animation": {
            "method": 5,
            "effect": 2,
            "speed": 2500
          }
        }],
        "alpha": 1
      }]
    };

    zingchart.render({
      id: 'myChart2',
      data: myConfig,
    });
  </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
