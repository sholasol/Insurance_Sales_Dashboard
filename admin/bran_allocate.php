<?php 
include 'mis_nav.php';
include 'fa_side.php';

$rol = $_GET['r'];
$prd =$_GET['p'];
$bra=$_GET['b'];
if($rol =='FA'){
    $ty = 'Financial Advisors'; 
    $brac1 = 'FA Abuja';
    $brac2 = "'%FA Lagos%' OR '%FA Abuja%' OR '%FA PH%'OR branch LIKE '%FA Ikeja%' OR  branch LIKE '%FA Broad Street%' OR branch LIKE '%FA Victoria Island%'";
    $branch ="'FA Lagos'"; // This is Branches for financial advisor (This should be edited in case FA are located branch wise )
    $brac3 = 'FA PH';
    
}
if($rol =='Agency'){
    $ty = 'Agency';
    $brac1 = 'Ensure Abuja';
    $brac2 = "'%Ensure Lagos%' OR branch LIKE '%Ensure Ikeja%' OR  branch LIKE '%Ensure Broad Street%' OR branch LIKE '%Ensure Victoria Island%'";
    $brac3 = 'Ensure PH';
    $branch ="'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH'";
}
if($rol =='TRAVEL'){$ty = 'TRAVEL';}
if($rol =='Partners'){$ty = 'Partners';}


//checking the budget for a specific branch and product

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];
$budget =$brow['amount'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='$ty' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$q1[] = 0;
$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch = $branch) GROUP BY product_class");
while($yr =$ach->fetch_array()){

$xxx= $yr['sum']/1000000;
 $q1[] = $xxx;
 
 $polc=$yr['count'];
$rAmount = $yr['sum'];
}
//
$q2[] = 0;
$ach2=$con->query("SELECT sum(r_amount) AS sum, count(distinct agent_code) AS count FROM myrecord where 
                  year='$y' AND type='RN' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch= $branch) GROUP BY product_class");
while($yr2 =$ach2->fetch_array()){
$totAgt = $yr2['count'];
$xxx2= $yr2['sum']/1000000;
 $q2[] = $xxx2;
}



//$bid= $_GET['budg'];
//$ya= $_GET['year'];




