<?php 
$rol = $_GET['r'];
$area='';
include 'mis_nav.php';
if($rol =="Partners"){
    include 'partner_side.php';
    $area = 'Partners - Autodealership';
}
elseif($rol =='HNI'){
    include 'hni_side.php';
    $area = '%HNI';
}
elseif($rol =='FI'){
    include 'fi_side.php';
    $area = 'Partners - Microfinance';
}



$ty = $rol;
$brac1 = 'Ensure Abuja'; //Abuja branch
//All ensure branch
$brac2 = "'%Ensure Lagos%' OR branch LIKE '%Ensure Ikeja%' OR  branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%'";
$brac3 = 'Ensure PH'; //PH branch
$branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'"; //Lagos branches







$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='$ty' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             c_area LIKE '$area' AND year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' ) ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where 
                 c_area LIKE '$area' AND  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')");
$yr2 =$ach2->fetch_array();
$totAgt = $yr2['count'];

$polc=$yr['count'];
$rAmount = $yr['sum'];


    
    //Branch Wise Budget and Policy
   
    //Getting Current Budget From budget
    $bud=$con->query("SELECT amount, bID, year FROM budget WHERE active =1");
    $rb=$bud->fetch_array();
    $bud_id = $rb['bID'];
    $yy = $rb['year'];
    $bbb = $rb['amount'];
    
    
    // Partners Budget
    $patB= $con->query("SELECT sum(nb) AS nb, sum(polnb) AS polnb, sum(rn) AS rn, sum(polrn) AS polrn FROM proclass_bud WHERE year='$yy' AND type='$ty'");
    $pr=$patB->fetch_array();
    $sumBud =$pr['nb'] + $pr['rn'];
    $sumPol =$pr['polnb'] + $pr['polrn'];
    
    
    //Coverage Areas Productions
    
    //Abuja
    $covArea = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
              c_area LIKE '$area' AND   year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE  branch=$branch ) ");
    $cv = $covArea->fetch_array();
    $suAmt = $cv['sum'];
    $suPol = $cv['count'];
    
    //Lagos
    $covArea1 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
         c_area LIKE '$area' AND year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch)");
    $cv1 = $covArea1->fetch_array();
    $suAmt1 = $cv1['sum'];
    $suPol1 = $cv1['count'];
    
    
    //PH
    $covArea2 = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
        c_area ='$area' AND year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3') ");
    $cv2 = $covArea2->fetch_array();
    $suAmt2 = $cv2['sum'];
    $suPol2 = $cv2['count'];
    
     //Percentage Achieved Budget Amount
    if($suAmt < 1 || $sumBud < 1){$PA_Abuja = 0;}
    else{$PA_Abuja = ($suAmt/$sumBud)*100;}
    /*
    if($suAmt1 < 1 || $sumBud1 < 1){$PA_Lagos = 0;}
    else{$PA_Lagos = ($suAmt1/$sumBud1)*100;}
    
    if($suAmt2 < 1 || $sumBud2 < 1){$PA_PH = 0;}
    else{$PA_PH = ($suAmt2/$sumBud2)*100;}
    */
    //Percentage Achieved Budget Policy
    
    if($suPol < 1 || $sumPol < 1){$PA_Abuja1 = 0;}
    else{$PA_Abuja1 = ($suPol/$sumPol)*100;}
    /*
    if($suPol1 < 1 || $sumPol1 < 1){$PA_Lagos1 = 0;}
    else{$PA_Lagos1 = ($suPol1/$sumPol1)*100;}
    
    if($suPol2 < 1 || $sumPol2 < 1){$PA_PH1 = 0;}
    else{$PA_PH1 = ($suPol2/$sumPol2)*100;}
    */
