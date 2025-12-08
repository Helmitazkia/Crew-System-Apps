<script type="text/javascript">
$(document).ready(function() {});

function searchData() {
    var txtSearch = $("#txtSearch").val();

    if (txtSearch == "") {
        alert("Search Text Empty..!!");
        $("#txtSearch").focus();
        return false;
    }

    $("#idLoading").show();

    $.post('<?php echo base_url("master/getDataVessel/search"); ?>', {
            txtSearch: txtSearch
        },
        function(data) {
            $('#idTbodyCert').empty();
            $('#idTbodyCert').append(data.trNya);

            $("#idLoading").hide();
        },
        "json"
    );
}

function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEdit").val();
    var txtVesselName = $("#txtVesselName").val();
    var slcDefinition = $("#slcDefinition").val();
    var slcStsDisplay = $("#slcStsDisplay").val();
    var slcCompany = $("#slcCompany").val();
    var slcCompanyName = $("#slcCompany option:selected").text();
    var txtIMO = $("#txtIMO").val();
    var txtGRT = $("#txtGRT").val();
    var txtSerpel = $("#txtSerpel").val();
	var txtLoa = $("#txtLoa").val();
	var slcOwn = $("#slcOwn").val();
	var osName = $("#txtOsName").val();
	var osMail = $("#txtOsMail").val();
	var txtMailVessel = $("#txtMailVessel").val();

    if (txtVesselName == "") {
        alert("Vessel Name Empty..!!");
        $("#txtVesselName").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('txtVesselName', txtVesselName);
    formData.append('slcDefinition', slcDefinition);
    formData.append('slcCompany', slcCompany);
    formData.append('slcCompanyName', slcCompanyName);
    formData.append('slcStsDisplay', slcStsDisplay);
    formData.append('txtIMO', txtIMO);
    formData.append('txtGRT', txtGRT);
    formData.append('txtSerpel', txtSerpel);
	formData.append('txtLoa',txtLoa);
	formData.append('slcOwn',slcOwn);
	formData.append('osName',osName);
	formData.append('osMail',osMail);
	formData.append('txtMailVessel',txtMailVessel);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataVessel'); ?>", {
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {

            alert(response);

            reloadPage();
            $("#idLoading").hide();
        }
    });
}

function getDataEdit(id, type) {
    $("#idLoading").show();

    $.post('<?php echo base_url("master/getDataEdit"); ?>', {
            type: type,
            idEdit: id
        },
        function(data) {
            $("#txtIdEdit").val(data['rsl'][0]['kdvsl']);
            $("#txtVesselName").val(data['rsl'][0]['nmvsl']);
            $("#slcDefinition").val(data['rsl'][0]['descvsl']);
            $("#slcStsDisplay").val(data['rsl'][0]['st_display']);
            $("#slcCompany").val(data['rsl'][0]['kdcmp']);
            $("#txtIMO").val(data['rsl'][0]['imo']);
            $("#txtGRT").val(data['rsl'][0]['grt']);
            $("#txtSerpel").val(data['rsl'][0]['serpel']);
			$("#txtLoa").val(data['rsl'][0]['loa']);
			$("#slcOwn").val(data['rsl'][0]['st_own']);
			$("#txtOsName").val(data['rsl'][0]['os_name']);
			$("#txtOsMail").val(data['rsl'][0]['os_mail']);
			$("#txtMailVessel").val(data['rsl'][0]['mail_vessel']);

            $("#idLoading").hide();
        },
        "json"
    );
}

function delData(id, type) {
    var cfm = confirm("Delete data...??");
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("master/deleteData"); ?>/', {
                type: type,
                idDel: id
            },
            function(data) {
                alert(data);
                reloadPage();
            },
            "json"
        );
    }
}

