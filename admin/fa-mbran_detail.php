<?php 
include 'mis_nav.php';
include 'fa_side.php';

   
  $bra=$_GET['b'];
   if($bra=='VI'){
       $bra="Ensure Victoria Island";
   }
   if($bra=='IKJ'){
       $bra="Ensure Ikeja";
   }
   if($bra=='BS'){
       $bra="Ensure Broad Street";
   }
   if($bra=='ABJ'){
       $bra="Ensure Abuja";
   }
   if($bra=='PH'){
       $bra="Ensure PH";
   }
    if($bra=='FLAG'){
       $bra="FA Lagos";
   }
   if($bra=='FPH'){
       $bra="FA PH";
   }
   if($bra=='FABJ'){
       $bra="FA Abuja";
   }
   $rol = $_GET['r'];

   
   $mon="";
$m="";
  $m= $_GET['m'];
  if(isset($_GET['m'])){
 $m = $_GET['m'];
    
       if($m ==1){$mon ="January";}
       elseif($m ==2){$mon ="February";}
       elseif($m ==3){$mon ="March";}
       elseif($m ==4){$mon  ="April";}
       elseif($m ==5){$mon ="May";}
       elseif($m ==6){$mon  ="June";}
       elseif($m ==7){$mon  ="July";}
       elseif($m ==8){$mon  ="August";}
       elseif($m ==9){$mon  ="September";}
       elseif($m ==10){$mon ="October";}
       elseif($m ==11){$mon ="November";}
       elseif($m ==12){$mon ="December";}
       
}
   
   
   
   
   


