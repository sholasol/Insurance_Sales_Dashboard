﻿﻿<?php 

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

$rol = $_GET['r'];
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


$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

$agcy=$con->query("SELECT sum(nb) AS nb, sum(rn) AS rn, sum(polnb) AS polnb, sum(polrn) AS polrn FROM proclass_bud WHERE bID='$bid' AND type='Financia Advisor' AND year='$y'");
$rw=$agcy->fetch_array();
$tBud=$rw['nb'] + $rw['rn'];
$tPol=$rw['polnb'] + $rw['polrn'];

$q1[] = 0;
$ach= $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `mymyrecord` where 
                  year='$y' AND type='NB' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='FA Victoria Island' OR branch='FA Ikeja' OR
                  branch='FA Broad Street' OR branch='FA Abuja' OR branch='FA Lagos' OR branch='FA PH') GROUP BY product_class");
while($yr =$ach->fetch_array()){

$xxx= $yr['sum']/1000000;
 $q1[] = $xxx;
 
 $polc=$yr['count'];
$rAmount = $yr['sum'];
}
//
$q2[] = 0;
$ach2=$con->query("SELECT sum(r_amount) AS sum, count(distinct agent_code) AS count FROM mymyrecord where 
                  year='$y' AND type='RN' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch= 'FA Victoria Island' OR branch='FA Ikeja' OR
                  branch='FA Broad Street' OR branch='FA Abuja' OR branch='FA Lagos' OR branch='FA PH') GROUP BY product_class");
while($yr2 =$ach2->fetch_array()){
$totAgt = $yr2['count'];
$xxx2= $yr2['sum']/1000000;
 $q2[] = $xxx2;
}

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
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css"/>
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <link href="../plugins/bower_components/css-chart/css-chart.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="../css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <!--Data Tables -->
    <link href="../plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- color CSS -->
    <link href="../css/colors/blue.css" id="theme" rel="stylesheet">
    <style>
        .pad{
            padding-left: 10px;
        }
    </style>
    
</head>

<body>
    <!-- Preloader -->
