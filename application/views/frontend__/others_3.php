<script type="text/javascript">
	$(document).ready(function(){
		
	});
	function saveDataOthers()
	{
		var idPerson = $("#txtIdPerson").val();
		var yearOperator = $("#txtItemOthers").val();
		var yearRank = $("#txtDate_issueOthers").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("others/saveDataOther3"); ?>',
		{ idPerson : idPerson,yearOperator : yearOperator,yearRank : yearRank },
			function(data)
			{				
				$("#idLoadingForm").hide();
				$("#idLblStatusOtherMatrix").text(data);
				$("#idLblStatusOtherMatrix").css('color','red');
			},
		"json"
		);
	}

</script>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-1 col-xs-12">
				<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="others3();"><i class="fa fa-refresh"></i> Refresh</button>
			</div>
			<div class="col-md-11 col-xs-12">
				<legend style="margin-bottom:5px;text-align:center;"><b><i>:: CREW MATRIX ::</i></b></legend>
			</div>
		</div>		
		<div class="row">
			<div class="col-md-12 col-xs-12" style="background-color:#ABABAB;padding-bottom:5px;margin-bottom: 5px;">
				<div class="row">
					<div class="col-md-12 col-xs-12" style="text-align:right;">
						<legend style="margin-bottom:10px;"><b><i>:: Form CREW MATRIX ::</i></b></legend>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-12">
						<div class="row">
							<div class="col-md-10 col-xs-12">
								<label for="txtItemOthers">Years With Operator (STC) -1</label><label style="float:right;">:</label>
							</div>
							<div class="col-md-2 col-xs-12">
								<input type="text" class="form-control input-sm" id="txtItemOthers" value="<?php echo $yearOpr; ?>" placeholder="Years" maxlength="10">
							</div>
						</div>
						<div class="row" style="margin-top:5px;">
							<div class="col-md-10 col-xs-12">
								<label for="txtDate_issueOthers">Years in rank</label><label style="float:right;">:</label>
							</div>
							<div class="col-md-2 col-xs-12">
								<input type="text" class="form-control input-sm" id="txtDate_issueOthers" value="<?php echo $yearRank; ?>" placeholder="Years" maxlength="10">
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-12">
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<button class="btn btn-primary btn-xs btn-block" onclick="saveDataOthers();" title="Submit"><i class="glyphicon glyphicon-saved"></i> Submit</button>
							</div>
							<div class="col-md-2 col-xs-12">
								<button class="btn btn-danger btn-xs btn-block" onclick="others3();" title="Cancel"><i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
							</div>
							<div class="col-md-12 col-xs-12">
								<label style="font-family: serif;" id="idLblStatusOtherMatrix"></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>