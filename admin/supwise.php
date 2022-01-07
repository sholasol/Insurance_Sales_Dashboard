<?php 
include 'mis_nav.php';
include 'mis_side.php';
$rol = $_GET['r'];

$tP=0; //total policy
$tA=0; // Total production Amount

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='Agency' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where 
                  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')");
$yr2 =$ach2->fetch_array();
$totAgt = $yr2['count'];

$polc=$yr['count'];
$rAmount = $yr['sum'];
















$b_id= $_GET['budg'];
$ya= $_GET['year'];
$bran= $_GET['bran'];
$sup= $_GET['sup'];

//getting supervisor code
if($rol =='Agency'){
$sp=$con->query("SELECT * FROM agent WHERE role='Supervisor' AND name='$sup'");
$sro=$sp->fetch_array();
$code=$sro['agent_code'];
}
if($rol =='FA'){
$sp=$con->query("SELECT * FROM agent WHERE name='$sup'");
$sro=$sp->fetch_array();
$code=$sro['agent_code'];
}


//Supervisor budget
//bID, year,branch, supervisor, sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn
$q=$con->query("SELECT bID, year,branch, supervisor, sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn  FROM sup_budget WHERE branch='$bran' AND bID='$b_id' AND year='$ya' AND supervisor='$sup' ");
$a1=[];
$row = $q->fetch_array();

$bid = $row['bID'];
$ya = $row['year'];
$bran = $row['branch'];
$tBdt = $row['nb']+ $row['rn'];
$tPol = $row['polnb']+ $row['polrn'];
$naM= $row['supervisor'];

$New_B = $row['nb'];
$Ren_B = $row['rn'];

$New_Pol = $row['polnb'];
$Ren_Pol = $row['polrn'];

//Total Budget for the chart
$a1[]=$tBdt/1000000;

//Total Supervisor Production
$prodd=0;
$poll = 0;
$pr =$con->query("SELECT  SUM(r_amount), count(id) AS count FROM  myrecord  WHERE agent_code ='$code' AND year='$ya'");
while($rp = $pr->fetch_array()){
    $ttS = $rp['SUM(r_amount)'];
    $ttP = $rp['count'];
    
    $prodd=$ttS;
    $poll = $ttP;
}

//Supervisor New business and Policy count
$newBAmt =0;
$reNAmt = 0;

$newPol =0;
$reNPol = 0;

//Supervisor production  NB
$pr1 =$con->query("SELECT sum(r_amount), count(id) AS count FROM  myrecord  WHERE agent_code ='$code' AND year='$ya' AND type='NB'");
while($rp1 = $pr1->fetch_array()){
    $spCou = $rp1['count'];
    $supAmt = $rp1['sum(r_amount)'];
    //New business 
    $newBAmt += $supAmt;
    $newPol += $spCou;
    
    $tP += $spCou;
    $tA += $supAmt;
}

//Supervisor production  RN
$pr2 =$con->query("SELECT sum(r_amount), count(id) AS count FROM  myrecord  WHERE agent_code ='$code' AND year='$ya' AND type='RN'");
while($rp2 = $pr2->fetch_array()){
    $spCou2 = $rp2['count'];
    $supAmt2 = $rp2['sum(r_amount)'];
    //Renewal
    $reNAmt += $supAmt2;
    $reNPol += $spCou2;
}


$agn=$con->query("SELECT name, agent_code , count(agent_code) AS rse FROM agent WHERE supervisor='$naM'");
while($arg=$agn->fetch_array()){
    $agnt= $arg['name'];
    $agtCod=$arg['agent_code'];
    $rse=$arg['rse'];
 
    //Getting each agent's production

    $w= $con->query("SELECT  SUM(r_amount), count(id) AS count FROM  myrecord  WHERE agent_code ='$agtCod' AND year='$ya' ");
    $wr=$w->fetch_array();
    $rAmt=$wr['SUM(r_amount)'];
    $pCount = $wr['count'];

    $prodd += $rAmt;
    $poll += $pCount;
    //Percentage Budget achieved in terms of sales and Nop
  
    
     if($prodd < 1 || $tBdt < 1){$perBud = 0;}
   else{ $perBud=($prodd/$tBdt)*100;}
   
   if($poll < 1 || $tPol < 1){$perPol = 0;}
   else{ $perPol = ($poll/$tPol) * 100;}
    
    
    //Agent Performance in term of New business and Policy count
    $agNB = $con->query("SELECT sum(r_amount), count(id) AS count FROM myrecord WHERE agent_code='$agtCod' AND year='$ya' AND type='NB'");
    while($qr=$agNB->fetch_array()){
        $agt_Pol = $qr['count'];
        $agt_Amt = $qr['sum(r_amount)'];
        
        $tP += $agt_Pol;
        $tA += $agt_Amt;
        
        $newBAmt += $agt_Amt;
        $newPol += $agt_Pol;
    }
    
    //Agent Performance in term of Renewal and policy count
    $agRN = $con->query("SELECT sum(r_amount), count(id) AS count FROM myrecord WHERE agent_code='$agtCod' AND year='$ya' AND type='RN'");
    while($qr1=$agRN->fetch_array()){
        $agt_Pol2 = $qr1['count'];
        $agt_Amt2 = $qr1['sum(r_amount)'];
        
        $reNAmt += $agt_Amt2;
        $reNPol += $agt_Pol2;
    }
    
    
    
} 

