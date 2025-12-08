<script type="text/javascript">
	$(document).ready(function(){
		
	});
	function saveDataOthers()
	{
		var idEdit = $("#txtIdEditOthers").val();
		var idPerson = $("#txtIdPerson").val();
		var items = $("#txtItemOthers").val();
		var dateIssue = $("#txtDate_issueOthers").val();
		var dateExp = $("#txtDate_expOthers").val();
		var remark = $("#txtRemarkOthers").val();

		if(items == "")
		{
			alert("Item Empty..!!");
			$("#txtItemOthers").focus();
			return false;
		}

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("others/saveDataOther2"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,items : items,dateIssue : dateIssue,dateExp : dateExp,remark : remark },
			function(data)
			{
				alert(data);
				$("#idLoadingForm").hide();
				others2();
			},
		"json"
		);
	}

	function getDataEditOthers(id)
	{
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("others/getDataEdit"); ?>',
		{ id : id,type : 'editOther2',idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditOthers").val(id);
				$("#txtItemOthers").val(data.phyitem);
				$("#txtDate_issueOthers").val(data.phyissdt);
				$("#txtDate_expOthers").val(data.phyexpdt);
				$("#txtRemarkOthers").val(data.phyremarks);

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
			{ id : id,type : 'delOther2',idPerson : idPerson },
				function(data) 
				{
					alert(data);
					others2();
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
				<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="others2();"><i class="fa fa-refresh"></i> Refresh</button>
			</div>
			<div class="col-md-11 col-xs-12">
				<legend style="margin-bottom:5px;text-align:center;"><b><i>:: PHYSICAL INSPECTION, YELLOW CARD ::</i></b></legend>
			</div>
		</div>		
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<div class="table-responsive">
					<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
						<thead>
							<tr style="background-color:#067780;color:#FFF;height:30px;">
								<th style="vertical-align:middle;width:3%;text-align:center;">No</th>
								<th style="vertical-align:middle;width:20%;text-align:center;">Item</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Issue</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Date of Expired</th>
								<th style="vertical-align:middle;width:25%;text-align:center;">Remarks</th>
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
						<label for="txtItemOthers">Item :</label>
						<input type="text" class="form-control input-sm" id="txtItemOthers" value="" placeholder="Name">
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
						<button class="btn btn-danger btn-xs btn-block" onclick="others2();" title="Cancel"><i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>