<script type="text/javascript">
	$(document).ready(function(){		
	});
	function searchData()
	{
		var txtSearchCert = $("#txtSearchCert").val();

		if(txtSearchCert == "")
		{
			alert("Search Text Empty..!!");
			$("#txtSearchCert").focus();
			return false;
		}

		$("#idLoading").show();

		$.post('<?php echo base_url("master/getDataCertificate/search"); ?>',
		{ txtSearchCert : txtSearchCert },
			function(data)
			{
				$('#idTbodyCert').empty();
				$('#idTbodyCert').append(data.trNya);

				$("#idLoading").hide();
			},
		"json"
		);
	}
	function saveData()
	{
		var formData = new FormData();

		var idEdit = $("#txtIdEdit").val();
		var group = $("#slcIdGroup").val();
		var certName = $("#txtCertName").val();
		var certDisplay = $("#txtCertNameDisplay").val();
		var definisi = $("#txtDefinition").val();
		var slcDisplay = $("#slcDisplayCert").val();

		// if(group == "")
		// {
			// alert("Group Empty..!!");
			// $("#slcIdGroup").focus();
			// return false;
		// }

		if(certName == "")
		{
			alert("Certificate Name Empty..!!");
			$("#txtCertName").focus();
			return false;
		}

		formData.append('idEdit',idEdit);
		formData.append('group',group);
		formData.append('certName',certName);
		formData.append('certDisplay',certDisplay);
		formData.append('definisi',definisi);
		formData.append('slcDisplay',slcDisplay);

		$("#idLoading").show();
		$.ajax("<?php echo base_url('master/saveDataCertificate'); ?>",{
			method: "POST",
            data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(response){

            	alert(response);
				
				reloadPage();
				$("#idLoading").hide();
			}
        });
	}

	function getDataEdit(id,type)
	{
		$("#idLoading").show();

		$.post('<?php echo base_url("master/getDataEdit"); ?>',
		{ type : type,idEdit : id },
			function(data)
			{
				$("#txtIdEdit").val(data['rsl'][0]['kdcert']);
				$("#slcIdGroup").val(data['rsl'][0]['certgroup']);
				$("#txtCertName").val(data['rsl'][0]['certname']);
				$("#txtCertNameDisplay").val(data['rsl'][0]['dispname']);
				$("#txtDefinition").val(data['rsl'][0]['definition']);
				$("#slcDisplayCert").val(data['rsl'][0]['st_display']);

				$("#idLoading").hide();
			},
		"json"
		);
	}

	function delData(id,type)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("master/deleteData"); ?>/',
			{ type : type, idDel : id },
				function(data) 
				{
					alert(data);
					reloadPage();
				},
			"json"
			);
		}
	}

	function reloadPage()
	{
		parent.buttonMenuMaster('certificate');
	}
</script>

<div class="row" style="margin-top:5px;">
	<div class="col-md-2 col-xs-12">
		<input type="text" class="form-control input-sm" id="txtSearchCert" value="" placeholder="Certificate Name..">
	</div>
	<div class="col-md-2 col-xs-12">
		<button class="btn btn-warning btn-sm btn-block" title="Search" onclick="searchData();"><i class="fa fa-search"></i> Search</button>
	</div>
	<div class="col-md-2 col-xs-12">
		<button class="btn btn-success btn-sm btn-block" title="Refresh" onclick="reloadPage();"><i class="fa fa-refresh"></i> Refresh</button>
	</div>
</div>
<div class="row" style="margin-top:5px;">
	<div class="col-md-8 col-xs-12" style="padding-bottom:15px;">
		<div class="table-responsive" style="height:500px;overflow-y:scroll;">
			<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
				<thead>
					<tr style="background-color:#067780;color:#FFF;height:30px;">
						<th style="vertical-align:middle;width:5%;text-align:center;">No</th>
						<th style="vertical-align:middle;width:35%;text-align:center;">CERTIFICATES NAME</th>
						<th style="vertical-align:middle;width:35%;text-align:center;">DEFINITION</th>
						<th style="vertical-align:middle;width:10%;text-align:center;">DISPLAY</th>
						<th style="vertical-align:middle;width:10%;text-align:center;">Action</th>
					</tr>
				</thead>
				<tbody id="idTbodyCert">
					<?php echo $trNya; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4 col-xs-12" style="background-color:#ABABAB;padding:5px;">		
		<div class="row" style="">
			<legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Certificate ::</i></b></legend>
			<div class="col-md-6 col-xs-12">
				<label for="txtVaccineName">Group :</label>
				<select class="form-control input-sm" id="slcIdGroup">
					<option value="">- Select -</option>
					<option value="A">(A) Reg I</option>
					<option value="B">(B) Reg I</option>
					<option value="C">(C) Reg I</option>
					<option value="D">(D) Reg I</option>
					<option value="E">(E) Reg I</option>
					<option value="F">(F) Reg I</option>
					<option value="G">(G) Reg I</option>
					<option value="H">(H) Reg I</option>
					<option value="PID">P.ID</option>
					<option value="OTH">OTH</option>
				</select>
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="slcDisplayCert">Display :</label>
				<select class="form-control input-sm" id="slcDisplayCert">
					<option value="Y">Y</option>
					<option value="N">N</option>
				</select>
			</div>
			<div class="col-md-12 col-xs-12">
				<label for="txtCertName">Certificates Name :</label>
				<input type="text" class="form-control input-sm" id="txtCertName" value="" placeholder="Certificate">
			</div>
			<div class="col-md-12 col-xs-12">
				<label for="txtCertNameDisplay">Display Name :</label>
				<input type="text" class="form-control input-sm" id="txtCertNameDisplay" value="" placeholder="Display">
			</div>
			<div class="col-md-12 col-xs-12">
				<label for="txtDefinition">Definition :</label>
				<input type="text" class="form-control input-sm" id="txtDefinition" value="" placeholder="Definition">
			</div>
		</div>
		<div class="row" style="padding-top:15px;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<input type="hidden" id="txtIdEdit" value="">
				<button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i class="glyphicon glyphicon-saved"></i> Save</button>
			</div>
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<button class="btn btn-danger btn-xs btn-block" onclick="reloadPage();"><i class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>