
<?php
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



//include_once "head.php";
//include_once "side.php";

if (isset($_GET['dashboard'])){
    include_once "home.php";
}
elseif (isset($_GET['page'])){
    include_once "dashboard.php";
}
elseif (isset($_GET['lead'])){
    include_once "lead.php";
}
elseif (isset($_GET['mprod'])){
    include_once "mprod.php";
}
elseif (isset($_GET['carea'])){
    include_once "carea.php";
}
elseif (isset($_GET['super'])){
    include_once "super.php";
}
elseif (isset($_GET['agr'])){
    include_once "agr.php";
}
elseif (isset($_GET['performance'])){
    include_once "performance.php";
}
elseif (isset($_GET['prem'])){
    include_once "prem.php";
}
elseif (isset($_GET['downline'])){
    include_once "downline.php";
}
elseif (isset($_GET['agency_downline'])){
    include_once "agency_downline.php";
}
elseif (isset($_GET['sum'])){
    include_once "sum.php";
}
elseif (isset($_GET['spoke'])){
    include_once "spoke.php";
}
elseif (isset($_GET['ramount'])){
    include_once "ramount.php";
}
elseif (isset($_GET['report'])){
    include_once "report.php";
}
elseif (isset($_GET['cdetail'])){
    include_once "cdetail.php";
}
elseif (isset($_GET['reports'])){
    include_once "reports.php";
}
elseif (isset($_GET['detail'])){
    include_once "detail.php";
}
elseif (isset($_GET['home'])){
    include_once "home.php";
}
elseif (isset($_GET['merge'])){
    include_once "merge.php";
}
elseif (isset($_GET['cadvisor'])){
    include_once "cadvisor.php";
}
elseif (isset($_GET['branch'])){
    include_once "branch.php";
}
elseif (isset($_GET['initiate'])){
    include_once "initiate.php";
}
elseif (isset($_GET['epot'])){
    include_once "epot.php";
}
elseif (isset($_GET['cpot'])){
    include_once "cpot.php";
}
elseif (isset($_GET['dash'])){
    include_once "dash.php";
}
elseif (isset($_GET['potential'])){
    include_once "potential.php";
}
elseif (isset($_GET['test'])){
    include_once "test.php";
}
elseif (isset($_GET['filter'])){
    include_once "filter.php";
}
elseif (isset($_GET['reversal'])){
    include_once "reversal.php";
}
elseif (isset($_GET['rejected'])){
    include_once "rejected.php";
}
elseif (isset($_GET['invoice'])){
    include_once "invoice.php";
}
elseif (isset($_GET['reset'])){
    include_once "reset.php";
}
elseif (isset($_GET['inv'])){
    include_once "inv.php";
}
elseif (isset($_GET['payagent'])){
    include_once "payagent.php";
}
elseif (isset($_GET['payagency'])){
    include_once "payagency.php";
}
elseif (isset($_GET['paysup'])){
    include_once "paysup.php";
}
elseif (isset($_GET['rinvoice'])){
    include_once "rinvoice.php";
}
elseif (isset($_GET['graph'])){
    include_once "graph.php";
}
elseif (isset($_GET['fixed'])){
    include_once "fixed.php";
}
elseif (isset($_GET['afixed'])){
    include_once "afixed.php";
}
elseif (isset($_GET['sfixed'])){
    include_once "sfixed.php";
}
elseif (isset($_GET['agrfixed'])){
    include_once "agrfixed.php";
}
elseif (isset($_GET['production'])){
    include_once "production.php";
}
elseif (isset($_GET['user'])){
    include_once "user.php";
}
elseif (isset($_GET['commission'])){
    include_once "commission.php";
}
elseif (isset($_GET['cagency'])){
    include_once "cagency.php";
}
elseif (isset($_GET['cagent'])){
    include_once "cagent.php";
}
elseif (isset($_GET['csup'])){
    include_once "csup.php";
}
elseif (isset($_GET['compute'])){
    include_once "compute.php";
}
elseif (isset($_GET['authorization'])){
    include_once "authorization.php";
}
elseif (isset($_GET['authorize'])){
    include_once "authorize.php";
}
elseif (isset($_GET['record'])){
    include_once "record.php";
}
elseif (isset($_GET['rate'])){
    include_once "rate.php";
}
elseif (isset($_GET['r_rate'])){
    include_once "r_rate.php";
}
elseif (isset($_GET['e_rate'])){
    include_once "e_rate.php";
}
elseif (isset($_GET['agent'])){
    include_once "agent.php";
}
elseif (isset($_GET['e_agent'])){
    include_once "e_agent.php";
}
elseif (isset($_GET['schedule'])){
    include_once "schedule.php";
} 
elseif (isset($_GET['detB'])){
    include_once "detB.php";
}
elseif (isset($_GET['detS'])){
    include_once "detS.php";
}
elseif (isset($_GET['prod'])){
    include_once "prod.php";
}
elseif (isset($_GET['detAgr'])){
    include_once "detAgr.php";
}
elseif (isset($_GET['upload'])){
    include_once "upload.php";
}
elseif (isset($_GET['brancom'])){
    include_once "brancom.php";
}
elseif (isset($_GET['supcom'])){
    include_once "supcom.php";
}
elseif (isset($_GET['agtcom'])){
    include_once "agtcom.php";
}






else{
    //include_once "manager.php";
}


?>
