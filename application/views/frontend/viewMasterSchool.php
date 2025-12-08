<script type="text/javascript">
function searchData() {
    var txtSearch = $("#txtSearch").val();

    if (txtSearch == "") {
        alert("Search Text Empty..!!");
        $("#txtSearch").focus();
        return false;
    }

    $("#idLoading").show();

    $.post('<?php echo base_url("master/getDataMasterSchool/search"); ?>', {
            txtSearch: txtSearch
        },
        function(data) {
            $('#idTbodyCert').empty();
            $('#idTbodyCert').append(data.tr);

            $("#idLoading").hide();
        },
        "json"
    );
}

function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEdit").val();
    var txtnameschool = $("#txtnameschool").val();

    if (txtnameschool == "") {
        alert("Vessel Type Name Empty..!!");
        $("#txtnameschool").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('txtnameschool', txtnameschool);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataMasterSchool'); ?>", {
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

    $.post("<?php echo base_url('master/getDataEdit'); ?>", {
            type: type,
            idEdit: id
        },
        function(data) {
            if (data.error) {
                alert("Error: " + data.error);
                console.error("Server Error:", data.error);
            } else if (data.rsl && data.rsl.length > 0) {
                $("#txtIdEdit").val(data.rsl[0].id);
                $("#txtnameschool").val(data.rsl[0].schoolname);
            } else {
                alert("Data tidak ditemukan!");
            }
            $("#idLoading").hide();
        },
        "json"
    ).fail(function(xhr, status, error) {
        console.error("AJAX Error:", xhr.responseText);
        alert("Terjadi kesalahan saat mengambil data. Periksa console untuk detail.");
        $("#idLoading").hide();
    });
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
    parent.buttonMenuMaster('masterSchool');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-2 col-xs-12">
        <input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="School Name"
            autocomplete="off">
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
                        <th style="vertical-align:middle;width:5%;text-align:center;">NO</th>
                        <th style="vertical-align:middle;width:40%;text-align:center;">NAME SCHOOL</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">ACTION</th>
                    </tr>
                </thead>
                <tbody id="idTbodyCert">
                    <?php echo $tr; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 col-xs-12" style="background-color:#ABABAB;padding:5px;">
        <div class="row" style="">
            <legend style="margin-bottom:10px;text-align:right;paddin   g-right:10px;"><b><i>:: Form School ::</i></b>
            </legend>
            <div class="col-md-12 col-xs-12">
                <label for="txtnameschool">Name School :</label>
                <input type="text" class="form-control input-sm" id="txtnameschool" value="" placeholder="Name School">
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