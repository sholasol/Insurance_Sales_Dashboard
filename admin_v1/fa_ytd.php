<?php 
include 'mis_nav.php';
include 'fa_side.php';

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='FA' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where c_area LIKE '%Financial Advisors%' AND
                  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where c_area LIKE '%Financial Advisors%' AND
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
    /*
    $Q=$con->query("SELECT nb, rn, polnb, polrn FROM branch_bud GROUP BY product_class ");
    $x=[];
    $y=[];
    while($t=$Q->fetch_array()){
        $Tbu= ($t['nb']+$t['rn'])/1000000;
        $x[]=$Tbu;
        
        $TPo= ($t['polnb']+$t['polrn'])/100;
        $y[]=$TPo;
    }
    */
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
    $covBudget = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%Ensure Abuja%' ");
    $cor=$covBudget->fetch_array();
    $sumBud =$cor['sum(nb)'] + $cor['sum(rn)'];
    $sumPol =$cor['sum(polnb)'] + $cor['sum(polrn)'];
    
    //Lagos
    $covBudget1 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%Ensure Lagos%'  OR branch LIKE '%Ensure Ikeja%'  OR branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%' ");
    $cor1=$covBudget1->fetch_array();
    $sumBud1 =$cor1['sum(nb)'] + $cor1['sum(rn)'];
    $sumPol1 =$cor1['sum(polnb)'] + $cor1['sum(polrn)'];
    
    
    //PH
    $covBudget2 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%PH%'  ");
    $cor2=$covBudget2->fetch_array();
    $sumBud2 =$cor2['sum(nb)'] + $cor2['sum(rn)'];
    $sumPol2 =$cor2['sum(polnb)'] + $cor2['sum(polrn)'];
    
    
    
    //Coverage Areas Productions
    
    //Abuja
    $covArea = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE  branch='Ensure Abuja' ) ");
    $cv = $covArea->fetch_array();
    $suAmt = $cv['sum'];
    $suPol = $cv['count'];
    
    //Lagos
    $covArea1 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street') ");
    $cv1 = $covArea1->fetch_array();
    $suAmt1 = $cv1['sum'];
    $suPol1 = $cv1['count'];
    
    
    //PH
    $covArea2 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure PH') ");
    $cv2 = $covArea2->fetch_array();
    $suAmt2 = $cv2['sum'];
    $suPol2 = $cv2['count'];
    
    //Percentage Achieved Budget Amount
    $PA_Abuja = ($suAmt/$sumBud)*100;
    
    $PA_Lagos = ($suAmt1/$sumBud1)*100;
    
    $PA_PH = ($suAmt2/$sumBud2)*100;
    
    //Percentage Achieved Budget Policy
    $PA_Abuja1 = ($suPol/$sumPol)*100;
    
    $PA_Lagos1 = ($suPol1/$sumPol1)*100;
    
    $PA_PH1 = ($suPol2/$sumPol2)*100;
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Bancassurance</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active"><a href="agency.php"><i class="icon-home"></i> Back</a> </li>
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
                            <ul class="list-inline text-right">
<!--                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #f75b36;"></i>Budget</h5>
                                </li>-->
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #C6E746;"></i>New Business</h5>
                                </li>
                            </ul>
                            <div id="myChart" style="height: 356px;"></div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Renewal Budget vs Target (in Million)</h3>
                            <ul class="list-inline text-right">
                                
