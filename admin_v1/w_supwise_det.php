 <?php 
                                                    //Supervisor Sales
                                                    $tProductioNB = 0;
                                                    $tProductioRN = 0;
                                                    $tPolicYNB= 0;
                                                    $tPolicYRN= 0;
                                                    $spSalE=$con->query("SELECT  SUM(r_amount), count(distinct policy_no) AS count FROM  myrecord  WHERE receipt_date BETWEEN '$frm' AND '$t2' AND agent_code ='$code' AND year='$ya' AND type='NB'");
                                                    while($spR=$spSalE->fetch_array()){
                                                        $sSaL = $spR['SUM(r_amount)'];
                                                        $sSPoL = $spR['count'];
                                                           
                                                        $tProductioNB += $sSaL;
                                                        $tPolicYNB += $sSPoL; 
                                                    }
                                                    
                                                    $spSalE2=$con->query("SELECT  SUM(r_amount), count(distinct policy_no) AS count FROM  myrecord  WHERE receipt_date BETWEEN '$frm' AND '$t2' AND agent_code ='$code' AND year='$ya' AND type='RN'");
                                                    while($spRr=$spSalE2->fetch_array()){
                                                        $sSaLL = $spRr['SUM(r_amount)'];
                                                        $sSPoLL = $spRr['count'];
                                                           
                                                        $tProductioRN += $sSaLL;
                                                        $tPolicYRN += $sSPoLL; 
                                                    }
                                                    
                                                    
                                                    
                                                    $aG=$con->query("SELECT name, agent_code FROM agent WHERE supervisor='$naM'");
                                                        $counter=0;
                                                        while($agR=$aG->fetch_array()){
                                                            $agenT= $agR['name'];
                                                            $agCode=$agR['agent_code'];
                                                         //Getting the supervisor budget   
                                                        $qQ=$con->query("SELECT * FROM sup_budget WHERE supervisor='$naM' AND bID='$b_id' AND year='$ya' ");
                                                        $counter = 0;
                                                        while($roW = $qQ->fetch_array()){
                                                           
                                                            $tBdt1 = $roW['nb']+ $roW['rn'];
                                                            $tPol1 = $roW['polnb']+ $roW['polrn'];
                                                            
                                                            $spp=$roW['supervisor'];
                                                            
                                                            //Getting each agent's production
                                                            
                                                            $x= $con->query("SELECT  SUM(r_amount), count(id) AS count FROM  myrecord  WHERE receipt_date BETWEEN '$frm' AND '$t2' AND agent_code ='$agCode' AND year='$ya' AND type='NB' ");
                                                            $xr=$x->fetch_array();
                                                            $salx=$xr['SUM(r_amount)'];
                                                            $Pol1 = $xr['count'];
                                                             
                                                            $tProductioNB +=  $salx;
                                                            $tPolicYNB += $Pol1;
                                                            
                                                            
                                                            $x2= $con->query("SELECT  SUM(r_amount), count(id) AS count FROM  myrecord  WHERE receipt_date BETWEEN '$frm' AND '$t2' AND agent_code ='$agCode' AND year='$ya' AND type='RN' ");
                                                            $xr2=$x2->fetch_array();
                                                            $salx2=$xr2['SUM(r_amount)'];
                                                            $Pol12 = $xr2['count'];
                                                             
                                                            $tProductioRN +=  $salx2;
                                                            $tPolicYRN += $Pol12;
                                                            
                                                        }  
                                                        
                                                        }
                                                    ?>




