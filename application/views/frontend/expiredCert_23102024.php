<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
	$(document).ready(function(){
		$("#slcSearchTypeCert").change(function(){
			var typeCert = $(this).val();
			$("#txtSearch").val("");
			$("#slcSearchType").val("crew");

			if(typeCert == "")
			{
				btnNavigasiDisabled("");
			}else{
				btnNavigasiDisabled("passport");
			}
			
			searchData('passport');			
		});

	});

	function searchData(typeButton)
	{
		var typeCert = $("#slcSearchTypeCert").val();		
		
		if(typeButton != "")
		{
			$("#txtIdBtnSelect").val(typeButton);
			$("#txtSearch").val("");
			$("#slcSearchType").val("crew");
		}

		if(typeButton == "")
		{
			typeButton = $("#txtIdBtnSelect").val();
		}

		if(typeCert == "")
		{
			btnNavigasiDisabled("");
		}else{
			btnNavigasiDisabled(typeButton);
		}

		var slcSearchType = $("#slcSearchType").val();
		var txtSearch = $("#txtSearch").val();

		$("#idLoading").show();
		$("#idTbody").empty();
		$.post('<?php echo base_url("expiredCertificate/getData"); ?>'+'/search',
		{ typeCert : typeCert,typeButton : typeButton,slcSearchType : slcSearchType,txtSearch : txtSearch },
			function(data)
			{				
				$("#idTbody").append(data.trNya);
				$("#idLoading").hide();
			},
		"json"
		);
	}

	function printData()
	{
		var typeCert = $("#slcSearchTypeCert").val();
		var typeSearch = $("#slcSearchType").val();
		var txtsearch = $("#txtSearch").val();
		var typeButton = $("#txtIdBtnSelect").val();

		if(typeCert != "")
		{
			window.open("<?php echo base_url('expiredCertificate/printData');?>/"+typeCert+"/"+typeButton+"/"+typeSearch+"/"+txtsearch,"_blank");
		}
		// window.open("<?php echo base_url('expiredCertificate/printData');?>","_blank");
		
	}

	function btnNavigasiDisabled(typeNya)
	{
		if(typeNya == "")
		{
			$("#btnSearchPassport").attr("disabled",true);
			$("#btnSearchSeaman").attr("disabled",true);
			$("#btnSearchCert").attr("disabled",true);
			$("#btnSearchPanamaCert").attr("disabled",true);
		}else{
			$("#btnSearchPassport").attr("disabled",false);
			$("#btnSearchSeaman").attr("disabled",false);
			$("#btnSearchCert").attr("disabled",false);
			$("#btnSearchPanamaCert").attr("disabled",false);
		
			if (typeNya == "passport")
			{
				$("#idLblNavigasi").text("Passport");
				$("#btnSearchPassport").attr("disabled",true);
			}

			if (typeNya == "seaman")
			{
				$("#idLblNavigasi").text("Seaman Book");
				$("#btnSearchSeaman").attr("disabled",true);
			}

			if (typeNya == "certificates")
			{
				$("#idLblNavigasi").text("Certificates");
				$("#btnSearchCert").attr("disabled",true);
			}

			if (typeNya == "panama")
			{
				$("#idLblNavigasi").text("Panama Certificates");
				$("#btnSearchPanamaCert").attr("disabled",true);
			}
		}
	}

	function reloadPage()
	{
		window.location = "<?php echo base_url('expiredCertificate/');?>";
	}