function reloadPage() {
    parent.buttonMenuMaster('vessel');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-2 col-xs-12">
        <input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="Vessel Name..">
    </div>
    <div class="col-md-2 col-xs-12">
        <button class="btn btn-warning btn-sm btn-block" title="Search" onclick="searchData();"><i
                class="fa fa-search"></i> Search</button>
    </div>
    <div class="col-md-2 col-xs-12">
        <button class="btn btn-success btn-sm btn-block" title="Refresh" onclick="reloadPage();"><i
                class="fa fa-refresh"></i> Refresh</button>
    </div>
</div>

<div class="row" style="margin-top:5px;">
    <div class="col-md-8 col-xs-12" style="padding-bottom:15px;">
        <div class="table-responsive" style="height:500px;overflow-y:scroll;">
            <table class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                style="background-color:#D7EAEC;">
                <thead>
                    <tr style="background-color:#067780;color:#FFF;height:30px;">
                        <th style="vertical-align:middle;width:5%;text-align:center;" colspan="2">NO</th>
                        <th style="vertical-align:middle;width:40%;text-align:center;">VESSEL NAME</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">IMO</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">GRT</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">SERPEL</th>
                        <th style="vertical-align:middle;width:30%;text-align:center;">DEFINITION</th>
                        <th style="vertical-align:middle;width:15%;text-align:center;">COMPANY</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">ACTION</th>
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
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Vessel ::</i></b>
            </legend>
            <div class="col-md-12 col-xs-12">
                <label for="txtVesselName">Vessel Name :</label>
                <input type="text" class="form-control input-sm" id="txtVesselName" value="" placeholder="Vessel">
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="slcDefinition">Definition :</label>
                <select class="form-control input-sm" id="slcDefinition">
                    <?php echo $getCrewVesselType; ?>
                </select>
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="slcStsDisplay">Status Display :</label>
                <select class="form-control input-sm" id="slcStsDisplay">
                    <option value="Y">YES</option>
                    <option value="N">NO</option>
                </select>
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtIMO">IMO :</label>
                <input type="text" class="form-control input-sm" id="txtIMO" value="" placeholder="imo">
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtGRT">GRT :</label>
                <input type="text" class="form-control input-sm" id="txtGRT" value="" placeholder="GRT">
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtSerpel">Serpel :</label>
                <input type="text" class="form-control input-sm" id="txtSerpel" value="" placeholder="Serpel">
            </div>
			<div class="col-md-6 col-xs-12">
				<label for="txtLoa">LOA :</label>
				<input type="text" class="form-control input-sm" id="txtLoa" value="" placeholder="LOA">
			</div>
            <div class="col-md-6 col-xs-12">
                <label for="slcCompany">Company :</label>
                <select class="form-control input-sm" id="slcCompany">
                    <?php echo $optCompany; ?>
                </select>
            </div>
			<div class="col-md-6 col-xs-12">
				<label for="slcOwn">Ship Own :</label>
				<select class="form-control input-sm" id="slcOwn">
					<option value="Y">Y</option>
					<option value="N">N</option>
				</select>
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtOsName">OS Name :</label>
				<input type="text" class="form-control input-sm" id="txtOsName" value="" placeholder="Name">
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtOsMail">OS Email :</label>
				<input type="text" class="form-control input-sm" id="txtOsMail" value="" placeholder="Email">
			</div>
			<div class="col-md-6 col-xs-12">
				<label for="txtMailVessel">Email Vessel :</label>
				<input type="text" class="form-control input-sm" id="txtMailVessel" value="" placeholder="Email">
			</div>
        </div>
        <div class="row" style="padding-top:15px;padding-bottom:15px;">
            <div class="col-md-6 col-xs-12" style="padding-top:5px;">
                <input type="hidden" id="txtIdEdit" value="">
                <button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i
                        class="glyphicon glyphicon-saved"></i> Save</button>
            </div>
            <div class="col-md-6 col-xs-12" style="padding-top:5px;">
                <button class="btn btn-danger btn-xs btn-block" onclick="reloadPage();"><i
                        class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
            </div>
        </div>
    </div>
</div>