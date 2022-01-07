<?php 
include 'mis_nav.php';
include 'mis_side.php';

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT * FROM type WHERE bID='$bid' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
                  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH') ");
$yr =$ach->fetch_array();

//
$ach2=$con->query("SELECT count(distinct agent_code) AS count FROM myrecord where 
                  year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR
                  branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')");
$yr2 =$ach2->fetch_array();
$totAgt = $yr2['count'];

$polc=$yr['count'];
$rAmount = $yr['sum'];
?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Agency</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active"><a href="mis.php"><i class="icon-home"></i> Back</a> </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                
                
                
                
                
                <!-- /.row -->
                <div class="row">
                    <!-- .col -->
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Year to Day </h3>
                            <ul class="list-inline text-right">
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #00b5c2;"></i>New Business</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #f75b36;"></i>Renewal</h5>
                                </li>
<!--                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #2c5ca9;"></i>iPod</h5>
                                </li>-->
                            </ul>
                            <div id="charta" style="height: 356px;"></div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-5 col-sm-6">
                        <div class="row">
                            <!-- .col -->
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-success">
                                    <h1 class="text-white counter"><?php echo number_format($tBud); ?></h1>
                                    <p class="text-white">Budget</p>
                                </div>
                            </div>
                            <!-- /.col -->
                            <!-- .col -->
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-inverse">
                                    <h1 class="text-white counter"><?php echo number_format($rAmount); ?></h1>
                                    <p class="text-white">Budget Achieved</p>
                                </div>
                            </div>
                            <!-- /.col -->
                            <!-- .col -->
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-info">
                                    <h1 class="counter text-white"><?php echo number_format($tPol); ?></h1>
                                    <p class="text-white">NoP Budget</p>
                                </div>
                            </div>
                            <!-- /.col -->
                            <!-- .col -->
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-purple">
                                    <h1 class="text-white counter"><?php echo number_format($polc); ?></h1>
                                    <p class="text-white">NoP Achieved</p>
                                </div>
                            </div>
                            <!-- /.col -->
                            <!-- .col -->
                            <div class="col-md-12 col-sm-12">
                                <div class="white-box">
                                    <h3 class="box-title">Number of Contributors (RSE)</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class="counter"><?php echo number_format($totAgt); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
                <!--row -->
                
                
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
                                <div class="col-lg-3 col-sm-6  b-0">
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
                                <div class="col-lg-3 col-sm-6 row-in-br">
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
                    
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (Branch)</h3>
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
                    
                    
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (Supervisor)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Supervisor</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Supervisor Performer
                                        $supNoP=0; //total supervisor and downline nop 
                                        $supAmout=0; 
                                        $supPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where year='$y' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE role='Supervisor' AND ( branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')) GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
                                        while($sr=$supPer->fetch_array()){
                                            $count2=$sr['count'];
                                            $ssup = $sr['agent_name'];
                                            $samt = $sr['sum'];
                                           $supAmout += $samt;
                                           $supNoP += $count2;
                                          //Supervisor Downline production
                                          $supDownline= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where year=2018 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE supervisor='$ssup' AND ( branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')) ");
                                          $srrr=$supDownline->fetch_array();
                                          $downAmount=$srrr['sum'];
                                          $downNop = $srrr['count'];
                                          
                                          $supAmout += $downAmount;
                                          $supNoP += $downNop;
                                        ?>
                                        <tr>
                                            <td><a href="" class="btn-link" style="font-size: 15px;"> <?php echo $sr['agent_name']; ?></a></td>
                                            <td class="text-center">
                                                <div class=""><span style="font-size: 15px;">  <?php echo number_format($supAmout); ?></span></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="label label-table label-success"><span style="font-size: 15px;"> <?php echo number_format($supNoP); ?></span></div>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (RSE)</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>RSEs</th>
                                            <th class="text-center">Achieved</th>
                                            <th class="text-center">NoP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Top Agent Performer
                                        $agtPer=$con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count, agent_name, agent_code FROM `myrecord` where year=2018 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE role='Agent' AND ( branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' OR branch='Ensure Abuja' OR branch='Ensure PH')) GROUP BY agent_code ORDER BY sum DESC LIMIT 5 ");
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
    <?php
    $qu=$con->query("SELECT proclass_bud.nb, myrecord.r_amount FROM proclass_bud, myrecord WHERE proclass_bud.product_class=myrecord.product_class AND myrecord.year='$y' GROUP BY myrecord.product_class");
    $rr=$qu->fetch_array();
    $phpdo= json_encode($rr);
    ?>
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
    <!--  ChartJS JavaScript -->
    
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
        element: 'charta',
        data: [{
                    period: '2010',
                    iphone: 0,
                    ipad: 0
                }, {
                    period: '2011',
                    iphone: 50,
                    ipad: 15
                }, {
                    period: '2012',
                    iphone: 20,
                    ipad: 50
                }, {
                    period: '2013',
                    iphone: 60,
                    ipad: 12
                }, {
                    period: '2014',
                    iphone: 30,
                    ipad: 20
                }, {
                    period: '2015',
                    iphone: 25,
                    ipad: 80
                }, {
                    period: '2016',
                    iphone: 10,
                    ipad: 10
                }


                ],
                lineColors: ['#f75b36', '#00b5c2'],
                xkey: 'period',
                ykeys: ['iphone', 'ipad'],
                labels: ['Site A', 'Site B'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
        
    }); 
    
    
    
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
