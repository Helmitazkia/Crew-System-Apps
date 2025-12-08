<script type="text/javascript">
	function saveData()
	{
		var idEdit = $("#txtIdEditEducation").val();
		var idPerson = $("#txtIdPerson").val();
		var year = $("#slcYearEduc").val();
		var school = $("#txtSchoolEduc").val();
		var course = $("#txtCourseFinishEduc").val();

		$("#idLoadingForm").show();
		$.post('<?php echo base_url("education/saveData"); ?>',
		{ idEdit :idEdit,idPerson : idPerson,year : year,school : school,course : course },
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

		$.post('<?php echo base_url("education/getDataEdit"); ?>',
		{ id : id,idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditEducation").val(id);
				$("#slcYearEduc").val(data.year);
				$("#txtSchoolEduc").val(data.school);
				$("#txtCourseFinishEduc").val(data.course);

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
			$.post('<?php echo base_url("education/deleteData"); ?>/',
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
						<th style="vertical-align:middle;width:15%;text-align:center;">Year of Graduate</th>
						<th style="vertical-align:middle;width:25%;text-align:center;">School</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Course Finished</th>
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
			<legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Educational Attainment ::</i></b></legend>
			<div class="col-md-6 col-xs-12">
				<label for="slcYearEduc">Year of Graduate :</label>
				<select class="form-control input-sm" id="slcYearEduc">
					<?php echo $yearNya; ?>
				</select>
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtSchoolEduc">School :</label>
				<input type="text" class="form-control input-sm" id="txtSchoolEduc" value="" placeholder="School">
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtCourseFinishEduc">Course Finished :</label>
				<input type="text" class="form-control input-sm" id="txtCourseFinishEduc" value="" placeholder="Course Finished">
			</div>
		</div>
		<div class="row" style="background-color:#ABABAB;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<input type="hidden" id="txtIdEditEducation" value="">
				<button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i class="glyphicon glyphicon-saved"></i> Save</button>
			</div>
			<div class="col-md-6 col-xs-12" style="padding-top:5px;">
				<button class="btn btn-danger btn-xs btn-block" onclick="navProsesCrew();"><i class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>