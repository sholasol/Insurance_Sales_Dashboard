 <?php
include('db.php');

if (isset($_POST['login'])) {
 
    $username = check_input($_POST["email"]);
    
    if(empty($_POST['email'])){
	 header("Location:login.php?err=" . urlencode("Please fill in your email!")); 
		}

	else{
		$fusername=$username;
	}
 
	$fpassword = check_input($_POST["pass"]);
        if(empty($_POST['pass'])){
			header("Location:login.php?err=" . urlencode("Password is required!"));  
		}

  else{
  
  
  $pass= sha1($fpassword);
 
  $query=$con->query("select * from users where email='$fusername' && pass='$pass'");
  $num_rows=$query->num_rows;
  $row=$query->fetch_array();
 
  if ($num_rows > 0){

                    session_start();
				
                    $_SESSION["email"] = $row['email']; // setting session
                    $_SESSION["id"] = $row['userID']; // setting session
                    $role = $row['role']; // setting role

                    switch($role){
                    case 'Admin':
                            //header("Location:admin/dashboard.php");
                            //header("Location:admin/mis.php"); // take user to the home page
                            echo  " <script>location.href='admin/mis.php'</script>";
                            exit();

                    case 'Finance':
                            header("Location:pay/index.php?dashboard"); // take user to the home page
                            exit();
                    case 'Authorize':
                            header("Location:authorize/index.php?dashboard"); // take user to the home page
                            exit();
                    case 'Confirmation':
                            header("Location:confirm/index.php?dashboard"); // take user to the home page
                            exit(); 
                    case 'Validate':
                            header("Location:validate/index.php?dashboard"); // take user to the home page
                            exit();
                        
                    case 'Mis':
                            header("Location:mis/Admin/index.php?dashboard"); // take user to the home page
                            exit();
                    }
                    

	//  Check if the user is an agent/supervisor/agency manager
  }elseif ($num_rows < 1) {
      
      
        $query=$con->query("select * from agent where email='$fusername' && pass='$pass'");
        $num_row2=$query->num_rows;
        $row=$query->fetch_array();

                    if ($num_row2 > 0){

                                session_start();

                                $_SESSION["email"] = $row['email']; // setting session
                                $_SESSION["id"] = $row['agentID']; // setting session
                                $role = $row['role']; // setting role
                                $tatus = $row['reset'];
                                switch($role){
                                case 'Agent':
                                        if($row['reset'] ==0){
                                         header("Location:admin/agt_reset.php");
                                        }
                                        if($row['reset'] == 1){
                                        header("Location:admin/agt_dashboard.php"); // take user to the home page
                                        }
                                        exit();

                                case 'Supervisor':

                                        if($row['reset'] ==0){
                                         header("Location:admin/sup_reset.php");
                                        }
                                        if($row['reset'] == 1){
                                        header("Location:admin/sup_dashboard.php"); // take user to the home page
                                        }

                                        exit();

                                case 'Agency Manager':
                                        if($row['reset']  == 0  ){
                                         header("Location:admin/agcy_reset.php");
                                        }
                                        if($row['reset'] == 1){
                                        header("Location:admin/agcy_dashboard.php"); // take user to the home page
                                        }
                                        exit();

                                case 'Financial Advisor':
                                        if($row['reset'] == 0){
                                         header("Location:admin/fa_reset.php");
                                        }
                                        if($row['reset'] == 1){
                                        header("Location:admin/fa_dashboard.php"); // take user to the home page
                                        }
                                        exit();

                                     }

                                    }else{
                                        header("Location:index.php?err=" . urlencode("Incorrect email or password. Please try again!")); 
                                    }
            
        }
  else{
	
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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png"> -->
<title>Lawunion | Reporting</title>
<!-- Bootstrap Core CSS -->
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- animation CSS -->
<link href="css/animate.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">
<!-- color CSS -->
<link href="css/colors/blue.css" id="theme"  rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="login-img-body">
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
    <div class="login-box login-sidebar" style="opacity: 0.8;">
    <div class="white-box">
      <form class="form-horizontal form-material" id="loginform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <a href="javascript:void(0)" class="text-center db">
            <img src="plugins/images/logo_1.png" alt=""/>
        </a>  
        <div class="alert-danger ">
                    <?php if(isset($_GET['err'])) { ?>

                    <div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

                    <?php } ?>
         </div>
        <div class="form-group m-t-40">
          <div class="col-xs-12">
              <input class="form-control" type="text" required="" name="email" placeholder="Email / Username">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" type="password" required="" name="pass" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
<!--            <div class="checkbox checkbox-primary pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox">
              <label for="checkbox-signup"> Remember me </label>
            </div>-->
            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
              <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" name="login" type="submit">Log In</button>
          </div>
        </div>
        
      </form>
      <form class="form-horizontal" id="recoverform" action="">
        <div class="form-group ">
          <div class="col-xs-12">
            <h3>Recover Password</h3>
            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" required="" placeholder="Email">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<!-- jQuery -->
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>

<!--slimscroll JavaScript -->
<script src="js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/custom.min.js"></script>
<!--Style Switcher -->
<script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>
</html>