//Achieved for the chart
$a2[]=$prodd/1000000;

$a3[] = $sup; // Chart label

$tnb=$newBAmt;
$trn=$total=$reNAmt;

include 'supwise_det.php';

$totAchieved = $tProductioNB + $tProductioRN;
//Percentage Achived NB
 if($tProductioNB < 1 || $New_B < 1){$perAchNB = 0;}
    else{$perAchNB = ($tProductioNB/$New_B)*100;}
  if($tProductioRN < 1 || $Ren_B < 1){$perAchRN = 0;}
  else{  $perAchRN = ($tProductioRN/$Ren_B)*100;}
    
    
//Percentage Policy Achieved
  if($tPolicYNB < 1 || $New_Pol < 1){$perNBPol = 0;}
  else{ $perNBPol = ($tPolicYNB/$New_Pol)*100;}
  if($tPolicYRN < 1 || $Ren_Pol < 1){$perRNPol = 0;}
  else{ $perRNPol = ($tPolicYRN/$Ren_Pol)*100;}

//Total Policy Count Achieved
 $totPolicy = $tPolicYNB + $tPolicYRN ;
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Agency</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <?php
                            if($bran =='Ensure Victoria Island'){
                                            echo "<a href='bran_detail.php?b=VI&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }elseif($bran=='Ensure Ikeja'){
                                            echo "<a href='bran_detail.php?b=IKJ&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='Ensure Broad Street'){
                                            echo "<a href='bran_detail.php?b=BS&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='Ensure Abuja'){
                                            echo "<a href='bran_detail.php?b=ABJ&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='Ensure PH'){
                                            echo "<a href='bran_detail.php?b=PH&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='Ensure Ibadan'){
                                            echo "<a href='bran_detail.php?b=IB&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='FA Ibadan'){
                                            echo "<a href='bran_detail.php?b=FIB&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='FA Lagos'){
                                            echo "<a href='bran_detail.php?b=FLAG&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='FA PH'){
                                            echo "<a href='bran_detail.php?b=FPH&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='FA Abuja'){
                                            echo "<a href='bran_detail.php?b=FABJ&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        else{
                                            echo "<a href='index.php?bran_detail&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                            ?>
                            </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Year to Day Production Report</h3>
                            <div id="myChart" style="height: 450px;"></div>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-6">
                        <div class="row"> 
                            <div class="col-md-12 col-sm-12">
                                <div class="white-box ">
                                    
                                    <div class="row sales-report">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h2><?php echo $naM; ?>'s</h2>
                                    <p>SALES REPORT</p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                    <h1 class="text-right text-success m-t-20"><i class="icon-people text-danger"> <?php echo number_format($rse); ?> RSEs</i></h1>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BUSINESS</th>
                                            <th>BUDGET</th>
                                            <th>ACHIEVED</th>
                                            <th>% aCHIEVED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th class="txt-oflo text-primary">NEW BUSINESS</th>
                                            <th><span class="label label-danger label-rouded"><?php echo number_format($row['nb']); ?></span> </th>
                                            <td class="txt-oflo"><?php echo number_format($tProductioNB); ?></th>
                                            <th><span class="text-success"><?php echo number_format((float)$perAchNB, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">NOP</th>
                                            <th><span class="label label-warning label-rouded"><?php echo number_format($row['polnb']); ?></span> </th>
                                            <td class="txt-oflo"><?php echo number_format($tPolicYNB); ?></th> 
                                            <th><span class="text-success"><?php echo number_format((float)$perNBPol, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">RENEWAL</th>
                                            <th><span class="label label-danger label-rouded"><?php echo number_format($row['rn']); ?></span> </th>
                                            <td class="txt-oflo"><?php echo number_format($tProductioRN); ?></th>
                                            <th><span class="text-success"><?php echo number_format((float)$perAchRN, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">NOP</th>
                                            <th><span class="label label-warning label-rouded"><?php echo number_format($row['polrn']); ?></span> </th>
                                            <td class="txt-oflo"><?php echo number_format($tPolicYRN); ?></th>
                                            <th><span class="text-success"><?php echo number_format((float)$perRNPol, 1, '.', '')."%"; ?></span></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">Budget: <?php echo number_format($tBdt); ?></span>
                                                <span class="text-warning pull-right">NoP: <?php echo number_format($tPol); ?> |</span> 
                                            </th>
                                            <td colspan="2">
                                                <span class="text-primary">| ACHIEVED <?php echo number_format($totAchieved); ?> </span> 
                                                <span class="text-primary pull-right">NoP <?php echo number_format($totPolicy); ?> </span>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table> </div>
                                </div>
                            </div> 
                            
                        </div>
                    </div>
                </div>
                <!--row -->
                
                
                
                
                <div class="row">
                   <div class="col-md-12 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title"><?php echo $naM ?>' Production</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                               <thead>
                                                    <tr>
                                                        <th width="25%"><strong>Agents</strong></th>
                                                        <th><strong>Agent Code</strong></th>
                                                        <th width="15%"><strong> Achieved </strong></th>
                                                        <th width="10%"><strong>% Achieved </strong></th>
                                                        <th width="15%"><strong>NoP  </strong></th>
                                                        <th width="10%"><strong>% Achieved </strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    //Supervisor Sales
                                                    $tProduction = 0;
                                                    $tPolicy= 0;
                                                    $spSale=$con->query("SELECT  SUM(r_amount), count(distinct policy_no) AS count FROM  myrecord  WHERE agent_code ='$code' AND year='$ya'");
                                                    while($spr=$spSale->fetch_array()){
                                                        $sSal = $spr['SUM(r_amount)'];
                                                        $sSPol = $spr['count'];
                                                        
                                                        //Percentage Budget achieved in terms of sales and Nop
                                                            if($sSal < 1 || $tBdt < 1){
                                                                $sBugg = 0;
                                                            }else{
                                                            $sBugg=($sSal/$tBdt)*100;
                                                            }
                                                            //$sPoly = ($sSPol/$tPol) * 100; //using total policy from the supervisor and the downline($poll)
                                                            if($sSPol < 1 || $tPol < 1){
                                                                $sPoly = 0;
                                                            }else{
                                                            $sPoly = ($sSPol/$tPol) * 100;
                                                            }
                                                            
                                                            
                                                            
                                                            $tProduction= $sSal;
                                                            $tPolicy = $sSPol; 
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th>
                                                            <span class="text-info">
                                                                <a href="agt.php?code=<?php echo $code ?>&bran=<?php echo $bran ?>&budg=<?php echo $bid ?>&year=<?php echo $ya ?>&r=<?php echo $rol; ?>" ><strong> <?php echo $naM; ?></strong></a>
                                                            </span> 
                                                        </th>
                                                        <th><span class="text-info"><?php echo $code ?></span></th>
                                                        <th>
                                                            <span class="text-inverse"> <strong> <?php echo number_format($sSal ); ?></strong></span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong> <?php echo number_format((float)$sBugg, 1, '.', '')."%"; ?></strong></span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong> <?php echo number_format($sSPol ); ?></strong> </span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong><?php echo number_format((float)$sPoly, 1, '.', '')."%"; ?></strong> </span>
                                                        </th>
                                                    </tr>
                                                    <?php
                                                        $ag=$con->query("SELECT name, agent_code FROM agent WHERE supervisor='$naM'");
                                                        $counter=0;
                                                        while($agr=$ag->fetch_array()){
                                                            $agent= $agr['name'];
                                                            $agCode=$agr['agent_code'];
                                                         //Getting the supervisor budget   
                                                        $q=$con->query("SELECT * FROM sup_budget WHERE supervisor='$naM' AND bID='$b_id' AND year='$ya' ");
                                                        $counter = 0;
                                                        while($row = $q->fetch_array()){
                                                            $id = $row['bbID'];
                                                            $bid = $row['bID'];
                                                            $ya = $row['year'];
                                                            $bran = $row['branch'];
                                                            $tBdt1 = $row['nb']+ $row['rn'];
                                                            $tPol1 = $row['polnb']+ $row['polrn'];
                                                            
                                                            $spp=$row['supervisor'];
                                                            
                                                            //Getting each agent's production
                                                            
                                                            $x= $con->query("SELECT  SUM(r_amount) AS amount, count(id) AS count FROM  myrecord  WHERE agent_code ='$agCode' AND year='$ya' ");
                                                            $xr=$x->fetch_array();
                                                            $salx=$xr['amount'];
                                                            $Pol1 = $xr['count'];
                                                             
                                                            $tProduction +=  $salx;
                                                            $tPolicy += $Pol1;
                                                            //Percentage Budget achieved in terms of sales and Nop
                                                            $Pach1=($salx/$tBdt)*100;
                                                            $PolAch1 = ($Pol1/$tPol) * 100; //$PolAch1 = ($Pol1/$tPol1) * 100; using $poll total supervisor and supervisor downline production
                                                        }                          
                                                    ?>
                                                    <tr>
                                                        <th><a href="agt.php?bran=<?php echo $bran ?>&code=<?php echo $agCode ?>&budg=<?php echo $bid ?>&year=<?php echo $ya ?>&r=<?php echo $rol; ?>" class="bg-aqua"> <strong> <?php echo $agent; ?></strong></a> </th>
                                                        <th><span class="text-info"><?php echo $agCode ?></span></th>
                                                        <th>
                                                            <span class="text-inverse"> <strong> <?php echo number_format($salx ); ?></strong></span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong> <?php echo number_format((float)$Pach1, 2, '.', '')."%"; ?></strong> </span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong> <?php echo number_format($Pol1 ); ?></strong> </span>
                                                        </th>
                                                        <th>
                                                            <span class="text-inverse"><strong><?php echo number_format((float)$PolAch1, 2, '.', '')."%"; ?></strong> </span>
                                                        </th>
                                                    </tr>
                                                        <?php } ?> 
                                                    <tr>
                                                        <th><span class="btn btn-primary btn-block">Grand Total</span></th>
                                                        <th></th>
                                                        <th><span class="btn btn-block btn-info"><?php echo number_format($totAchieved); ?></span></th>
                                                        <th></th>
                                                        <th><span class="btn btn-block btn-success"><?php echo number_format($totPolicy); ?></span></th>
                                                    </tr>
                                                     
                                                </tbody>
                            </table>
                            </div> 
                        </div>
                   </div>
                    
                    
                    
                    
                    
                    
          
                    
                </div>
                <!-- /.row -->
                
                <?php 
    $a=$tBdt/1000000;
    $x = $totAchieved/1000000;
    
   if($a <= 50){
                $a = 50; $d= 5;
                $VL = 10;
                $L = 20;
                $M = 30;
                $H = 40;
                $VH = 400;

            }
            if($a <= 200){
                $a = 200; $d= 10;
                $VL = 20;
                $L = 50;
                $M = 100;
                $H = 160;
                $VH = 160;

            }
            elseif($a >= 200 && $a < 500){
                $a = 500; $d = 50;
                $VL = 100;
                $L = 200;
                $M = 300;
                $H = 400;
                $VH = 400;
            }
            elseif($a >= 500 && $a < 1000){
                $a = 1000; $d = 100;
                $VL = 100;
                $L = 300;
                $M = 500;
                $H = 700;
                $VH = 700;
            }
    ?>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
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
    <!--Counter js -->
    <script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!--Morris JavaScript -->
    <script src="../plugins/bower_components/raphael/raphael-min.js"></script>
    <script src="../plugins/bower_components/morrisjs/morris.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../js/custom.min.js"></script>
    <script src="../js/dashboard1.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="../plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>
    <script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <!-- Zing chart  -->
    <script src="../plugins/bower_components/zing/zingchart.min.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.jquery.min.js"></script>
    
 
  
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
          "values": "0:<?php echo $a; ?>:<?php echo $d ?>",
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
          "values": [<?php  echo $x ?>],
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
      id: 'myChart',
      data: myConfig,
    });
  </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