<!--    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>-->
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header" style="background-color: #fff; "> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                  <div class="top-left-part">
                       <a class="logo" href="mis.php" >
                         <!--<b><img src="../plugins/images/ensureLogo.png" width="70" height="50" alt="home" /></b>--><!--<span class="hidden-xs"><i class="fa fa-bar-chart"></i> RBD RETAIL MIS</span>-->
                           <span class="hidden-xs" style="margin-left: 600px;"><img src="../plugins/images/ensureLogo.png" alt="home" /></span>
                       </a>
                  </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <!-- /.dropdown -->
                    <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" style=" color: #0074d9;"><i class="icon-note"></i>
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
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" style="color: #008efa;"> <img src="../plugins/images/users/no-photo.png" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $name ?></b> </a>
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
                    
                    <li> <a href="#" class="waves-effect "><i data-icon="&#xe008;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Reports<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="week.php?r=<?php echo $rol ?>">Weekly</a></li>
                            <li><a href="month.php?r=<?php echo $rol ?>">Monthly</a></li>
                            <li><a href="ytd.php?r=<?php echo $rol ?>">YTD</a></li>
                        </ul>
                    </li>
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
                        <h4 class="page-title">Bancassurance</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active"><a href="fa_budget.php?r=<?php echo $rol; ?>"><i class="icon-home"></i> Back</a> </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                
                <!-- Row-->
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
                                <h4 class="card-title" id="basic-layout-form">Total Product Budget</h4>
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
                                                        $qq=$con->query("SELECT * FROM proclass_bud WHERE bID='$bid' AND type='$ty' ");
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
                                <h4 class="card-title" id="basic-layout-form">Product Budget  (FA Lagos)</h4>
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
                                                $p5=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='Financial Advisors'");
                                                while($rr5=$p5->fetch_array()){
                                                    $pr5=$rr5['product_class'];
                                                    $prd5=$rr5['prID'];
                                                    
                                                    $pp5=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr5' AND bID='$bid' AND branch='FA Lagos'");
                                                    $ro5=$pp5->fetch_array();
                                                    $nb5=$ro5['nb'];
                                                    $rn5=$ro5['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr5;?></span> &nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      if($nb5 < 1 or $rn5 < 1){
                                                          echo "<a href='bran_allocate.php?&p=$prd5&b=FLAG&r=$rol' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      ?>
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb5); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn5); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                    <td></td>
                                                    <td><?php // echo number_format($totalNB); ?> </td>
                                                    <td><?php // echo number_format($totalRN); ?> <br></td>
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
                                <h4 class="card-title" id="basic-layout-form">Product Budget  (FA Abuja)</h4>
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
                                                $p=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='Financial Advisors'");
                                                while($rr=$p->fetch_array()){
                                                    $pr=$rr['product_class'];
                                                    $prd=$rr['prID'];
                                                    
                                                    $pp=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr' AND bID='$bid' AND branch='FA Abuja'");
                                                    $ro=$pp->fetch_array();
                                                    $nb=$ro['nb'];
                                                    $rn=$ro['rn'];
                                                    
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr;?></span>&nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      if($nb < 1 or $rn < 1){
                                                          echo "<a href='bran_allocate.php?p=$prd&b=FABJ&r=$rol' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      ?>
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                    <td></td>
                                                    <td><?php // echo number_format($totalNB); ?> </td>
                                                    <td><?php // echo number_format($totalRN); ?> </td>
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
                                <h4 class="card-title" id="basic-layout-form">Product Budget  (FA PH)</h4>
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
                                                $p1=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='Financial Advisors'");
                                                while($rr1=$p1->fetch_array()){
                                                    $pr1=$rr1['product_class'];
                                                    $prd1=$rr1['prID'];
                                                    
                                                    $pp1=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr1' AND bID='$bid' AND branch='FA PH'");
                                                    $ro1=$pp1->fetch_array();
                                                    $nb1=$ro1['nb'];
                                                    $rn1=$ro1['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr1;?></span>&nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      if($nb1 < 1 or $rn1 < 1){
                                                          echo "<a href='bran_allocate.php?p=$prd1&b=FPH&r=$rol' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      ?>
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb1); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn1); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                    <td></td>
                                                    <td><?php // echo number_format($totalNB); ?> </td>
                                                    <td><?php // echo number_format($totalRN); ?> </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
             
                    
                    
                    
            <?php 
            /*
            <div class="col-md-6">

             <div class="white-box">
                        <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Product Budget  (Ensure Abuja )</h4>
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
                                                $p2=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='Financial Advisors'");
                                                while($rr2=$p2->fetch_array()){
                                                    $pr2=$rr2['product_class'];
                                                    $prd2=$rr2['prID'];
                                                    
                                                    $pp2=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr2' AND bID='$bid' AND branch='Ensure Abuja'");
                                                    $ro2=$pp2->fetch_array();
                                                    $nb2=$ro2['nb'];
                                                    $rn2=$ro2['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr2;?></span>&nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      if($nb2 < 1 or $rn2 < 1){
                                                          echo "<a href='bran_allocate.php?budg=$bid&year=$y&p=$prd2&b=ABJ' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      ?>
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb2); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn2); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
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
                                <h4 class="card-title" id="basic-layout-form">Product Budget  (Ensure PH)</h4>
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
                                                $p3=$con->query("SELECT product_class, prID FROM proclass_bud WHERE type='Financial Advisors'");
                                                while($rr3=$p3->fetch_array()){
                                                    $pr3=$rr3['product_class'];
                                                    $prd3=$rr3['prID'];
                                                    
                                                    $pp3=$con->query("SELECT nb, rn FROM branch_bud WHERE product_class='$pr3' AND bID='$bid' AND branch='Ensure PH'");
                                                    $ro3=$pp3->fetch_array();
                                                    $nb3=$ro3['nb'];
                                                    $rn3=$ro3['rn'];
                                                ?>
                                              <tr>
                                                  <td>
                                                      <span class="text-primary"><?php echo $pr3;?></span>&nbsp;&nbsp;&nbsp;
                                                      <?php 
                                                      if($nb3 < 1 or $rn3 < 1){
                                                          echo "<a href='bran_allocate.php?budg=$bid&year=$y&p=$prd3&b=PH' class='card-title'><i class='icon-plus'></i> Add</a>";
                                                      }
                                                      ?>
                                                  </td>
                                                  <td width="9%"><span class="text-danger"><?php echo number_format($nb3); ?></span></td>
                                                  <td width="9%"><span class="text-info"><?php echo number_format($rn3); ?></span></td>
                                              </tr>
                                                <?php } ?>
                                              
                                              <tr>
                                                        <td></td>
                                                        <td><?php echo number_format($totalNB); ?> </td>
                                                        <td><?php echo number_format($totalRN); ?> </td>
                                                    </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
             </div>
            </div>
            */
            ?>
            
            
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


