<script src="<?php echo base_url();?>assets/js/bootstrap-select.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.css">
<script type="text/javascript">
	$(document).ready(function(){
		$("#btnAddSea").click(function(){
			$("#teksJudulPage").html("<b><i>:: Form Sea Experience ::</i></b>");
			$("#idDataTableCC").hide();
			$("#idFormCC").show(100);
		});
		$('[id^=optSlc]').selectpicker('refresh');
	});
	function saveData()
	{
		var idEdit = $("#txtIdEditSeaExp").val();
		var idPerson = $("#txtIdPerson").val();
		var company = $("#txtCompanySeaExp").val();
		var flag = $("#txtFlagSeaExp").val();
		var vessel = $("#txtVesselSeaExp").val();
		var type = $("#optSlc_Type").val();
		var grt = $("#txtGrtSeaExp").val();
		var dwt = $("#txtDwtSeaExp").val();
		var mainEngine = $("#txtEngineSeaExp").val();
		var bhp = $("#txtBhpSeaExp").val();
		var rank = $("#optSlc_Rank").val();
		var fromDate = $("#txtDate_fromSeaExp").val();
		var toDate = $("#txtDate_ToSeaExp").val();
		var reason = $("#txtReasonSeaExp").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("seaExperiance/saveData"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,company : company,flag : flag,vessel : vessel,type : type,grt : grt,dwt : dwt,mainEngine : mainEngine,bhp : bhp,rank : rank,fromDate : fromDate,toDate : toDate,reason : reason },
			function(data)
			{
				alert(data);
				$("#idLoadingForm").hide();
				navProsesCrew();
			},
		"json"
		);
	}

	function getDataEdit(id)
	{
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("seaExperiance/getDataEdit"); ?>',
		{ id : id,idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditSeaExp").val(id);
				$("#txtCompanySeaExp").val(data.company);
				$("#txtFlagSeaExp").val(data.flag);
				$("#txtVesselSeaExp").val(data.vessel);
				$("#optSlc_Type").val(data.type);
				$("#txtGrtSeaExp").val(data.grt);
				$("#txtDwtSeaExp").val(data.dwt);
				$("#txtEngineSeaExp").val(data.mainEngine);
				$("#txtBhpSeaExp").val(data.bhp);
				$("#optSlc_Rank").val(data.rank);
				$("#txtDate_fromSeaExp").val(data.dateFrom);
				$("#txtDate_ToSeaExp").val(data.dateTo);
				$("#txtReasonSeaExp").val(data.reason);

				$("#idDataTableCC").hide();
				$("#idFormCC").show(100);
				$("#idLoadingForm").hide();
				$('[id^=optSlc]').selectpicker('refresh');
			},
		"json"
		);
	}

	function delData(id,idPerson)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("seaExperiance/deleteData"); ?>/',
			{ id : id,idPerson : idPerson },
				function(data) 
				{
					alert(data);
					navProsesCrew();
				},
			"json"
			);
		}
	}
</script>

<div class="row" id="idFormCC" style="display:none;">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-2 col-xs-12">
				<label for="txtCompanySeaExp">Company :</label>
				<input type="text" class="form-control" id="txtCompanySeaExp" value="" placeholder="Company">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtFlagSeaExp">Flag Name :</label>
				<input type="text" class="form-control" id="txtFlagSeaExp" value="" placeholder="Flag Name">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtVesselSeaExp">Vessel Name :</label>
				<input type="text" class="form-control" id="txtVesselSeaExp" value="" placeholder="Vessel">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="optSlc_Type">Type :</label>
				<select class="form-control" id="optSlc_Type" data-live-search="true">
					<?php echo $optType; ?>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtGrtSeaExp">GRT :</label>
				<input type="text" class="form-control" id="txtGrtSeaExp" value="" placeholder="GRT">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDwtSeaExp">DWT :</label>
				<input type="text" class="form-control" id="txtDwtSeaExp" value="" placeholder="DWT">
			</div>
		</div>
		<div class="row">
			<div class="col-md-2 col-xs-12">
				<label for="txtEngineSeaExp">Main Engine :</label>
				<input type="text" class="form-control" id="txtEngineSeaExp" value="" placeholder="Main Engine">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtBhpSeaExp">BHP :</label>
				<input type="text" class="form-control" id="txtBhpSeaExp" value="" placeholder="BHP">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="optSlc_Rank">Rank :</label>
				<select class="form-control" id="optSlc_Rank" data-live-search="true">
					<?php echo $optRank; ?>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_fromSeaExp">Date From :</label>
				<input type="text" class="form-control" id="txtDate_fromSeaExp" value="" placeholder="Date">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_ToSeaExp">Date To :</label>
				<input type="text" class="form-control" id="txtDate_ToSeaExp" value="" placeholder="Date">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtReasonSeaExp">Reason of Sign Off :</label>
				<input type="text" class="form-control" id="txtReasonSeaExp" value="" placeholder="Reason">
			</div>
		</div>
		<div class="row" style="margin-top:15px;">
			<input type="hidden" id="txtIdEditSeaExp" value="">
			<div class="col-md-6 col-xs-12">
				<button class="btn btn-primary btn-xs btn-block" title="Submit Data" onclick="saveData();">
					<i class="glyphicon glyphicon-savede"></i> Submit</button>
			</div>
			<div class="col-md-6 col-xs-12">
				<button class="btn btn-danger btn-xs btn-block" title="Cancel Data" onclick="navProsesCrew();">
					<i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top:5px;" id="idDataTableCC">
	<div class="col-md-12 col-xs-12">
		<div class="row" style="margin-top:5px;">
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-primary btn-xs btn-block" title="Add Data" id="btnAddSea"><i class="fa fa-plus"></i> Add</button>
			</div>
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="navProsesCrew();"><i class="fa fa-refresh"></i> Refresh</button>
			</div>
		</div>
		<div class="row" style="margin-top:5px;">
			<div class="col-md-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
						<thead>
							<tr style="background-color:#067780;color:#FFF;height:30px;">
								<th style="vertical-align:middle;width:3%;text-align:center;">No</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Company</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Flag Name</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Vessel Name</th>
								<th style="vertical-align:middle;width:7%;text-align:center;">Type</th>
								<th style="vertical-align:middle;width:7%;text-align:center;">GRT</th>
								<th style="vertical-align:middle;width:7%;text-align:center;">DWT</th>
								<th style="vertical-align:middle;width:7%;text-align:center;">Main Engine</th>
								<th style="vertical-align:middle;width:7%;text-align:center;">BHP</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Rank</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date&nbspFrom</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date&nbspTo</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Reason&nbspof Signoff</th>
								<th style="vertical-align:middle;width:5%;text-align:center;">Action</th>
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