<!--                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #f75b36;"></i>Budget</h5>
                                </li>-->
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #2c5ca9;"></i>Renewal</h5>
                                </li>
                            </ul>
                            <div id="myChart2" style="height: 356px;"></div>
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
                                                        <th width="25%">Coverage Area</strong></th>
                                                        <th width="10%">Budget</strong></th>
                                                        <th width="15%">Achieved (YTD)</strong></th>
                                                        <th width="10%">%Achieved (YTD)</strong></th>
                                                        <th width="5%"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                                        <th width="15%">Achieved (YTD)</strong></th>
                                                        <th width="10%">%Achieved (YTD)</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th><strong><a href="abuja.php" class="btn-link" style="font-size: 17px;">Abuja</a> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja, 2, '.', '')."%"; ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    <tr>
                                                        <th><strong><a href="lagos.php" class="btn-link" style="font-size: 17px;">Lagos </a></strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Lagos, 2, '.', '')."%"; ?></span> </strong></strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol1); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Lagos1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    <tr>
                                                        <th><strong><a href="ph.php" class="btn-link" style="font-size: 17px;">PH </a></strong></th>
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
                                            
                                     $bugt_P = $con->query("SELECT nb, polnb FROM proclass_bud WHERE product_class='$prdt' AND year='$yy'");
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
                                            $pb = $con->query("SELECT nb, polnb FROM proclass_bud WHERE product_class='$prodt' AND year='$yy'");
                                            $or = $pb->fetch_array();
                                            $pBu = $or['nb']; //new business budget for each of the product
                                            $polly=$or['polnb'];
                                            
                                            //Abuja Product Performance NB
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Abuja' ) ");
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
                                            
                                            $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Abuja'  ) ");
                                            $ar=$acvtAbj->fetch_array();
                                            $act= $ar['agent'];
                                            
                                            
                                            
                                            //Lagos Product Performance NB
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                                                    branch='Ensure Broad Street' ) ");
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
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                                                    branch='Ensure Broad Street'  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            //PH Product Performance NB
                                            $cpp2 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure PH' ) ");
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
                                                
                                                $acvtPH = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure PH'  ) ");
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
                                            
                                     $bugt_P = $con->query("SELECT rn, polrn FROM proclass_bud WHERE product_class='$prdt' AND year='$yy'");
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
                                            $pb = $con->query("SELECT rn, polrn FROM proclass_bud WHERE product_class='$prodt' AND year='$yy'");
                                            $or = $pb->fetch_array();
                                            $pBu = $or['rn']; //new business budget for each of the product
                                            $polly=$or['polrn'];
                                            
                                            //Abuja Product Performance RN
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Abuja' ) ");
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
                                            
                                            $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Abuja'  ) ");
                                            $ar=$acvtAbj->fetch_array();
                                            $act= $ar['agent'];
                                            
                                            
                                            
                                            //Lagos Product Performance RN
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                                                    branch='Ensure Broad Street' ) ");
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
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                                                    branch='Ensure Broad Street'  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            //PH Product Performance RN
                                            $cpp2 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure PH' ) ");
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
                                                
                                                $acvtPH = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure PH'  ) ");
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
                                <li>
                                    <div class="checkbox checkbox-success">
                                        <input id="checkbox4" type="checkbox" class="open-close">
                                        <label for="checkbox4"> Toggle Sidebar </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="checkbox2" type="checkbox" class="fxsdr">
                                        <label for="checkbox2"> Fix Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme">4</a></li>
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
    $a=$newBizBud /1000000;
    $x=$tsale1 /1000000;
    
    
    $b=$renewalBud /1000000;
    $y=$tsale /1000000;
    ?>
     <script>
        var myConfig3 = {
          "type": "gauge",
          "scale-r": {
            "aperture": 200,
            "values": "0:<?php echo $a ?>:100"
          },
          "series": [ {
            "values": [<?php echo $x ?>], //New Business
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
  
   <script>
        var myConfig3 = {
          "type": "gauge",
          "scale-r": {
            "aperture": 200,
            "values": "0:<?php echo $b ?>:100"
          },
          "series": [  {
            "values": [<?php echo $y ?>], //Renewal
            "csize": "10%", //Needle Indicator Width
            "size": "70%", //Needle Indicator Length
            "background-color": "#2c5ca9 #FFCCFF"
          }]
        };

        zingchart.render({
          id: 'myChart2',
          data: myConfig3,
          height: "100%",
          width: "100%"
        });
  </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
