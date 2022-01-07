<?php 
include 'mis_nav.php';
include 'fa_side.php';

$q=$con->query("SELECT * FROM budget WHERE active = 1 ");
$brow=$q->fetch_array();
$y=$brow['year'];
$bid= $brow['bID'];


if(isset($_POST['save'])){
    if(empty($_POST['supe'])){
        echo  " <script>alert('Please select a supervisor '); </script>";
    }
    elseif(empty($_POST['rn']) && empty($_POST['rnpercent'])){
        echo  " <script>alert('Please specify renewal value or percentage '); </script>";
    }
    elseif(empty($_POST['nb']) && empty($_POST['nbpercent'])){
        echo  " <script>alert('Please specify New business value or percentage '); </script>";
    }
    elseif(empty($_POST['prod'])){
        echo  " <script>alert('Please select a product '); </script>";
    }
    elseif(empty($_POST['year'])){
        echo  " <script>alert('Please specify the year '); </script>";
    }
    else{
    $sup=check_input($_POST['supe']);
    //$tpol=check_input($_POST['tpol']);
    $nb=check_input($_POST['nb']);
    $rn=check_input($_POST['rn']);
    $yr=check_input($_POST['year']);
    $rnop=check_input($_POST['rnop']);
    $nbnop=check_input($_POST['nbnop']);
    //$prd=check_input($_POST['prod']);
    
    $Pnb=check_input($_POST['nbpercent']);
    $Prn=check_input($_POST['rnpercent']);
    
    
    $Prnop=check_input($_POST['prnnop']); 
    $Pnbnop=check_input($_POST['pnbnop']);
    
    //Find out if the product budget had been set
    $brChk = $con->query("SELECT count(sID) AS count FROM sup_budget WHERE branch ='$bran' AND supervisor='$sup_name' AND product_class='$prd'");
    $bro=$brChk->fetch_array();
    
    $co=$bro['count'];
    
    if($balBud <= 0){
        echo  " <script>alert('Budget Amount had been fully allocated '); </script>";
    }
    elseif ($balNop <= 0) {
        echo  " <script>alert('Budgeted number of policy had been fully allocated '); </script>";
    }
    
    
    
    elseif($co > 0){
        
        echo  " <script>alert('$pro budget for $brn Already exists '); </script>";
    }
    
    else{
        $in=$con->query("INSERT INTO sup_budget SET bbID='$bbid', bID='$b_id', branch='$bran',product_class='$prd', supervisor='$sup_name', nb='$nb', pnb='$Pnb', rn='$rn',prn='$Prn', polnb='$nbnop', ppolnb='$Pnbnop', polrn='$rnop', ppolrn='$Prnop',  year='$yr', created=now() ");
        if($in){
            
            echo  " <script>alert('The budget for $sup_name has been successfully created '); window.location='index.php?branch_detail' </script>";
        }else{
            echo  " <script>alert('Operation failed. Try again '); </script>";
        }
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
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Supervisors' Budget</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active"><a href="agency.php"><i class="icon-home"></i> Back</a> </li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                
                 
                
                
                <div class="row">
                   <div class="col-md-6 col-xs-6 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Top Performer (Branch)</h3>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Supervisor</th>
                                            <th>NB Budget</th>
                                            <th>NB NoP</th>
                                            <th>RN Budget</th>
                                            <th>RN NoP</th>
                                            <th>Branch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $mt=$con->query("SELECT * FROM sup_budget WHERE bID='$bid'");
                                        $counter =0;
                                        while($rm=$mt->fetch_array()){
                                        ?>
                                        <tr>
                                            <td><?php echo ++$counter; ?></td>
                                            <td><?php echo $rm['supervisor']; ?></td>
                                            <td><?php echo number_format($rm['nb']); ?></td>
                                            <td><?php echo number_format($rm['polnb']); ?></td>
                                            <td><?php echo number_format($rm['rn']);; ?></td>
                                            <td><?php echo number_format($rm['polrn']); ?></td>
                                            <td><?php echo $rm['branch']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                   </div>
                    
                    
                    
                  
                    
                    
                   <div class="col-md-6 col-xs-6 col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title">Top Performer (Branch)</h3>
                        <div class="table-responsive"> 
                            <form id="form" class="form" method="post">
                                <div class="row"> 
                                        <div class="col-md-12 ">
                                            <h4 class="form-section"><i class="icon-user-plus"></i> <span class="tag tag-info">Supervisor </span> </h4>
                                            <div class="form-group col-md-12">
                                                    <label>Supervisor</label>
                                                    <select id="issueinput5" name="supe" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Category" required>
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        $pre=$con->query("SELECT name, branch FROM agent WHERE role='Supervisor' OR role='Finanacial advisor'");
                                                        while($re=$pre->fetch_array()){
                                                        ?>
                                                        <option value="<?php echo $re['name']; ?>"><?php echo $re['name']." (".$re['branch'].")"; ?></option>
                                                        <?php } ?>
                                                    </select>
                                            </div>

                                            <h4 class="form-section"><i class="icon-money"></i>Supervisor Budget</h4>
                                                <div class="form-group col-md-6">
                                                        <label>Budget Amount</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">=N=</span>
                                                                <input type="number" id="amount" class="form-control" value="<?php // echo $budget; ?>" placeholder="Budget Amount" aria-label="Amount (to the nearest naira)" name="amt" required>
                                                                <span class="input-group-addon">.00</span>
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                        <label>Total Policy</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">#</span>
                                                                <input type="number" id="amount" class="form-control" value="<?php // echo $budgetNoP; ?>" placeholder="Total Policy" aria-label="Amount (to the nearest naira)" name="tpol" required>
                                                                <span class="input-group-addon">.00</span>
                                                        </div>
                                                </div>

                                                <h4 class="form-section"><i class="icon-clipboard4"></i>Allocation By Business Type</h4>
                                                <div class="form-group col-md-6">
                                                        <label>New Business (Amount)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">=N=</span>
                                                                <input type="number" class="form-control" placeholder=" New Business Budget" aria-label="Amount (to the nearest naira)" name="nb" onkeyup="calculatePerc()" />
                                                                <span class="input-group-addon">.00</span>
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                        <label>New Business (%)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">%</span>
                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Percentage (to the nearest naira)" name="nbpercent" onkeyup="calculatePrice()"/>
                                                                <span class="input-group-addon">%</span>
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                        <label>Renewal (Amount)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">=N=</span>
                                                                <input type="number" class="form-control" placeholder="Renewal Budget " aria-label="Amount (to the nearest naira)" name="rn" onkeyup="calculatePerc2()" />
                                                                <span class="input-group-addon">.00</span>
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                        <label>Renewal (%)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">%</span>
                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="rnpercent" onkeyup="calculatePrice2()" />
                                                                <span class="input-group-addon">%</span>
                                                        </div>
                                                </div>
                                                <h4 class="form-section"><i class="icon-list-alt"></i>Number of Policy</h4>
                                                <div class="form-group col-md-6">
                                                        <label>NoP (NB)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">#</span>
                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="nbnop" onkeyup="calculatePercNoP()" />

                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                        <label>NoP (NB) (%)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">%</span>
                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="pnbnop" onkeyup="calculateNoP()" >
                                                                <span class="input-group-addon">%</span>
                                                        </div>
                                                </div>


                                                <div class="form-group col-md-6">
                                                        <label>NoP (Renewal)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">#</span>
                                                                <input type="number" id="amount" class="form-control" placeholder="Number of Policies" aria-label="Amount (to the nearest naira)" name="rnop" onkeyup="calculatePercNoP2()" />

                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                        <label>NoP Renewal (%)</label>
                                                        <div class="input-group">
                                                                <span class="input-group-addon">%</span>
                                                                <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="Budget percentage" aria-label="Amount (to the nearest naira)" name="prnnop" onkeyup="calculateNoP2()"  />
                                                                <span class="input-group-addon">%</span>
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                        <label for="issueinput5">Year</label>
                                                        <select id="issueinput5" name="year" class="form-control" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Year" required>
                                                                <option><?php echo $y ?></option> 

                                                        </select>
                                                </div>


                                        <div class="form-group offset-md-3">
                                                <button type="reset" class="btn btn-warning mr-1">
                                                        <i class="icon-cross2"></i> Reset
                                                </button>
                                                <button type="submit" name="save" class="btn btn-primary">
                                                        <i class="icon-check2"></i> Save
                                                </button>
                                        </div>

                                        </div>
                                </div>

                            </form>
                        </div>
                    </div>
                   </div>
            </div>
            <!-- /.container-fluid -->
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
    <!-- Zing chart  -->
    <script src="../plugins/bower_components/zing/zingchart.min.js"></script>
    <script src="../plugins/bower_components/zing/zingchart.jquery.min.js"></script>
    <!-- Data Tables-->
    <script src="../plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
     <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                            );

                            last = group;
                        }
                    });
                }
            });

            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
