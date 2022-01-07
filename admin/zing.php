﻿<?php 
include_once "../db.php";
session_start();

if (isset($_SESSION["id"])){
    $user_id = $_SESSION["id"];
    $userQuery = "SELECT * FROM users WHERE userID = '$user_id'";
    $result = mysqli_query($con, $userQuery);
    $user = mysqli_fetch_assoc($result);
    $name=$user['name'];
    $uid=$user['userID'];
    $email=$user['email'];
}else{
    header('Location:../index.php');
}


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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>Ensure | RBD</title>
    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--Data table-->
    <link href="../plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="../css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="../css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="home.php"><span class="hidden-xs"><i class="fa fa-bar-chart"></i> RBD RETAIL MIS</span></a></div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <!-- /.dropdown -->
                    <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-note"></i>
          <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
          </a>
                        <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                            <li>
                                <a href="#">
                                    <div>
                                        <p> <strong>Task 1</strong> <span class="pull-right text-muted">40% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p> <strong>Task 2</strong> <span class="pull-right text-muted">20% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"> <span class="sr-only">20% Complete</span> </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#"> <strong>See All Tasks</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                        <!-- /.dropdown-tasks -->
                    </li>
                    <!-- /.dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="../plugins/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $name ?></b> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="home.php"><i class="ti-wallet"></i> Commission Portal</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        
        
        
        
        
        
        
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
                            <li><a href="">Bancassurance</a></li>
                            <li><a href="">Travel</a></li>
                            <li> <a href="" class="waves-effect">Partners <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="">FI</a></li>
                                    <li><a href="">HNI</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    
                    <li> <a href="#" class="waves-effect "><i data-icon="&#xe008;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Reports<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="week.php">Weekly</a></li>
                            <li><a href="month.php">Monthly</a></li>
                            <li><a href="ytd.php">YTD</a></li>
                            <li> <a href="" class="waves-effect">YTD <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="">Banccassurance</a></li>
                                    <li><a href="">HNI</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="" class="waves-effect"><i data-icon="&#xe045;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Create Budget</span></a></li>
                    
                    <li><a href="logout.php" class="waves-effect"><i data-icon="&#xe045;" class="linea-icon linea-aerrow fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
                    
                </ul>
                
            </div>
        </div>
        <!-- Left navbar-header end -->
        
        
        
        <!-- Page Content -->
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
                <!-- row -->
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Line Chart</h3>
                            <div>
                                <div id="myChart" style="height: 500px;"></div>
<!--                                <canvas id="chart1" height="150"></canvas>-->
                            </div>
                        </div>
                    </div>