?>
        
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
                                    if($rol =='FI'){echo "<a href='fi.php'><i class='icon-home'></i> Back</a> ";}
                                    if($rol =='HNI'){echo "<a href='hni.php'><i class='icon-home'></i> Back</a> ";}
                                    if($rol =='Partners'){echo "<a href='partner.php'><i class='icon-home'></i> Back</a> ";}
                                ?>
                            </li>
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
                            <h3 class="box-title"><strong>Budget vs Actual</strong></h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="25%"></th>
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
                                                        <th><strong><a href="detail.php?r=<?php echo $rol; ?>" class="btn-link" style="font-size: 17px;">Sales Performance</a> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumBud); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suAmt); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja, 2, '.', '')."%"; ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($sumPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""><?php echo number_format($suPol); ?></span></strong> </strong></th>
                                                        <th><strong><strong><span class=""> <?php echo number_format($PA_Abuja1, 2, '.', '')."%"; ?></span></strong></strong></th>
                                                    </tr>
                                                    
                                                </tbody>
                            </table>
                            </div> 
                        </div>
                   </div>
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-xs-12"> 
                        <div class="col-md-12" style="margin-bottom: 15px;"> 
                            <form action="pfunction.php" method="post"> 
                                <input type="hidden" name="role" value="<?php echo $rol ?>" />
                                <button class="btn btn-block btn-info" type="submit" name="ytd"><i class="icon-doc"></i> Export to Excel</button>
                            </form> 
                        </div>
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
                                        <th><strong><span class="text-primary"><?php echo number_format($nb_b); ?></span> </strong></th>
                                        <th><strong><span class="text-success"><?php echo number_format($nb_pol); ?></span> </strong></th>
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
                                <h3 class="box-title">New Business</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                            <th height="44" align="center" colspan="8" bgcolor="#206ea1">
                                                <h3 align="center" style="color: #fff; font-size: 17px;">
                                                     <?php 
                                                        if($rol =='FI'){echo "Partners - Financial Instituition";}
                                                        elseif($rol =='HNI'){echo "Partners - HNI";}
                                                        elseif($rol =='Partners'){echo "Partners - Autodealership";}

                                                      ?>
                                                </h3>
                                            </th>
                                         <!-- <th colspan="8" align="center" bgcolor="#66CCCC"><h3 align="center" style="color: #fff; font-size: 17px;">Abuja</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">PH</h3></strong></th>-->
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
                                            
                                            
                                            
                                            
                                            
                                            //Lagos Product Performance NB
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND c_area LIKE '$area' AND  year='$yy' 
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
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='NB' AND c_area LIKE '$area' AND year='$yy' AND r_amount >= 10000 
                                                AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            
                                            $totalProduction = $proAmt1 ;
                                            $tsale1 +=$totalProduction;
                                        ?>
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
                                          
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($totalProduction); ?></span></strong></th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleLag); ?></span></strong></th>
                                          <th><strong><span class="text-success"><?php echo number_format($perAchLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopLag); ?></span></strong></th>
                                          <th><strong><span class="text-success"><?php echo number_format($perNoPLag, 1,'.','')."%" ?></span></strong></th>
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
                                        <th><strong><span class="text-primary"><?php echo number_format($nb_b); ?></span> </strong></th>
                                        <th><strong><span class="text-success"><?php echo number_format($nb_pol); ?></span> </strong></th>
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
                                <h3 class="box-title">Renewal</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                            <th height="44" align="center" colspan="8" bgcolor="#566473">
                                                <h3 align="center" style="color: #fff; font-size: 17px;">
                                                    <?php 
                                                        if($rol =='FI'){echo "Partners - Financial Instituition";}
                                                        elseif($rol =='HNI'){echo "Partners - HNI";}
                                                        elseif($rol =='Partners'){echo "Partners - Autodealership";}

                                                      ?>
                                                </h3>
                                            </th>
                                          <th width="104" align="center" rowspan="2">Total</strong></th>
                                        </tr>
                                        <tr>
                                          <th width="20" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></th>

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
                                            AND c_area LIKE '$area' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac1' ) ");
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
                                            
                                            $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE  product_class='$prodt' AND type='RN' AND year='$yy' AND r_amount >= 10000 AND c_area LIKE '$area' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac1'  ) ");
                                            $ar=$acvtAbj->fetch_array();
                                            $act= $ar['agent'];
                                            
                                            
                                            
                                            //Lagos Product Performance RN
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND c_area LIKE '$area' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch ) ");
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
                                            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND c_area ='$area' AND year='$yy' AND r_amount >= 10000 AND
                                                `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch=$branch  ) ");
                                            $ar1=$acvtLag->fetch_array();
                                            $act1= $ar1['agent'];
                                            
                                            
                                            //PH Product Performance RN
                                            $cpp2 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND c_area ='$area' AND  year='$yy' 
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
                                                
                                                $acvtPH = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND c_area ='$area' AND year='$yy' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$brac3'  ) ");
                                            $ar2=$acvtPH->fetch_array();
                                            $act2= $ar2['agent'];
                                            
                                            $totalProduction = $proAmt1;
                                            $tsale +=$totalProduction;
                                        ?>
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
                                          
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($totalProduction); ?></span></strong></th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                          <th><strong><span class="label label-info">=N= <?php echo number_format($saleLag); ?></span></strong></th>
                                          <th><strong><span class="text-success"><?php echo number_format($perAchLag, 1,'.','')."%" ?></span></strong></th>
                                          <th><strong><span class="tag tag-primary"><?php echo number_format($nopLag); ?></span></strong></th>
                                          <th><strong><span class="text-success"><?php echo number_format($perNoPLag, 1,'.','')."%" ?></span></strong></th>
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
