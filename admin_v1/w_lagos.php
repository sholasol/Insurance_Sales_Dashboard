<?php 
include 'mis_nav.php';
include 'mis_side.php';

$rol = $_GET['r'];
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

if($rol =='FA'){
    $ty = 'Financial Advisors'; 
    $brac1 = 'FA Abuja';
    $brac2 = "'%FA Lagos%' OR branch LIKE '%FA Ikeja%' OR  branch LIKE '%FA Broad Street%' OR branch LIKE '%FA Victoria Island%'";
    $branch ="'FA Lagos'"; // This is Branches for financial advisor (This should be edited in case FA are located branch wise )
    $brac3 = 'FA PH';
    $c_area='Financial Advisors';
    
}
if($rol =='Agency'){
    $ty = 'Agency';
    $brac1 = 'Ensure Abuja';
    $brac2 = "'%Ensure Lagos%' OR branch LIKE '%Ensure Ikeja%' OR  branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%'";
    $brac3 = 'Ensure PH';
    $branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'";
    $c_area='Agency';
}
if($rol =='TRAVEL'){$ty = 'TRAVEL';}
if($rol =='Partners'){$ty = 'Partners';}

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='Agency' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                receipt_date BETWEEN '$frm' AND '$t2' AND  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where 
                 receipt_date BETWEEN '$frm' AND '$t2' AND year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')");
$yr2 =$ach2->fetch_array();
$totAgt = $yr2['count'];