<!--                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Bar Chart</h3>
                            <div>
                                <div id="myChart2" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>-->
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="white-box">
                            <h3 class="box-title">Pie Chart</h3>
                            <div>
                                <div id="myChart2" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="white-box">
                            <h3 class="box-title">Doughnut Chart</h3>
                            <div>
                                <div id="myChart3" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="white-box">
                            <h3 class="box-title">Group Column Chart</h3>
                            <div>
                                <div id="myChart4" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="white-box">
                            <h3 class="box-title">Radar Chart</h3>
                            <div>
                                <canvas id="chart6" height="150"></canvas>
                            </div>
                        </div>
                    </div>
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
            <footer class="footer text-center"> 2017 &copy; Pixel Admin brought to you by wrappixel.com </footer>
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
<!--    <script src="../plugins/bower_components/Chart.js/chartjs.init.js"></script>-->
    <script src="../plugins/bower_components/Chart.js/Chart.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- Zing chart  -->
    <script src="../plugins/bower_components/zing/zingchart.min.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.jquery.min.js"></script>
    
   
    
    
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
          "margin": "0 0 0 0"
        },
        "scale": {
          "size-factor": 1.25,
          "offset-y": 120
        },
        "tooltip": {
          "background-color": "black"
        },
        "scale-r": {
          "values": "0:100:10",
          "border-color": "#b3b3b3",
          "border-width": "2",
          "background-color": "#eeeeee,#b3b3b3",
          "ring": {
            "size": 10,
            "offset-r": "130px",
            "rules": [{
              "rule": "%v >=0 && %v < 20",
              "background-color": "#FB0A02"
            }, {
              "rule": "%v >= 20 && %v < 40",
              "background-color": "#EC7928"
            }, {
              "rule": "%v >= 40 && %v < 60",
              "background-color": "#FAC100"
            }, {
              "rule": "%v >= 60 && %v < 80",
              "background-color": "#B1AD00"
            }, {
              "rule": "%v >= 80",
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
          "text": "Very Low",
          "backgroundColor": "#FB0A02",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#ea0901",
            "text": "< 80 <br>Units",
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
          "text": "Low",
          "backgroundColor": "#EC7928",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#da6817",
            "text": "> 60 < 80<br>Units",
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
            "text": "> 40 < 60<br>Units",
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
          "text": "High",
          "backgroundColor": "#B1AD00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#a09c00",
            "text": "> 20 < 40<br>Units",
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
          "text": "Very High",
          "backgroundColor": "#348D00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#237b00",
            "text": "< 20<br>Units",
            "shadow": 0
          }
        }],
        "series": [{
          "values": [90],
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
          "values": "0:100:10",
          "border-color": "#b3b3b3",
          "border-width": "2",
          "background-color": "#eeeeee,#b3b3b3",
          "ring": {
            "size": 10,
            "offset-r": "130px",
            "rules": [{
              "rule": "%v >=0 && %v < 20",
              "background-color": "#FB0A02"
            }, {
              "rule": "%v >= 20 && %v < 40",
              "background-color": "#EC7928"
            }, {
              "rule": "%v >= 40 && %v < 60",
              "background-color": "#FAC100"
            }, {
              "rule": "%v >= 60 && %v < 80",
              "background-color": "#B1AD00"
            }, {
              "rule": "%v >= 80",
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
          "text": "Very Low",
          "backgroundColor": "#FB0A02",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#ea0901",
            "text": "< 80 <br>Units",
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
          "text": "Low",
          "backgroundColor": "#EC7928",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#da6817",
            "text": "> 60 < 80<br>Units",
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
            "text": "> 40 < 60<br>Units",
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
          "text": "High",
          "backgroundColor": "#B1AD00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#a09c00",
            "text": "> 20 < 40<br>Units",
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
          "text": "Very High",
          "backgroundColor": "#348D00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#237b00",
            "text": "< 20<br>Units",
            "shadow": 0
          }
        }],
        "series": [{
          "values": [50],
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
          "margin": "0 0 0 0"
        },
        "scale": {
          "size-factor": 1.25,
          "offset-y": 120
        },
        "tooltip": {
          "background-color": "black"
        },
        "scale-r": {
          "values": "0:100:10",
          "border-color": "#b3b3b3",
          "border-width": "2",
          "background-color": "#eeeeee,#b3b3b3",
          "ring": {
            "size": 10,
            "offset-r": "130px",
            "rules": [{
              "rule": "%v >=0 && %v < 20",
              "background-color": "#FB0A02"
            }, {
              "rule": "%v >= 20 && %v < 40",
              "background-color": "#EC7928"
            }, {
              "rule": "%v >= 40 && %v < 60",
              "background-color": "#FAC100"
            }, {
              "rule": "%v >= 60 && %v < 80",
              "background-color": "#B1AD00"
            }, {
              "rule": "%v >= 80",
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
          "text": "Very Low",
          "backgroundColor": "#FB0A02",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#ea0901",
            "text": "< 80 <br>Units",
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
          "text": "Low",
          "backgroundColor": "#EC7928",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#da6817",
            "text": "> 60 < 80<br>Units",
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
            "text": "> 40 < 60<br>Units",
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
          "text": "High",
          "backgroundColor": "#B1AD00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#a09c00",
            "text": "> 20 < 40<br>Units",
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
          "text": "Very High",
          "backgroundColor": "#348D00",
          "tooltip": {
            "padding": 10,
            "backgroundColor": "#237b00",
            "text": "< 20<br>Units",
            "shadow": 0
          }
        }],
        "series": [{
          "values": [30],
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
      id: 'myChart3',
      data: myConfig,
    });
  </script>
  
   <script>
    var myConfig = {
      "type": "bar3d",
      "background-color": "#fff",
      "3d-aspect": {
        "true3d": 0,
        "y-angle": 10,
        "depth": 10
      },
      "title": {
        "text": "Product Sales Comparison",
        "height": "40px",
        "font-weight": "normal",
        "text-color": "#ffffff"
      },
      "legend": {
        "layout": "float",
        "background-color": "none",
        "border-color": "none",
        "item": {
          "font-color": "#333"
        },
        "x": "37%",
        "y": "10%",
        "width": "90%",
        "shadow": 0
      },
      "plotarea": {
        "margin": "95px 35px 50px 70px",
        "background-color": "#fff",
        "alpha": 0.3
      },
      "scale-y": {
        "background-color": "#fff",
        "border-width": "1px",
        "border-color": "#333",
        "alpha": 0.5,
        "format": "$%v",
        "guide": {
          "line-style": "solid",
          "line-color": "#333",
          "alpha": 0.2
        },
        "tick": {
          "line-color": "#333",
          "alpha": 0.2
        },
        "item": {
          "font-color": "#333",
          "padding-right": "6px"
        }
      },
      "scale-x": {
        "background-color": "#fff",
        "border-width": "1px",
        "border-color": "#333",
        "alpha": 0.5,
        "values": ["January", "February", "March", "April", "May", "June"],
        "guide": {
          "visible": false
        },
        "tick": {
          "line-color": "#333",
          "alpha": 0.2
        },
        "item": {
          "font-size": "11px",
          "font-color": "#333"
        }
      },
      "series": [{
        "values": [22650, 18750, 29050, 28745, 31500, 31625],
        "text": "Product 1",
        "background-color": "#03A9F4 #4FC3F7",
        "border-color": "#03A9F4",
        "legend-marker": {
          "border-color": "#03A9F4"
        },
        "tooltip": {
          "background-color": "#03A9F4",
          "text": "$%v",
          "font-size": "12px",
          "padding": "6 12",
          "border-color": "none",
          "shadow": 0,
          "border-radius": 5
        }
      }, {
        "values": [24200, 12750, 24250, 11500, 22550, 24250],
        "text": "Product 2",
        "background-color": "#673AB7 #9575CD",
        "border-color": "#673AB7",
        "legend-marker": {
          "border-color": "#673AB7"
        },
        "tooltip": {
          "background-color": "#673AB7",
          "text": "$%v",
          "font-size": "12px",
          "padding": "6 12",
          "border-color": "none",
          "shadow": 0,
          "border-radius": 5
        }
      }]
    };

    zingchart.render({
      id: 'myChart4',
      data: myConfig,
      defaults: {
        'font-family': 'sans-serif'
      }
    });
  </script>
</body>

</html>


