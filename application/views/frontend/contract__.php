<script src="<?php echo base_url();?>assets/js/bootstrap-select.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.css">
<script type="text/javascript">
	$(document).ready(function(){
		$("#btnAddSea").click(function(){
			$("#teksJudulPage").html("<b><i>:: Form Contract ::</i></b>");
			$("#idDataTableCont").hide();
			$("#idFormCont").show(100);
		});
		$('[id^=optSlc]').selectpicker('refresh');
	});

	function saveData()
	{
		var formData = new FormData();

		var idEdit = $("#txtIdEditContract").val();
		var idPerson = $("#txtIdPerson").val();
		var company = $("#slcCompanyCont").val();
		var signOnDate = $("#txtDate_signOnCont").val();
		var rank = $("#slcSignOnRankCont").val();
		var vessel = $("#slcSignOnVesselCont").val();
		var port = $("#txtSignOnPortCont").val();
		var signOnDesc = $("#txtSignOnDescCont").val();
		var lastVessel = $("#txtLastVesselCont").val();
		var txtNoPkl = $("#txtNoPkl").val();
		var estSignOffCont = $("#txtDate_EstSignOffCont").val();
		var remark = $("#txtRemarkCont").val();
		var signOffDate = $("#txtDate_signOffCont").val();
		var signOffRemark = $("#slcSignOffRemarkCont").val();
		var replacement = $("#slcReplacementCont").val();
		var addForCont = $("input[name='addForCont']:checked").val();
		var fileContract = $("#uploadFileContract").val();

		if (typeof $("input[name='addForCont']:checked").val() === "undefined")
		{
		    addForCont = "";
		}

		formData.append('idEdit',idEdit);
		formData.append('idPerson',idPerson);
		formData.append('company',company);
		formData.append('signOnDate',signOnDate);
		formData.append('rank',rank);
		formData.append('vessel',vessel);
		formData.append('port',port);
		formData.append('signOnDesc',signOnDesc);
		formData.append('lastVessel',lastVessel);
		formData.append('estSignOffCont',estSignOffCont);
		formData.append('remark',remark);
		formData.append('signOffDate',signOffDate);
		formData.append('signOffRemark',signOffRemark);
		formData.append('replacement',replacement);
		formData.append('addForCont',addForCont);
		formData.append('txtNoPkl',txtNoPkl);
		
		formData.append('cekFileContract',fileContract);
		formData.append('fileContract',$("#uploadFileContract").prop('files')[0]);

		$("#idLoadingForm").show();
		// $("#btnSave").attr('disabled',true);
		$.ajax("<?php echo base_url('contract/saveData'); ?>",{
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

		$.post('<?php echo base_url("contract/getDataEdit"); ?>',
		{ id : id,idPerson : idPerson },
			function(data)
			{
				$("#txtIdEditContract").val(id);
				$("#slcCompanyCont").val(data.company);
				$("#txtDate_signOnCont").val(data.signOnDate);
				$("#slcSignOnRankCont").val(data.rank);
				$("#slcSignOnVesselCont").val(data.vessel);
				$("#txtSignOnPortCont").val(data.port);
				$("#txtSignOnDescCont").val(data.signOnDesc);
				$("#txtLastVesselCont").val(data.lastVessel);
				$("#txtDate_EstSignOffCont").val(data.estSignOffDate);
				$("#txtRemarkCont").val(data.estRemark);
				$("#txtDate_signOffCont").val(data.signOffDate);
				$("#slcSignOffRemarkCont").val(data.remark);
				$("#slcReplacementCont").val(data.reason);
				$("#txtNoPkl").val(data.noPkl);

				if(data.add == "1")
				{
					$("#addForCont1").attr("checked",true);
				}
				if(data.for == "1")
				{
					$("#addForCont2").attr("checked",true);
				}

				$("#idDivViewContract").empty();
				$("#idDivViewContract").append(data.imgContract);

				$("#idDataTableCont").hide();
				$("#idFormCont").show(100);
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
			$.post('<?php echo base_url("contract/deleteData"); ?>/',
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

	function delFileContract(id,fileName)
	{
		var cfm = confirm("Delete data...??");
		if(cfm)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("contract/deleteFileContract"); ?>/',
			{ id : id,fileName : fileName },
				function(data) 
				{
					alert(data);
					$("#idDivViewContract").empty();
				},
			"json"
			);
		}
	}

	function calculateDate()
	{
		var dateSignOn = $("#txtDate_signOnCont").val();
		var calc = $("#txtMonthCalculate").val();

		if(dateSignOn == "" || calc == "")
		{
			return false;
		}

		$.post('<?php echo base_url("contract/hitungCalculate"); ?>',
		{ dateSignOn : dateSignOn,calc : calc },
			function(data)
			{
				$("#txtDate_EstSignOffCont").val(data);
			},
		"json"
		);
	}
</script>

<div class="row" id="idFormCont" style="display:none;background-color:#ABABAB;padding:10px;">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="row">
					<div class="col-md-4 col-xs-12">
						<label for="slcCompanyCont">Company Name</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<select class="form-control input-sm" id="slcCompanyCont">
							<?php echo $optCompany; ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtDate_signOnCont">Sign on Date</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-3 col-xs-12">
						<input type="text" class="form-control" id="txtDate_signOnCont" value="" placeholder="Date">
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="slcSignOnRankCont">Sign on Rank</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<select class="form-control input-sm" id="slcSignOnRankCont">
							<?php echo $optRank; ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="slcSignOnVesselCont">Sign on Vessel</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<select class="form-control input-sm" id="slcSignOnVesselCont">
							<?php echo $optVessel; ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtSignOnPortCont">Sign on Port</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-8 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtSignOnPortCont" value="" placeholder="Port">
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtSignOnDescCont">Sign on Description</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-8 col-xs-12">
						<textarea id="txtSignOnDescCont" class="form-control input-sm"></textarea>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtLastVesselCont">Last Vessel</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-8 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtLastVesselCont" value="" placeholder="Last Vessel">
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtNoPkl">No. PKL</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-8 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtNoPkl" value="" placeholder="No PKL">
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="row">
					<div class="col-md-4 col-xs-12" style="text-align: right;">
						<label for="txtMonthCalculate">Month</label>
					</div>
					<div class="col-md-2 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtMonthCalculate" value="" placeholder="1,2,3..">
					</div>
					<div class="col-md-2 col-xs-12">
						<button class="btn btn-info btn-xs btn-block" title="Calculate" style="margin-top:5px;" onclick="calculateDate();">Calculate</button>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtDate_EstSignOffCont">Estimate Sign off Date</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-3 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtDate_EstSignOffCont" value="" placeholder="Date">
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtRemarkCont">Remarks</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-8 col-xs-12">
						<textarea id="txtRemarkCont" class="form-control input-sm"></textarea>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="txtDate_signOffCont">Sign off Date</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-3 col-xs-12">
						<input type="text" class="form-control input-sm" id="txtDate_signOffCont" value="" placeholder="Date">
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="slcSignOffRemarkCont">Sign off remarks</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<select class="form-control input-sm" id="slcSignOffRemarkCont">
							<?php echo $optSignOffRemark; ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="slcReplacementCont">Replacement Candidare</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<select class="form-control input-sm" id="slcReplacementCont" disabled="disabled">
							<option value="">-</option>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12"></div>
					<div class="col-md-8 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="addForCont" id="addForCont1" value="add"> Additional
						</label>
						<label class="radio-inline">
							<input type="radio" name="addForCont" id="addForCont2" value="for"> Foreign Crew
						</label>
					</div>
				</div>
				<div class="row" style="margin-top:2px;">
					<div class="col-md-4 col-xs-12">
						<label for="uploadFileContract">File Contract</label><label style="float:right;">:</label>
					</div>
					<div class="col-md-5 col-xs-12">
						<input type="file" class="form-control input-sm" id="uploadFileContract">
					</div>
					<div class="col-md-2 col-xs-12">
						<button class="btn btn-warning btn-xs btn-block" onclick="$('#uploadFileContract').val('')">Clear</button>
						<div id="idDivViewContract"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top:15px;">
			<input type="hidden" id="txtIdEditContract" value="">
			<div class="col-md-6 col-xs-12">
				<button class="btn btn-primary btn-xs btn-block" title="Submit Data" onclick="saveData();">
					<i class="glyphicon glyphicon-saved"></i> Submit</button>
			</div>
			<div class="col-md-6 col-xs-12">
				<button class="btn btn-danger btn-xs btn-block" title="Cancel Data" onclick="navProsesCrew();">
					<i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top:5px;" id="idDataTableCont">
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
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign On</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign Off</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign On Rank</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign On Vessel</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign On Port</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign On Description</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Last Vessel</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Estimate Sign Off</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">No. PKL</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Remark</th>
								<th style="vertical-align:middle;width:10%;text-align:center;">Sign&nbspOff Remark</th>
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