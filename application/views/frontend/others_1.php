<script type="text/javascript">
	$(document).ready(function(){
		
	});
	function saveDataOthers()
	{
		var idEdit = $("#txtIdEditOthers").val();
		var idPerson = $("#txtIdPerson").val();
		var nameCert = $("#txtNameCertOthers").val();
		var number = $("#txtNumberOthers").val();
		var regulation = $("#txtRegulationOthers").val();
		var dateIssue = $("#txtDate_issueOthers").val();
		var dateExp = $("#txtDate_expOthers").val();
		var remark = $("#txtRemarkOthers").val();

		if(nameCert == "")
		{
			alert("Name of Certificates Empty..!!");
			$("#txtNameCertOthers").focus();
			return false;
		}

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("others/saveDataOther1"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,nameCert : nameCert,number : number,regulation : regulation,dateIssue : dateIssue,dateExp : dateExp,remark : remark },
			function(data)
			{
				alert(data);
				$("#idLoadingForm").hide();
				others1();
			},
		"json"
		);
	}

	function getDataEditOthers(id)
	{
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("others/getDataEdit"); ?>',
		{ id : id,type : 'editOther1',idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditOthers").val(id);
				$("#txtNameCertOthers").val(data.certname);
				$("#txtNumberOthers").val(data.certno);
				$("#txtRegulationOthers").val(data.certrgtn);
				$("#txtDate_issueOthers").val(data.certissdt);
				$("#txtDate_expOthers").val(data.certexpdt);
				$("#txtRemarkOthers").val(data.certremarks);

				$("#idLoadingForm").hide();
			},
		"json"
		);
	}

	function delDataOthers(id,idPerson)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("others/deleteData"); ?>/',
			{ id : id,type : 'delOther1',idPerson : idPerson },
				function(data) 
				{
					alert(data);
					others1();
				},
			"json"
			);
		}
	}

</script>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="others1();"><i class="fa fa-refresh"></i> Refresh</button>
			</div>
			<div class="col-md-11 col-xs-12">
				<legend style="margin-bottom:5px;text-align:center;"><b><i>:: OTHER CERTIFICATE (MARINA) ::</i></b></legend>
			</div>
		</div>		
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<div class="table-responsive">
					<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
						<thead>
							<tr style="background-color:#067780;color:#FFF;height:30px;">
								<th style="vertical-align:middle;width:3%;text-align:center;">No</th>
								<th style="vertical-align:middle;width:20%;text-align:center;">Certificate</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Number</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Regulation</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Expired</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Remark</th>
								<th style="vertical-align:middle;width:5%;text-align:center;">Action</th>
							</tr>
						</thead>
						<tbody id="idTbody">
							<?php echo $trNya; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-3 col-xs-12" style="background-color:#ABABAB;padding-bottom:5px;margin-bottom: 5px;">
				<div class="row">
					<div class="col-md-12 col-xs-12" style="text-align:right;">
						<legend style="margin-bottom:10px;"><b><i>:: Form Others ::</i></b></legend>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<label for="txtNameCertOthers">Name of Certificates :</label>
						<input type="text" class="form-control input-sm" id="txtNameCertOthers" value="" placeholder="Name">
					</div>
					<div class="col-md-6 col-xs-12">
						<label for="txtNumberOthers">Number :</label>
						<input type="text" class="form-control input-sm" id="txtNumberOthers" value="" placeholder="Number">
					</div>
					<div class="col-md-6 col-xs-12">
						<label for="txtRegulationOthers">Regulation :</label>
						<input type="text" class="form-control input-sm" id="txtRegulationOthers" value="" placeholder="Regulation">
					</div>
					<div class="col-md-6 col-xs-12">
						<label for="txtDate_issueOthers">Date of Issue :</label>
						<input type="text" class="form-control input-sm" id="txtDate_issueOthers" value="" placeholder="Date">
					</div>
					<div class="col-md-6 col-xs-12">
						<label for="txtDate_expOthers">Date of Expiry :</label>
						<input type="text" class="form-control input-sm" id="txtDate_expOthers" value="" placeholder="Date">
					</div>
					<div class="col-md-12 col-xs-12">
						<label for="txtRemarkOthers">Remarks :</label>
						<textarea class="form-control input-sm" id="txtRemarkOthers"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-12">					
						<input type="hidden" id="txtIdEditOthers" value="">
						<label>&nbsp</label>
						<button class="btn btn-primary btn-xs btn-block" onclick="saveDataOthers();" title="Submit"><i class="glyphicon glyphicon-saved"></i> Submit</button>
					</div>
					<div class="col-md-6 col-xs-12">
						<label>&nbsp</label>
						<button class="btn btn-danger btn-xs btn-block" onclick="others1();" title="Cancel"><i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>