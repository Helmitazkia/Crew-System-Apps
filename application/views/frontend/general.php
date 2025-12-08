<script type="text/javascript">
	function saveData()
	{
		var idEdit = $("#txtIdEditGeneral").val();
		var idPerson = $("#txtIdPerson").val();
		var txtCompanyGen = $("#txtCompanyGen").val();
		var txtPersonContactGen = $("#txtPersonContactGen").val();
		var txtAddressGen = $("#txtAddressGen").val();
		var slcCountryGen = $("#slcCountryGen").val();
		var txtPhoneGen = $("#txtPhoneGen").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("general/saveData"); ?>',
		{ idEdit : idEdit,idPerson : idPerson,txtCompanyGen : txtCompanyGen,txtPersonContactGen : txtPersonContactGen,txtAddressGen : txtAddressGen,slcCountryGen : slcCountryGen,txtPhoneGen : txtPhoneGen },
			function(data)
			{
				alert(data);
				navProsesCrew();
			},
		"json"
		);
	}

	function saveDataRef()
	{
		var idPerson = $("#txtIdPerson").val();
		var ref1 = $("#slcRefGen1").val();
		var ref2 = $("#slcRefGen2").val();

		if(ref1 == "")
		{
			if(ref2 != "")
			{
				alert("Reference 1 Empty..!!!");
				return false;
			}			
		}

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("general/saveDataRef"); ?>',
		{ idPerson : idPerson,ref1 : ref1,ref2 : ref2 },
			function(data)
			{
				alert(data);
				navProsesCrew();
			},
		"json"
		);
	}

	function getDataEdit(id)
	{
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("general/getDataEdit"); ?>',
		{ id : id,idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditGeneral").val(id);
				$("#txtCompanyGen").val(data.company);
				$("#txtPersonContactGen").val(data.pic);
				$("#txtAddressGen").val(data.address);
				$("#slcCountryGen").val(data.country);
				$("#txtPhoneGen").val(data.phone);

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
			$.post('<?php echo base_url("general/deleteData"); ?>/',
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

<div class="row" style="margin-top:5px;">
	<div class="col-md-1 col-xs-12">
		<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="navProsesCrew();"><i class="fa fa-refresh"></i> Refresh</button>
	</div>
</div>
<div class="row" style="margin-top:5px;">
	<div class="col-md-8 col-xs-12">
		<div class="table-responsive">
			<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
				<thead>
					<tr style="background-color:#067780;color:#FFF;height:30px;">
						<th style="vertical-align:middle;width:5%;text-align:center;">No</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Company</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Person to Contact</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Address</th>
						<th style="vertical-align:middle;width:15%;text-align:center;">Country</th>
						<th style="vertical-align:middle;width:10%;text-align:center;">Telephone</th>
						<th style="vertical-align:middle;width:10%;text-align:center;">Action</th>
					</tr>
				</thead>
				<tbody id="idTbody">
					<?php echo $trNya; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4 col-xs-12">		
		<div class="row" style="background-color:#ABABAB;padding-bottom:5px;margin-bottom: 5px;">
			<legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Reference ::</i></b></legend>
			<div class="col-md-6 col-xs-12">
				<label for="slcRefGen1">Reference 1 :</label>
				<select class="form-control input-sm" id="slcRefGen1">
					<?php echo $optRef1; ?>
				</select>
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="slcRefGen2">Reference 2 :</label>
				<select class="form-control input-sm" id="slcRefGen2">
					<?php echo $optRef2; ?>
				</select>
			</div>
			<div class="col-md-12 col-xs-12" style="padding-top: 5px;">
				<button class="btn btn-primary btn-xs btn-block" onclick="saveDataRef();" title="Save Reference">
					<i class="glyphicon glyphicon-floppy-saved"></i> Save
				</button>
			</div>
		</div>		
		<div class="row" style="background-color:#ABABAB;">
			<legend style="margin-top:5px;margin-bottom:5px;text-align:right;padding-right:10px;"><b><i>:: All Reference ::</i></b></legend>
			<div class="col-md-6 col-xs-12">
				<label for="txtCompanyGen">Name Of Company :</label>
				<input type="text" class="form-control input-sm" id="txtCompanyGen" value="" placeholder="Company">
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtPersonContactGen">person to contact :</label>
				<input type="text" class="form-control input-sm" id="txtPersonContactGen" value="" placeholder="Name">
			</div>
		</div>
		<div class="row" style="background-color:#ABABAB;">
			<div class="col-md-12 col-xs-12">
				<label for="txtAddressGen">Address :</label>
				<textarea class="form-control input-sm" id="txtAddressGen"></textarea>
			</div>
		</div>
		<div class="row" style="background-color:#ABABAB;">
			<div class="col-md-6 col-xs-12">
				<label for="slcCountryGen">Country :</label>
				<select class="form-control input-sm" id="slcCountryGen">
					<?php echo $optCountry; ?>
				</select>
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtPhoneGen">Telephone :</label>
				<input type="text" class="form-control input-sm" id="txtPhoneGen" value="" placeholder="Telephone">
			</div>
		</div>
		<div class="row" style="background-color:#ABABAB;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<input type="hidden" id="txtIdEditGeneral" value="">
				<button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i class="glyphicon glyphicon-saved"></i> Save</button>
			</div>
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<button class="btn btn-danger btn-xs btn-block" onclick="navProsesCrew();"><i class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>