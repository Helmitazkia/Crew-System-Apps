<?php
	if(!$this->session->userdata('idUserCrewSystem'))
	{
		redirect(base_url());
	}
?>
<!doctype html>
<html lang="en">
    <head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Crewing System">
    	<meta name="author" content="andhika group">
        <title>Crewing System</title>

		<link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
        <!-- <link rel="stylesheet" href="assets/css/magnific-popup.css"> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
		<!-- <link rel="stylesheet" href="assets/css/owl.theme.default.min.css"/> -->
        <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
        <!-- <link rel="stylesheet" href="assets/css/bootsnav.css"/> -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
        <style type="text/css">
        	#myBtnToOnTop {
					  display: none;
					  position: fixed;
					  bottom: 20px;
					  right: 30px;
					  z-index: 99;
					  font-size: 24px;
					  border: none;
					  outline: none;
					  background-color:red;
					  color: white;
					  cursor: pointer;
					  padding: 10px;
					  border-radius: 22px;
					}
			#myBtnToOnTop:hover { background-color: #555; }
        </style>
    </head>
	
	<body style="background-color:#d1e9ef;">
		<div class="clearfix visible-lg-block visible-md-block">
		<section class="header" style="padding-top:10px;padding-bottom:5px;">
			<div class="container">
				<div class="header-left">
					<a class="navbar-brand" href="" style="margin: 0px;">
						<img src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;">
					</a>					
				</div>
				<label style="padding:5px;font-size:30px;color:#000080;"> ANDHIKA GROUP </label>
			</div>
		</section>
		</div>
		<section id="menu" style="background-color:#067780;height:50px;">
			<div class="container">
				<div class="menubar">
					<nav class="navbar navbar-default" style="margin-bottom:0px;">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#idMenuNav" aria-expanded="false" title="Menu Crew's">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" style="color:#FFFFFF;font-size:28px;font-weight:bold;margin-top:0px;padding-top:10px;font-family: serif;">
								Crewing System
							</a>
						</div>
						<div class="collapse navbar-collapse" id="idMenuNav">
							<ul class="nav navbar-nav navbar-right">
								<li id="idLiHome">
									<a href="<?php echo base_url('dashboard'); ?>">Home</a>
								</li>
								<li id="idLiPersonal">
									<a href="<?php echo base_url('personal/getData'); ?>" title="Personal / Crew">Personal</a>
								</li>
								<li id="idLiContract">
									<a href="<?php echo base_url('contract/getDataCrewStatus'); ?>" title="Contract Crew">Contract</a>
								</li>
								<li id="idLiExpCert">
									<a href="<?php echo base_url('expiredCertificate/getData'); ?>" title="Expired Certificate">Expired Certificate</a>
								</li>
								<li id="idLiReport">
									<a href="<?php echo base_url('report/'); ?>" title="Report Data">Report</a>
								</li>
								<!-- <li id="idLiMaster">
									<a href="team.html">Master</a>
								</li> -->
								<li id="idLiLogOut">
									<a href="<?php echo base_url('personal/logOut'); ?>">Logout</a>
								</li>
							</ul><!-- / ul -->
						</div><!-- /.navbar-collapse -->
					</nav><!--/nav -->
				</div><!--/.menubar -->
			</div><!-- /.container -->
		</section>

		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<!-- <script type="text/javascript" src="assets/js/bootsnav.js"></script> -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
		<!-- <script type="text/javascript" src="assets/js/jquery.magnific-popup.min.js"></script> -->
		<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
		<!-- <script type="text/javascript" src="assets/js/jquery.counterup.min.js"></script> -->
		<!-- <script type="text/javascript" src="assets/js/waypoints.min.js"></script> -->
        <!-- <script type="text/javascript" src="assets/js/jak-menusearch.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
		
        <script type="text/javascript">
        	$(document).ready(function() {
        		$(".wrapper-sticky").css('height','50');
        		
        		window.onscroll = function() {
					if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
					{
		        		document.getElementById("myBtnToOnTop").style.display = "block";
				    }else{
				        document.getElementById("myBtnToOnTop").style.display = "none";
				    }
				};
        	});
        	function topFunction()
        	{ 
				$('html, body').animate({scrollTop:0}, 'smooth');//fast,smooth,slow
			}
        </script>
    </body>
	
</html>