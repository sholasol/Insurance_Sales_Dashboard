<?php 
include 'mis_nav.php';
include 'fa_side.php';

$m= $_GET['m'];
$rol= $_GET['r'];
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

if($rol =='FA'){
    $ty = 'Financial Advisors'; 
    $brac1 = 'FA Abuja';
    $brac2 = "'%FA Lagos%' OR branch LIKE '%FA Ikeja%' OR  branch LIKE '%FA Broad Street%' OR branch LIKE '%FA Victoria Island%'";
    $branch ="'FA Lagos'"; // This is Branches for financial advisor (This should be edited in case FA are located branch wise )
    $brac3 = 'FA PH';
    $roly = 'FA'; // This is used to determine budget for each category e.g FA PH
    
}
if($rol =='Agency'){
    $ty = 'Agency';
    $brac1 = 'Ensure Abuja';
    $brac2 = "'%Ensure Lagos%' OR branch LIKE '%Ensure Ikeja%' OR  branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%'";
    $brac3 = 'Ensure PH';
    $branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'";
    
    $roly = 'Ensure'; // This is used to determine budget for each category e.g Ensure PH
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

$q1[] = 0;
$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') GROUP BY product_class");
while($yr =$ach->fetch_array()){

$xxx= $yr['sum']/1000000;
 $q1[] = $xxx;
 
 $polc=$yr['count'];
$rAmount = $yr['sum'];
}
//
$q2[] = 0;
$ach2=$con->query("SELECT sum(r_amount) AS sum, count(distinct agent_code) AS count FROM myrecord where 
                  year='$y' AND type='RN' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') GROUP BY product_class");
while($yr2 =$ach2->fetch_array()){
$totAgt = $yr2['count'];
$xxx2= $yr2['sum']/1000000;
 $q2[] = $xxx2;
}


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
                                if($rol =='FA'){echo "<li class='active'><a href='monthlybud.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                                if($rol =='Agency'){echo "<li class='active'><a href='monthlybud.php?r=$rol'><i class='icon-home'></i> Back</a> </li>";}
                                ?>
                            </li>
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
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Total Product Budget for <?php echo $mon ?></h4>
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>NB Bgdt</th>
                                                        <th>RN Bgdt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $totalNB=0;
                                                        $totalRN=0;
                                                        $totalNBP=0;
                                                        $totalRNP=0;
                                                        $ptotalNB=0;
                                                        $ptotalRN=0;
                                                        $qq=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn,product_class FROM monthly WHERE bID='$bid' AND month='$m' AND branch LIKE '%$roly%' GROUP BY `product_class`");
                                                        while($rw = $qq->fetch_array()){
                                                            $prod = $rw['product_class'];
                                                            $newB = $rw['nb'];
                                                            //$pnewB = $rw['pnb'];
                                                            //$newBP = $rw['polnb'];
                                                            //$newBPP = $rw['ppolnb'];
                                                            $renB = $rw['rn'];
                                                            //$prenB = $rw['prn'];
                                                            //$renP = $rw['polrn'];
                                                            //$renPP = $rw['ppolrn'];
                                                            //$tot = $newB + $renB;
                                                            //$totP = $newBP + $renP;
                                                            
                                                        $totalNB +=$newB;
                                                        $totalRN +=$renB;
                                                        //$totalNBP +=$newBP;
                                                        //$totalRNP +=$renP;
                                                        //$ptotalNB +=$pnewB;
                                                        //$ptotalRN +=$pnewB;
                                                        $tt=  $totalNB  +$totalRN;                     
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <span class="text-primary"><i class="icon-plus-sign"></i> <?php echo $prod; ?></span>
                                                        </td>
                                                        <td><span class="text-danger"><?php echo number_format($rw['nb']); ?></span></td>
                                                        <td><span class="text-info"><?php echo number_format($rw['rn']); ?></span></td>
                                                    </tr>
                                                        <?php } ?> 
                                                    <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                </div>  
            </div>
            
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">HQ Budget <?php echo $mon ?></h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                <div class="heading-elements">

                                </div>
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p5=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type LIKE '%$ty%'");
                                                while($rr5=$p5->fetch_array()){
                                                    $pr5=$rr5['product_class'];
                                                    $prd5=$rr5['prID'];
                                                    
                                                    $pp5=$con->query("SELECT nb, rn FROM monthly WHERE product_class='$pr5' AND bID='$bid' AND branch='Ensure Victoria Island'  AND month='$m' AND branch LIKE '%$roly%'");
                                                    $ro5=$pp5->fetch_array();
                                                    $nb5=$ro5['nb'];
                                                    $rn5=$ro5['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr5;?></span> 
                                                      
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb5); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn5); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                    <td></td>
                                                    <td><?php // echo number_format($totalNB); ?> </td>
                                                    <td><?php // echo number_format($totalRN); ?><br> </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">  Broad Street Budget for <?php echo $mon ?></h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
<!--                                <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="bran_allocate.php?budg=<?php echo $bid; ?>&year=<?php echo $y; ?>" class="card-title"><i class="icon-plus"></i> Add New</a></li>
                                        </ul>
                                </div>-->
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type LIKE '%$ty%'");
                                                while($rr=$p->fetch_array()){
                                                    $pr=$rr['product_class'];
                                                    $prd=$rr['prID'];
                                                    
                                                    $pp=$con->query("SELECT nb, rn FROM monthly WHERE month='$m' AND product_class='$pr' AND bID='$bid' AND branch='Ensure Broad Street' AND branch LIKE '%$roly%'");
                                                    $ro=$pp->fetch_array();
                                                    $nb=$ro['nb'];
                                                    $rn=$ro['rn'];
                                                    
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr;?></span>&nbsp;&nbsp;&nbsp;
                                                     
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
<!--                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>-->
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
            
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Ensure Ikeja Budget for <?php echo $mon ?></h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
<!--                                <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="bran_allocate.php?budg=<?php echo $bid; ?>&year=<?php echo $y; ?>" class="card-title"><i class="icon-plus"></i> Add New</a></li>
                                        </ul>
                                </div>-->
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p1=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type LIKE '%$ty%'");
                                                while($rr1=$p1->fetch_array()){
                                                    $pr1=$rr1['product_class'];
                                                    $prd1=$rr1['prID'];
                                                    
                                                    $pp1=$con->query("SELECT nb, rn FROM monthly WHERE month='$m' AND product_class='$pr' AND bID='$bid' AND branch='Ensure Ikeja' AND branch LIKE '%$roly%'");
                                                    $ro1=$pp1->fetch_array();
                                                    $nb1=$ro1['nb'];
                                                    $rn1=$ro1['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr1;?></span>&nbsp;&nbsp;&nbsp;
                                                     
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb1); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn1); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
<!--                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>-->
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
                 
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Ensure Abuja Budget for <?php echo $mon ?></h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
<!--                                <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="bran_allocate.php?budg=<?php echo $bid; ?>&year=<?php echo $y; ?>" class="card-title"><i class="icon-plus"></i> Add New</a></li>
                                        </ul>
                                </div>-->
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p2=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type LIKE '%$ty%'");
                                                while($rr2=$p2->fetch_array()){
                                                    $pr2=$rr2['product_class'];
                                                    $prd2=$rr2['prID'];
                                                    
                                                    $pp2=$con->query("SELECT nb, rn FROM monthly WHERE month='$m' AND product_class='$pr2' AND bID='$bid' AND branch='Ensure Abuja' AND branch LIKE '%$roly%'");
                                                    $ro2=$pp2->fetch_array();
                                                    $nb2=$ro2['nb'];
                                                    $rn2=$ro2['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr2;?></span>&nbsp;&nbsp;&nbsp;
                                                      
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb2); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn2); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
<!--                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>-->
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
            
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Ensure PH Budget for <?php echo $mon ?></h4>
                                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
				</div>
				<div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p3=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type LIKE '%$ty%'");
                                                while($rr3=$p3->fetch_array()){
                                                    $pr3=$rr3['product_class'];
                                                    $prd3=$rr3['prID'];
                                                    
                                                    $pp3=$con->query("SELECT nb, rn FROM monthly WHERE month='$m' AND product_class='$pr3' AND bID='$bid' AND branch='Ensure PH' AND branch LIKE '%$roly%'");
                                                    $ro3=$pp3->fetch_array();
                                                    $nb3=$ro3['nb'];
                                                    $rn3=$ro3['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr3;?></span>&nbsp;&nbsp;&nbsp;
                                                      
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb3); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn3); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
<!--                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>-->
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
            
            
        </div>
                
                
                
                
                
               
                
                
                 
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
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
    <!-- Data Tables-->
    <script src="../plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
    <!-- Line Charts-->
    <script src="..scripts/charts/chartjs/line/line.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-area.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-stacked-area.js" type="text/javascript"></script>

<!--    <script src="../plugins/bower_components/Chart.js/chartjs.init.js"></script>-->
    <script src="../plugins/bower_components/Chart.js/Chart.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    
     <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                            );

                            last = group;
                        }
                    });
                }
            });

            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>
</body>

</html>


