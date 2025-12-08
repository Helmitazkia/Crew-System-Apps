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

    $.post('<?php echo base_url("master/getDataRank/search"); ?>', {
            txtSearch: txtSearch
        },
        function(data) {
            $('#idTbody').empty();
            $('#idTbody').append(data.trNya);

            $("#idLoading").hide();
        },
        "json"
    );
}

function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEdit").val();
    var txtRankName = $("#txtRankName").val();
    var txtDefinition = $("#txtDefinition").val();
    var txtNumber = $("#txtNumber").val();
    var txtCadangan = $("#txtCadangan").val();

    if (txtRankName == "") {
        alert("Rank Name Empty..!!");
        $("#txtRankName").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('txtRankName', txtRankName);
    formData.append('txtDefinition', txtDefinition);
    formData.append('txtNumber', txtNumber);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataRank'); ?>", {
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
            $("#txtIdEdit").val(data['rsl'][0]['kdrank']);
            $("#txtRankName").val(data['rsl'][0]['nmrank']);
            $("#txtDefinition").val(data['rsl'][0]['descrank']);
            $("#txtNumber").val(data['rsl'][0]['urutan']);

            $("#idLoading").hide();
        },
        "json"
    );
}

function btnNavUrut(kdRank, type, urutan) {
    $("#idLoading").show();

    $.post('<?php echo base_url("master/updateUrutRank"); ?>', {
            kdRank: kdRank,
            type: type,
            urutan: urutan
        },
        function(data) {
            if (data == "sukses") {
                $("#idLoading").show();

                reloadPage();
            }
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
    parent.buttonMenuMaster('rank');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-2 col-xs-12">
        <input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="Rank Name..">
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
                        <th style="vertical-align:middle;width:8%;text-align:center;" colspan="2">NO</th>
                        <th style="vertical-align:middle;width:50%;text-align:center;">RANK NAME</th>
                        <th style="vertical-align:middle;width:30%;text-align:center;">DEFINITION</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="idTbody">
                    <?php echo $trNya; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 col-xs-12" style="background-color:#ABABAB;padding:5px;">
        <div class="row" style="">
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Rank ::</i></b>
            </legend>
            <div class="col-md-12 col-xs-12">
                <label for="txtRankName">Rank Name :</label>
                <input type="text" class="form-control input-sm" id="txtRankName" value="" placeholder="Rank">
            </div>
            <div class="col-md-12 col-xs-12">
                <label for="txtDefinition">Definition :</label>
                <input type="text" class="form-control input-sm" id="txtDefinition" value="" placeholder="Definition">
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtNumber">Number :</label>
                <input type="text" class="form-control input-sm" id="txtNumber" value="" placeholder="Number">
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtCadangan">Cadangan :</label>
                <input type="text" class="form-control input-sm" id="txtCadangan" value="" placeholder="Cadangan">
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