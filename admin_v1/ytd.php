<?php 
include 'mis_nav.php';
include 'mis_side.php';

$rol = $_GET['r'];
if($rol =='FA'){
    $ty = 'Financial Advisors'; 
    $brac1 = 'FA Abuja';
    $brac2 = "'%FA Lagos%' OR branch LIKE '%FA Ikeja%' OR  branch LIKE '%FA Broad Street%' OR branch LIKE '%FA Victoria Island%'";
    $branch ="'FA Lagos'"; // This is Branches for financial advisor (This should be edited in case FA are located branch wise )
    $brac3 = 'FA PH';
    
}
if($rol =='Agency'){
    $ty = 'Agency';
    $brac1 = 'Ensure Abuja';
    $brac2 = "'%Ensure Lagos%' OR branch LIKE '%Ensure Ikeja%' OR  branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%'";
    $brac3 = 'Ensure PH';
    $branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'";
}
if($rol =='TRAVEL'){$ty = 'TRAVEL';}
if($rol =='Partners'){$ty = 'Partners';}







$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='$ty' AND year='$y'");
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













 $proName=$con->query("SELECT distinct branch FROM branch_bud ");
    $a3=[];
    while($c=$proName->fetch_array()){
        $label= $c['branch'];
        $a3[] = $label;
    }
    
    //Branch Wise Budget and Policy
   
    //Getting Current Budget From budget
    $bud=$con->query("SELECT amount, bID, year FROM budget WHERE active =1");
    $rb=$bud->fetch_array();
    $bud_id = $rb['bID'];
    $yy = $rb['year'];
    $bbb = $rb['amount'];
    
    
     //Branch budget data for chart
    $q=$con->query("SELECT sum(nb), sum(rn), branch FROM `branch_bud` WHERE bID='$bud_id' group by branch ");
    $a1 =[];
    while($row = $q->fetch_array()){
        $snb= $row['sum(nb)'];
        $srn=$snb= $row['sum(rn)'];
        $xx= ($snb +$srn)/1000000;
        $a1[] = $xx;
    }
    
    
    //Coverage Areas Budget
    
    //Abuja
    $covBudget = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%$brac1%' ");
    $cor=$covBudget->fetch_array();
    $sumBud =$cor['sum(nb)'] + $cor['sum(rn)'];
    $sumPol =$cor['sum(polnb)'] + $cor['sum(polrn)'];
    
    //Lagos
    $covBudget1 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE $brac2 ");
    $cor1=$covBudget1->fetch_array();
    $sumBud1 =$cor1['sum(nb)'] + $cor1['sum(rn)'];
    $sumPol1 =$cor1['sum(polnb)'] + $cor1['sum(polrn)'];
    
    
    //PH
    $covBudget2 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%$brac3%'  ");
    $cor2=$covBudget2->fetch_array();
    $sumBud2 =$cor2['sum(nb)'] + $cor2['sum(rn)'];
    $sumPol2 =$cor2['sum(polnb)'] + $cor2['sum(polrn)'];
    
    
    
    //Coverage Areas Productions
    
    //Abuja
    $covArea = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE  branch='$brac1' ) ");
    $cv = $covArea->fetch_array();
    $suAmt = $cv['sum'];
    $suPol = $cv['count'];
    
    //Lagos
    $covArea1 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch)");
    $cv1 = $covArea1->fetch_array();
    $suAmt1 = $cv1['sum'];
    $suPol1 = $cv1['count'];
    
    
    //PH
    $covArea2 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
            year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3') ");
    $cv2 = $covArea2->fetch_array();
    $suAmt2 = $cv2['sum'];
    $suPol2 = $cv2['count'];
    
     //Percentage Achieved Budget Amount
    if($suAmt < 1 || $sumBud < 1){$PA_Abuja = 0;}
    else{$PA_Abuja = ($suAmt/$sumBud)*100;}
    
    if($suAmt1 < 1 || $sumBud1 < 1){$PA_Lagos = 0;}
    else{$PA_Lagos = ($suAmt1/$sumBud1)*100;}
    
    if($suAmt2 < 1 || $sumBud2 < 1){$PA_PH = 0;}
    else{$PA_PH = ($suAmt2/$sumBud2)*100;}
    
    //Percentage Achieved Budget Policy
    
    if($suPol < 1 || $sumPol < 1){$PA_Abuja1 = 0;}
    else{$PA_Abuja1 = ($suPol/$sumPol)*100;}
    
    if($suPol1 < 1 || $sumPol1 < 1){$PA_Lagos1 = 0;}
    else{$PA_Lagos1 = ($suPol1/$sumPol1)*100;}
    
    if($suPol2 < 1 || $sumPol2 < 1){$PA_PH1 = 0;}
    else{$PA_PH1 = ($suPol2/$sumPol2)*100;}
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">
                            <?php 
                            if($rol =='FA'){echo "Bancassurance";}else{ echo $rol;}
                            ?>
                        </h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <?php 
                            if($rol =='FA'){echo "<li class='active'><a href='bancca.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                            if($rol =='Agency'){echo "<li class='active'><a href='agency.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                            ?>
                            
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                
                 <div class="row">
                    <!-- .col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">New Business Budget vs Target (in Million)</h3>
                           
                            <div id="myChart" style="height: 450px;"></div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Renewal Budget vs Target (in Million)</h3>
                            <div id="myChart2" style="height: 450px;"></div>
                        </div>
                    </div>
                </div>
                <!--row -->
                
                
                <div class="row">
                   <div class="col-md-12 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (Branch)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="25%"><strong>Coverage Area</strong></th>
                                                        <th width="10%"><strong>Budget</strong></th>
                                                        <th width="15%"><strong>Achieved (YTD)</strong></th>
                                                        <th width="10%"><strong>%Achieved (YTD)</strong></th>
                                                        <th width="5%"><strong>NoP</strong></th>
                                                        <th width="15%"><strong>Achieved (YTD)</strong></th>
                                                        <th width="10%"><strong>%Achieved (YTD)</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th><strong><a href="abuja.php?r=<?php echo $rol; ?>" class="btn-link" style="font-size: 17px;">Abuja</a> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja, 2, '.', '')."%"; ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    <tr>
                                                        <th><strong><a href="lagos.php?r=<?php echo $rol; ?>" class="btn-link" style="font-size: 17px;">Lagos </a></strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Lagos, 2, '.', '')."%"; ?></span> </strong></strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Lagos1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    <tr>
                                                        <th><strong><a href="ph.php?r=<?php echo $rol; ?>" class="btn-link" style="font-size: 17px;">PH </a></strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud2); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt2); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_PH, 2, '.', '')."%"; ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol2); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol2); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_PH1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    
                                                </tbody>
                            </table>
                            </div> 
                        </div>
                   </div>
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-xs-12"> 
                        <div class="col-xs-4 ">
                            <div class="white-box">
                                <h3 class="box-title">Product & Budget</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50%"><strong>Product<br><br><br><br><br><br><br><br> </strong></th>
                                        <th width="25%"><strong>Budget<br><br><br><br><br><br><br><br> </strong></th>
                                        <th width="25%"><strong>NoP<br><br><br><br><br><br><br><br></strong></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php 
                                    $newBizBud=0;
                                    $sumBu=0;
                                    $allPol = 0;
                                    $p_bgt=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($tr = $p_bgt->fetch_array()){
                                            $prdt = $tr['product_class'];
                                            
                                     $bugt_P = $con->query("SELECT nb, polnb FROM proclass_bud WHERE product_class='$prdt' AND year='$yy' AND type='$ty'");
                                            $rr = $bugt_P->fetch_array();
                                            $nb_b = $rr['nb']; //new business budget for each of the product
                                            $nb_pol=$rr['polnb'];
                                            
                                            $sumBu +=$nb_b;
                                            $allPol +=$nb_pol;
                                    ?>
                                    <tr>
                                        <th><strong><?php echo $prdt; ?> </strong></th>
                                        <th><strong><span class="tag tag-warning"><?php echo number_format($nb_b); ?></span> </strong></th>
                                        <th><strong><span class="tag tag-success"><?php echo number_format($nb_pol); ?></span> </strong></th>
                                    </tr> 
                                        <?php } 
                                        $newBizBud= $sumBu;
                                        ?>
                                    <tr>
                                        <th><strong>Total Budget</strong></th>
                                        <th><strong><span class="label label-info">=N= <?php echo number_format($sumBu); ?></span></strong></th>
                                        <th><strong><span class="label label-info"><?php echo number_format($allPol); ?></span></strong></th>
                                    </tr>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-8 " >
                            <div class="white-box">
                                <h3 class="box-title">Budget vs Actual (New Business)</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                            <th height="44" align="center" colspan="8" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Lagos</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Abuja</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">PH</h3></strong></th>
                                          <th width="104" align="center" rowspan="2">Total</strong></th>
                                        </tr>
                                        <tr>
                                          <th width="20" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
                                          <th width="20" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
                                          <th width="20" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"> <h3 align="center" style="color: #fff; font-size: 17px; ">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
