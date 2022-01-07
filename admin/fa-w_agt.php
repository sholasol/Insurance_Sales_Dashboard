<?php 
include 'mis_nav.php';
include 'fa_side.php';

$b_id= $_GET['budg'];
$ya= $_GET['year'];
$bran= $_GET['bran'];
$code= $_GET['code'];

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

$ag = $con->query("SELECT name, supervisor FROM agent WHERE agent_code='$code'");
$r=$ag->fetch_array();
$naM = $r['name'];
$sup= $r['supervisor'];


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
<!--                            <li class="active"><a href="m_supwise.php?&bran=<?php echo $bran; ?>&budg=<?php echo $b_id; ?>&year=<?php echo $ya; ?>&sup=<?php echo $sup; ?>&m=<?php echo $m ?>"><i class="icon-home"></i> Back</a> </li>-->
                            <?php
                            if($bran =='FA Lagos'){
                                            echo "<a href='fa-w_bran_detail.php?b=FLAG&frm=$frm&to=$t2&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }elseif($bran=='FA Abuja'){
                                            echo "<a href='fa-w_bran_detail.php?b=FABJ&frm=$frm&to=$t2&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        elseif($bran=='FA PH'){
                                            echo "<a href='fa-w_bran_detail.php?b=FPH&frm=$frm&to=$t2&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }else{
                                            echo "<a href='fa-w_bran_detail.php&frm=$frm&to=$t2&r=$rol' class='card-title'> <i class='icon-home'></i> Back</a>";
                                        }
                                        
                            ?>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
<!--                <div class="row">
                     .col 
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Year to Day Production Report</h3>
                            <ul class="list-inline text-right">
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #00b5c2;"></i>iPhone</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #f75b36;"></i>iPad</h5>
                                </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5" style="color: #2c5ca9;"></i>iPod</h5>
                                </li>
                            </ul>
                            <div id="extra-area-chart" style="height: 356px;"></div>
                        </div>
                    </div>
                     /.col 
                    <div class="col-md-5 col-sm-6">
                        <div class="row">
                             .col 
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-success">
                                    <h1 class="text-white counter"><?php echo number_format($tBud); ?></h1>
                                    <p class="text-white">Budget</p>
                                </div>
                            </div>
                             /.col 
                             .col 
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-inverse">
                                    <h1 class="text-white counter"><?php echo number_format($rAmount); ?></h1>
                                    <p class="text-white">Budget Achieved</p>
                                </div>
                            </div>
                             /.col 
                             .col 
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-info">
                                    <h1 class="counter text-white"><?php echo number_format($tPol); ?></h1>
                                    <p class="text-white">NoP Budget</p>
                                </div>
                            </div>
                             /.col 
                             .col 
                            <div class="col-md-6 col-sm-12">
                                <div class="white-box text-center bg-purple">
                                    <h1 class="text-white counter"><?php echo number_format($polc); ?></h1>
                                    <p class="text-white">NoP Achieved</p>
                                </div>
                            </div>
                             /.col 
                             .col 
                            <div class="col-md-12 col-sm-12">
                                <div class="white-box">
                                    <h3 class="box-title">Number of Contributors (<h3 align="center" style="color: #fff; font-size: 17px;">RSEs</h3>)</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class="counter"><?php echo number_format($totAgt); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                             /.col 
                        </div>
                    </div>
                </div>-->
                <!--row -->
                
                
                
                
                <div class="row">
                   <div class="col-md-12 col-xs-12 col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title"><?php echo $naM ?>'s Production Between <i class="fa fa-calendar"></i> <?php echo $frm." & ".$t2; ?></h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" width="100%">
                                                <thead>
                                    <tr>
                                      <th><strong></strong></th>
                                      <th><strong>Policy</strong></th>
                                      <th><strong>Receipt No.:</strong></th>
                                      <th><strong>Debit Note</strong></th>
                                      <th><strong>Product</strong></th>
                                      <th><strong>Receipt Amount</strong></th>
                                      <th><strong>Date</strong></th> 
                                    </tr>
                                  </thead>
                                  <tbody>
                                     <?php 
                                     $amt=0;
                                     $query=$con->query("SELECT distinct r_number, policy_no, id, month, year, r_amount,   debit_note, product from myrecord WHERE receipt_date BETWEEN '$frm' AND '$t2' AND agent_code='$code' AND year='$ya' ORDER BY r_amount DESC ");
                                     $counter=0;
                                     while ($row =$query->fetch_array()) {
                                     $amount= number_format($row['r_amount']); 
                                     $id=$row['id'];
                                     $mon=$row['month'];
                                     $sum= $row['r_amount'];
                                     $year=$row['year'];
                                     $amt += $sum;
                                     ?>
                                    <tr class="gradeX">
                                      <th><strong><?php echo ++$counter; ?></strong></th>
                                      <th><strong><?php echo $row['policy_no']; ?></strong></th>
                                      <th><strong><?php echo $row['r_number']; ?></strong></th>
                                      <th><strong><?php echo $row['debit_note']; ?></strong></th>
                                      <th><strong><?php echo $row['product']; ?></strong></th>
                                      <th class="center"><strong><span class=" text-primary"><?php echo number_format($row['r_amount']); ?></span> </strong></strong></th>
                                      <th class="center"><strong>
                                              <span class="text-success">
                                                    <?php 
                                                    if($mon ==1){echo "Jan".", ".$year; }if($mon ==2){
                                                       echo "Feb".", ".$year; } if($mon ==3){echo "Mar".", ".$year;
                                                    }if($mon ==4){ echo "Apr".", ".$year; }if($mon ==5){ echo "May".", ".$year;
                                                    }if($mon ==6){ echo "Jun".", ".$year; }if($mon ==7){ echo "Jul".", ".$year;
                                                    }if($mon ==8){ echo "Aug".", ".$year;}if($mon ==9){ echo "Sep".", ".$year; }
                                                    if($mon ==10){ echo "Oct".", ".$year; } if($mon ==11){ echo "Nov".", ".$year;
                                                    }if($mon ==12){ echo "Dec".", ".$year; }

                                                    ?>
                                              </span>
                                      </strong></th>
                                     </tr><?php } ?>
                                     <tr>
                                         <th colspan="5">Total</strong></th>
                                         <th><strong><span class="btn btn-info"><?php echo number_format($amt); ?></span></strong></th>
                                         <th><strong></strong></th>
                                     </tr>
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
