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
	function saveData()
	{
		var formData = new FormData();

		var idEdit = $("#txtIdEditVaccine").val();
		var idPerson = $("#txtIdPerson").val();
		var vaccineName = $("#txtVaccineName").val();
		var vaccineDate = $("#txtDateVaccine").val();
		var remark = $("#txtRemark").val();
		var fileUpload = $("#uploadFile").val();

		formData.append('idEdit',idEdit);
		formData.append('idPerson',idPerson);
		formData.append('vaccineName',vaccineName);
		formData.append('vaccineDate',vaccineDate);
		formData.append('remark',remark);

		formData.append('cekFileUpload',fileUpload);
		formData.append('fileUpload',$("#uploadFile").prop('files')[0]);

		$("#idLoadingForm").show();
		$.ajax("<?php echo base_url('vaccine/saveData'); ?>",{
			method: "POST",
            data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(response){
            	alert(response);
				$("#idLoadingForm").hide();
				navProsesCrew();
			}
        });
	}

	function getDataEdit(id)
	{
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$.post('<?php echo base_url("vaccine/getDataEdit"); ?>',
		{ id : id,idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditVaccine").val(id);
				$("#txtVaccineName").val(data.vaccineName);
				$("#txtDateVaccine").val(data.vaccineDate);
				$("#txtRemark").val(data.remark);

				$("#idViewFile").empty();
				$("#idViewFile").append(data.btnFile);

				$("#idLoadingForm").hide();
			},
		"json"
		);
	}

	function delFile(id,file,idPerson)
	{
		var cfm = confirm("Delete File...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("vaccine/deleteFile"); ?>/',
			{ id : id,file : file,idPerson : idPerson },
				function(data) 
				{
					alert(data);
					$("#idViewFile").empty();
				},
			"json"
			);
		}
	}

	function delData(id,idPerson)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("vaccine/deleteData"); ?>/',
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
						<th style="vertical-align:middle;width:15%;text-align:center;">Vaccine Name</th>
						<th style="vertical-align:middle;width:25%;text-align:center;">Vaccine Date</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Remark</th>
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
			<legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Vaccine ::</i></b></legend>
			<div class="col-md-6 col-xs-12">
				<label for="txtVaccineName">Vaccine Name :</label>
				<input type="text" class="form-control input-sm" id="txtVaccineName" value="" placeholder="Vaccine Name">
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtDateVaccine">Date :</label>
				<input type="text" class="form-control input-sm" id="txtDateVaccine" value="" placeholder="Date">
			</div>
			<div class="col-md-12 col-xs-12">
				<label for="txtCourseFinishEduc">Remark :</label>
				<textarea class="form-control input-sm" id="txtRemark"></textarea>
			</div>
			<div class="col-md-8 col-xs-12">
				<label for="uploadFile">File :</label>
				<input type="file" class="form-control" id="uploadFile" value="">
			</div>
			<div class="col-md-4 col-xs-12">
				<label>&nbsp;</label>
				<button class="btn btn-warning btn-xs btn-block" title="Clear File" onclick="$('#uploadFile').val('');">Clear</button>
				<div id="idViewFile" style="margin-top:5px;"></div>
			</div>
		</div>
		<div class="row" style="background-color:#ABABAB;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<input type="hidden" id="txtIdEditVaccine" value="">
				<button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i class="glyphicon glyphicon-saved"></i> Save</button>
			</div>
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<button class="btn btn-danger btn-xs btn-block" onclick="navProsesCrew();"><i class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>