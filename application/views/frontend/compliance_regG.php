<script type="text/javascript">
	$(document).ready(function(){
		
	});
	function saveDataRegACC()
	{
		var idEdit = $("#txtIdEditCC").val();
		var idPerson = $("#txtIdPersonCC").val();
		var typeCC = $("#slcTypeCC").val();
		var docTypeCC = $("#slcDocTypeCC").val();
		var paraCC = $("#slcParaCC").val();
		var levelCC = $("#slcLevelCC").val();
		var kdRank = $("#slcRankCC").val();
		var countryIssueCC = $("#slcCountryCC").val();
		var dateIssueCC = $("#txtDate_issueCC").val();
		var dateExpCC = $("#txtDate_expCC").val();
		var numberCC = $("#txtNumberCC").val();
		var placeCC = $("#txtPlaceCC").val();
		var issueAuthCC = $("#txtIssuingCC").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("compliance/saveDataRegG"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,typeCC : typeCC,docTypeCC : docTypeCC,paraCC : paraCC,levelCC : levelCC,kdRank : kdRank,countryIssueCC : countryIssueCC,dateIssueCC : dateIssueCC,dateExpCC : dateExpCC,numberCC : numberCC,placeCC : placeCC,issueAuthCC : issueAuthCC },
			function(data)
			{
				alert(data);
				$("#idLoading").hide();
				certRegG();
			},
		"json"
		);
	}

	function getDataEditRegACC(id)
	{
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("compliance/getDataEdit"); ?>',
		{ id : id,type : 'editRegG',idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditCC").val(data.idrg1);
				$("#slcTypeCC").val(data.rg1lvltipe);
				$("#slcDocTypeCC").val(data.rg1doc);
				$("#slcParaCC").val(data.rg1para);
				$("#slcLevelCC").val(data.rg1lvl);
				$("#slcRankCC").val(data.kdrank);				
				$("#slcCountryCC").val(data.rg1issctryid);
				$("#txtDate_issueCC").val(data.rg1issdt);
				$("#txtDate_expCC").val(data.rg1expdt);
				$("#txtNumberCC").val(data.rg1docno);
				$("#txtPlaceCC").val(data.rg1issplc);
				$("#txtIssuingCC").val(data.rg1issby);

				$("#idDataTableCC").hide();
				$("#idFormCC").show();
				$("#idLoadingForm").hide();
			},
		"json"
		);
	}

	function delDataRegACC(id,idPerson)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("compliance/deleteData"); ?>/',
			{ id : id,type : 'delRegG',idPerson : idPerson },
				function(data) 
				{
					alert(data);
					certRegG();
				},
			"json"
			);
		}
	}

</script>

<div class="row" id="idFormCC" style="display:none;">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-12 col-xs-12" style="text-align:right;">
				<legend style="margin-bottom:10px;"><b><i>:: (G) Reg V / 1 - Special Requirement for Tankers ::</i></b></legend>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2 col-xs-12">
				<label for="slcTypeCC">Type :</label>
				<select class="form-control input-sm" id="slcTypeCC">					
					<option value="Chemical">Chemical</option>
					<option value="Gas">Gas</option>
					<option value="Oil">Oil</option>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcDocTypeCC">Document Type :</label>
				<select class="form-control input-sm" id="slcDocTypeCC">					
					<option value="Endorsement">Endorsement</option>
					<option value="Tanker Familiarisation">Tanker Familiarisation</option>
					<option value="Special Tanker Safety">Special Tanker Safety</option>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcParaCC">Para :</label>
				<select class="form-control input-sm" id="slcParaCC">					
					<option value=""> - </option>
					<option value="Para 1">Para 1</option>
					<option value="Para 2">Para 2</option>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcLevelCC">Level :</label>
				<select class="form-control input-sm" id="slcLevelCC">
					<option value=""> - </option>
					<option value="Incharge">Incharge</option>
					<option value="Asst.">Asst.</option>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcRankCC">Rank :</label>
				<select class="form-control input-sm" id="slcRankCC">
					<?php echo $optRank; ?>
				</select>
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="slcCountryCC">Country of Issue :</label>
				<select class="form-control input-sm" id="slcCountryCC">
					<?php echo $optCountry; ?>
				</select>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-2 col-xs-12">
				<label for="txtNumberCC">Number :</label>
				<input type="text" class="form-control input-sm" id="txtNumberCC" value="" placeholder="Number">
			</div>
			<div class="col-md-3 col-xs-12">
				<label for="txtPlaceCC">Place of Issue :</label>
				<input type="text" class="form-control input-sm" id="txtPlaceCC" value="" placeholder="Place">
			</div>
			<div class="col-md-3 col-xs-12">
				<label for="txtIssuingCC">Issuing Authority / Body :</label>
				<input type="text" class="form-control input-sm" id="txtIssuingCC" value="" placeholder="Issuing Authority">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_issueCC">Date of Issue :</label>
				<input type="text" class="form-control input-sm" id="txtDate_issueCC" value="" placeholder="Date">
			</div>
			<div class="col-md-2 col-xs-12">
				<label for="txtDate_expCC">Date of Expiry :</label>
				<input type="text" class="form-control input-sm" id="txtDate_expCC" value="" placeholder="Date">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">					
				<input type="hidden" id="txtIdEditCC" value="">
				<label>&nbsp</label>
				<button class="btn btn-primary btn-xs btn-block" onclick="saveDataRegACC();" title="Submit"><i class="glyphicon glyphicon-saved"></i> Submit</button>
			</div>
			<div class="col-md-6 col-xs-12">
				<label>&nbsp</label>
				<button class="btn btn-danger btn-xs btn-block" onclick="certRegG();" title="Cancel"><i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top:10px;display:;" id="idDataTableCC">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-info btn-xs btn-block" title="ADD" onclick="actBtnAdd('idFormCC','idDataTableCC');"><i class="fa fa-plus"></i> Add</button>
			</div>
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="certRegG();"><i class="fa fa-refresh"></i> Refresh</button>
			</div>
			<div class="col-md-10 col-xs-12" style="text-align:right;">
				<legend style="margin-bottom:10px;"><b><i>:: (G) Reg V / 1 - Special Requirement for Tankers ::</i></b></legend>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
						<thead>
							<tr style="background-color:#067780;color:#FFF;height:30px;">
								<th style="vertical-align:middle;width:3%;text-align:center;">No</th>
								<th style="vertical-align:middle;width:20%;text-align:center;">Description</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Level</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Rank</th>
								<th style="vertical-align:middle;width:15%;text-align:center;">Country of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Number</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date OF Expired</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Place OF Issue</th>
								<th style="vertical-align:middle;width:15%;text-align:center;">Issuing&nbspAuthority / Body</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Action</th>
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