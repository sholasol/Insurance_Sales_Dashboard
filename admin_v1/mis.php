<?php 
include 'mis_nav.php';

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];


$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='Agency' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];
$nbBudget = $rw['nb'];
$rnBudget = $rw['rn'];

//Agency Performcance
$agency_budget = $tBud; //Budget

//production
$totalNB = 0;
$totalRN = 0;

//Agency Production
$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%Agency%'");
$yr =$ach->fetch_array();
$agency= $yr['sum'];
$agencyPol= $yr['count'];
//Percentage Achieved
$PerAgcy = ($agency/$tBud)*100;


//Agency New business and Renewal
$agcy_nb=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND c_area LIKE '%Agency%'");
$ar=$agcy_nb->fetch_array();
$agcyNB = $ar['sum'];
$totalNB += $agcyNB;
$agcy_rn=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='RN' AND c_area LIKE '%Agency%'");
$ar2=$agcy_rn->fetch_array();
$agcyRN = $ar2['sum'];
$totalRN += $agcyRN;
        

//Partners Production
$ach2= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND (c_area LIKE '%Partners%' OR  c_area LIKE '%HNI%')");
$yr2 =$ach2->fetch_array();
$partner= $yr2['sum'];
$partnerPol= $yr2['count'];

//Percentage Achieved
$PerPart = ($partner/$tBud)*100;


//Partners New business and Renewal
$part_nb=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND (c_area LIKE '%Partners%' OR c_area LIKE '%HNI%') ");
$pr=$part_nb->fetch_array();
$partNB = $pr['sum'];
$totalNB += $partNB;
$part_rn=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='RN'  AND (c_area LIKE '%Partners%' OR c_area LIKE '%HNI%')");
$pr2=$part_rn->fetch_array();
$partRN = $pr2['sum'];
$totalRN += $partRN;




//Travels Production
$ach3= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND (c_area LIKE '%TRAVEL%' OR c_area LIKE '%Travel & Health%' )");
$yr3 =$ach3->fetch_array();
$travel= $yr3['sum'];
$travelPol= $yr3['count'];

//Percentage Achieved
$PerTr = ($travel/$tBud)*100;



//Travel New business and Renewal
$tr_nb=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND (c_area LIKE '%TRAVEL%' OR c_area LIKE '%Travel & Health%' ) ");
$tr=$tr_nb->fetch_array();
$trNB = $tr['sum'];
$totalNB += $trNB;
$tr_rn=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='RN' AND (c_area LIKE '%TRAVEL%' OR c_area LIKE '%Travel & Health%' )");
$tr2=$tr_rn->fetch_array();
$trRN = $tr2['sum'];
$totalRN += $trRN;


//FA or Banca Production
$ach4= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND c_area LIKE '%Financial Advisors%'");
$yr4 =$ach4->fetch_array();
$fa= $yr4['sum'];
$faPol= $yr4['count'];

//Percentage Achieved
$PerFa = ($fa/$tBud)*100;

//Bancassurance or FA New business and Renewal
$banc_nb=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='NB' AND c_area LIKE '%Financial Advisors%'  ");
$br=$banc_nb->fetch_array();
$bancNB = $br['sum'];
$totalNB += $bancNB;
$banc_rn=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND type='RN' AND c_area LIKE '%Financial Advisors%' ");
$br2=$banc_rn->fetch_array();
$bancRN = $br2['sum'];
$totalRN += $bancRN;
?>
        <!-- End Top Navigation -->
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
                        <!-- /input-group -->
                    </li>
                    
                    <li> <a href="" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu">SBUs<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="agency.php">Agency</a></li>
                            <li><a href="bancca.php">Bancassurance</a></li>
                            <li><a href="travel.php">Travel</a></li>
                            <li> <a href="" class="waves-effect">Partners <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="fi.php">FI</a></li>
                                    <li><a href="hni.php">HNI</a></li>
                                    <li><a href="partner.php">Partner - Auto Dealer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    
