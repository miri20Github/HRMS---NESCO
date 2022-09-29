<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>NESCO</title>
        <!-- BOOTSTRAP STYLES-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
            <!-- CUSTOM STYLES-->
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
        <link href="assets/css/pagination.css" rel="stylesheet" type="text/css"> 

        <link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.css" media="all"  />	      
        <link rel='stylesheet' type="text/css" href='datatables/jquery.dataTables.css'/>  
    </head>
    <style type="text/css">
        .menu-style {
            color: white;
        }

        .buttonx {  
            display: block;
            color: rgb(255, 255, 000);
            text-decoration: none;
            text-align: center;
            padding: 6px;
            margin: 15px 0px 0px 15px;
            font-size: 12px;          
            background: green;
            color: #FFF;
            border: 0px none;
            outline: 0px none;
        }

        .navbar-nav > li > a {
            color: #F9F5F5;
        }
		
		.btnx{
			width:230px; height:30px; radius:10px; margin:auto;
		}

		.btnBorder {
			border-radius: 0px;
		}

        .panel {

            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }
    </style>
<body>     
          
    <div id="wrapper">
         <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="?p=home">
                        <img src="../images/icons/nesco.jpg" width='205 ' height='55'/>
                    </a>                    
                </div>
                <div class="collapse navbar-collapse menu-style" id="bs-example-navbar-collapse-1" style="padding-right: 10px; padding-top: 23px;">
                    <ul class="nav navbar-nav navbar-right">
                        <div class="navbar-form navbar-left">                               
							<div class="form-group">
								<div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btnBorder">&nbsp;<i class="glyphicon glyphicon-user"></i></button>
                                    </span>
									<input type="text" class="form-control" style="font-family: 'Arial'; font-weight: bold; width:260px" id="" name='searchs' placeholder="Lname, Fname or Emp. Id" style="border-radius:0px;">               
									<span class="input-group-btn">
										<button class="btn btn-primary btnBorder" name="search" style="font-weight: bold;" onclick="searchEmp()"><i class="glyphicon glyphicon-search"></i>&nbsp; Search</button>
									</span>
								</div>								
							</div>	
                        </div>
						
                        <?php

                            //COUNTS THE MESSAGES
                            $countinbox = mysql_query("SELECT count(msgdet_id) FROM `messages`, `message_details` WHERE `messages`.`msg_id` = `message_details`.`msg_id` AND cc = '$_SESSION[emp_id]' AND msg_stat = 0 AND (sender_delete != '$_SESSION[emp_id]' AND receiver_delete != '$_SESSION[emp_id]')")or die(mysql_error());
                            $rcinbx     = mysql_fetch_array($countinbox);
                            $ctrinbox   = $rcinbx['count(msgdet_id)'];

                            //check if allow sa payroll nesco
                            $queryP = mysql_query("SELECT count(empId) as numAllow FROM timekeeping.setup_nesco_payroll WHERE 
                                                    setup_nesco_payroll.empId = '$_SESSION[emp_id]' AND status = 'active'") or die(mysql_error());
                            $qP     = mysql_fetch_array($queryP);
                            $numAllow = $qP['numAllow'];                               
                        
                        if($_SESSION['emp_id'] == "19708-2018" || $_SESSION['emp_id'] == "06359-2013" || $_SESSION['emp_id'] == "01476-2015" || $_SESSION['emp_id'] == "03399-2013" || $_SESSION['emp_id'] == "03553-2013"){
                        ?>
						
                        <li class = ''>
                            <a href="../placement/" class="menu-style"><span class="fa  fa-hand-o-left fa-2x" aria-hidden="true"></span> <font style="font-size:16px; font-weight:bold;">Placement</font></a>
                        </li> 
                        <?php } 

                        if($_SESSION['emp_id'] == "04387-2017"){

                            echo '
                                    <li>
                                        <a href="../promo/" class="menu-style"><span class="fa  fa-hand-o-left fa-2x" aria-hidden="true"></span> <font style="font-size:16px; font-weight:bold;">Promo</font></a>
                                    </li> 
                                ';
                        }

                        ?>

                        <li class = ''>
                            <a href="?p=message" class="menu-style"><span class="fa fa-envelope-o fa-2x" aria-hidden="true"></span> <font style="font-size:16px; font-weight:bold;">Messages</font>
                                <span class="badge" style='background-color:red;color:white' id="badgenewmsg"><?php echo $ctrinbox;?></span>
                            </a>
                        </li>  
							
                        <li class="dropdown">       
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="fa fa-user fa-2x" style='margin-top:0px;margin-right:2px' width="20" height="20"> </span><font style="font-size:16px; font-weight:bold;"> <?php echo ucwords(strtolower($nq->getApplicantName($_SESSION['emp_id']))); ?></font>
                            <b class="caret"></b></a> 
                            <ul class="dropdown-menu">  
                                <li style='width:320px;margin-left:auto;margin-right:auto'> 
                                    <div class="row">
                                        <table align='center'>
                                            <tr>
                                                <td><img src='<?php echo $nq->getPhoto($_SESSION['emp_id']);?>'  height='130' width='130' class='img-responsive' style='border-radius:50%'></td>
                                                <td>
                                                <button class="buttonx" id='changeusername'> Change Username</button>
                                                <button class="buttonx" id='changepassword'> Change Password&nbsp; </button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li><button style='width:60px;margin-left:250px;margin-top:5px' id='logout' class="buttonx">Logout</button></li>        
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
					<!--li>
					<img src="../images/icons/nesco-png.png" width='205 ' height='70'/>
                    </li-->
					<li>
						<table style='margin:3%'>
							<tr>
								<td><img src='<?php echo $nq->getPhoto($_SESSION['emp_id']);?>' height='50' width='50' class='img-responsive' style='border-radius:50%'></td>
								<td>&nbsp;<span style='font-size:15px; font-style: italic; color:green'>Howdy <?php echo $nq->getFirstName($_SESSION['emp_id']);?> !</span></td>
							</tr>
						</table>                       
                    </li>
					
					<?php 

                        if(!isset($_GET['q'])){
                    ?>
                            <li>
                                <a href="?p=homeDashboard&&q=recruitment"><i class="fa fa-arrow-right"></i>Recruitment </a>
                            </li>
                            <li <?php if(@$_GET['p']=='home'){echo "class='active-link'";}?>>
                                <a href="?p=home"><i class="fa fa-dashboard"></i>Dashboard </a>
                            </li>
        					<li <?php if(@$_GET['db']=='nescoemployee'){echo "class='active-link'";}?>>
        						<a href="?p=dashboard&&db=nescoemployee"><i class="fa fa-user"></i>NESCO Employee</a></li>
        					<li <?php if(@$_GET['db']=='entries'){echo "class='active-link'";}?> >
        						<a href="?p=dashboard&&db=entries"><i class="fa fa-clipboard"></i>Entries</a></li>
                            <li <?php if(@$_GET['db']=='contracts'){echo "class='active-link'";}?> >
        						<a href="?p=dashboard&&db=contracts"><i class="fa fa-file-text"></i>Contracts</a></li>
        					<li <?php if(@$_GET['db']=='reports'){echo "class='active-link'";}?> >
        						<a href="?p=dashboard&&db=reports"><i class="fa fa-bar-chart-o"></i>Reports</a></li>                   
                            <li <?php if(@$_GET['db']=='setup'){echo "class='active-link'";}?> >
        						<a href="?p=dashboard&&db=setup"><i class="fa fa-gear"></i>Setup/Settings</a></li>
                            <?php
                            /*if($_SESSION['emp_id'] == "03399-2013" || $_SESSION['emp_id'] == "05296-2015" || $_SESSION['emp_id'] == "04478-2017"){ ?>
                            <li <?php if(@$_GET['db']=='calamityca'){echo "class='active-link'";}?> >
                                <a href="http://172.16.161.34:8080/ebs/cashadvance_vCI/hr/verify/calamity_ca"><i class="fa fa-gear"></i> Calamity CA </a></li>
        					<?php } */ ?>

                            <?php
                            $id_req103      = $_SESSION['emp_id'];
                            @$ebm_access    = mysql_query("Select ebm_user_access.ui_access_type, ebm_user_access.ui_DC_key, dr_path  from ebs.ebm_user_access where ebm_user_access.ui_emp_id = '$id_req103' AND ebm_user_access.access_status = 'ON'") or die(mysql_error());
                            $count_acces    = mysql_num_rows($ebm_access);
                            $sbm_fetch      = mysql_fetch_array($ebm_access);
                            if($count_acces>=1){    
                                echo "<li><a href='".$sbm_fetch['dr_path']."'><i class='fa fa-gear'></i>Employee Benefits </a></li>";
                            }
                            ?>

                            <?php if($numAllow): ?>          
                              <li class="nav-item dropdown active">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-dashboard"></i>Timekeeping <i class="fa fa-angle-left pull-right fa-angle-down"></i></a>
                                <div class="dropdown-menu">
                                  <br>
                                  <a style = "text-decoration:none" href="http://172.16.161.34/timekeeping/others/declare/index.php?id=<?php echo $_SESSION['emp_id'];?>&status=in&type=nesco_payroll&type2=nesco_payroll" dropdown-item>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right">&nbsp;</i>Corp-Server</a>
                                  <hr>
                                  <a style = "text-decoration:none" href="http://172.16.90.220:8080/timekeeping/others/declare/index.php?id=<?php echo $_SESSION['emp_id'];?>&status=in&type=nesco_payroll&type2=nesco_payroll" dropdown-item>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right">&nbsp; Tal-Server</i></a>
                                  <hr>
                                  <a style = "text-decoration:none" href="http://172.16.221.1/timekeeping/others/declare/index.php?id=<?php echo $_SESSION['emp_id'];?>&status=in&type=nesco_payroll&type2=nesco_payroll" dropdown-item>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right">&nbsp; Tub-Server</i></a>
                                  <hr>
                                  <a style = "text-decoration:none" href="http://172.16.105.1/timekeeping/others/declare/index.php?id=<?php echo $_SESSION['emp_id'];?>&status=in&type=nesco_payroll&type2=nesco_payroll" dropdown-item>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right">&nbsp; Ubay-Server</i></a>
                                  <br>
                                  <br>
                                </div>
                              </li>                                                   
                            <?php endif; ?>                              
        					<?php 
                            if($_SESSION['emp_id']=='03399-2013'  || $_SESSION['emp_id']=='02330-2017' || $_SESSION['emp_id']=='01476-2015'){
        					   echo "&nbsp;<center> <input type='text' name='searchsID'  class='btnx' placeholder='Search...(For ID Incharge only)'> </center> &nbsp; ";	
                            }
                        }  else {
                    ?>

                            <li <?php if(@$_GET['p']=='home'){echo "class='active-link'";}?>>
                                <a href="?p=home"><i class="fa fa-arrow-left"></i>Placement </a>
                            </li>
                            <li <?php if(@$_GET['p']=='homeDashboard'){echo "class='active-link'";}?>>
                                <a href="?p=homeDashboard&&q=recruitment"><i class="fa  fa-dashboard"></i>Dashboard </a>
                            </li>
                            <li <?php if(@$_GET['db']=='finalCompletion'){echo "class='active-link'";}?>>
                                <a href="?p=dashboard&&db=finalCompletion&&q=recruitment"><i class="fa fa-picture-o"></i>Final Completion</a>
                            </li>
                            <li <?php if(@$_GET['db']=='hiring'){echo "class='active-link'";}?> >
                                <a href="?p=dashboard&&db=hiring&&q=recruitment"><i class="fa fa-thumbs-o-up"></i>Hiring</a>
                            </li>
                            <li <?php if(@$_GET['db']=='deployment'){echo "class='active-link'";}?> >
                                <a href="?p=dashboard&&db=deployment&&q=recruitment"><i class="fa fa-user"></i>Deployment</a>
                            </li>
                            <li <?php if(@$_GET['db']=='searchApp'){echo "class='active-link'";}?> >
                                <a href="?p=searchApp&&db=searchApp&&q=recruitment"><i class="fa fa-search "></i>Search Applicant</a>
                            </li>
                    <?php
                        } ?>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
				<!--
                <div class="row">
                    <div class="col-lg-12">
                     <h2>NESCO DASHBOARD</h2>   
                    </div>
                </div>             
                <hr />-->
				
				<div id='body'>
				 <!-- page content -->
				&nbsp; 
				<?php include 'pages/'.@$page;?>
				<!-- /page content -->
				</div>
			
                
                  <!-- /. ROW  --> 
			</div>
             <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <div class="footer">		
        <div class="row">
            <div class="col-lg-12" >
                &copy; All rights reserved: NESCO HRMS | ALTURAS GROUP OF COMPANIES </div>
        </div>
    </div> 

<script type="text/javascript">
    function searchEmp(){

        var search = $("[name = 'searchs']").val();
        window.location = '?p=searchemployee&&search='+search;
    }
</script>
	
<?php include('footer.php'); ?>