<!--                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <td bgcolor="#66FF99">% <h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>-->
                                        </tr>
                                        <tr>
                                             <?php 
                                             $saleAbj = 0; //total sales Abj
                                             $saleLag = 0; //total sales Lag
                                             $salePH = 0; //total sales PH
                                             $nopAbj = 0; //total Nop Abj
                                             $nopLag = 0; //total NoP Lag
                                             $nopPH = 0; //total Nop PH
                                             $tsale1 = 0; // Total Sale Lag, ph And Abj
                                             $perNoPAbj = 0; // %Nop Abj
                                             $perAchAbj = 0; // % Achieved Abj
                                             $perNoPLag = 0; // %Nop Lag
                                             $perAchLag = 0; // % Achieved Lag
                                             $perNoPPH = 0; // %Nop PH
                                             $perAchPH = 0; // % Achieved PH
                                             
                                        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($cr = $cp->fetch_array()){
                                            $prodt = $cr['product_class'];
                                            
                                            
                                            $pro[] = $prodt;
                                            
                                            //Getting New business budget for each of the product
                                            $pb = $con->query("SELECT nb, polnb FROM proclass_bud WHERE product_class='$prodt' AND year='$yy' AND type='$ty' ");
                                            $or = $pb->fetch_array();
                                            $pBu = $or['nb']; //new business budget for each of the product
                                            $polly=$or['polnb'];
                                            
                                            //Abuja Product Performance NB
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac1' ) ");
                                            $ro=$cpp->fetch_array();
                                            $proAmt = $ro['amount'];
                                            $proPol = $ro['count'];
                                            $agent = $ro['agent'];
                                            $premium = $ro['premium'];
                                            
                                            //Actual amount for chart
                                            if($proAmt < 1){
                                                $abj[] = 0;
                                            }else{ 
                                                $p= $proAmt/1000000;
                                                $abj[] = $p;
                                                
                                            }
                                            
                                               
                                            $saleAbj += $proAmt;    
                                            $nopAbj += $proPol;
                                            
                                            $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac1'  ) ");
                                            $ar=$acvtAbj->fetch_array();
                                            $act= $ar['agent'];
                                            
                                            
                                            
                                            //Lagos Product Performance NB
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch ) ");
                                            $ro1=$cpp1->fetch_array();
                                            $proAmt1 = $ro1['amount'];
                                            $proPol1 = $ro1['count'];
                                            $agent1 = $ro1['agent'];
                                            $premium1 = $ro1['premium'];
                                            
                                            //Actual amount for chart
                                            if($proAmt1 < 1){
                                                $lag[] = 0;
                                            }else{ 
                                                $p1= $proAmt1/1000000;
                                                $lag[] = $p1;
                                                
                                            }
                                            
                                            $saleLag += $proAmt1;
                                            $nopLag += $proPol1;
                                            // CAlculating Activization
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 
                                                AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            //PH Product Performance NB
                                            $cpp2 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3' ) ");
                                            $ro2=$cpp2->fetch_array();
                                                $proAmt2 = $ro2['amount'];
                                                $proPol2 = $ro2['count'];
                                                $agent2 = $ro2['agent'];
                                                $premium2 = $ro2['premium'];
                                                
                                                //Actual Amount for chart
                                                if($proAmt2 < 1){
                                                $ph[] = 0;
                                                    }else{ 
                                                        $p2= $proAmt2/1000000;
                                                        $ph[] = $p2;

                                                    }
                                                
                                                $salePH += $proAmt2;
                                                $nopPH += $proPol2;
                                                
                                                $acvtPH = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3'  ) ");
                                            $ar2=$acvtPH->fetch_array();
                                            $act2= $ar2['agent'];
                                            
                                            $totalProduction = $proAmt + $proAmt1 + $proAmt2;
                                            $tsale1 +=$totalProduction;
                                        ?>
