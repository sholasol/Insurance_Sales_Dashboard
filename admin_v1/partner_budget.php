<?php 
include 'mis_nav.php';
$b=$_GET['r'];
if($b=='Partners'){
    $b='Partners';
    include 'partner_side.php';
}
if($b=='FI'){
    $b='FI';
    include 'fi_side.php';
}
if($b=='HNI'){
    $b='HNI';
    include 'hni_side.php';
}


$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];


$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='FA' AND year='$y'");
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

//Partners Production
$ach4= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%Partners%'");
$yr4 =$ach4->fetch_array();
$pAmt= $yr4['sum'];
$pPol= $yr4['count'];
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                 <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $b ?></h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <?php 
                                    if($b =='FI'){echo "<a href='fi.php'><i class='icon-home'></i> Back</a> ";}
                                    if($b =='HNI'){echo "<a href='hni.php'><i class='icon-home'></i> Back</a> ";}
                                    if($b =='Partners'){echo "<a href='partner.php'><i class='icon-home'></i> Back</a> ";}
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
                                            <h4 class="counter text-right m-t-10 text-info"><?php echo number_format($pAmt); ?></h4>
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
                                            <h4 class="counter text-right m-t-15 text-success"><?php echo number_format($pPol); ?></h4>
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
<!--                      <h3 class="box-title">
                          Agency Budget Breakdown
                          <div class="pull-right">
                            <a href="create_budget.php" class="card-title  pad"><i class="fa fa-calendar"></i> Create Yearly Budget</a>
                            <a href="monthlybud.php" class="card-title  pad"><i class="icon-clock"></i> Create Monthly Budget</a>
                            <a href="branchbud.php" class="card-title  pad"><i class="icon-home"></i> Create Branch Budget</a>
                          </div>
                      </h3>-->
                      <h3 class="box-title"> Budget
                        <div class="col-md-2 col-sm-3 col-xs-6 pull-right">
                          <select class="form-control pull-right b-none" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                              <option>Create Budget</option>
                              <option value="create_budget.php?r=<?php echo $b ?>">Yearly Budget</option>
                              <option value="branchbud.php?r=<?php echo $b ?>">Branch Budget</option>
                              <option value="monthlybud.php?r=<?php echo $b ?>">Monthly Budget</option>
                          </select>
                        </div>
                      </h3>
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
                                    $ppolnb=0;
                                    $ppolrn=0;
                                    $tt=0;
                                    $qq=$con->query("SELECT * FROM proclass_bud WHERE bID='$bid' AND type='$b' ");
                                    while($rw = $qq->fetch_array()){
                                        $prod = $rw['product_class'];
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
                                    $ptotalRN +=$pnewB;
                                    $ppolnb += $rw['ppolnb'];
                                    $ppolrn += $rw['ppolrn'];
                                    $tt=  $totalNB  +$totalRN;                     
                                ?>
                                <tr>
                                    <td>
                                        <span class="text-primary">  <?php echo $prod; ?>
                                            <a href="#" class="" title="Edit product budget"><!--<i class="icon-pencil"></i>--></a>
                                        </span>
                                    </td>
                                    <td><span class="text-primary"><?php echo number_format($rw['nb']); ?></span></td>
                                    <td scope="row"><span class="text-info"> <?php echo $rw['pnb']."%"; ?></span> </td>
                                    <td><span class="text-danger"><?php echo number_format($rw['rn']); ?></span></td>
                                    <td scope="row"><span class="text-info"> <?php echo $rw['prn']."%"; ?> </span></td>
                                    <td><span class="text-primary"><?php echo number_format($rw['polnb']); ?></span></td>
                                    <td scope="row"><span class="text-info"> <?php echo $rw['ppolnb']."%"; ?></span> </td>
                                    <td><span class="text-danger"><?php echo number_format($rw['polrn']); ?></span></td>
                                    <td scope="row"><span class="text-info"> <?php echo $rw['ppolrn']."%"; ?></span> </td>
                                </tr>
                                    <?php } ?> 
                                <tr>
                                    <td></td>
                                    <td><span class="btn btn-primary"><?php echo number_format($totalNB); ?> </span></td>
                                    <td><span class="btn btn-info"><?php echo number_format($ptotalNB); ?></span></td>
                                    <td><span class="btn btn-danger"><?php echo number_format($totalRN); ?></span> </td>
                                    <td><span class="btn btn-info"><?php echo number_format($ptotalRN); ?></span></td>
                                    <td><span class="btn btn-success"><?php echo number_format($totalNBP); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo $ppolnb."%"; ?></span></td>
                                    <td><span class="btn btn-info"><?php echo number_format($totalRNP); ?></span> </td>
                                    <td><span class="btn btn-primary"><?php echo $ppolrn."%"; ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="btn btn-info"><strong>Total Allocated Budget</strong></span></td>
                                    <td colspan="8"><span class="col-md-12 btn btn-info">=N= <?php echo number_format($tt); ?></span></td>
                                </tr>
                            </tbody>
                        </table>
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
    
    
</body>

</html>


