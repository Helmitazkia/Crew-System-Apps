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

    $.post('<?php echo base_url("master/getDataCity/search"); ?>', {
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
    var txtCity = $("#txtCity").val();

    if (txtCity == "") {
        alert("City Name Empty..!!");
        $("#txtCity").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('txtCity', txtCity);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataCity'); ?>", {
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

function getDataEdit(userId, type) {
    $("#idLoading").show();

    $.post('<?php echo base_url("master/getDataEdit"); ?>', {
            type: type,
            idEdit: userId
        },
        function(data) {
            $("#txtIdEdit").val(data['rsl'][0]['KdKota']);
            $("#txtCity").val(data['rsl'][0]['NmKota']);

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
    parent.buttonMenuMaster('city');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-2 col-xs-12">
        <input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="City Name..">
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
                        <th style="vertical-align:middle;width:5%;text-align:center;">No</th>
                        <th style="vertical-align:middle;width:75%;text-align:center;">CITY NAME</th>
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
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form City ::</i></b>
            </legend>
            <div class="col-md-12 col-xs-12">
                <label for="txtCity">City Name :</label>
                <input type="text" class="form-control input-sm" id="txtCity" value="" placeholder="City">
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