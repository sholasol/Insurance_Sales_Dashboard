<?php 
$rol = $_GET['r'];
if($rol =='Agency'){
      //Coverage Areas Budget
    
    //VI
    $covBudgetL = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch ='Ensure Victoria Island' ");
    $corL=$covBudgetL->fetch_array();
    $sumBudL =$corL['sum(nb)'] + $corL['sum(rn)'];
    $sumPolL =$corL['sum(polnb)'] + $corL['sum(polrn)'];
    
    //Ikeja
    $covBudget1L = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch ='Ensure Ikeja' ");
    $cor1L=$covBudget1L->fetch_array();
    $sumBud1L =$cor1L['sum(nb)'] + $cor1L['sum(rn)'];
    $sumPol1L =$cor1L['sum(polnb)'] + $cor1L['sum(polrn)'];
    
    
    //B/Street
    $covBudget2L = $con->query("SELECT sum(nb), sum(rn), sum(polnb), sum(polrn) FROM branch_bud WHERE bID='$bud_id' AND branch='Ensure Broad Street'  ");
    $cor2L=$covBudget2L->fetch_array();
    $sumBud2L =$cor2L['sum(nb)'] + $cor2L['sum(rn)'];
    $sumPol2L =$cor2L['sum(polnb)'] + $cor2L['sum(polrn)'];
    
    
    
    //Coverage Areas Productions
    
    //VI
    $covAreaL = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
     year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE  branch='Ensure Victoria Island' ) ");
    $cvL = $covAreaL->fetch_array();
    $suAmtL = $cvL['sum'];
    $suPolL = $cvL['count'];
    
    //Ikeja
    $covArea1L = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
      year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Ikeja') ");
    $cv1L = $covArea1L->fetch_array();
    $suAmt1L = $cv1L['sum'];
    $suPol1L = $cv1L['count'];
    
    
    //B?Street
    $covArea2L = $con->query("SELECT sum(r_amount) as sum, count(distinct policy_no) AS count FROM `myrecord` where 
    year='$yy' AND `agent_code` IN (SELECT agent_code FROM `agent` WHERE branch='Ensure Broad Street') ");
    $cv2L = $covArea2L->fetch_array();
    $suAmt2L = $cv2L['sum'];
    $suPol2L = $cv2L['count'];
    
 
    
    //Percentage Achieved Budget Amount
    if($suAmtL < 1 || $sumBudL < 1){$PA_AbujaL = 0;}
    else{$PA_AbujaL = ($suAmtL/$sumBudL)*100;}
    
    if($suAmt1L < 1 || $sumBud1L < 1){$PA_LagosL = 0;}
    else{$PA_LagosL = ($suAmt1L/$sumBud1L)*100;}
    
    if($suAmt2L < 1 || $sumBud2L < 1){$PA_PHL = 0;}
    else{$PA_PHL = ($suAmt2L/$sumBud2L)*100;}
    
    //Percentage Achieved Budget Policy
    if($suPol < 1 || $sumPol < 1){$PA_Abuja1L = 0;}
    else{$PA_Abuja1L = ($suPol/$sumPol)*100;}
    
    if($suPol1L < 1 || $sumPol1L < 1){$PA_Lagos1L = 0;}
    else{$PA_Lagos1L = ($suPol1L/$sumPol1L)*100;}
    
    if($suPol2L < 1 || $sumPol2L < 1){$PA_PH1L = 0;}
    else{$PA_PH1L = ($suPol2L/$sumPol2L)*100;}
}

 
    
    ?>
<div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6">
                             <div class="white-box">
                                 <h3 class="box-title">Branch Wise (Branch)</h3>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" width="100%">
                                                   <thead>
                                                       <tr>
                                                           <th width="25%">Coverage Area</th>
                                                           <th width="10%">Budget</th>
                                                           <th width="15%">Achieved (YTD)</th>
                                                           <th width="10%">%Achieved (YTD)</th>
                                                           <th width="5%">NoP</th>
                                                           <th width="15%">Achieved (YTD)</th>
                                                           <th width="10%">%Achieved (YTD)</th>
                                                       </tr>
                                                   </thead>
                                                   <tbody>
                                                       <tr>
                                                           <th><strong><a href="bran_detail.php?b=VI&r=<?php echo $rol; ?>">Victoria Island (Supervisor Wise)</a></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($sumBudL); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suAmtL); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_AbujaL, 1, '.', '')."%"; ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($sumPolL); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suPolL); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_Abuja1L, 1, '.', '')."%"; ?></span></strong></th>
                                                       </tr>
                                                       <tr>
                                                           <th><strong><a href="bran_detail.php?b=IKJ&r=<?php echo $rol; ?>">Ikeja (Supervisor Wise) </a></strong></th>
                                                           <th><strong><span class=""><?php echo number_format($sumBud1L); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suAmt1L); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_LagosL, 1, '.', '')."%"; ?></span> </strong></th>
                                                           <th><strong><span class=""><?php echo number_format($sumPol1L); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suPol1L); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_Lagos1L, 1, '.', '')."%"; ?></span></strong></th>
                                                       </tr>
                                                       <tr>
                                                           <th><strong><a href="bran_detail.php?b=BS&r=<?php echo $rol; ?>">Broad Street (Supervisor Wise) </a></strong></th>
                                                           <th><strong><span class=""><?php echo number_format($sumBud2L); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suAmt2L); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_PHL, 1, '.', '')."%"; ?></span> </strong></th>
                                                           <th><strong><span class=""><?php echo number_format($sumPol2L); ?></span></strong> </th>
                                                           <th><strong><span class=""><?php echo number_format($suPol2L); ?></span></strong> </th>
                                                           <th><strong><span class=""> <?php echo number_format($PA_PH1L, 1, '.', '')."%"; ?></span></strong></th>
                                                       </tr>

                                                   </tbody>
                                                </table>
                                 </div>
                             </div>
                        </div>
                    </div>