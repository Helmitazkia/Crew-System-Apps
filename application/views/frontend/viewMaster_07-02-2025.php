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
		        yearRange: "-70:+4"
		    });
		});
		function buttonMenuMaster(type)
		{
			$("#dataTableMaster").empty();
			$("#idLoading").show();
			refreshButton();

			if(type == "certificate")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataCertificate"); ?>', function() {
					$("#idLiMasterName").text(" > Certificate");
					$('#btnMasterCert').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "city")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataCity"); ?>', function() {
					$("#idLiMasterName").text(" > City");
					$('#btnMasterCity').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "company")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataCompany"); ?>', function() {
					$("#idLiMasterName").text(" > Company");
					$('#btnMasterCompany').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "country")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataCountry"); ?>', function() {
					$("#idLiMasterName").text(" > Country");
					$('#btnMasterCountry').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "rank")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataRank"); ?>', function() {
					$("#idLiMasterName").text(" > Rank");
					$('#btnMasterRank').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "vessel")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataVessel"); ?>', function() {
					$("#idLiMasterName").text(" > Vessel");
					$('#btnMasterVessel').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
			else if(type == "vesselType")
			{
				$("#dataTableMaster").load('<?php echo base_url("master/getDataVesselType"); ?>', function() {
					$("#idLiMasterName").text(" > Vessel Type");
					$('#btnMasterVesselType').attr('class', 'btn btn-danger btn-sm');
					$("#idLoading").hide();
				});
			}
		}

		function refreshButton()
		{
			$('#btnMasterCert').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterCity').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterCountry').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterCompany').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterRank').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterVessel').attr('class', 'btn btn-primary btn-sm');
			$('#btnMasterVesselType').attr('class', 'btn btn-primary btn-sm');
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('personal/getData');?>";
		}
	</script>
</head>
<body>
	<!-- <section style="padding: 0px 45px;"> -->
	<div class="container-fluid" style="background-color:#D4D4D4;min-height:600px;">
		<div class="form-panel" style="padding-top:5px;display:;" id="idDataTable">
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="col-md-4 col-xs-12">
						<ol class="breadcrumb" style="margin-bottom:0px;">
							<li><a href="<?php echo base_url('master/'); ?>" style="color:#067780;font-weight:bold;">MASTER</a> </li>
					  		<li class="active" id="idLiMasterName"></li>
						</ol>
					</div>
					<div class="col-md-8 col-xs-12">
						<legend style="text-align:right;margin-bottom:0px;">
							<img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
							<b><i>:: Master Data ::</i></b>
						</legend>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2 col-xs-12">
					<div class="btn-group-vertical btn-block" role="group">
						<div class="btn-group" role="group" style="padding:5px;">
							<button type="button" class="btn btn-primary btn-sm" title="Certificate" style="font-weight:bold;" onclick="buttonMenuMaster('certificate');" id="btnMasterCert">Certificate</button>
						</div>
						<div class="btn-group" role="group" style="padding:5px;">
							<button type="button" class="btn btn-primary btn-sm" title="City" style="font-weight:bold;" onclick="buttonMenuMaster('city');" id="btnMasterCity">City</button>
					  	</div>
					  	<div class="btn-group" role="group" style="padding:5px;">
					    	<button type="button" class="btn btn-primary btn-sm" title="Country" style="font-weight:bold;" onclick="buttonMenuMaster('country');" id="btnMasterCountry">Country</button>
					  	</div>
					  	<div class="btn-group" role="group" style="padding:5px;">
					    	<button type="button" class="btn btn-primary btn-sm" title="Company" style="font-weight:bold;" onclick="buttonMenuMaster('company');" id="btnMasterCompany">Company</button>
					  	</div>
					  	<div class="btn-group" role="group" style="padding:5px;">
					    	<button type="button" class="btn btn-primary btn-sm" title="Company" style="font-weight:bold;" onclick="buttonMenuMaster('rank');" id="btnMasterRank">Rank</button>
					  	</div>
					  	<div class="btn-group" role="group" style="padding:5px;">
					    	<button type="button" class="btn btn-primary btn-sm" title="Company" style="font-weight:bold;" onclick="buttonMenuMaster('vessel');" id="btnMasterVessel">Vessel</button>
					  	</div>
					  	<div class="btn-group" role="group" style="padding:5px;">
					    	<button type="button" class="btn btn-primary btn-sm" title="Company" style="font-weight:bold;" onclick="buttonMenuMaster('vesselType');" id="btnMasterVesselType">Vessel Type</button>
					  	</div>
					</div>
				</div>
				<div class="col-md-10 col-xs-12" id="dataTableMaster">					
				</div>
			</div>
		</div>
	</div>
	<!-- </section> -->
</body>
</html>