$polc=$yr['count'];
$rAmount = $yr['sum'];













 //Branch Wise Budget and Policy
    
    $Q=$con->query("SELECT nb, rn, polnb, polrn FROM branch_bud  ");
    $x=[];
    $y=[];
    while($t=$Q->fetch_array()){
        $Tbu= ($t['nb']+$t['rn'])/1000000;
        $x[]=$Tbu;
        
        $TPo= ($t['polnb']+$t['polrn'])/100;
        $y[]=$TPo;
    }
    
    //Getting Current Budget From budget
    $bud=$con->query("SELECT amount, bID, year FROM budget WHERE active =1");
    $rb=$bud->fetch_array();
    $bud_id = $rb['bID'];
    $yy = $rb['year'];
    $bbb = $rb['amount'];
    
    
    //Coverage Areas Budget
    
    //Abuja
    $covBudget = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%Abuja%' ");
    $cor=$covBudget->fetch_array();
    $sumBud =$cor['sum(nb)'] + $cor['sum(rn)'];
    $sumPol =$cor['sum(polnb)'] + $cor['sum(polrn)'];
    
    //Lagos
    $covBudget1 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM monthly WHERE month BETWEEN '$d1' AND '$d2' AND bID='$bud_id' AND branch = $branch ");
    $cor1=$covBudget1->fetch_array();
    $sumBud1 =$cor1['sum(nb)'] + $cor1['sum(rn)'];
    $sumPol1 =$cor1['sum(polnb)'] + $cor1['sum(polrn)'];
    
    
    //PH
    $covBudget2 = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch LIKE '%PH%' ");
    $cor2=$covBudget2->fetch_array();
    $sumBud2 =$cor2['sum(nb)'] + $cor2['sum(rn)'];
    $sumPol2 =$cor2['sum(polnb)'] + $cor2['sum(polrn)'];
    
    
    
    //Coverage Areas Productions
    
    //Abuja
    $covArea = $con->query("SELECT count(distinct policy_no) AS count, sum(r_amount) FROM myrecord WHERE receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' AND c_area LIKE '%Abuja%' ");
    $cv = $covArea->fetch_array();
    $suAmt = $cv['sum(r_amount)'];
    $suPol = $cv['count'];
    
    //Lagos
    $covArea1 = $con->query("SELECT count(distinct policy_no) AS count, sum(r_amount) FROM myrecord WHERE receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' AND agent_code IN (SELECT agent_code FROM agent WHERE branch=$branch ) ");
    $cv1 = $covArea1->fetch_array();
    $suAmt1 = number_format($cv1['sum(r_amount)']);
    $suPol1 = number_format($cv1['count'], 2, '.', '');
    
    
    
    
    //PH
    $covArea2 = $con->query("SELECT count(distinct policy_no) AS count, sum(r_amount) FROM myrecord WHERE receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' AND c_area LIKE '%Port Harcourt%' ");
    $cv2 = $covArea2->fetch_array();
    $suAmt2 = $cv2['sum(r_amount)'];
    $suPol2 = $cv2['count'];
    
 //Percentage Achieved Budget Amount
    if($suAmt < 1 || $sumBud < 1){$PA_Abuja = 0;}
    else{$PA_Abuja = ($suAmt/$sumBud)*100;}
    
    if($suAmt1 < 1 || $sumBud1 < 1){$PA_Lagos = 0;}
    else{
        $PA_Lagos = ($suAmt1/$sumBud1)*100;
         $PA_Lagos = number_format($PA_Lagos, 2, '.', '');
    }
    
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
                            <li class="active"><a href="week.php?frm=<?php echo $frm ?>&to=<?php echo $t2 ?>&r=<?php echo $rol; ?>"><i class="icon-home"></i> Back</a> </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                 <div class="row">
                    <!-- .col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">
                                New Business vs Renewal
                                <span class="pull-right">
                                    <i class="fa fa-circle m-r-5" style="color: #03A9F4;"></i>New Business
                                    <i class="fa fa-circle m-r-5" style="color: #00b5c2;"></i>Renewal
                                </span>
                            </h3>
                            <div>
                                <canvas id="chart2" height="225"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Budget vs Target (in Million)</h3>
                            <div id="myChart2" style="height: 450px;"></div>
                        </div>
                    </div>
                </div>
                <!--row -->
                
                
                
                
                <div class="row">
                     <?php 
                     if($rol =='FA'){ 
                         echo"
                        <div class='col-md-12 col-xs-12 col-sm-6'>
                               <div class='white-box'>
                                   <h3 class='box-title'>Bancassurance Lagos</h3>
                                   <div class='table-responsive'>
                                       <table class='table table-striped table-bordered' width='100%'>
                                                       <thead>
                                                           <tr>
                                                               <th width='25%'><strong>Coverage Area</strong></th>
                                                               <th width='10%'><strong>Budget</strong></th>
                                                               <th width='15%'><strong>Achieved (YTD)</strong></th>
                                                               <th width='10%'><strong>%Achieved (YTD)</strong></th>
                                                               <th width='5%'><strong>NoP</strong></th>
                                                               <th width='15%'><strong>Achieved (YTD)</strong></th>
                                                               <th width='10%'><strong>%Achieved (YTD)</strong></th>
                                                           </tr>
                                                       </thead>
                                                       <tbody>
                                                           <tr>
                                                               <th><strong>
                                                              <a href='w_bran_detail.php?b=FLAG&frm=$frm&to=$t2&r=$rol'> Lagos (FA Wise) </a> 
                                                               </strong></th>
                                                               <th><strong><span class=''> $sumBud1</span></strong></th>
                                                               <th><strong><span class=''>$suAmt1</span></strong></th>
                                                               <th><strong><span class=''> $PA_Lagos %</span> </strong></th>
                                                               <th><strong><span class=''>$sumPol1</span></strong></th>
                                                               <th><strong><span class=''>$suPol1</span></strong></th>
                                                               <th><strong><span class=''> $PA_Lagos1 %</span></strong></th>
                                                           </tr>

                                                       </tbody>
                                   </table>
                                   </div> 
                               </div>
                          </div>
                     ";}?>
                 <?php if($rol =='Agency'){include 'w_lag.php';} ?>
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-xs-12"> 
                        <div class="col-xs-5 ">
                            <div class="white-box">
                                <h3 class="box-title"> Budget (Lagos)</h3>
                                <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>NoP </td>
                                                  <td>RN</td>
                                                  <td>NoP <br><br><br><br><br><br><br><br></td>
                                              </tr>
                                              <?php 
                                                $newBizBud = 0;
                                                $renewalBud =0;
                                                $p2=$con->query(" SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                                while($rr2=$p2->fetch_array()){
                                                    $pr2=$rr2['product_class'];
                                                    
                                                    
                                                    $pp2=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM monthly WHERE month BETWEEN '$d1' AND '$d2' AND product_class='$pr2' AND bID='$bid' AND (branch=$branch)");
                                                    $ro2=$pp2->fetch_array();
                                                    $nb2=$ro2['nb'];
                                                    $newBizBud +=$nb2;
                                                    $rn2=$ro2['rn'];
                                                    $renewalBud += $rn2;
                                                    
                                                    $p3=$con->query("SELECT product_class, prID FROM proclass_bud WHERE product_class='$pr2'");
                                                    $prr=$p3->fetch_array();
                                                    $prd2=$prr['prID'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <strong><span class="text-info"><?php echo $pr2;?></span></strong>
                                                      <?php 
                                                      /*
                                                      if($nb2 < 1 or $rn2 < 1){
                                                          echo "<a href='index.php?bran_allocate&budg=$bid&year=$yy&p=$prd2&b=ABJ' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      */
                                                      ?>
                                                  </td>
                                                  <td width="9%"><strong><?php echo number_format($nb2); ?></strong></td>
                                                  <td width="9%"><strong><?php echo number_format($ro2['polnb']); ?></strong></td>
                                                  <td width="9%"><strong><?php echo number_format($rn2); ?></strong></td>
                                                  <td width="9%"><strong><?php echo number_format($ro2['polrn']); ?></strong></td>
                                              </tr>
                                                <?php } ?>
                                              <tr>
                                                <td><br><br></td>
                                                <td><br><br></td>
                                                <td><br><br></td>
                                            </tr>
                                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-7 " >
                            <div class="white-box">
                                <h3 class="box-title">Budget vs Actual (Lagos)</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
<!--                                          <th width="152" align="center" height="53" rowspan="2">Product</strong></th>
                                          <th width="97" align="center" rowspan="2">Budget</strong></th>-->
<!--                                          <th height="44" align="center" colspan="4" bgcolor="#99FF33">Lagos</strong></th>-->
                                          <th colspan="8" align="center" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">RENEWAL</h3></strong></th>
                                          <th colspan="8" align="center" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">NEW BUSINESS</h3></strong></th>
<!--                                          <th colspan="4" align="center" bgcolor="#66FF99">Ibadan</strong></th>-->
                                          <th width="104" align="center" rowspan="2">Total</strong></th>
                                        </tr>
                                        <tr>

                                          <th width="20" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></strong></th>
                                          <th width="15" bgcolor="#206ea1"> <h3 align="center" style="color: #fff; font-size: 17px;">% Ach</h3></strong></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></strong></th>
                                          <th width="15" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></strong></th>
                                          <th width="15" height="42" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></strong></th>
                                          <th width="20" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Ach</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"> <h3 align="center" style="color: #fff; font-size: 17px; ">% Ach</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">NoP</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">% NoP</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">ATS</h3></strong></strong></th>
                                          <th width="15" height="42" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Actv</h3></strong></strong></th>
                                          <th width="15" bgcolor="#566473"><h3 align="center" style="color: #fff; font-size: 17px;">Case Rate</h3></strong></strong></th>
                                        </tr>
                                        <tr>
                                             <?php 
                                             $t=0;
                                             $tsale1 =0; // Total new business sales
                                             $tsale =0;
                                        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($cr = $cp->fetch_array()){
                                            $prodt = $cr['product_class'];
                                            $a3[] = $prodt;
                                            
                                            //Getting New business budget for each of the product
                                            $pb = $con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn, product_class FROM `branch_bud` WHERE product_class='$prodt' AND year='$yy' AND (branch = $branch)");
                                            $or = $pb->fetch_array();
                                            $nbB = $or['nb'];
                                            $rnB = $or['rn'];
                                            $nbP = $or['polnb'];
                                            $rnP = $or['polrn'];
                                            $pBu = $nbB + $rnB; //new business budget for each of the product
                                            $polly=$nbP + $rnP;
                                            $aa = $pBu/1000000;
                                            $a1[] = $aa;
                                            //PH Product Performance NB
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' 
                                            AND agent_code IN (SELECT agent_code FROM agent WHERE branch= $branch) ");
                                            while($ro=$cpp->fetch_array()){
                                                $proAmt = $ro['amount'];
                                                $proPol = $ro['count']; 
                                                $agent = $ro['agent'];
                                                $premium = $ro['premium'];
                                                $tsale1 +=$proAmt;
                                                
                                                $aa2=$proAmt/1000000;
                                                $a2[] = $aa2;
                                                $t +=$proAmt;
                                                
                                                //Activization for PH
                                                $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' AND  r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch= $branch) ");
                                                $ar=$acvtAbj->fetch_array();
                                                $act= $ar['agent'];
                                            }
                                            
                                            
                                            //PH Product Performance RN
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy' 
                                            AND agent_code IN (SELECT agent_code FROM agent WHERE branch= $branch  ) ");
                                            while($ro1=$cpp1->fetch_array()){
                                                $proAmt1 = $ro1['amount'];
                                                $proPol1 = $ro1['count'];
                                                $agent1 = $ro1['agent'];
                                                $premium1 = $ro1['premium'];
                                                $tsale +=$proAmt1;
                                                
                                                $aa3=$proAmt1/1000000;
                                                
                                                $a4[] = $aa3;
                                                
                                                $t +=$proAmt1;
                                                
                                                //Activization for PH
                                                $acvtAbj1 = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND receipt_date BETWEEN '$frm' AND '$t2' AND year='$yy'  AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch= $branch) ");
                                                $ar1=$acvtAbj1->fetch_array();
                                                $act1= $ar1['agent'];
                                            }
                                            
                                            
                                            
                                            
                                            $totalProduct = $proAmt + $proAmt1;
                                          
                                            
                                        ?>
                                          <th><strong><?php echo number_format($proAmt1); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($rnB < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt1 = ($proAmt1/$rnB)*100;
                                                    echo number_format($pproAmt1, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol1); ?></strong></th>
                                          <th><strong>
                                              
                                              <?php
                                              if($rnP < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp1 = ($proPol1/$rnP)*100;
                                                    echo number_format($ppp1, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                                if($proPol1 < 1 || $premium1 < 1){ echo 0;}
                                              else{
                                                $ats1 = $premium1 /$proPol1;
                                                echo number_format($ats1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo $agent1 ?></strong></th>
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
                                          <th><strong><?php echo number_format($proAmt); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($nbB < 1){
                                                    echo 0;
                                                }else{
                                                    $pproAmt = ($proAmt/$nbB)*100;
                                                    echo number_format($pproAmt, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo number_format($proPol); ?></strong></th>
                                          <th><strong>
                                              <?php
                                              if($nbP < 1){
                                                    echo 0;
                                                }else{
                                                    $ppp = ($proPol/$nbP)*100;
                                                    echo number_format($ppp, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                                if($proPol1 < 1 || $premium1 < 1){ echo 0;}
                                              else{
                                                $ats1 = $premium1 /$proPol1;
                                                echo number_format($ats1);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo $agent1 ?></strong></th>
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
                                         
                                          <th><strong><?php echo number_format($totalProduct); ?></strong></th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <th><strong><strong>Total</span></strong></th>
                                            <th colspan="16"><span class="btn btn-primary btn-block"><?php echo number_format($t); ?></span></th>
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
   $p=$con->query("SELECT product_class FROM myrecord GROUP BY product_class ");
            while($pr=$p->fetch_array()){
               $produt=$pr['product_class'];  
               $prDD[] = $produt; 
               
               
               // $q1[] = 0;
            $ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             product_class= '$produt' AND type='NB' AND  year='$yy' AND receipt_date BETWEEN '$frm' AND '$t2' AND  agent_code IN (SELECT agent_code FROM agent WHERE branch=$branch  ) ");
            while($yr =$ach->fetch_array()){

            $xxx= $yr['sum']/1000000;
             $q1[] = $xxx;
            }
            
            
             $ach2= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
             product_class= '$produt' AND  year='$yy' AND receipt_date BETWEEN '$frm' AND '$t2' AND type='RN'  AND  agent_code IN (SELECT agent_code FROM agent WHERE branch=$branch  )");
            while($yr2 =$ach2->fetch_array()){

            $xxx2= $yr2['sum']/1000000;
             $q2[] = $xxx2;
            }
            
            }
   
   
   
   
              $a=$newBizBud /1000000;
              $x=$tsale1 /1000000;

            if($a < 5){$d = 1;}     
            elseif($a < 50){$d = 5;}
            elseif($a > 50 && $a < 100){$d = 10;}
            elseif($a >100 ){$d = 50;}


             $b=$sumBud1 /1000000;
            $y=$suAmt1 /1000000;
            
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
