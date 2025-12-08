<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>
<body>
	<div class="container" style="background-color:;">
		<div class="form-panel" style="margin-top:5px;padding-bottom:15px;">
			<legend style="text-align:right;color:#067780;">
				<img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
				<b><i>:: DASHBOARD ::</i></b>
			</legend>
			<div class="row">
				<div class="col-md-3">
					
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-6">
					<label style="font-size:18px;font-weight:bold;color:#067780;">Total : <?php echo $totalCrew; ?> Person</label>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#16839B;color:#FFFFFF;border:2px solid #000000;">
						<div class="row">							
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa-group fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $onBoard; ?></p>
								<p style="font-size:20px;text-align:center;font-weight:bold;">On Board</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#078415;color:#FFFFFF;border:2px solid #000000;">
						<div class="row">							
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa-user-circle fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $onLeave; ?></p>
								<p style="font-size:20px;text-align:center;font-weight:bold;">On Leave</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#E47100;color:#FFFFFF;border:2px solid #000000;">
						<div class="row">							
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa-user-circle-o fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $nonAktif; ?></p>
								<p style="font-size:20px;text-align:center;font-weight:bold;">Non Aktif</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#C80000;color:#FFFFFF;border:2px solid #000000;">
						<div class="row">							
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa-user-secret fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $notForEmp; ?></p>
								<p style="font-size:18px;text-align:center;font-weight:bold;">Not for Employeed</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>