$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='Agency' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                 month='$m' AND year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where 
                 month='$m' AND year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
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
    
    $Q=$con->query("SELECT nb, rn, polnb, polrn FROM branch_bud GROUP BY product_class ");
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
    
    //VI
    $covBudget = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM monthly WHERE bID='$bud_id' AND month='$m' AND branch ='$bra' ");
    $cor=$covBudget->fetch_array();
    $sumBud =$cor['sum(nb)'] + $cor['sum(rn)'];
    $sumPol =$cor['sum(polnb)'] + $cor['sum(polrn)'];
    
    
    
    //Coverage Areas Productions
    
    //Branch Achievement
    /*
    $covArea = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                 month='$m' AND year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE  branch ='$bra' ) ");
    */
    $suAmt = 0;
    $suPol = 0;
    $covArea = $con->query("SELECT sum(r_amount) AS amount, count(distinct policy_no) AS count, agent_code, agent_name FROM `myrecord` WHERE 
      month='$m' AND year='$yy' AND agent_code IN (SELECT agent_code FROM agent WHERE branch = '$bra') GROUP BY agent_name ");
    while($cv = $covArea->fetch_array()){
    $suAmtx = $cv['amount'];
    $suPolx = $cv['count'];
    
    $suAmt +=$suAmtx;
    $suPol +=$suPolx;
    }
    
    
    //Percentage Achieved Budget Amount
    if($suAmt < 1 || $sumBud < 1){$PA_Abuja = 0;}
    else{$PA_Abuja = ($suAmt/$sumBud)*100;}
    
    
    //Percentage Achieved Budget Policy
    if($suPol < 1 || $sumPol < 1){$PA_Abuja1 = 0;}
    else{$PA_Abuja1 = ($suPol/$sumPol)*100;}
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
                            <li class="active">
                                <?php
                            if($_GET['b'] =='VI'){
                                    echo "<a href='m_lagos.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }elseif($_GET['b']=='IKJ'){
                                    echo "<a href='m_lagos.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }
                                elseif($_GET['b']=='BS'){
                                    echo "<a href='m_lagos.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }
                                elseif($_GET['b']=='ABJ'){
                                    echo "<a href='m_abuja.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }
                                elseif($_GET['b']=='PH'){
                                    echo "<a href='m_ph.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }else{
                                    echo "<a href='month.php?m=$m&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                }
                            ?>
                            </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                
                
                
                
                <div class="row">
                   <div class="col-md-12 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Budget vs Actual For <?php echo $mon; ?></h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="25%"><strong>Coverage Area </strong></th>
                                                        <th width="10%"><strong>Budget </strong></th>
                                                        <th width="15%"><strong>Achieved (YTD)</strong></th>
                                                        <th width="10%"><strong>%Achieved (YTD)</strong></th>
                                                        <th width="5%"><strong>NoP </strong></th>
                                                        <th width="15%"><strong>Achieved (YTD)</strong></th>
                                                        <th width="10%"><strong>%Achieved (YTD)</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th><strong><?php echo $bra; ?></strong> </th>
                                                        <th><strong><span class="tag tag-warning"><?php echo number_format($sumBud); ?></span></strong> </th>
                                                        <th><strong><span class="tag tag-success"><?php echo number_format($suAmt); ?></span></strong> </th>
                                                        <th><strong><span class="tag tag-info"> <?php echo number_format($PA_Abuja, 1, '.', '')."%"; ?></span></strong> </th>
                                                        <th><strong><span class="tag tag-warning"><?php echo number_format($sumPol); ?></span></strong> </th>
                                                        <th><strong><span class="tag tag-success"><?php echo number_format($suPol); ?></span></strong> </th>
                                                        <th><strong><span class="tag tag-info"> <?php echo number_format($PA_Abuja1, 1, '.', '')."%"; ?></span></strong></th>
                                                    </tr>

                                                    
                                                </tbody>
                            </table>
                            </div> 
                        </div>
                   </div>
                   
                    
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6">
                             <div class="white-box">
                                 <h3 class="box-title"><?php echo $bra; ?> (FAs)</h3>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" width="100%">
                                                  <thead>
                                                    <tr>
                                                        <th width="25%"><strong>Name</strong></th>
                                                        <th width="15%"><strong>Agent Code </strong></th>
                                                        <th width="15%"><strong>Budget </strong></th>
                                                        <th width="5%"><strong>Achieved </strong></th>
                                                        <th width="5%"><strong>NoP Budget</strong></th>
                                                        <th width="5%"><strong> NoP Achieved</strong> </th>
<!--                                                        <th width="10%"><strong>RN Budget</strong></th>
                                                        <th width="5%"><strong>Achieved</strong></th>
                                                        <th width="5%"><strong>NoP Bdgt</strong></th>
                                                        <th width="5%"><strong>NoP Achieved</strong></th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                         //Supervisor New business and Policy count
                                                            $newBAmt =0;
                                                            $reNAmt = 0;

                                                            $newPol =0;
                                                            $reNPol = 0;
                                                            $totalBranchNewBusiness =0;
                                                            $totalBranchRenewal =0;
                                                            $totalNewBusinessBudget = 0;
                                                            $totalRenewalBudget = 0;
                                                        
                                                        $q=$con->query("SELECT sum(r_amount) AS amount, count(distinct policy_no) AS count, agent_code, agent_name FROM `myrecord` WHERE 
                                                         month='$m' AND year='$yy' AND agent_code IN (SELECT agent_code FROM agent WHERE branch = '$bra') GROUP BY agent_name");
                                                        $counter = 0;
                                                        while($row = $q->fetch_array()){
                                                            $agCode = $row['agent_code'];
                                                            $faName = $row['agent_name'];
                                                            $spCou = $row['count'];
                                                            $supAmt = $row['amount'];
                                                            $newBAmt += $supAmt;
                                                            $newPol += $spCou;
                                                         
                                                    ?>
                                                    <tr>
                                                        <th scope="row">
                                                            <strong>
                                                                <a href="fa-m_agt.php?code=<?php echo $agCode ?>&bran=<?php echo $bra;?>&budg=<?php echo $bid ?>&m=<?php echo $m ?>&year=<?php echo $yy ?>&r=<?php echo $rol ?>" ><strong> <?php echo $faName; ?></strong></a>
                                                            </strong>
                                                        </th>
                                                        <th><strong><?php echo $agCode ?></strong></th>
                                                        <th><strong><?php // echo number_format($row['nb']); ?></strong></th>
                                                        <th scope="row"><strong><span class=""> <?php   echo number_format($supAmt) ?></span> </strong></th>
                                                        <th><strong><?php // echo number_format($row['polnb']); ?></strong></th>
                                                        <th scope="row"><strong><span class=""> <?php  echo number_format($newPol); ?></span> </strong></th>
<!--                                                        <th><strong><?php // echo number_format($row['rn']); ?></strong></th>
                                                        <th scope="row"><strong><span class=""> <?php // echo number_format($reNAmt); ?> </span></strong></th>
                                                        <th><strong><?php // echo number_format($row['polrn']); ?></strong></th>
                                                        <th scope="row"><strong><span class=""> <?php  echo number_format($reNPol); ?></span></strong> </th>-->
                                                    </tr>
                                                        <?php } ?> 
<!--                                                    <tr>
                                                        <th><span class="btn btn-primary"><strong>Total</strong></span></th>
                                                        <th></th>
                                                        <th><span class="btn btn-warning"><strong><?php  echo number_format($totalNewBusinessBudget); ?></strong></span></th>
                                                        <th><span class="btn btn-info"><strong><?php  echo number_format($totalBranchNewBusiness); ?></strong></span></th>
                                                        <th><strong><?php //  echo number_format($bg1); ?></strong></th>
                                                        <th><strong><?php // echo number_format((float)$pAmt1, 1, '.', '')."%"; ?></strong></th>
                                                        <th><span class="btn btn-warning"><strong><?php  echo number_format($totalRenewalBudget); ?></strong></span></th>
                                                        <th><span class="btn btn-info"><strong><?php  echo number_format($totalBranchRenewal); ?></strong></span></th>
                                                        
                                                        
                                                        
                                                        <th><strong><?php //  echo number_format($sNop1); ?></strong></th>
                                                        <th><strong><?php // echo number_format((float)$pNoP1, 1, '.', '')."%"; ?></strong></th>
                                                        <th></th>
                                                    </tr>-->
                                                </tbody>
                                            </table> 
                                 </div>
                             </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12"> 
                        <div class="col-xs-4 ">
                            <div class="white-box">
                                <h3 class="box-title">NB Budget (<?php echo $bra; ?>)</h3>
                                <div class="table-responsive">
                                <table class="table table-bordered table-striped" width="100%">
                                              <tr>
                                                  <td width="40%">Product</td>
                                                  <td width="40%">NB</td>
                                                  <td width="20%">NoP <br><br><br><br><br><br><br><br></td>
                                              </tr>
                                              <?php 
                                                //
                                              $total_budget = 0;
                                              $total_nop = 0;
                                                $p2=$con->query(" SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                                while($rr2=$p2->fetch_array()){
                                                    $pr2=$rr2['product_class'];
                                                    
                                                    
                                                    $pp2=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM branch_bud WHERE product_class='$pr2' AND bID='$bid' AND branch='$bra'");
                                                    $ro2=$pp2->fetch_array();
                                                    $nb2=$ro2['nb'];
                                                    $pol_nb=$ro2['polnb'];
                                                    
                                                    $total_budget +=$nb2;
                                                    $total_nop += $pol_nb;
                                                    
                                                    $p3=$con->query("SELECT product_class, prID FROM proclass_bud WHERE product_class='$pr2'");
                                                    $prr=$p3->fetch_array();
                                                    $prd2=$prr['prID'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <strong><span class="text-primary"><?php echo $pr2;?></span></strong>&nbsp;&nbsp;&nbsp;
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
                                              </tr>
                                                <?php } ?>
                                              <tr>
                                                  <td></td>
                                                  <th><strong><span class="btn btn-warning"><?php echo number_format($total_budget); ?></span></strong></th>
                                                  <th> <strong><span class="btn btn-warning"><?php echo number_format($total_nop); ?></span></strong><br><br></th>
                                            </tr>
                                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-8 " >
                            <div class="white-box">
                                <h3 class="box-title">NB - Budget vs Actual (<?php echo $bra; ?>)</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                          <th colspan="8" align="center" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">New Business</h3></strong></th>
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
                                        </tr>
                                        <tr>
                                             <?php 
                                             $t=0;
                                        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($cr = $cp->fetch_array()){
                                            $prodt = $cr['product_class'];
                                            $a3[] = $prodt;
                                            
                                            //Getting New business budget for each of the product
                                            $pb = $con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn, product_class FROM `branch_bud` WHERE product_class='$prodt' AND year='$yy' AND branch='$bra'");
                                            $or = $pb->fetch_array();
                                            $nbB = $or['nb'];
                                            //$rnB = $or['rn'];
                                            $nbP = $or['polnb'];
                                            //$rnP = $or['polrn'];
                                            $pBu = $nbB ; //new business budget for each of the product
                                            //$polly=$nbP + $rnP;
                                            $aa = $pBu/1000000;
                                            $a1[] = $aa;
                                            //PH Product Performance NB
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND month='$m' AND year='$yy' 
                                            AND agent_code IN (SELECT agent_code FROM agent WHERE branch='$bra'   ) ");
                                            while($ro=$cpp->fetch_array()){
                                                $proAmt = $ro['amount'];
                                                $proPol = $ro['count']; 
                                                $agent = $ro['agent'];
                                                $premium = $ro['premium'];
                                                
                                                $aa2=$proAmt/1000000;
                                                $a2[] = $aa2;
                                                $t +=$proAmt;
                                                
                                                //Activization for PH
                                                $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='NB' AND month='$m' AND year='$yy' AND  r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$bra'  ) ");
                                                $ar=$acvtAbj->fetch_array();
                                                $act= $ar['agent'];
                                            }
                                            
                                            $totalProduct = $proAmt;
                                          
                                            
                                        ?>
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
                                                    $ppp1 = ($proPol/$nbP)*100;
                                                    echo number_format($ppp1, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                                if($proPol < 1 || $premium < 1){ echo 0;}
                                              else{
                                                $ats = $premium /$proPol;
                                                echo number_format($ats);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo $agent ?></strong></th>
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
                                          <th><strong><span class="text-info"><?php echo number_format($totalProduct); ?></span></strong></th>
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
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-xs-12"> 
                        <div class="col-xs-4 ">
                            <div class="white-box">
                                <h3 class="box-title">RN Budget (<?php echo $bra; ?>)</h3>
                                <div class="table-responsive">
                                <table class="table table-bordered table-striped" width="100%">
                                              <tr>
                                                  <td width="40%">Product</td>
                                                  <td width="40%">RN</td>
                                                  <td width="20%">NoP <br><br><br><br><br><br><br><br></td>
                                              </tr>
                                              <?php 
                                                //
                                              $total_budget = 0;
                                              $total_nop = 0;
                                                $p2=$con->query(" SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                                while($rr2=$p2->fetch_array()){
                                                    $pr2=$rr2['product_class'];
                                                    
                                                    
                                                    $pp2=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM branch_bud WHERE product_class='$pr2' AND bID='$bid' AND branch='$bra'");
                                                    $ro2=$pp2->fetch_array();
                                                    $nb2=$ro2['rn'];
                                                    $pol_nb=$ro2['polrn'];
                                                    
                                                    $total_budget +=$nb2;
                                                    $total_nop += $pol_nb;
                                                    
                                                    $p3=$con->query("SELECT product_class, prID FROM proclass_bud WHERE product_class='$pr2'");
                                                    $prr=$p3->fetch_array();
                                                    $prd2=$prr['prID'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <strong> <span class="text-primary"><?php echo $pr2;?></span></strong> &nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      /*
                                                      if($nb2 < 1 or $rn2 < 1){
                                                          echo "<a href='index.php?bran_allocate&budg=$bid&year=$yy&p=$prd2&b=ABJ' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      */
                                                      ?>
                                                  </td>
                                                  <td width="9%"><strong><?php echo number_format($nb2); ?></strong></td>
                                                  <td width="9%"><strong><?php echo number_format($ro2['polrn']); ?></strong></td>
                                              </tr>
                                                <?php } ?>
                                              <tr>
                                                  <td></td>
                                                  <th><strong><?php echo number_format($total_budget); ?></strong></th>
                                                  <th> <strong><?php echo number_format($total_nop); ?></strong><br><br></th>
                                            </tr>
                                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-8 " >
                            <div class="white-box">
                                <h3 class="box-title">RENEWAL - Budget vs Actual (<?php echo $bra; ?>)</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" width="974" height="133" border="1">
                                        <tr>
                                          <th colspan="8" align="center" bgcolor="#206ea1"><h3 align="center" style="color: #fff; font-size: 17px;">RENEWAL</h3></strong></th>
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
                                        </tr>
                                        <tr>
                                             <?php 
                                             $t=0;
                                        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$yy'  GROUP BY product_class");
                                        while($cr = $cp->fetch_array()){
                                            $prodt = $cr['product_class'];
                                            $a3[] = $prodt;
                                            
                                            //Getting Renewal budget for each of the product
                                            $pb = $con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn, product_class FROM `branch_bud` WHERE product_class='$prodt' AND year='$yy' AND branch='$bra'");
                                            $or = $pb->fetch_array();
                                            $nbB = $or['rn'];
                                            //$rnB = $or['rn'];
                                            $nbP = $or['polrn'];
                                            //$rnP = $or['polrn'];
                                            $pBu = $nbB ; //new business budget for each of the product
                                            //$polly=$nbP + $rnP;
                                            $aa = $pBu/1000000;
                                            $a1[] = $aa;
                                            //PH Product Performance NB
                                            $cpp = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount, count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND month='$m' AND year='$yy' 
                                            AND agent_code IN (SELECT agent_code FROM agent WHERE branch='$bra'   ) ");
                                            while($ro=$cpp->fetch_array()){
                                                $proAmt = $ro['amount'];
                                                $proPol = $ro['count']; 
                                                $agent = $ro['agent'];
                                                $premium = $ro['premium'];
                                                
                                                $aa2=$proAmt/1000000;
                                                $a2[] = $aa2;
                                                $t +=$proAmt;
                                                
                                                //Activization for PH
                                                $acvtAbj = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND month='$m' AND year='$yy' AND  r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='$bra'  ) ");
                                                $ar=$acvtAbj->fetch_array();
                                                $act= $ar['agent'];
                                            }
                                            
                                            /*
                                            //PH Product Performance RN
                                            $cpp1 = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND  year='$yy' 
                                            AND agent_code IN (SELECT agent_code FROM agent WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'  ) ");
                                            while($ro1=$cpp1->fetch_array()){
                                                $proAmt1 = $ro1['amount'];
                                                $proPol1 = $ro1['count'];
                                                $agent1 = $ro1['agent'];
                                                $premium1 = $ro1['premium'];
                                                
                                                $aa3=$proAmt1/1000000;
                                                
                                                $a4[] = $aa3;
                                                
                                                $t +=$proAmt1;
                                                
                                                //Activization for PH
                                                $acvtAbj1 = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND type='RN' AND year='$yy'  AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'  ) ");
                                                $ar1=$acvtAbj1->fetch_array();
                                                $act1= $ar1['agent'];
                                            }
                                            
                                            */
                                            
                                            
                                            $totalProduct = $proAmt;
                                          
                                            
                                        ?>
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
                                                    $ppp1 = ($proPol/$nbP)*100;
                                                    echo number_format($ppp1, 2, '.', '')."%";
                                                }
                                              ?>
                                          </strong></th>
                                          <th><strong>
                                              <?php 
                                                if($proPol < 1 || $premium < 1){ echo 0;}
                                              else{
                                                $ats = $premium /$proPol;
                                                echo number_format($ats);
                                              }
                                              ?>
                                          </strong></th>
                                          <th><strong><?php echo $agent ?></strong></th>
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
                                          <th><strong><span class="text-info"><?php echo number_format($totalProduct); ?></span></strong></th>
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
            <footer class="footer text-center"> 2017 &copy;  </footer>
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
    <script type="text/javascript">
        /*
    $(document).ready(function() {
        $.toast({
            heading: 'Welcome to Pixel admin',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'info',
            hideAfter: 3500,
            stack: 6
        })
    });
    */
    </script>
<script>
       Morris.Area({
        element: 'extra-area-chart',
        data: [{
                    period: '2010',
                    iphone: 0,
                    ipad: 0,
                    itouch: 0
                }, {
                    period: '2011',
                    iphone: 50,
                    ipad: 15,
                    itouch: 5
                }, {
                    period: '2012',
                    iphone: 20,
                    ipad: 50,
                    itouch: 65
                }, {
                    period: '2013',
                    iphone: 60,
                    ipad: 12,
                    itouch: 7
                }, {
                    period: '2014',
                    iphone: 30,
                    ipad: 20,
                    itouch: 120
                }, {
                    period: '2015',
                    iphone: 25,
                    ipad: 80,
                    itouch: 40
                }, {
                    period: '2016',
                    iphone: 10,
                    ipad: 10,
                    itouch: 10
                }


                ],
                lineColors: ['#f75b36', '#00b5c2', '#8698b7'],
                xkey: 'period',
                ykeys: ['iphone', 'ipad', 'itouch'],
                labels: ['Site A', 'Site B', 'Site C'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
        
    }); 
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