</script>
</head>
<body>
	<div class="container-fluid" style="background-color:#D4D4D4;min-height:550px;">
		<div class="form-panel" style="margin-top:5px;" id="idDataTableCont">
			<legend style="margin-bottom:5px;">
				<div class="row">
					<div class="col-md-4 col-xs-12">
						<span style="color:red;font-size:11px;font-weight:bold;padding-left:20px;">* (Will expire), ** (Over due)</span>
					</div>
					<div class="col-md-4 col-xs-12" style="text-align:center;">
						<span id="idLblNavigasi" style="font-weight:bold;color:#067780;"></span>
					</div>
					<div class="col-md-4 col-xs-12" style="text-align:right;">
						<img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
						<b><i>:: Expired Certificates ::</i></b>
					</div>
				</div>
			</legend>
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="row" style="margin-top:5px;">
						<div class="col-md-2 col-xs-12">
							<select class="form-control input-sm" id="slcSearchTypeCert">
								<option value="">- Type Certificate -</option>
								<option value="allCert">ALL CERTIFICATE / DOCUMENT</option>
								<option value="compCert">COMPLIANCE CERTIFICATES</option>
							</select>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="btn-group btn-group-justified" role="group" aria-label="Status Crew">
								<div class="btn-group" role="group">
									<input type="hidden" id="txtIdBtnSelect" value="">
									<button type="button" id="btnSearchPassport" onclick="searchData('passport');" class="btn btn-primary btn-xs" title="Passport" style="font-weight:bold;" disabled="disabled">Passport</button>
								</div>
								<div class="btn-group" role="group">
									<button type="button" id="btnSearchSeaman" onclick="searchData('seaman');" class="btn btn-primary btn-xs" title="Seaman Book" style="font-weight:bold;" disabled="disabled">Seaman Book</button>
								</div>
								<div class="btn-group" role="group">
									<button type="button" id="btnSearchCert" onclick="searchData('certificates');" class="btn btn-primary btn-xs" title="Certificates" style="font-weight:bold;" disabled="disabled">Certificates</button>
								</div>
								<div class="btn-group" role="group">
									<button type="button" id="btnSearchPanamaCert" onclick="searchData('panama');" class="btn btn-primary btn-xs" title="Panama Certificates" style="font-weight:bold;" disabled="disabled">Panama Cert.</button>
								</div>
							</div>
						</div>
						<div class="col-md-1 col-xs-12">
							<select class="form-control input-sm" id="slcSearchType">
								<option value="crew">CREW</option>
	                            <option value="cert">CERTIFICATE</option>
	                            <option value="country">COUNTRY</option>
	                            <option value="noDoc">NO. DOC</option>
	                            <option value="expMonth">EXP. MONTH</option>
							</select>
						</div>
						<div class="col-md-2 col-xs-12">
							<input type="text" class="form-control input-sm" id="txtSearch" placeholder="SearchText..">
						</div>
						<div class="col-md-1 col-xs-12">
							<button class="btn btn-info btn-xs btn-block" title="Refresh" onclick="searchData('');">
								<i class="fa fa-check-square-o"></i> Show</button>
						</div>
						<div class="col-md-1 col-xs-12">
							<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="reloadPage();"><i class="fa fa-refresh"></i> Refresh</button>
						</div>
						<div class="col-md-1 col-xs-12">
							<button class="btn btn-primary btn-xs btn-block" title="Export" onclick="printData();">
								<i class="fa fa-print"></i> Export</button>
						</div>
					</div>
					<div class="row" style="margin-top:5px;">
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
									<thead>
										<tr style="background-color:#067780;color:#FFF;height:30px;">
											<th style="vertical-align:middle;width:3%;text-align:center;">No</th>
											<th style="vertical-align:middle;width:20%;text-align:center;">Crew Name</th>
											<th style="vertical-align:middle;width:20%;text-align:center;">Certificate Name</th>
											<th style="vertical-align:middle;width:15%;text-align:center;">Country / Place</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">No Document</th>
											<th style="vertical-align:middle;width:13%;text-align:center;">Date Of Issue</th>
											<th style="vertical-align:middle;width:14%;text-align:center;">Date of Expiry</th>
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
			</div>
		</div>
	</div>
	<button onclick="topFunction()" id="myBtnToOnTop" title="Go to top"><i class="glyphicon glyphicon-eject"></i></button>
</body>
</html>