$tB=$con->query("SELECT sum(nb), sum(pnb), sum(rn) ,sum(prn),sum(polnb),sum(ppolnb),sum(polrn),sum(ppolrn) 
               FROM branch_bud WHERE bID='$bid' AND branch='$bra' ");
    while($rc=$tB->fetch_array()){
        $nbBud=$rc['sum(nb)'];
        $PnbB=$rc['sum(pnb)'];
        $rnBud=$rc['sum(rn)'];
        $PrnB=$rc['sum(prn)'];
        $nbPol=$rc['sum(polnb)'];
        $PnbPol=$rc['sum(ppolnb)'];
        $rnPol=$rc['sum(polrn)'];
        $PrnPol=$rc['sum(ppolrn)'];
        
        $ttBud =$nbBud + $rnBud;
        $ttNop= $nbPol + $rnPol;
    }
    
    $balBud = $tBud - $ttBud;
    $balNop = $tPol - $ttNop;

$prd1=$con->query("SELECT product_class FROM proclass_bud WHERE prID='$prd' ");
$roo=$prd1->fetch_array();
$product = $roo['product_class'];

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

if(isset($_POST['save'])){
    if(empty($_POST['branch'])){
        echo  " <script>alert('Please select a branch '); </script>";
    }
    elseif(empty($_POST['prod'])){
        echo  " <script>alert('Please select a product '); </script>";
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
    $b_id=check_input($_POST['bid']);
    $brn=check_input($_POST['branch']);
    $pro=check_input($_POST['prod']);
    $nb=check_input($_POST['nb']);
    $rn=check_input($_POST['rn']);
    $yr=check_input($_POST['year']);
    $rnop=check_input($_POST['rnop']);
    $nbnop=check_input($_POST['nbnop']);
    
    $Pnb=check_input($_POST['nbpercent']);
    $Prn=check_input($_POST['rnpercent']);
    
    
    $Prnop=check_input($_POST['prnnop']); 
    $Pnbnop=check_input($_POST['pnbnop']);
    
    $brChk = $con->query("SELECT count(bbID) AS count FROM branch_bud WHERE branch ='$brn' AND bID='$b_id' AND product_class='$pro'");
    $bro=$brChk->fetch_array();
    
    $co=$bro['count'];
    
    if($co > 0){
        
        echo  " <script>alert('$pro budget for $brn Already exists '); </script>";
    }  else {
        $in=$con->query("INSERT INTO branch_bud SET bID='$b_id', branch='$brn',product_class='$pro', nb='$nb', pnb='$Pnb', rn='$rn',prn='$Prn', polnb='$nbnop', ppolnb='$Pnbnop', polrn='$rnop', ppolrn='$Prnop',  year='$yr', created=now() ");
        if($in){
            echo  " <script>alert('$pro budget for $brn has been successfully created ');  </script>"; //window.location='index.php?branch_detail'
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
                                    //if($rol =='FA'){echo "<a href='fa_budget.php'><i class='icon-home'></i> Back</a> ";}
                                    if($rol =='Agency'){echo "<a href='branchbud.php?r=$rol'><i class='icon-home'></i> Back</a> ";}
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
                 <div class="row">
                     <div class="col-md-5">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="white-box">
                                <h3 class="box-title m-b-0">Yearly Budget for <?php echo $bra; ?> </h3>
                                <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                              <tr>
                                                  <td>Product</td>
                                                  <td>NB</td>
                                                  <td>RN</td>
                                              </tr>
                                              <?php 
                                                $p5=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='$ty'");
                                                while($rr5=$p5->fetch_array()){
                                                    $pr5=$rr5['product_class'];
                                                    $prd5=$rr5['prID'];
                                                    
                                                    $pp5=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr5' AND bID='$bid' AND branch='$bra'");
                                                    $ro5=$pp5->fetch_array();
                                                    $nb5=$ro5['nb'];
                                                    $rn5=$ro5['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr5;?></span> &nbsp;&nbsp;&nbsp;
                                                      
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb5); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn5); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                            </div>
                        </div>
                         
                     </div>
                     <div class="white-box col-md-7">
                         <h3 class="box-title"></h3>
                         <form id="form" class="form" method="post">
                         <h4 class="form-section">
                             <i class="icon-money"></i> Budget</h4>
                             <input type="hidden" name="bid" value="<?php echo $bid ?>"/>
                                    <div class="col-md-12">
                                         <a class="btn btn-info col-md-6"><span style="color: #fff;">Budget Balance: <?php echo number_format($balBud) ?></span></a>
                                         <a class="btn btn-warning col-md-6"><span style="color: #fff;">Policy Balance: <?php echo number_format($balNop) ?></span></a>
                                     </div>
                                        <div class="form-group col-md-6">
                                                <label>Budget Amount</label>
                                                <div class="input-group">
                                                        <span class="input-group-addon">=N=</span>
                                                        <input type="number" id="amount" class="form-control" value="<?php echo $balBud; ?>" placeholder="Budget Amount" aria-label="Amount (to the nearest naira)" name="amt" required>
                                                        <span class="input-group-addon">.00</span>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                                <label>Total Policy</label>
                                                <div class="input-group">
                                                        <span class="input-group-addon">#</span>
                                                        <input type="number" id="amount" class="form-control" value="<?php echo $balNop; ?>" placeholder="Total Policy" aria-label="Amount (to the nearest naira)" name="tpol" required>
                                                        <span class="input-group-addon">.00</span>
                                                </div>
                                        </div>
                                        <h4 class="form-section"><i class="icon-home"></i>Branch</h4>
                                        <div class="form-group col-md-6">
                                                <label>Branch</label>
                                                <select id="issueinput5" name="branch" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Branch" required>
                                                    <option><?php echo $bra; ?></option>
                                                </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                                <label>Product</label>
                                                <select id="issueinput5" name="prod" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Product" required>
                                                    <option><?php echo $product; ?></option>
                                                </select>
                                        </div>

                                        <h4 class="form-section"><i class="icon-clipboard4"></i>Allocation By Business Type</h4>
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
                                        <h4 class="form-section"><i class="icon-list-alt"></i>Number of Policy</h4>
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
                                        <div class="form-group col-md-12">
                                                <label for="issueinput5">Year</label>
                                                <select id="issueinput5" name="year" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Year" required>
                                                        <option><?php echo $y ?></option> 
                                                        
                                                </select>
                                        </div>
                         `              <div class="modal-footer">
                                            <button type="reset" class="btn btn-default" >Reset</button>
                                            <button type="submit" name="save" class="btn btn-primary">Save Budget</button>
                                        </div>
                         
                         </form>
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
    
    <!-- Line Charts-->
    <script src="..scripts/charts/chartjs/line/line.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-area.js" type="text/javascript"></script>
    <script src="..scripts/charts/chartjs/line/line-stacked-area.js" type="text/javascript"></script>

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


