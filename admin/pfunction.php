<?php
include_once "../db.php";
session_start();

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];

if (isset($_POST['ytd'])){
        $x=$con->query("SELECT count(id) AS count FROM ytd");
        $rr=$x->fetch_array();
        $count=$rr['count'];
        
        if($count > 0) {
            $del=$con->query("TRUNCATE ytd");
        }
        
	$rol=$_POST['role'];
       
        if($rol =="Partners"){
            $area = 'Partners - Autodealership';
        }
        elseif($rol =='HNI'){
            $area = 'HNI';
        }
        elseif($rol =='FI'){
            $area = 'Partners - Microfinance';
        }
      $ty = $rol;
        
        // Partners
 
        $cp=$con->query("SELECT  product_class FROM `myrecord` WHERE  year='$y'  GROUP BY product_class");
            while($cr = $cp->fetch_array()){
                $prodt = $cr['product_class'];
                                            
            //Getting New business budget for each of the product
            $pb = $con->query("SELECT nb, polnb, rn, polrn FROM proclass_bud WHERE product_class='$prodt'  AND year='$y' AND type='$ty' ");
            $or = $pb->fetch_array();
            $nb = $or['nb']; //new business budget for each of the product
            $polnb=$or['polnb'];
            $rn= $or['rn'];
            $polrn= $or['polrn'];

            
            
            
            
            //Lagos
          //Lagos Product Performance NB
            $cppL = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE c_area LIKE '$area'  AND product_class='$prodt' AND type='NB' AND  year='$y' 
            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch= 'Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' ) ");
            $roL=$cppL->fetch_array();
            $proAmtL = $roL['amount'];
            $proPolL = $roL['count'];
            $agentL = $roL['agent'];
            $premiumL = $roL['premium'];  
            
            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND c_area LIKE '$area'  AND type='NB' AND year='$y' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'  ) ");
            $arL=$acvtLag->fetch_array();
            $actL= $arL['agent'];
            //ats
            if($premiumL <= 0 || $proPolL <= 0){$atsL= 0;}
            else{$atsL = $premiumL/$proPolL;}
            //Activization
            if($actL <= 0 || $agentL <=0){$actvL = 0;}
            else{$actvL = $actL/$agentL;}
            //Case Rate
            if($proPolL <= 0 || $agentL <= 0 ){$caseRateL = 0;}
            else{$caseRateL= $proPolL/$agentL;}
            //Percentage Achieve
            if($proAmtL <= 0 || $nb <= 0){$pAchL = 0;}
            else{$pAchL = ($proAmtL/$nb)*100;}
            //Percentage NOP Achieve
            if($proPolL <= 0 || $polnb <= 0){$pNopL = 0;}
            else{$pNopL = ($proPolL/$polnb)*100;}
            
            
            
            //Lagos Product Performance RN
            $cpp2L = $con->query("SELECT sum(premium) AS premium, sum(r_amount) AS amount,count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE c_area LIKE '$area'  AND  product_class='$prodt' AND type='RN' AND  year='$y' 
            AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street' ) ");
            $rwL=$cpp2L->fetch_array();
            $proAmt2L = $rwL['amount'];
            $proPol2L = $rwL['count'];
            $agent2L = $rwL['agent'];
            $premium2L = $rwL['premium'];  
            
            $acvtLag = $con->query("SELECT count(distinct agent_code) AS agent, count(distinct policy_no) AS count, product_class FROM `myrecord` WHERE product_class='$prodt' AND c_area LIKE '$area'  AND type='RN' AND year='$y' AND r_amount >= 10000 AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Victoria Island' OR branch='Ensure Ikeja' OR branch='Ensure Broad Street'  ) ");
            $arL=$acvtLag->fetch_array();
            $act2L= $arL['agent'];
     
            
            //ats
            if($premium2L <= 0 || $proPol2L <= 0){$ats2L= 0;}
            else{$ats2L = $premium2L/$proPol2L;}
            //Activization
            if($act2L <= 0 || $agent2L <=0){$actv2L = 0;}
            else{$actv2L = $act2L/$agent2L;}
            //Case Rate
            if($proPol2L <= 0 || $agent2L <= 0 ){$caseRate2L = 0;}
            else{$caseRate2L= $proPol2L/$agent2L;}
            //Percentage Achieve
            if($proAmt2L <= 0 || $rn <= 0){$pAch2L = 0;}
            else{$pAch2L = ($proAmtL/$rn)*100;}
            //Percentage NOP Achieve
            if($proPol2L <= 0 || $polrn <= 0){$pNop2L = 0;}
            else{$pNop2L = ($proPol2L/$polrn)*100;}
            
            
            
            
            
            //Inserting Record into ytd table
            $ytdL=$con->query("INSERT INTO ytd SET product='$prodt', nb='$nb', polnb='$polnb', rn='$rn', polrn='$polrn', nb_ach='$proAmtL', pnb_ach='$pAchL', nb_nop='$proPolL', pnb_nop='$pNopL', nb_ats='$atsL',
             nb_rse='$agentL',nb_actv='$actvL', nb_cas='$caseRateL', rn_ach='$proAmt2L', prn_ach='$pAch2L', rn_nop='$proPol2L', prn_nop='$pNop2L',rn_ats='$ats2L', rn_rse='$agent2L', rn_actv='$actv2L', rn_cas='$caseRate2L', branch='$rol', role='$rol', date=now() " );
          
            
            
            
            
            
            
          
         
            
          }
          
          
         
 if($rol =="Partners"){    
          
   
    header("Content-Disposition: attachment; filename=Partners_YTD_Report.csv");
    header("Content-Type: application/vnd.ms-excel");
    //header("Content-Type: text/csv; charset=utf-8");
    
    $output= fopen("php://output", "w");
    
    
    
    //Output Lagos Report
    fputcsv($output, array('Partners-Autodealership Report (New Business & Renewal)'));
    
    fputcsv($output, array('Product', 'NB Budget', 'NoP Budget', 'NB Achieved', '% Achieved','NoP Achieved', '% NoP Achieved', 'ATS', 'No of RSEs', 'Activization','Case Rate','RN Budget', 'NoP Budget','RN Achieved', '% Achieved',   'NoP Achieved', '%NoP Achieved', 'ATS', 'RSEs', 'Activization', 'Case rate'));
    
    $query=$con->query("SELECT product, nb, polnb, nb_ach, pnb_ach, nb_nop, pnb_nop, nb_ats, nb_rse, nb_actv, nb_cas, rn, polrn, rn_ach, prn_ach,
     rn_nop, prn_nop, rn_ats, rn_rse, rn_actv, rn_cas FROM ytd  WHERE branch='$rol'");
    while($row=$query->fetch_assoc()){
        fputcsv($output, $row);
    }
    
    fclose($output);
        
 }  
 elseif($rol =="HNI"){    
          
   
    header("Content-Disposition: attachment; filename=Partners_YTD_Report.csv");
    header("Content-Type: application/vnd.ms-excel");
    //header("Content-Type: text/csv; charset=utf-8");
    
    $output= fopen("php://output", "w");
    
    
    
    //Output Lagos Report
    fputcsv($output, array('HNI Report (New Business & Renewal)'));
    
    fputcsv($output, array('Product', 'NB Budget', 'NoP Budget', 'NB Achieved', '% Achieved','NoP Achieved', '% NoP Achieved', 'ATS', 'No of RSEs', 'Activization','Case Rate','RN Budget', 'NoP Budget','RN Achieved', '% Achieved',   'NoP Achieved', '%NoP Achieved', 'ATS', 'RSEs', 'Activization', 'Case rate'));
    
    $query=$con->query("SELECT product, nb, polnb, nb_ach, pnb_ach, nb_nop, pnb_nop, nb_ats, nb_rse, nb_actv, nb_cas, rn, polrn, rn_ach, prn_ach,
     rn_nop, prn_nop, rn_ats, rn_rse, rn_actv, rn_cas FROM ytd  WHERE branch='$rol'");
    while($row=$query->fetch_assoc()){
        fputcsv($output, $row);
    }
    
    fclose($output);
        
 }  
 
  if($rol =="FI"){    
          
   
    header("Content-Disposition: attachment; filename=Partners_YTD_Report.csv");
    header("Content-Type: application/vnd.ms-excel");
    //header("Content-Type: text/csv; charset=utf-8");
    
    $output= fopen("php://output", "w");
    
    
    
    //Output Lagos Report
    fputcsv($output, array('Partners-Microfinance(FI) Report (New Business & Renewal)'));
    
    fputcsv($output, array('Product', 'NB Budget', 'NoP Budget', 'NB Achieved', '% Achieved','NoP Achieved', '% NoP Achieved', 'ATS', 'No of RSEs', 'Activization','Case Rate','RN Budget', 'NoP Budget','RN Achieved', '% Achieved',   'NoP Achieved', '%NoP Achieved', 'ATS', 'RSEs', 'Activization', 'Case rate'));
    
    $query=$con->query("SELECT product, nb, polnb, nb_ach, pnb_ach, nb_nop, pnb_nop, nb_ats, nb_rse, nb_actv, nb_cas, rn, polrn, rn_ach, prn_ach,
     rn_nop, prn_nop, rn_ats, rn_rse, rn_actv, rn_cas FROM ytd  WHERE branch='$rol'");
    while($row=$query->fetch_assoc()){
        fputcsv($output, $row);
    }
    
    fclose($output);
        
 } 
        
}