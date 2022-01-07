<?php 
include 'mis_nav.php';



$rol = $_GET['r'];
$ty= '';
$tp= '';
if($rol =='FA'){
    $ty = 'Financial Advisors'; 
    include 'fa_side.php';
}elseif($rol =='Agency'){
    $ty = 'Agency'; 
    include 'mis_side.php';
}elseif($rol =='HNI'){
    $ty = 'HNI'; 
    include 'hni_side.php';
}
if($rol =='FI'){
    $ty = 'FI';
    include 'fi_side.php';
}
if($rol =='Partners'){
    $ty = 'Partners';
    include 'partner_side.php';
}

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

if($rol =='FA'){$tp = 'Financial Advisors';}elseif($rol =='Agency'){$tp = 'Agency';}
elseif($rol =='Partners' || $rol =='FI' || $rol =='HNI'){$tp = $rol;}

$agcy=$con->query("SELECT * FROM type WHERE bID='$bid' AND type='$tp' AND year='$y'");
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


//FA Production
$agcy_ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%Agency%'");
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
                            if($rol =='FA'){echo "Bancassurance";}
                            elseif($rol =='Partners'){echo "Partners -Autodealership";}
                            elseif($rol =='FI'){echo "Partners - FI";}
                            elseif($rol =='HNI'){echo "Partners - HNI";}
                            else{ echo $rol;}
                            ?>
                        </h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <?php 
                                    if($rol =='FA'){echo "<a href='fa_budget.php'><i class='icon-home'></i> Back</a> ";}
                                    if($rol =='Agency'){echo "<a href='a_budget.php'><i class='icon-home'></i> Back</a> ";}
                                    if($rol =='Partners' || $rol =='FI' || $rol =='HNI'){echo "<a href='partner_budget.php?r=$rol'><i class='icon-home'></i> Back</a> ";}
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
                                            <h4 class="counter text-right m-t-10 text-info"><?php echo number_format($agency); ?></h4>
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
                                            <h4 class="counter text-right m-t-15 text-success"><?php echo number_format($agencyPol); ?></h4>
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
                   <div class="white-box">
                       
                      <h3 class="box-title">
                          <?php 
                          if($rol =='FA'){echo "Bancassurance";}
                            elseif($rol =='Partners'){echo "Partners -Autodealership";}
                            elseif($rol =='FI'){echo "Partners - FI";}
                            elseif($rol =='HNI'){echo "Partners - HNI";}
                            else{ echo $rol;}
                          ?> Yearly Budget
                          <a href="" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="card-title pull-right"><i class="icon-plus"></i> Add Budget by Product</a>
                      </h3>
                    <div class="table-responsive">
                        <form method="post" action="">
                            <input  type="hidden" id="budget" name="" value="<?php echo $tBud; ?>"/>
                            <input type="hidden" id="policy" name="" value="<?php echo $tPol; ?>"/>
                            <input  type="hidden" id="budget" name="rol" value="<?php echo $ty; ?>"/>
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th width="15%">Product</th>
                                    <th width="17%">NB Budget</th>
                                    <th>% Budget</th>
                                    <th>NoP NB</th>
                                    <th>% NoP </th>
                                    <th width="17%">RN Budget</th>
                                    <th>% Budget</th>
                                    <th width="10%">NoP RN</th>
                                    <th>% NoP </th>
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
                                    $ppolnb=0;
                                    $ppolrn=0;
                                    $qq=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$y' GROUP BY product_class");
                                     
                                    while($rw = $qq->fetch_array()){
                                        $prod = $rw['product_class'];
                                        
                                        $pd=$con->query("SELECT * FROM proclass_bud WHERE product_class ='$prod' AND bID='$bid' AND type='$ty' ");
                                        $pr=$pd->fetch_array();
                                        $nb=$pr['nb'];
                                        $totalNB += $nb;
                                        $rn=$pr['rn'];
                                        $totalRN +=$rn;
                                        $polnb=$pr['polnb'];
                                        $totalNBP += $polnb;
                                        $polrn=$pr['polrn'];
                                        $totalRNP += $polrn;
                                        $year=$pr['year'];
                                        $ttBudget = $totalNB + $totalRN;
                                ?>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group"> <span class="input-group-addon text-info">P</span>
                                                <input type="text" id="prod"  name="prod[]" value="<?php echo $prod; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                                <span class="input-group-addon">=N=</span>
                                                <input type="number" class="form-control" id="nb<?php echo str_replace(' ', '', $prod);?>"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['nb'] ?>" name="nb[]" onkeyup="calculatePriceClassReverse('#nb<?php echo  str_replace(' ', '', $prod);?>','#pnb<?php echo str_replace(' ', '', $prod);?>')" />
                                        </div>
                                    </td>
                                    <td scope="row"> 
                                        <div class="input-group">
                                                <input type="number" min="0" step="0.01" id="pnb<?php echo str_replace(' ', '', $prod);?>" class="form-control"  aria-label="Percentage (to the nearest naira)" value="<?php echo $pr['pnb'] ?>" name="nbpercent[]" onkeyup="calculatePriceClass('#pnb<?php echo str_replace(' ', '', $prod);?>','#nb<?php echo str_replace(' ', '', $prod);?>')"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                                <input type="number" id="polnb<?php echo str_replace(' ', '', $prod);?>" class="form-control"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['polnb'] ?>" name="nbnop[]" onkeyup="calNoPRev('#polnb<?php echo str_replace(' ', '', $prod);?>','#ppolnb<?php echo str_replace(' ', '', $prod);?>')" />

                                        </div>
                                    </td>
                                    <td scope="row">
                                        <div class="input-group">
                                                <input type="number" min="0" step="0.01" id="ppolnb<?php echo str_replace(' ', '', $prod);?>" class="form-control"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['ppolnb'] ?>" name="pnbnop[]" onkeyup="calNoP('#ppolnb<?php echo str_replace(' ', '', $prod);?>','#polnb<?php echo str_replace(' ', '', $prod);?>')" >
                                        </div>
                                    </td>
                                    <td>
                                      <div class="input-group">
                                                <span class="input-group-addon">=N=</span>
                                                <input type="number" class="form-control"  id="rn<?php echo str_replace(' ', '', $prod);?>" aria-label="Amount (to the nearest naira)" value="<?php echo $pr['rn'] ?>" name="rn[]" onkeyup="calBudRev('#rn<?php echo  str_replace(' ', '', $prod);?>','#prn<?php echo str_replace(' ', '', $prod);?>')" />
                                        </div>  
                                    </td>
                                    <td scope="row">
                                        <div class="input-group">
                                                <input type="number" min="0" step="0.01" id="prn<?php echo str_replace(' ', '', $prod);?>" class="form-control"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['prn'] ?>" name="rnpercent[]" onkeyup="calculatePriceClass('#prn<?php echo str_replace(' ', '', $prod);?>','#rn<?php echo str_replace(' ', '', $prod);?>')" />
                                        </div>
                                    </td>
                                    <td>
                                      <div class="input-group">
                                                <input type="number" class="form-control" id="polrn<?php echo str_replace(' ', '', $prod);?>"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['polrn'] ?>" name="rnop[]" onkeyup="calNoPRev2('#polrn<?php echo str_replace(' ', '', $prod);?>','#ppolrn<?php echo str_replace(' ', '', $prod);?>')" />

                                        </div>  
                                    </td>
                                    <td scope="row">
                                        <div class="input-group">
                                                <input type="number" min="0" step="0.01" id="ppolrn<?php echo str_replace(' ', '', $prod);?>" class="form-control"  aria-label="Amount (to the nearest naira)" value="<?php echo $pr['ppolrn'] ?>"  name="prnnop[]" onkeyup="calNoP2('#ppolrn<?php echo str_replace(' ', '', $prod);?>','#polrn<?php echo str_replace(' ', '', $prod);?>')"  />
                                        </div>
                                    </td>
                                </tr>
                                
                                    <?php } ?> 
                                <tr>
                                    <td colspan="5">
                                        <div class="form-group col-md-12">
                                          <select id="issueinput5" name="year" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Year" required>
                                                 <?php 
                                                 if($year > 0){
                                                     echo"<option> $year</option>";
                                                 }else{ 
                                                    echo" <option value=''>Please Choose Year</option>"; 
                                                    
                                                 }
                                                 ?> 
                                                    <?php
                                                    for($x=date("Y") + 1; $x > 1980; $x--){
                                                        echo"<option value='$x'> $x</option>";
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td colspan="3"><button type="submit" name="save" class='btn btn-success btn-block'>Save Budget</button></td>
                                </t>
                                <tr>
                                    <td></td>
                                    <td><span class="btn btn-primary"><?php echo number_format($totalNB); ?> </span></td>
                                    <td><span class="btn btn-info"><?php echo number_format($ptotalNB); ?></span></td>
                                    <td><span class="btn btn-success"><?php echo number_format($totalNBP); ?></span> </td>
                                    <td><span class="btn btn-info"><?php echo number_format($ptotalRN); ?></span></td>
                                    <td><span class="btn btn-danger"><?php echo number_format($totalRN); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo $ppolnb; ?></span></td>
                                    <td><span class="btn btn-info"><?php echo number_format($totalRNP); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo $ppolrn; ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-info"><strong>Total Allocated Budget</strong></span></td>
                                    <td colspan="8"><span class="col-md-12 btn btn-info">=N= <?php echo number_format($ttBudget); ?></span></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                   </div>
                </div>
                <!-- /.row -->
                
                
                
                
                
                
                
                
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="exampleModalLabel1">Create Budget</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form" class="form" method="post">
                                                <input  type="hidden" id="budget" name="rol" value="<?php echo $ty; ?>"/>
                                                <div class="row"> 
                                                        <div class="col-md-12 ">
                                                            <h4 class="form-section"><i class="fa fa-money text-info"></i> Budget</h4>
                                                                <div class="form-group col-md-6">
                                                                <label>Budget Amount</label>
                                                                <div class="input-group">
                                                                        <span class="input-group-addon">=N=</span>
                                                                        <input type="number" id="amount" class="form-control" value="<?php echo $tBud; ?>" placeholder="Budget Amount" aria-label="Amount (to the nearest naira)" name="amt" required>
                                                                        <span class="input-group-addon">.00</span>
                                                                </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>Total Policy</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" value="<?php echo $tPol; ?>" placeholder="Total Policy" aria-label="Amount (to the nearest naira)" name="tpol" required>
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <h4 class="form-section"><i class="icon-briefcase text-info"></i> Product</h4>
                                                                <div class="form-group col-md-12">
                                                                        <label>Product</label>
                                                                        <select id="issueinput5" name="prod" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Product" required>
                                                                            <option value="">Please Select</option>
                                                                            <?php
                                                                            $pre=$con->query("SELECT DISTINCT product_class FROM myrecord");
                                                                            while($re=$pre->fetch_array()){
                                                                            ?>
                                                                            <option><?php echo $re['product_class']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                </div>

                                                                <h4 class="form-section"><i class="fa fa-list-alt text-info"></i> Allocation By Business Type</h4>
                                                                <div class="form-group col-md-6">
                                                                        <label>New Business (Amount)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">=N=</span>
                                                                                <input type="number" class="form-control" placeholder=" New Business Budget" aria-label="Amount (to the nearest naira)" name="nb1" onkeyup="calculatePerc()" />
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>New Business (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Percentage (to the nearest naira)" name="nbpercent1" onkeyup="calculatePrice()"/>
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                        <label>Renewal (Amount)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">=N=</span>
                                                                                <input type="number" class="form-control" placeholder="Renewal Budget " aria-label="Amount (to the nearest naira)" name="rn1" onkeyup="calculatePerc2()" />
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>Renewal (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="rnpercent1" onkeyup="calculatePrice2()" />
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>
                                                                <h4 class="form-section"><i class="fa fa-list-alt text-info"></i> Number of Policy</h4>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (NB)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="nbnop1" onkeyup="calculatePercNoP()" />

                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (NB) (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="pnbnop1" onkeyup="calculateNoP()" >
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>


                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (Renewal)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="rnop1" onkeyup="calculatePercNoP2()" />

                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP Renewal (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="prnnop1" onkeyup="calculateNoP2()"  />
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                        <label for="issueinput5">Year</label>
                                                                        <select id="issueinput5" name="year" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Year" required>
                                                                                <option value="">Please Choose Year</option> 
                                                                                <?php
                                                                                for($x=date("Y") + 1; $x > 1980; $x--){
                                                                                    echo"<option value='$x'> $x</option>";
                                                                                }
                                                                                ?>
                                                                        </select>
                                                                </div>

                                                       
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="savebudget" class="btn btn-primary">Save Budget</button>
                                                    </div>
                                                </div>

                                        </form>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                
                
                
                
                
                
                
                
                
               
                
                <?php 
                
                        if(isset($_POST['save'])){
                        foreach ($_POST['prod'] as $prod => $value){

                        $pro= $_POST['prod'][$prod];
                        $nb=$_POST['nb'][$prod];
                        $rn=$_POST['rn'][$prod];
                        $yr=$_POST['year'];
                        $rl=$_POST['rol'];
                        $rnop=$_POST['rnop'][$prod];
                        $nbnop=$_POST['nbnop'][$prod];
                        $Pnb=$_POST['nbpercent'][$prod];
                        $Prn=$_POST['rnpercent'][$prod];
                        $Prnop=$_POST['prnnop'][$prod]; 
                        $Pnbnop=$_POST['pnbnop'][$prod];




                        $brChk = $con->query("SELECT count(prID) AS count FROM proclass_bud WHERE product_class ='$pro' AND bID='$bid' AND type='$ty' AND year='$yr'");
                        $bro=$brChk->fetch_array();
                        //$cnt=$brChk->num_rows;
                        $cnt=$bro['count'];

                        if($cnt < 1){
                            $in=$con->query("INSERT INTO proclass_bud SET bID='$bid', product_class='$pro', nb='$nb', pnb='$Pnb', rn='$rn',prn='$Prn', polnb='$nbnop', ppolnb='$Pnbnop', polrn='$rnop', ppolrn='$Prnop',  year='$yr', type='$rl', created=now() ");
                            
                        }else{
                            echo  " <script>alert('Budget for $pro Already exists '); </script>";
                        }
                        }
                        if($in){
                                echo  " <script>alert('The budget for $yr has been successfully created ');  </script>";
                            }else{
                                echo  " <script>alert('Operation failed. Try again '); </script>";
                            }




                }
                
                



if(isset($_POST['savebudget'])){
    if(empty($_POST['prod'])){
        echo  " <script>alert('Please select a product '); </script>";
    }
    elseif(empty($_POST['rn1']) && empty($_POST['rnpercent1'])){
        echo  " <script>alert('Please specify renewal value or percentage '); </script>";
    }
    elseif(empty($_POST['nb1']) && empty($_POST['nbpercent1'])){
        echo  " <script>alert('Please specify New business value or percentage '); </script>";
    }
    elseif(empty($_POST['year'])){
        echo  " <script>alert('Please specify the year '); </script>";
    }
    else{
    //$brn=check_input($_POST['branch']);
    $pro=check_input($_POST['prod']);
    $nb=check_input($_POST['nb1']);
    $rn=check_input($_POST['rn1']);
    $yr=check_input($_POST['year']);
    $rl=check_input($_POST['rol']);
    $rnop=check_input($_POST['rnop1']);
    $nbnop=check_input($_POST['nbnop1']);
    
    $Pnb=check_input($_POST['nbpercent1']);
    $Prn=check_input($_POST['rnpercent1']);
    
    
    $Prnop=check_input($_POST['prnnop1']); 
    $Pnbnop=check_input($_POST['pnbnop1']);
    
    $brChk = $con->query("SELECT count(prID) AS count FROM proclass_bud WHERE product_class ='$pro' AND bID='$bid' AND type='$ty' AND year='$yr'");
    $bro=$brChk->fetch_array();
    
    $co=$bro['count'];
    
    if($co > 0){
        
        echo  " <script>alert('Budget for $pro Already exists '); </script>";
    }  else {
        $in=$con->query("INSERT INTO proclass_bud SET bID='$bid', product_class='$pro', nb='$nb', pnb='$Pnb', rn='$rn',prn='$Prn', polnb='$nbnop', ppolnb='$Pnbnop', polrn='$rnop', ppolrn='$Prnop',  year='$yr', type='$rl', created=now() ");
        if($in){
            echo  " <script>alert('The budget for $pro has been successfully created ');  </script>";
        }else{
            echo  " <script>alert('Operation failed. Try again '); </script>";
        }
    }
    

    }
    
}
function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
                
                
                
                
                ?>
                

                
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
    
    <!-- Line Charts-->
    <script src="..scripts/charts/chartjs/line/line.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-area.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-stacked-area.js" type="text/javascript"></script>

<!--    <script src="../plugins/bower_components/Chart.js/chartjs.init.js"></script>-->
    <script src="../plugins/bower_components/Chart.js/Chart.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    
    <script>
        //For BULK BUDGETTING
        //Get New Business Budget From Percentage
         function calculatePriceClass(nbpercent,nb) {
            var percentage = $(nbpercent).val(),
                price = $('#budget').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $(nb).val(discountPrice);
        }
        //Get New Business Budget NOP From Percentage
        function calculatePriceClassReverse(rn,rnpercent) {
            var percentage = $(rn).val(),
                price = $('#budget').val(),
                calcPrice = ( (percentage/price) * 100 ),
                discountPrice = calcPrice.toFixed(2);
            $(rnpercent).val(discountPrice);
        }
        
        //Get New Business NoP From Percentage NoP
         function calNoP(polnb,ppolnb) {
            var percentage = $(polnb).val(),
                price = $('#policy').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $(ppolnb).val(discountPrice);
        }
        //Get New Business Percentage NoP From NoP
        function calNoPRev(ppolnb,polnb) {
            var percentage = $(ppolnb).val(),
                price = $('#policy').val(),
                calcPrice = ( (percentage/price) * 100 ),
                discountPrice = calcPrice.toFixed(2);
            $(polnb).val(discountPrice);
        }
        
        
        //Get RENEWAL Budget From Percentage
         function calBud(rnpercent,rn) {
            var percentage = $(rnpercent).val(),
                price = $('#budget').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $(rn).val(discountPrice);
        }
        //Get RENEWAL NOP Budget From Percentage
        function calBudRev(rn,rnpercent) {
            var percentage = $(rn).val(),
                price = $('#budget').val(),
                calcPrice = ( (percentage/price) * 100 ),
                discountPrice = calcPrice.toFixed(2);
            $(rnpercent).val(discountPrice);
        }
        
        //Get Renewal NoP From Percentage NoP
         function calNoP2(polrn,ppolrn) {
            var percentage = $(polrn).val(),
                price = $('#policy').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $(ppolrn).val(discountPrice);
        }
        //Get Renewal Percentage NoP From NoP
        function calNoPRev2(ppolrn,polrn) {
            var percentage = $(ppolrn).val(),
                price = $('#policy').val(),
                calcPrice = ( (percentage/price) * 100 ),
                discountPrice = calcPrice.toFixed(2);
            $(polrn).val(discountPrice);
        }
        
        
        
        
        
        //SINGULAR BUDGETTING
        function calculatePrice() {
            var percentage = $('input[name=nbpercent1]').val(),
                price = $('input[name=amt]').val(),
                calcPrice = ( (percentage/price) * (100) ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'nb1\']').val(discountPrice);
        }
        function calculatePerc() {
            var discountPrice = $('input[name=nb1]').val(),    
                price = $('input[name=amt]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=nbpercent1]').val(discountPerc);
        }
        
        //For Renewal
        function calculatePrice2() {
            var percentage = $('input[name=rnpercent1]').val(),
                price = $('input[name=amt]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'rn1\']').val(discountPrice);
        }
        function calculatePerc2() {
            var discountPrice = $('input[name=rn1]').val(),    
                price = $('input[name=amt]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=rnpercent1]').val(discountPerc);
        }
        
        //NOP New Business
        function calculateNoP() {
            var percentage = $('input[name=pnbnop1]').val(),
                price = $('input[name=tpol]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'nbnop1\']').val(discountPrice);
        }
        function calculatePercNoP() {
            var discountPrice = $('input[name=nbnop1]').val(),    
                price = $('input[name=tpol]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=pnbnop1]').val(discountPerc);
        }
        
        //NOP Renewal
        function calculateNoP2() {
            var percentage = $('input[name=prnnop1]').val(),
                price = $('input[name=tpol]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'rnop1\']').val(discountPrice);
        }
        function calculatePercNoP2() {
            var discountPrice = $('input[name=rnop1]').val(),    
                price = $('input[name=tpol]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=prnnop1]').val(discountPerc);
        }
        
        
    </script>
</body>

</html>


