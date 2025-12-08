<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">

	$(document).ready(function(){
		$( "[id^=txtDate]" ).datepicker({
			dateFormat: 'yy-mm-dd',
			showButtonPanel: true,
			changeMonth: true,
			changeYear: true,
			defaultDate: new Date(),
		});
	});

	function searchData()
	{
		var txtSearch = $("#txtSearch").val();

		$("#idLoading").show();
		$.post('<?php echo base_url("report/getData/search"); ?>',
		{ txtSearch : txtSearch },
			function(data)
			{
				$("#idTbody").empty();
				$("#idTbody").append(data.trNya);

				$("#idLoading").hide();
			},
		"json"
		);
	}

	function pickUpData(id,lblName)
	{
		$("#lblPickPerson").empty();
		$("#lblPickPerson").append(lblName);
		$("#txtIdPerson").val(id);

		$("#btnPrintPrincipal").attr("disabled",false);
		$("#btnExportPrincipal").attr("disabled",false);
	}

	function printDataPrincipal()
	{
		var idPerson = $("#txtIdPerson").val();
		var company = $("#slcCompanyPrins").val();

		if(idPerson == "")
		{
			alert("Person Empty..!!!");
			return false;
		}

		console.log("Print CV Crew System Report");
		return false;
		window.open("<?php echo base_url('report/navReport');?>/"+idPerson+"/"+company,"_blank");
	}

	function reloadPage()
	{
		window.location = "<?php echo base_url('report/');?>";
	}
</script>
</head>
<body>
	<div class="container-fluid" style="background-color:#D4D4D4;min-height:500px;">
		<div class="form-panel" style="margin-top:5px;padding-bottom:15px;" id="idDataTable">
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<legend style="text-align:right;margin-bottom:5px;">
								<img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
								<b><i>:: Report Data ::</i></b>
							</legend>
						</div>
					</div>
					<div class="row" style="margin-top:5px;">
						<div class="col-md-5 col-xs-12">
							<div class="row" style="margin-top:5px;">
								<div class="col-md-8 col-xs-12">
									<input type="text" class="form-control input-sm" id="txtSearch" oninput="searchData();" placeholder="Crew Name..">
								</div>
								<div class="col-md-4 col-xs-12">
									<button class="btn btn-success btn-sm btn-block" title="Refresh" onclick="reloadPage();"><i class="fa fa-refresh"></i> Refresh</button>
								</div>
							</div>
							<div class="row" style="margin-top:5px;height:510px;overflow: auto;" id="divIdDataTable">
								<div class="col-md-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;width:100%;">
											<thead>
												<tr style="background-color:#067780;color:#FFF;height:30px;">
													<th style="vertical-align:middle;width:10%;text-align:center;">No</th>
													<th style="vertical-align:middle;width:80%;text-align:center;">Crew</th>
													<th style="vertical-align:middle;width:10%;text-align:center;">#</th>
												</tr>
											</thead>
											<tbody id="idTbody">
												<?php echo $trNya; ?>
											</tbody>
										</table>
									</div>
								</div>						
							</div>
						</div>
						<div class="col-md-7 col-xs-12">
							<input type="hidden" id="txtIdPerson" value="">
							<legend>
								<div class="row">								
									<div class="col-md-8 col-xs-12">
										<span>Principal</span>
										<span id="lblPickPerson" style="float:right;color:blue;"></span>
									</div>
								</div>
							</legend>							
							<div class="row">
								<div class="col-md-7 col-xs-12">
									<div class="row">
										<div class="col-md-3 col-xs-12">
											<label for="slcCompanyPrins">Company</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-8 col-xs-12">
											<select class="form-control input-sm" id="slcCompanyPrins">
												<?php echo $optCompany; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-5 col-xs-12">
									<div class="row">
										<div class="col-md-5 col-xs-12">
											<button class="btn btn-primary btn-sm btn-block" title="Cetak" onclick="printDataPrincipal();" id="btnPrintPrincipal" disabled="disabled">
												<i class="fa fa-print"></i> Print
											</button>
										</div>
										<div class="col-md-5 col-xs-12">
											<!-- <button class="btn btn-info btn-sm btn-block" title="Export Excel" onclick="" id="btnExportPrincipal" disabled="disabled">
												<i class="fa fa-file-excel-o"></i>&nbsp Export
											</button> -->
										</div>
									</div>
								</div>
							</div>
							<!-- <legend style="margin-top:15px;">Data Convert</legend>
							<div class="row">
								<div class="col-md-7 col-xs-12">
									<div class="row">
										<div class="col-md-3 col-xs-12">
											<label for="slcCompanyPrins">Status</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-4 col-xs-12">
											<select class="form-control input-sm" id="slcStatusDataConv">
												<option value="all">All</option>
					                            <option value="nonaktif">Non Aktif</option>
					                            <option value="notforemp">Not for Emp.</option>
					                            <option value="onboard">On Board</option>
					                            <option value="onleave">On Leave</option>
											</select>
										</div>
									</div>
									<div class="row" style="margin-top:5px;">
										<div class="col-md-3 col-xs-12">
											<label for="slcCompanyPrins">Page</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-8 col-xs-12">
											<select class="form-control input-sm" id="slcStatusDataConv">
												<option value="personal_data">Personal Data</option>
												<option value="personal_id">Personal Id</option>
												<option value="family">Family Details</option>
												<option value="certificate">All Certificate / Document</option>
												<option value="compliance">Compliance Certificate</option>
												<option value="sea">Sea Experiance</option>
												<option value="general">General</option>
												<option value="language">Language Knowledge</option>
												<option value="education">Education Attainment</option>
												<option value="contract">Contract</option>
												<option value="other">Others</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-5 col-xs-12">
									<div class="row">
										<div class="col-md-5 col-xs-12">
											<button class="btn btn-primary btn-sm btn-block" title="Cetak" onclick="">
												<i class="fa fa-print"></i> Print
											</button>
										</div>
										<div class="col-md-5 col-xs-12">
											<button class="btn btn-info btn-sm btn-block" title="Export Excel" onclick="">
												<i class="fa fa-file-excel-o"></i>&nbsp Export
											</button>
										</div>
									</div>
								</div>
							</div>
							<legend style="margin-top:15px;">Monthly Changes</legend>
							<div class="row">
								<div class="col-md-7 col-xs-12">
									<div class="row">
										<div class="col-md-3 col-xs-12">
											<label for="slcCompanyMonthly">Company</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-4 col-xs-12">
											<select class="form-control input-sm" id="slcCompanyMonthly">
												<?php echo $optCompany; ?>
											</select>
										</div>
									</div>
									<div class="row" style="margin-top:5px;">
										<div class="col-md-3 col-xs-12">
											<label for="txtDate_Start">Start Date</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-5 col-xs-12">
											<input type="text" class="form-control input-sm" id="txtDate_Start" placeholder="Start Date">
										</div>
									</div>
									<div class="row" style="margin-top:5px;">
										<div class="col-md-3 col-xs-12">
											<label for="txtDate_End">End Date</label>
											<span style="float:right;"><b>:</b></span>
										</div>
										<div class="col-md-5 col-xs-12">
											<input type="text" class="form-control input-sm" id="txtDate_End" placeholder="End Date">
										</div>
									</div>
								</div>
								<div class="col-md-5 col-xs-12">
									<div class="row">
										<div class="col-md-5 col-xs-12">
											<button class="btn btn-primary btn-sm btn-block" title="Cetak" onclick="">
												<i class="fa fa-print"></i> Print
											</button>
										</div>
									</div>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>