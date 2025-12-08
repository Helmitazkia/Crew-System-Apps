<script type="text/javascript">
	$(document).ready(function(){
		$("#btnAddPI").click(function(){
			var tksName = $("#teksJudulName").text();
			$("#teksJudulPage").html("<b><i>:: Form Personal Id ::</i></b>");
			$("#divIdDataTable").hide();
			$("#txtPersonalName").val(tksName);
			$("#divIdForm").show(100);
		});
	});
	function saveData()
	{
		var idEdit = $("#txtIdEditPI").val();
		var idPerson = $("#txtIdPerson").val();
		var txtIssueAtPlace = $("#txtIssueAtPlace").val();
		var slcCountryIssuePI = $("#slcCountryIssuePI").val();
		var txtDate_issuePI = $("#txtDate_issuePI").val();
		var txtDate_validUntiPI = $("#txtDate_validUntiPI").val();
		var txtTypeDocPI = $("#txtTypeDocPI").val();
		var txtNoDocPI = $("#txtNoDocPI").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("personal/saveDataPersonalId"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,txtIssueAtPlace : txtIssueAtPlace,slcCountryIssuePI : slcCountryIssuePI,txtDate_issuePI : txtDate_issuePI,txtDate_validUntiPI : txtDate_validUntiPI,txtTypeDocPI : txtTypeDocPI,txtNoDocPI : txtNoDocPI },
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
		var tksName = $("#teksJudulName").text();
		$("#txtPersonalName").val(tksName);
		$("#teksJudulPage").html("<b><i>:: Form Personal Id ::</i></b>");

		$.post('<?php echo base_url("personal/getDataProses"); ?>',
		{ id : id,idPerson : idPerson,type : "editPersonalId" },
			function(data)
			{
				$("#txtIdEditPI").val(id);
				$("#txtIssueAtPlace").val(data.issuePlace);
				$("#slcCountryIssuePI").val(data.country);
				$("#txtDate_issuePI").val(data.dateIssue);
				$("#txtDate_validUntiPI").val(data.dateValid);
				$("#txtTypeDocPI").val(data.typeDoc);
				$("#txtNoDocPI").val(data.noDoc);

				$("#divIdDataTable").hide();
				$("#divIdForm").show(100);
				$("#idLoadingForm").hide();
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
			$.post('<?php echo base_url("personal/deleteData"); ?>/',
			{ id : id,idPerson : idPerson,type : "deletePersonalId" },
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

<div class="row" id="divIdForm" style="display:none;">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-3 col-xs-12">
				<label for="txtPersonalName">Name Personal :</label>
				<input type="text" class="form-control input-sm" id="txtPersonalName" value="" disabled="disabled">
			</div>
			<div class="col-md-3 col-xs-12">
				<label for="txtIssueAtPlace">Issue at (Place) :</label>
				<input type="text" class="form-control input-sm" id="txtIssueAtPlace" value="" placeholder="Issue at Place">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcCountryIssuePI">Country of Issue :</label>
				<select class="form-control input-sm" id="slcCountryIssuePI">
					<?php echo $optCountry; ?>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_issuePI">Date of Issue :</label>
				<input type="text" class="form-control input-sm" id="txtDate_issuePI" value="" placeholder="Date">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_validUntiPI">Valid Until :</label>
				<input type="text" class="form-control input-sm" id="txtDate_validUntiPI" value="" placeholder="Date">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-xs-12">
				<label for="txtTypeDocPI">Type of Document :</label>
				<input type="text" class="form-control" id="txtTypeDocPI" value="" placeholder="Type of Document">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtNoDocPI">No Document :</label>
				<input type="text" class="form-control" id="txtNoDocPI" value="" placeholder="No Document">
			</div>
		</div>
		<div class="row" style="margin-top:15px;">
			<input type="hidden" id="txtIdEditPI" value="">
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
<div class="row" style="margin-top:5px;display:;" id="divIdDataTable">
	<div class="col-md-12 col-xs-12">
		<div class="row" style="margin-top:5px;">
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-primary btn-xs btn-block" title="Add Data" id="btnAddPI"><i class="fa fa-plus"></i> Add</button>
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
								<th style="vertical-align:middle;width:5%;text-align:center;">No</th>
								<th style="vertical-align:middle;width:25%;text-align:center;">Type of Document ID</th>
								<th style="vertical-align:middle;width:15%;text-align:center;">Country of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">No Doc</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Issue at (Place)</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Valid Until</th>
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