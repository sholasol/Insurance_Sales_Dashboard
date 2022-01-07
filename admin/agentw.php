<?php 
$rol = $_GET['r'];
$code = $_GET['code'];
$frm="";
$t2="";
$d1=0;
$d2 = 0;
if(isset($_GET['frm'])){
$frm =$_GET['frm'];
$d1= date("m", strtotime($frm));
}
if(isset($_GET['to'])){
$t2 = $_GET['to'];
$d2= date("m", strtotime($t2));
}
$area='';
include 'mis_nav.php';
if($rol =="Partners"){
    include 'partner_side.php';
    $area = 'Partners - Autodealership';
  
}
elseif($rol =='HNI'){
    include 'hni_side.php';
    $area = 'HNI';
}
elseif($rol =='FI'){
    include 'fi_side.php';
    $area = 'Partners - Microfinance';
}
$branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'";

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='$rol'  AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];


//
//$q2[] = 0;

$ach2=$con->query("SELECT sum(r_amount) AS sum, count(distinct agent_code) AS count FROM myrecord where c_area LIKE '$area' AND 
year='$y'  AND receipt_date BETWEEN '$frm' AND '$t2' AND agent_code ='$code' GROUP BY product_class");
while($yr2 =$ach2->fetch_array()){
$totAgt = $yr2['count'];
//$xxx2= $yr2['sum']/1000000;
// $q2[] = $xxx2;
}








//Agency Production
$agcy_ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
   receipt_date BETWEEN '$frm' AND '$t2' AND  year='$y' AND agent_code = '$code' ");
$ro =$agcy_ach->fetch_array();
$agency= $ro['sum'];
$agencyPol= $ro['count'];




$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
receipt_date BETWEEN '$frm' AND '$t2' AND  year='$y' AND type='NB' AND `agent_code`='$code' ");
$yr =$ach->fetch_array();

$polNB=$yr['count'];
$AmtNB = $yr['sum'];

//
$ach2=$con->query("SELECT sum(r_amount) as sum, count(distinct agent_code) AS agent, count(distinct policy_no) AS count FROM myrecord where 
receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND type='RN' AND `agent_code`='$code'");
$yr2 =$ach2->fetch_array();
$totAgt = $yr2['agent'];

$polRN=$yr2['count'];
$AmtRN = $yr2['sum'];


 //Coverage Areas Budget
    
    //VI
    $covBudget = $con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM branch_bud WHERE bID='$bid' AND branch =$branch ");
    $cor=$covBudget->fetch_array();
    $sumBud =$cor['nb'] + $cor['rn'];
    $sumPol =$cor['polnb'] + $cor['polrn'];
    
    
    
    //Coverage Areas Productions
    
    //VI
    $covArea = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
    receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND `agent_code`='$code' ");
    $cv = $covArea->fetch_array();
    $suAmt = $cv['sum'];
    $suPol = $cv['count'];
    
    //Number of RSEs in Each Branch
    $ag=$con->query("SELECT count(distinct policy_no) AS count, agent_name FROM `myrecord` where receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND `agent_code`='$code' ");
    
    //$ag=$con->query("SELECT count(agent_code) AS agents FROM agent WHERE branch =$branch ");
    $gr=$ag->fetch_array();
    $NoRse =  $gr['count'];
    $Name =  $gr['agent_name'];
    
    
    //Percentage Achieved Budget Amount 
    $PA_Abuja = ($suAmt/$sumBud)*100;
    
    
    //Percentage Achieved Budget Policy
    $PA_Abuja1 = ($suPol/$sumPol)*100;
    
    //Percentage Achieved Budget NB
    $perAchAmtNB=($AmtNB/$cor['nb'])*100;
    $perAchPolNB = ($polNB/$cor['polnb']) * 100;
    
    //Percentage Achieved Budget RN
    $perAchAmtRN=($AmtRN/$cor['rn'])*100;
    $perAchPolRN = ($polRN/$cor['polrn']) * 100;
    
    
    
    
    //Percentage Achieved Budget Amount
    if($suAmt < 1 || $sumBud < 1){$PA_Abuja = 0;}
    else{$PA_Abuja = ($suAmt/$sumBud)*100;}
    
    
    //Percentage Achieved Budget Policy
    if($suPol < 1 || $sumPol < 1){$PA_Abuja1 = 0;}
    else{$PA_Abuja1 = ($suPol/$sumPol)*100;}
    
    
    //Percentage Achieved Budget NB
    if($AmtNB < 1 || $cor['nb'] < 1){$perAchAmtNB= 0;}
    else{$perAchAmtNB=($AmtNB/$cor['nb'])*100;}
    
    if($polNB < 1 || $cor['polnb'] < 1){$perAchPolNB= 0;}
    else{$perAchPolNB = ($polNB/$cor['polnb']) * 100;}
    
    //Percentage Achieved Budget RN
    //$perAchAmtRN=($AmtRN/$cor['sum(rn)'])*100;
    if($AmtRN < 1 || $cor['rn'] < 1){$perAchAmtRN= 0;}
    else{$perAchAmtRN=($AmtRN/$cor['rn'])*100;}
    
    
    //$perAchPolRN = ($polRN/$cor['sum(polrn)']) * 100;
    
    if($polRN < 1 || $cor['polrn'] < 1){$perAchPolRN= 0;}
    else{$perAchPolRN = ($polRN/$cor['polrn']) * 100;}

?>
        
       <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">
                            <?php 
                            if($rol =='FI'){echo "Partners - FI";}
                            elseif($rol =='HNI'){echo "Partners - HNI";}
                            elseif($rol =='Partners'){echo "Partners - Autodealership";}
                            
                            ?>
                        </h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <?php 
                                 echo "<a href='wdetail.php?r=$rol&frm=$frm&to=$t2'><i class='icon-home'></i> Back</a> ";
                                ?>
                            </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                
                <!-- Row-->
                 <!-- /.row -->
               
                
                
                
                <!-- row -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">
                                New Business vs Renewal
                                <span class="pull-right">
                                    <i class="fa fa-circle m-r-5" style="color: #03A9F4;"></i>New Business
                                    <i class="fa fa-circle m-r-5" style="color: #00b5c2;"></i>Renewal
                                </span>
                            </h3>
                            <div>
                                <canvas id="chart2" height="206"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row"> 
                            <div class="col-md-12 col-sm-12">
                                <div class="white-box ">
                                    
                                    <div class="row sales-report">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h4>
                                        <?php echo $Name; ?>
                                    </h4>
                                    <p>SALES REPORT</p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                    <h1 class="text-right text-success m-t-20"><i class="icon-briefcase text-danger"> <?php echo number_format($NoRse); ?> Policies</i></h1>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BUSINESS</th>
                                            <th>ACHIEVED</th>
                                            <th>% aCHIEVED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th class="txt-oflo text-primary">NEW BUSINESS</th>
                                            <td class="txt-oflo"><?php echo number_format($AmtNB); ?></th>
                                            <th><span class="text-success"><?php  echo number_format((float)$perAchAmtNB, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">NOP</th>
                                            <td class="txt-oflo"><?php echo number_format($polNB); ?></th> 
                                            <th><span class="text-success"><?php  echo number_format((float)$perAchPolNB, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">RENEWAL</th>
                                            <td class="txt-oflo"><?php echo number_format($AmtRN); ?></th>
                                            <th><span class="text-success"><?php echo number_format((float)$perAchAmtRN, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">NOP</th>
                                            <td class="txt-oflo"><?php echo number_format($polRN); ?></th>
                                            <th><span class="text-success"><?php echo number_format((float)$perAchPolRN, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                           <td >
                                               <!-- <span class="text-danger">Budget: <?php echo number_format($sumBud); ?></span>
                                                <span class="text-warning pull-right">NoP: <?php echo number_format($sumPol); ?> |</span> -->
                                            </td>
                                            <td colspan="2">
                                                <span class="text-primary">| ACHIEVED <?php echo number_format($suAmt); ?> </span> 
                                                <span class="text-primary pull-right">NoP <?php echo number_format($suPol); ?> </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> </div>
                                </div>
                            </div> 
                            
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                   <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6">
                             <div class="white-box">
                                 <h3 class="box-title"><?php echo $Name." Production" ?></h3>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" width="100%">
                                                  <thead>
                                                    <tr>
                                                        <th width="50%"><strong>Agent Name </strong></th>
                                                        <th width="5%"><strong>Agent Code </strong></th>
                                                        <th width="5%"><strong>Policy No. </strong></th>
                                                        <th width="20%"><strong>Product </strong></th>
                                                        <th width="5%"><strong>NoP </strong></th>
                                                        <th width="8%"><strong>Authorization Date </strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $t=0;
                                                    $q=$con->query("SELECT distinct policy_no, agent_name, agent_code, product, r_amount, p_aut_date FROM `myrecord` where receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND `agent_code`='$code'  ");
                                                    while($row = $q->fetch_array()){
                                                        $t +=$row['r_amount'];
                                                    ?>
                                                    <tr>
                                                        <th><strong><a><?php echo $row['agent_name']; ?></a></strong></th>
                                                        <th><strong><?php echo $row['agent_code']; ?></strong></th>
                                                        <th><strong><span class="text-info"><?php echo $row['policy_no']; ?></span></strong></th>
                                                        <th><strong><span class="text-primary"><?php echo $row['product']; ?></span></strong></th>
                                                        <th><strong><span class="text-primary"><?php echo number_format($row['r_amount']);?></span></strong></th>
                                                        <th><strong><span class="text-primary"><?php echo $row['p_aut_date'];?></span></strong></th>
                                                    </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="4">Total</td>
                                                        <td><span class="btn btn-info"><?php echo number_format($t); ?></span></td>
                                                    </tr>
                                                </tbody>
                                        </table>
                                    </div>
                             </div>
                        </div>
                   </div>
                    
                   
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                   
                    
                </div>
                <!-- /.row -->
                
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul>
                                <li><b>Layout Options</b></li>
                                <li>
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox1" type="checkbox" class="fxhdr">
                                        <label for="checkbox1"> Fix Header </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
                                <li><b>With Dark sidebar</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/varun.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/genu.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/ritesh.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/arijit.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/govinda.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/hritik.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/john.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/pawandeep.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.right-sidebar -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    
    <?php  
    $p=$con->query("SELECT product_class FROM myrecord GROUP BY product_class ");
            while($pr=$p->fetch_array()){
               $produt=$pr['product_class'];  
               $prDD[] = $produt; 
               
               
               // $q1[] = 0;
            $ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
           receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND product_class= '$produt' AND type='NB' AND `agent_code`='$code' ");
            while($yr =$ach->fetch_array()){

            $xxx= $yr['sum']/1000000;
             $q1[] = $xxx;
            }
            
            
             $ach2= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
          receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND product_class= '$produt' AND type='RN' AND `agent_code`='$code'");
            while($yr2 =$ach2->fetch_array()){

            $xxx2= $yr2['sum']/1000000;
             $q2[] = $xxx2;
            }
            
            } 
        
    ?>
    
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
    $xcy=$agency /1000000; //agency production
    
    
    ?>
     <script>
        var myConfig3 = {
          "type": "gauge",
          "scale-r": {
            "aperture": 200,
            "values": "0:<?php echo $agy; ?>:100"
          },
          "series": [ {
            "values": [<?php  echo $xcy ?>], //New Business
            "csize": "15%", //Needle Indicator Width
            "size": "75%", //Needle Indicator Length
            "background-color": "#C6E746"
          }]
        };

        zingchart.render({
          id: 'myChart',
          data: myConfig3,
          height: "100%",
          width: "100%"
        });
  </script>
</body>

</html>