<!--                                          <td height="37"><?php echo $prodt; ?></strong></th>
                                          <th><strong><span class="tag tag-warning"><?php echo number_format($pBu); ?></span></strong></th>-->
                                          <th><strong><?php echo number_format($proAmt1); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    //$pproAmt1 = ($proAmt1/$pBu)*100;
                                                    $pproAmt1 = ($proAmt1/$sumBu)*100;
                                                    echo number_format($pproAmt1, 2, '.', '')."%";
                                                    $perAchLag += $pproAmt1;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol1); ?></strong></th>
                                          <th><strong>
                                             
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp1 = ($proPol1/$allPol)*100;
                                                    echo number_format($ppp1, 2, '.', '')."%";
                                                    $perNoPLag += $ppp1;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol1 < 1){ echo 0;}
                                              else{
                                                $ats1 = $premium1/$proPol1;
                                                echo number_format($ats1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent1); ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act1 < 1 or $agent1 < 1){ echo 0;}else{
                                              $actv1 = $act1/$agent1;
                                              echo number_format($actv1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol1 < 1 or $agent1 < 1){echo 0;}else{
                                                  $caseRate1= $proPol1/$agent1;
                                                  echo number_format($caseRate1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><strong><?php echo number_format($proAmt); ?></strong></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt = ($proAmt/$sumBu)*100;
                                                    echo number_format($pproAmt, 2, '.', '')."%";
                                                    $perAchAbj += $pproAmt;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp = ($proPol/$allPol)*100;
                                                    echo number_format($ppp, 2, '.', '')."%";
                                                    $perNoPAbj += $ppp;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol < 1){ echo 0;}
                                              else{
                                                $ats = $premium/$proPol;
                                                echo number_format($ats);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent) ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act < 1 or $agent < 1){ echo 0;}else{
                                              $actv = $act/$agent;
                                              echo number_format($actv);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol < 1 or $agent < 1){echo 0;}else{
                                                  $caseRate= $proPol/$agent;
                                                  echo number_format($caseRate);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><strong><?php echo number_format($proAmt2); ?></strong></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt2 = ($proAmt2/$sumBu)*100;
                                                    echo number_format($pproAmt2, 2, '.', '')."%";
                                                    $perAchPH += $pproAmt2;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol2); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp2 = ($proPol2/$allPol)*100;
                                                    echo number_format($ppp2, 2, '.', '')."%";
                                                    $perNoPPH += $ppp2;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol2 < 1){ echo 0;}
                                              else{
                                                $ats2 = $premium2/$proPol2;
                                                echo number_format($ats2);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent2) ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act2 < 1 or $agent2 < 1){ echo 0;}else{
                                              $actv2 = $act2/$agent2;
                                              echo number_format($actv2);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol2 < 1 or $agent2 < 1){echo 0;}else{
                                                  $caseRate2= $proPol2/$agent2;
                                                  echo number_format($caseRate2);
                                              }
                                              ?>
                                          </strong></th>