<!--                    <li> <a href="#" class="waves-effect "><i data-icon="&#xe008;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Reports<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="fa_week.php">Weekly</a></li>
                            <li><a href="fa_month.php">Monthly</a></li>
                            <li><a href="fa_ytd.php">YTD</a></li>
                        </ul>
                    </li>-->
                    <li><a href="logout.php" class="waves-effect"><i data-icon="&#xe045;" class="linea-icon linea-aerrow fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
                    
                </ul>
                
            </div>
        </div>
        <!-- Left navbar-header end -->



        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Homes</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active"><a href="budget.php" style="color: #000080;"><i class="icon-briefcase"></i> Create Budget</a> </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box m-b-0 bg-purple">
                            <h3 class="text-white box-title">Agency <span class="pull-right"><i class="fa fa-caret-up"></i> NoP:  <?php echo number_format($agencyPol); ?></span></h3>
                            <div id="sparkline2dash2 "></div>
                        </div>
                        <div class="white-box">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="text-muted m-t-20">Total Production</div>
                                    <h2><?php echo number_format($agency); ?></h2>
                                </div>
                                <div data-label="<?php echo number_format($PerAgcy, 2, '.', ''); ?>%" class="css-bar css-bar-0 css-bar-lg m-b-0 css-bar-purple pull-right"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box m-b-0 bg-info">
                            <h3 class="text-white box-title">Bancassurance <span class="pull-right"><i class="fa fa-caret-down"></i> NoP:  <?php echo number_format($faPol); ?></span></h3>
                            <div id="sparkline2dash" class="text-center"></div>
                        </div>
                        <div class="white-box">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="text-muted m-t-20">Total Production</div>
                                    <h2><?php echo number_format($fa); ?></h2>
                                </div>
                                <div data-label="<?php echo number_format($PerFa, 2, '.', ''); ?>%" class="css-bar css-bar-0 css-bar-lg m-b-0  css-bar-info pull-right"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box m-b-0 bg-purple">
                            <h3 class="text-white box-title">Partners <span class="pull-right"><i class="fa fa-caret-up"></i> NoP:  <?php echo number_format($partnerPol); ?></span></h3>
                            <div id="sparkline3dash"></div>
                        </div>
                        <div class="white-box">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="text-muted m-t-20">Total Production</div>
                                    <h2><?php echo number_format($partner); ?></h2>
                                </div>
                                <div data-label="<?php echo number_format($PerPart, 2, '.', ''); ?>%" class="css-bar css-bar-0 css-bar-lg m-b-0 css-bar-purple pull-right"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box m-b-0 bg-inverse">
                            <h3 class="text-white box-title">Travels <span class="pull-right"><i class="fa fa-caret-up"></i> NoP: <?php echo number_format($travelPol); ?></span></h3>
                            <div id="sparkline4dash" class="text-center"></div>
                        </div>
                        <div class="white-box">
                            <div class="row">
                                <div class="pull-left">
                                    <div class="text-muted m-t-20">Total Production</div>
                                    <h2><?php echo number_format($travel); ?></h2>
                                </div>
                                <div data-label="<?php echo number_format($PerTr, 2, '.', ''); ?>%" class="css-bar css-bar-0 css-bar-lg m-b-0 css-bar-inverse pull-right"></div>
                            </div>
                        </div>
                    </div>
                </div> 
                
                
                
                
                
                
                
                
                <div class="row">
                    <!-- .col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="white-box">
<!--                            <h3 class="box-title">New Business Budget vs Target</h3>-->
                            <ul class="list-inline text-left">
                                <li>
                                    <h3 class="box-title">New Business Budget vs Target (in Million)</h3>
                                </li>
                            </ul>
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
                    
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performing Agency</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $y= date('Y');
                                        //Top branch performance
                                        $per=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, c_area FROM `myrecord` where c_area LIKE '%Agency%' AND year='$y' 
                                                        AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') GROUP BY c_area ORDER BY sum DESC");
                                        while($r=$per->fetch_array()){
                                        $count=$r['count'];
                                        ?>
                                        <tr>
                                            <th><a href="" class="btn-link" style="font-size: 16px;"> <?php echo $r['c_area']; ?></a></th>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($r['sum']); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($count); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performing Partners</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Partners Performer
                                        
                                        $supPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where
                                                year='$y' AND (c_area LIKE '%Partners%' OR c_area LIKE '%HNI%') GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
                                        while($sr=$supPer->fetch_array()){
                                            $count2=$sr['count'];
                                            $ssup = $sr['agent_name'];
                                            $samt = $sr['sum'];
                                        ?>
                                        <tr>
                                            <td><a href="" class="btn-link" style="font-size: 15px;"> <?php echo $sr['agent_name']; ?></a></td>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($samt); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="text-danger"><span style="font-size: 15px;"> <?php echo number_format($count2); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Top Performing Travels</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Agent Performer
                                        $agtPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where year='$y' AND c_area LIKE '%TRAVEL%' AND agent_name !=''  GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
                                        while($srr=$agtPer->fetch_array()){
                                            $count3=$srr['count'];
                                        ?>
                                        <tr>
                                            <td><a href="" class="btn-link" style="font-size: 15px;"> <?php echo $srr['agent_name']; ?></a></td>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($srr['sum']); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($count3); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   
                   <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Top Performing Bancassurance</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Agent Performer
                                        $faPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code, c_area FROM `myrecord` where year='$y' AND c_area LIKE '%Financial Advisors%'
                                         AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='FA Lagos' OR branch='FA PH' OR branch='FA Abuja' )  GROUP BY c_area ORDER BY sum DESC LIMIT 5 ");
                                        while($fr=$faPer->fetch_array()){
                                            $count4=$fr['count'];
                                        ?>
                                        <tr>
                                            <td><a href="" class="btn-link" style="font-size: 15px;"> <?php echo $fr['c_area']; ?></a></td>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($fr['sum']); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($count4); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
<!--            <footer class="footer text-center"> 2017 &copy;  </footer>-->
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
    <script src="../js/widget.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.min.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.jquery.min.js"></script>
    
    <script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <?php 
    /*
    $a=$totalNB/1000000 ;
    $b= $totalRN /1000000 ;
    
    $x= $nbBudget /1000000;
    $y= $rnBudget /1000000;
    */
    ?>
    <?php 
 
              $x=$totalNB /1000000;
            $a=$nbBudget /1000000; //Agency budget
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
            
            
            
            
            
            $b=$nbBudget /1000000;
            $y=$totalRN /1000000;
            
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
</body>

</html>