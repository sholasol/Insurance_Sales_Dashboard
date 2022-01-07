<?php 
include 'mis_nav.php';
include 'mis_side.php';


$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];
$budget = $brow['amount'];
$budgetNoP = $brow['totalpol'];
$NB = $brow['nb'];
$RN = $brow['rn'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM type WHERE bID='$bid' AND year='$y'");
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

//Agency Production
$agcy_ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND (c_area LIKE '%Agency%' OR c_area LIKE '%Partners%' OR c_area LIKE '%Financial Advisors%' OR c_area LIKE '%TRAVEL%')");
$ro =$agcy_ach->fetch_array();
$agency= $ro['sum'];
$agencyPol= $ro['count'];


if(isset($_POST['save'])){
    
    if(empty($_POST['amt'])){
        echo  " <script>alert('Please specify budget amount for the year '); </script>";
    }
    elseif(empty($_POST['tpol'])){
        echo  " <script>alert('Please specify total policy '); </script>";
    }
    elseif(empty($_POST['rn']) && empty($_POST['rnpercent'])){
        echo  " <script>alert('Please specify renewal value or percentage '); </script>";
    }
    elseif(empty($_POST['nb']) && empty($_POST['nbpercent'])){
        echo  " <script>alert('Please specify New business value or percentage '); </script>";
    }
    elseif(empty($_POST['year'])){
        echo  " <script>alert('Please specify the year '); </script>";
    }
    else{
    $cat=check_input($_POST['cat']);
    $bd=check_input($_POST['amt']);
    $tpol=check_input($_POST['tpol']);
    $nb=check_input($_POST['nb']);
    $rn=check_input($_POST['rn']);
    $yr=check_input($_POST['year']);
    $rnop=check_input($_POST['rnop']);
    $nbnop=check_input($_POST['nbnop']);
    
    $Pnb=check_input($_POST['nbpercent']);
    $Prn=check_input($_POST['rnpercent']);
    
    
    $Prnop=check_input($_POST['prnnop']); 
    $Pnbnop=check_input($_POST['pnbnop']);
    
    
    $sumBudget= $nb + $rn ; // sum of budget entered
    $sumPerceBud = $Pnb + $Prn; // total budget percentage
    
    $sumPol = $rnop + $nbnop; // sum of the total policy
    $sumPercPol= $Prnop+ $Pnbnop ; // sum percentage policy
    
    $YeaBudget=$con->query("SELECT count(bID) AS count FROM type WHERE type='$cat' AND year='$yr'");
    $ror=$YeaBudget->fetch_array();
    
    $bCount=$ror['count'];
    if($bCount > 0){
        
        echo  " <script>alert('The budget of $cat for year ($yr) already exists.'); </script>";
    }
       
    else{
        
        $in=$con->query("INSERT INTO type SET bID='$bid', type='$cat' ,  nb='$nb', rn='$rn',prn='$Prn', pnb='$Pnb', polrn='$rnop', ppolrn='$Prnop', polnb='$nbnop', ppolnb='$Pnbnop', year='$yr', created=now() ");
        if($in){
            echo  " <script>alert('The budget for the year $yr has been successfully created '); </script>";
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
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Budget</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="">
                                <a href="" style="color: #005983;" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
                                    <i class="icon-briefcase"></i> Create Budget
                                </a> 
                            </li>
                            <li class="active"><a href="mis.php"><i class="icon-home"></i> Back</a> </li>
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
                      <h3 class="box-title">Budget</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>NB Bgdt</th>
                                    <th>% Bgdt</th>
                                    <th>RN Bgdt</th>
                                    <th>% Bgdt</th>
                                    <th>NoP NB</th>
                                    <th>% NoP </th>
                                    <th>NoP RN</th>
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
                                    $qq=$con->query("SELECT * FROM type WHERE bID='$bid' ");
                                    while($rw = $qq->fetch_array()){
                                         $type = $rw['type'];
                                        $newB = $rw['nb'];
                                        $pnewB = $rw['pnb'];
                                        $newBP = $rw['polnb'];
                                        $newBPP = $rw['ppolnb'];
                                        $renB = $rw['rn'];
                                        $prenB = $rw['prn'];
                                        $renP = $rw['polrn'];
                                        $renPP = $rw['ppolrn'];
                                        //$tot = $newB + $renB;
                                        //$totP = $newBP + $renP;

                                    $totalNB +=$newB;
                                    $totalRN +=$renB;
                                    $totalNBP +=$newBP;
                                    $totalRNP +=$renP;
                                    $ptotalNB +=$pnewB;
                                    $ptotalRN +=$prenB;

                                ?>
                                <tr>
                                    <td>
                                        <a href="#index.php?" class=""><i class="icon-plus-sign"></i> 
                                            <?php
                                            if($type =='Financial Advisors'){echo "Bancassurance";}
                                            elseif($type =='FI'){echo "Partners - FI";}
                                            elseif($type =='HNI'){echo "Partners - HNI";}
                                            elseif($type =='Partners'){echo "Partners - Autodealership";}
                                            else{echo $type;}
                                            ?>
                                        </a>
                                    </td>
                                    <td><span class="tag tag-primary"><?php echo number_format($rw['nb']); ?></span></td>
                                    <td scope="row"> <?php echo $rw['pnb']."%"; ?> </td>
                                    <td><span class="tag tag-success"><?php echo number_format($rw['rn']); ?></span></td>
                                    <td scope="row"> <?php echo $rw['prn']."%"; ?> </td>
                                    <td><span class="tag tag-primary"><?php echo number_format($rw['polnb']); ?></span></td>
                                    <td scope="row"> <?php echo $rw['ppolnb']."%"; ?> </td>
                                    <td><span class="tag tag-success"><?php echo number_format($rw['polrn']); ?></span></td>
                                    <td scope="row"> <?php echo $rw['ppolrn']."%"; ?> </td>
                                </tr>
                                    <?php } ?> 
                                <tr>
                                    <td></td>
                                    <td><span class="btn btn-info"><?php echo number_format($totalNB); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo number_format($ptotalNB)."%"; ?></span></td>
                                    <td><span class="btn btn-info"><?php echo number_format($totalRN); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo number_format($ptotalRN)."%"; ?></span></td>
                                    <td><span class="btn btn-success"><?php echo number_format($totalNBP); ?></span> </td>
                                    <td></td>
                                    <td> <span class="btn btn-success"><?php echo number_format($totalRNP); ?></span> </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                   </div>
                </div>
                <!-- /.row -->
                
               
                
                
                
                
                
                
                
                
                
                
              
                
                
                
                
                
                
                
                
               
                
                
                 
                
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="exampleModalLabel1">New message</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form" class="form" method="post">
                                                <div class="row"> 
                                                        <div class="col-md-12 ">
                                                            <h4 class="form-section text-info"><i class="fa fa-money"></i> Budget</h4>
                                                                <div class="form-group col-md-6">
                                                                        <label>Budget Amount</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">=N=</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Budget Amount" aria-label="Amount (to the nearest naira)" name="amt" value="<?php echo $budget; ?>" required>
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>Total Policy</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Total Policy" aria-label="Amount (to the nearest naira)" value="<?php echo $budgetNoP; ?>" name="tpol" required>
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>

                                                                <h4 class="form-section text-info"><i class="fa fa-book"></i> Allocation By Business Type</h4>
                                                                <div class="form-group col-md-6">
                                                                        <label>New Business (Amount)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">=N=</span>
                                                                                <input type="number" class="form-control" placeholder=" New Business Budget" aria-label="Amount (to the nearest naira)" name="nb" onkeyup="calculatePerc()" />
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>New Business (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Percentage (to the nearest naira)" name="nbpercent" onkeyup="calculatePrice()"/>
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                        <label>Renewal (Amount)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">=N=</span>
                                                                                <input type="number" class="form-control" placeholder="Renewal Budget " aria-label="Amount (to the nearest naira)" name="rn" onkeyup="calculatePerc2()" />
                                                                                <span class="input-group-addon">.00</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>Renewal (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="rnpercent" onkeyup="calculatePrice2()" />
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>
                                                                <h4 class="form-section text-info"><i class="fa fa-list-alt"></i> Number of Policy</h4>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (NB)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="nbnop" onkeyup="calculatePercNoP()" />

                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (NB) (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="pnbnop" onkeyup="calculateNoP()" >
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>


                                                                <div class="form-group col-md-6">
                                                                        <label>NoP (Renewal)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">#</span>
                                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="rnop" onkeyup="calculatePercNoP2()" />

                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label>NoP Renewal (%)</label>
                                                                        <div class="input-group">
                                                                                <span class="input-group-addon">%</span>
                                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="prnnop" onkeyup="calculateNoP2()"  />
                                                                                <span class="input-group-addon">%</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                        <label for="issueinput5">Category</label>
                                                                        <select id="issueinput5" name="cat" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Category" required>
                                                                                <option value="">Please Choose a Category</option> 
                                                                                <option>Agency</option>
                                                                                <option value="Financial Advisors">Bancassurance</option>
                                                                                <option value="FI">Partners - FI</option>
                                                                                <option value="HNI">Partners - HNI</option>
                                                                                <option value="Partners">Partners - Autodealership</option>
                                                                                <option>Travel</option>
                                                                        </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
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


<!--                                                        <div class="form-group offset-md-3">
                                                                <button type="reset" class="btn btn-warning mr-1">
                                                                        <i class="icon-cross2"></i> Reset
                                                                </button>
                                                                <button type="submit" name="save" class="btn btn-primary">
                                                                        <i class="icon-check2"></i> Save
                                                                </button>
                                                        </div>-->

                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="save" class="btn btn-primary">Save Budget</button>
                                                </div>
                                        </form>
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
    
    

<!--    <script src="../plugins/bower_components/Chart.js/chartjs.init.js"></script>-->
    <script src="../plugins/bower_components/Chart.js/Chart.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    
    <script>
        //New Business
        function calculatePrice() {
            var percentage = $('input[name=nbpercent]').val(),
                price = $('input[name=amt]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'nb\']').val(discountPrice);
        }
        function calculatePerc() {
            var discountPrice = $('input[name=nb]').val(),    
                price = $('input[name=amt]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=nbpercent]').val(discountPerc);
        }
        
        //For Renewal
        function calculatePrice2() {
            var percentage = $('input[name=rnpercent]').val(),
                price = $('input[name=amt]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'rn\']').val(discountPrice);
        }
        function calculatePerc2() {
            var discountPrice = $('input[name=rn]').val(),    
                price = $('input[name=amt]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=rnpercent]').val(discountPerc);
        }
        
        //NOP New Business
        function calculateNoP() {
            var percentage = $('input[name=pnbnop]').val(),
                price = $('input[name=tpol]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'nbnop\']').val(discountPrice);
        }
        function calculatePercNoP() {
            var discountPrice = $('input[name=nbnop]').val(),    
                price = $('input[name=tpol]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=pnbnop]').val(discountPerc);
        }
        
        //NOP Renewal
        function calculateNoP2() {
            var percentage = $('input[name=prnnop]').val(),
                price = $('input[name=tpol]').val(),
                calcPrice = ( (price/100) * percentage ),
                discountPrice = calcPrice.toFixed(2);
            $('input[name=\'rnop\']').val(discountPrice);
        }
        function calculatePercNoP2() {
            var discountPrice = $('input[name=rnop]').val(),    
                price = $('input[name=tpol]').val(),
                calcPerc = ((discountPrice/price) * (100)),
                discountPerc = calcPerc.toFixed("2");
            $('input[name=prnnop]').val(discountPerc);
        }
        
        
    </script>
</body>

</html>