<!--                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>-->
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($totalProduction); ?></span></strong></th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleLag); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopLag); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleAbj); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchAbj, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopAbj); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPAbj, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($salePH); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchPH, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopPH); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPPH, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-primary">=N= <?php echo number_format($tsale1); ?></span></strong></th>
                                        </tr>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                   
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-xs-12"> 
                        <div class="col-xs-4 ">
                            <div class="white-box">
                                <h3 class="box-title">Product & Budget</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50%"><strong>Product<br><br><br><br><br><br><br><br> </strong></th>
                                        <th width="25%"><strong>Budget<br><br><br><br><br><br><br><br> </strong></th>
                                        <th width="25%"><strong>NoP<br><br><br><br><br><br> <br><br></strong></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                     <?php 
                                    $sumBu=0;
                                    $allPol = 0;
                                    $p_bgt=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($tr = $p_bgt->fetch_array()){
                                            $prdt = $tr['product_class'];
                                            
                                     $bugt_P = $con->query("SELECT rn, polrn FROM proclass_bud WHERE product_class='$prdt' AND year='$yy' AND type='$ty'");
                                            $rr = $bugt_P->fetch_array();
                                            $nb_b = $rr['rn']; //new business budget for each of the product
                                            $nb_pol=$rr['polrn'];
                                            
                                            $sumBu +=$nb_b;
                                            $allPol +=$nb_pol;
                                    ?>
                                    <tr>
                                        <th><strong><?php echo $prdt; ?> </strong></th>
                                        <th><strong><span class="tag tag-warning"><?php echo number_format($nb_b); ?></span> </strong></th>
                                        <th><strong><span class="tag tag-success"><?php echo number_format($nb_pol); ?></span> </strong></th>
                                    </tr> 
                                        <?php }
                                        $renewalBud= $sumBu;
                                        ?>
