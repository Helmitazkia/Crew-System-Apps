<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

	<script type="text/javascript">
		function displayOnBoard()
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("dashboard/getDetailOnBoard"); ?>',
			{ },
				function(data)
				{
					$("#idBodyModal").empty();
					$("#idBodyModal").append(data.trNya);

					$("#idLblModal").text(data.totalCrew);

					$('#modalReqDetail').modal('show');
					$("#idLoading").hide();
				},
			"json"
			);
		}
		
		function displayNewApplicent()
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("dashboard/getDetailCrewNewApplicent"); ?>',
			{ },
				function(data)
				{
					$("#idBodyModalCrew").empty();
					$("#idBodyModalCrew").append(data.trNya);

					$("#idLblModalTotalCrew").text(data.totalCrew);

					$('#modalShowCrewing').modal('show');
					$("#idLoading").hide();
				},
			"json"
			);
		}

		function getDetailCrew(vslCode)
		{
			$("#idLoadingModal").show();
			$.post('<?php echo base_url("dashboard/getDetailCrewOnBoard"); ?>',
			{ vslCode : vslCode },
				function(data)
				{
					$("#idBodyModalCrewDetail").empty();
					$("#idBodyModalCrewDetail").append(data.trNya);
					$("#idLblModalVesselDetail").text(data.vessel);

					$("#idLoadingModal").hide();
				},
			"json"
			);
		}
	</script>
</head>
<body>
	<div class="container" style="background-color:;">
		<div class="form-panel" style="margin-top:5px;padding-bottom:15px;">
			<legend style="text-align:right;color:#067780;">
				<img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
				<b><i>:: DASHBOARD ::</i></b>
			</legend>
			<div class="row">
				<div class="col-md-3"></div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-6">
					<label style="font-size:18px;font-weight:bold;color:#067780;">Total : <?php echo $totalCrew; ?> Person (On Board & On Leave)</label>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#16839B;color:#FFFFFF;border:2px solid #000000;cursor:pointer;border-radius:30px;" onclick="displayOnBoard();">
						<div class="row">							
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa-anchor fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $onBoard; ?></p>
								<p style="font-size:20px;text-align:center;font-weight:bold;">On Board</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#078415;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
					<div class="panel-heading" style="background-color:#E47100;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
					<div class="panel-heading" style="background-color:#C80000;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
			<div class="row" style="margin-top:10px;">
				<div class="col-lg-3 col-6"></div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#66007A;color:#FFFFFF;border:2px solid #000000;border-radius:30px;cursor:pointer;" onclick="displayNewApplicent();">
						<div class="row">
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa fa-user-plus fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $newApplicent; ?></p>
								<p style="font-size:18px;text-align:center;font-weight:bold;">New Applicent</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="panel-heading" style="background-color:#7A1A00;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
						<div class="row">
							<div class="col-xs-3" style="text-align:center;">
								<i class="fa fa fa-child fa-5x"></i>
							</div>
							<div class="col-xs-9">
								<p style="font-size:46px;text-align:center;"><?php echo $cadetOnBoard; ?></p>
								<p style="font-size:18px;text-align:center;font-weight:bold;">Cadet On Board</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<div class="modal fade" id="modalReqDetail" role="dialog">
	<div class="modal-dialog modal-lg">
	   	<div class="modal-content">
	       	<div class="modal-header" style="padding: 10px;background-color:#16839B;">
	       		<button type="button" class="close" data-dismiss="modal" style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
	       		<h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew On Board ::</i></h4>
	       	</div>
	       	<div class="modal-body" id="idModalDetail">
	       		<div class="row">
					<div class="col-md-5 col-xs-12">
						<legend style="text-align: left;margin-bottom:0px;">
							<label id="lblModal">Total : <span id="idLblModal"></span></label>
							<img id="idLoadingModal" style="display:none;" src="<?php echo base_url('assets/img/loading.gif'); ?>">
						</legend>						
						<div class="table-responsive">
							<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
								<thead>
									<tr style="background-color: #16839B;color: #FFF;height:40px;">
										<th style="vertical-align: middle; width:3%;text-align:center;">No</th>
										<th style="vertical-align: middle; width:25%;text-align:center;">Vessel Name</th>
										<th style="vertical-align: middle; width:10%;text-align:center;">Total Crew</th>
									</tr>
								</thead>
								<tbody id="idBodyModal">
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-7 col-xs-12">
						<div style="border:1px solid black;padding:10px;">
							<legend style="text-align: left;margin-bottom:0px;">
								<label id="lblModal">Vessel : <span id="idLblModalVesselDetail"></span></label>
							</legend>
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #16839B;color: #FFF;height:40px;">
											<th style="vertical-align: middle; width:3%;text-align:center;">No</th>
											<th style="vertical-align: middle; width:60%;text-align:center;">Crew Name</th>
											<th style="vertical-align: middle; width:37%;text-align:center;">Posisi</th>
										</tr>
									</thead>
									<tbody id="idBodyModalCrewDetail">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
        	</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalShowCrewing" role="dialog">
	<div class="modal-dialog modal-md">
	   	<div class="modal-content">
	       	<div class="modal-header" style="padding: 10px;background-color:#66007A;">
	       		<button type="button" class="close" data-dismiss="modal" style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
	       		<h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew New Applicent ::</i></h4>
	       	</div>
	       	<div class="modal-body" id="idDivModalCrewDetail">
	       		<div class="row">
					<div class="col-md-12 col-xs-12">						
						<div class="table-responsive">
							<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
								<thead>
									<tr style="background-color: #66007A;color: #FFF;height:40px;">
										<th style="vertical-align: middle; width:3%;text-align:center;">No</th>
										<th style="vertical-align: middle; width:50%;text-align:center;">Crew Name</th>
										<th style="vertical-align: middle; width:45%;text-align:center;">Apply For</th>
									</tr>
								</thead>
								<tbody id="idBodyModalCrew">
								</tbody>
							</table>
						</div>
					</div>
				</div>
        	</div>
		</div>
	</div>
</div>