<!--                                    <tr>
                                        <td><br><br></td>
                                        <td><br><br></td>
                                        <td><br><br></td>
                                    </tr>-->
                                    <tr>
                                        <th><strong>Total Budget</strong></th>
                                        <th><strong><span class="label label-info">=N= <?php echo number_format($sumBu); ?></span></strong></th>
                                        <th><strong><span class="label label-info"><?php echo number_format($allPol); ?></span></strong></th>
                                    </tr>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-8 " >
                            <div class="white-box">
                                <h3 class="box-title">Budget vs Actual (Renewal)</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                            <th height="44" align="center" colspan="8" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Lagos</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Abuja</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">PH</h3></strong></th>
                                          <th width="104" align="center" rowspan="2">Total</strong></th>
                                        </tr>
                                        <tr>
                                          <th width="20" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
                                          <th width="20" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
                                          <th width="20" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"> <h3 align="center" style="color: #fff; font-size: 17px; ">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>
<!--                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <td bgcolor="#66FF99">% <h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <td bgcolor="#66FF99"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>-->
                                        </tr>
                                        <tr>
                                             <?php 
                                             $saleAbj = 0; //total sales Abj
                                             $saleLag = 0; //total sales Lag
                                             $salePH = 0; //total sales PH
                                             $nopAbj = 0; //total Nop Abj
                                             $nopLag = 0; //total NoP Lag
                                             $nopPH = 0; //total Nop PH
                                             $tsale = 0; // Total Sale Lag, ph And Abj
                                             $perNoPAbj = 0; // %Nop Abj
                                             $perAchAbj = 0; // % Achieved Abj
                                             $perNoPLag = 0; // %Nop Lag
                                             $perAchLag = 0; // % Achieved Lag
                                             $perNoPPH = 0; // %Nop PH
                                             $perAchPH = 0; // % Achieved PH
                                             
                                        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($cr = $cp->fetch_array()){
                                            $prodt = $cr['product_class'];
                                            
                                            
                                            //Getting RN budget for each of the product
                                            $pb = $con->query("SELECT rn, polrn FROM proclass_bud WHERE product_class='$prodt' AND year='$yy' AND type='$ty'");
                                            $or = $pb->fetch_array();
                                            $pBu = $or['rn']; //new business budget for each of the product
                                            $polly=$or['polrn'];
                                            
                                            //Abuja Product Performance RN
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac1' ) ");
                                            $ro=$cpp->fetch_array();
                                            $proAmt = $ro['amount'];
                                            $proPol = $ro['count'];
                                            $agent = $ro['agent'];
                                            $premium = $ro['premium'];
                                            
                                             //Actual amount for chart
                                            if($proAmt < 1){
                                                $abjj[] = 0;
                                            }else{ 
                                                $px= $proAmt/1000000;
                                                $abjj[] = $px;
                                                
                                            }  
                                            $saleAbj += $proAmt;    
                                            $nopAbj += $proPol;
                                            
                                            $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='brac1'  ) ");
                                            $ar=$acvtAbj->fetch_array();
                                            $act= $ar['agent'];
                                            
                                            
                                            
                                            //Lagos Product Performance RN
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch ) ");
                                            $ro1=$cpp1->fetch_array();
                                            $proAmt1 = $ro1['amount'];
                                            $proPol1 = $ro1['count'];
                                            $agent1 = $ro1['agent'];
                                            $premium1 = $ro1['premium'];
                                            
                                            //Actual amount for chart
                                            if($proAmt1 < 1){
                                                $lagg[] = 0;
                                            }else{ 
                                                $px1= $proAmt1/1000000;
                                                $lagg[] = $px1;
                                                
                                            }
                                            
                                            $saleLag += $proAmt1;
                                            $nopLag += $proPol1;
                                            // CAlculating Activization
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND
                                                `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            //PH Product Performance RN
                                            $cpp2 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3' ) ");
                                            $ro2=$cpp2->fetch_array();
                                                $proAmt2 = $ro2['amount'];
                                                $proPol2 = $ro2['count'];
                                                $agent2 = $ro2['agent'];
                                                $premium2 = $ro2['premium'];
                                                
                                                //Actual amount for chart
                                            if($proAmt2 < 1){
                                                $phh[] = 0;
                                            }else{ 
                                                $px2= $proAmt2/1000000;
                                                $phh[] = $px2;
                                                
                                            }
                                                $salePH += $proAmt2;
                                                $nopPH += $proPol2;
                                                
                                                $acvtPH = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3'  ) ");
                                            $ar2=$acvtPH->fetch_array();
                                            $act2= $ar2['agent'];
                                            
                                            $totalProduction = $proAmt + $proAmt1 + $proAmt2;
                                            $tsale +=$totalProduction;
                                        ?>
<!--                                          <td height="37"><?php echo $prodt; ?></strong></th>
                                          <th><strong><span class="tag tag-warning"><?php echo number_format($pBu); ?></span></strong></th>-->
                                          <th><strong><?php echo number_format($proAmt1); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    //$pproAmt1 = ($proAmt1/$pBu)*100;
                                                    $pproAmt1 = ($proAmt1/$sumBu)*100;
                                                    echo number_format($pproAmt1, 2, '.', '')."%";
                                                    $perAchLag += $pproAmt1;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol1); ?></strong></th>
                                          <th><strong>
                                             
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp1 = ($proPol1/$allPol)*100;
                                                    echo number_format($ppp1, 2, '.', '')."%";
                                                    $perNoPLag += $ppp1;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol1 < 1){ echo 0;}
                                              else{
                                                $ats1 = $premium1/$proPol1;
                                                echo number_format($ats1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent1); ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act1 < 1 or $agent1 < 1){ echo 0;}else{
                                              $actv1 = $act1/$agent1;
                                              echo number_format($actv1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol1 < 1 or $agent1 < 1){echo 0;}else{
                                                  $caseRate1= $proPol1/$agent1;
                                                  echo number_format($caseRate1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><strong><?php echo number_format($proAmt); ?></strong></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt = ($proAmt/$sumBu)*100;
                                                    echo number_format($pproAmt, 2, '.', '')."%";
                                                    $perAchAbj += $pproAmt;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp = ($proPol/$allPol)*100;
                                                    echo number_format($ppp, 2, '.', '')."%";
                                                    $perNoPAbj += $ppp;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol < 1){ echo 0;}
                                              else{
                                                $ats = $premium/$proPol;
                                                echo number_format($ats);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent) ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act < 1 or $agent < 1){ echo 0;}else{
                                              $actv = $act/$agent;
                                              echo number_format($actv);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol < 1 or $agent < 1){echo 0;}else{
                                                  $caseRate= $proPol/$agent;
                                                  echo number_format($caseRate);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><strong><?php echo number_format($proAmt2); ?></strong></strong></th>
                                          <th><strong>
                                              <?php
                                              if($pBu < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt2 = ($proAmt2/$sumBu)*100;
                                                    echo number_format($pproAmt2, 2, '.', '')."%";
                                                    $perAchPH += $pproAmt2;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol2); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($polly < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp2 = ($proPol2/$allPol)*100;
                                                    echo number_format($ppp2, 2, '.', '')."%";
                                                    $perNoPPH += $ppp2;
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                              if($proPol2 < 1){ echo 0;}
                                              else{
                                                $ats2 = $premium2/$proPol2;
                                                echo number_format($ats2);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($agent2) ?></strong></th>
                                          <th><strong>
                                              <?php 
                                              if($act2 < 1 or $agent2 < 1){ echo 0;}else{
                                              $actv2 = $act2/$agent2;
                                              echo number_format($actv2);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php //Case rate
                                              if($proPol2 < 1 or $agent2 < 1){echo 0;}else{
                                                  $caseRate2= $proPol2/$agent2;
                                                  echo number_format($caseRate2);
                                              }
                                              ?>
                                          </strong></th>
<!--                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>-->
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($totalProduction); ?></span></strong></th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleLag); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopLag); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleAbj); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchAbj, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopAbj); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPAbj, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($salePH); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perAchPH, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopPH); ?></span></strong></th>
                                          <th><strong><span class="tag tag-success"><?php echo number_format($perNoPPH, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong></strong></th>
                                          <th><strong><span class="label label-primary">=N= <?php echo number_format($tsale); ?></span></strong></th>
                                        </tr>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                </div>
                <!-- /.row -->
                
                
                
                
                
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
    
   
  
   <?php 
 
              $x=$tsale1 /1000000;
            $a=$newBizBud /1000000; //Agency budget
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
            
            
            
            
            
            $b=$renewalBud /1000000;
            $y=$tsale /1000000;
            
           if($b <= 50){
            $b = 50; $c= 5;
            $VLx = 10;
            $Lx = 20;
            $Mx = 30;
            $Hx = 40;
            $VHx = 400;

        }
    if($b >= 50 && $b <= 200){
        $b = 200; $c= 10;
        $VLx = 20;
        $Lx = 50;
        $Mx = 100;
        $Hx = 160;
        $VHx = 160;
        
    }
    elseif($b >= 200 && $b < 500){
        $b = 500; $c = 50;
        $VLx = 100;
        $Lx = 200;
        $Mx = 300;
        $Hx = 400;
        $VHx = 400;
    }
    elseif($b >= 500 && $b < 1000){
        $b = 1000; $c = 100;
        $VLx = 100;
        $Lx = 300;
        $Mx = 500;
        $Hx = 700;
        $VHx = 700;
    }
         
            
            
            
            
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
          "values": "0:<?php echo $b; ?>:<?php echo $c ?>",
          "border-color": "#b3b3b3",
          "border-width": "2",
          "background-color": "#eeeeee,#b3b3b3",
          "ring": {
            "size": 10,
            "offset-r": "130px",
            "rules": [{
              "rule": "%v >=0 && %v < <?php echo $VLx; ?>",
              "background-color": "#FB0A02"
            }, {
              "rule": "%v >= <?php echo $VLx; ?> && %v < <?php echo $Lx; ?>",
              "background-color": "#EC7928"
            }, {
              "rule": "%v >= <?php echo $Lx; ?> && %v < <?php echo $Mx; ?>",
              "background-color": "#FAC100"
            }, {
              "rule": "%v >= <?php echo $Mx; ?> && %v < <?php echo $Hx; ?>",
              "background-color": "#B1AD00"
            }, {
              "rule": "%v >= <?php echo $Hx; ?> && %v >= <?php echo $VHx; ?>",
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
            "text": "<= <?php echo $VHx; ?> <br>Units",
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
            "text": ">= <?php echo $Mx; ?> <= <?php echo $Hx; ?><br>Units",
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
            "text": ">= <?php echo $Lx; ?> <= <?php echo $Mx; ?><br>Units",
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
            "text": ">= <?php echo $VLx; ?> < <?php echo $Lx; ?><br>Units",
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
            "text": " <= <?php echo $VLx; ?><br>Units",
            "shadow": 0
          }
        }],
        "series": [{
          "values": [<?php  echo $y ?>],
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
