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

    $.post('<?php echo base_url("master/getDataMasterCrewUser/search"); ?>', {
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
    var txtidperson = $("#txtidperson").val();
    var txtfullname = $("#txtfullname").val();
    var txtusername = $("#txtusername").val();
    var txtpassword = $("#txtpassword").val();

    if (txtidperson == "") {
        alert("ID Person Empty..!!");
        $("#txtidperson").focus();
        return false;
    }
    if (txtusername == "") {
        alert("Username Empty..!!");
        $("#txtusername").focus();
        return false;
    }
    if (txtpassword == "") {
        alert("Password Empty..!!");
        $("#txtpassword").focus();
        return false;
    }
    if (txtfullname == "") {
        alert("Full Name Empty..!!");
        $("#txtfullname").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('txtidperson', txtidperson);
    formData.append('txtfullname', txtfullname);
    formData.append('txtusername', txtusername);
    formData.append('txtpassword', txtpassword);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataUserMaster'); ?>", {
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
            $("#txtIdEdit").val(data['rsl'][0]['id']);
            $("#txtidperson").val(data['rsl'][0]['idperson']);
            $("#txtfullname").val(data['rsl'][0]['fullname']);
            $("#txtusername").val(data['rsl'][0]['username']);
            $("#txtpassword").val(data['rsl'][0]['password']);

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
    parent.buttonMenuMaster('user');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-2 col-xs-12">
        <input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="Full Name..">
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
                    <tr style="background-color: #067780; color: #FFF; height: 30px;">
                        <th style="width: 5%;  text-align: center; vertical-align: middle;">No</th>
                        <th style="width: 10%; text-align: center; vertical-align: middle;">ID Person</th>
                        <th style="width: 40%; text-align: center; vertical-align: middle;">Full Name</th>
                        <th style="width: 45%; text-align: center; vertical-align: middle;">Username</th>
                        <th style="width: 50%; text-align: center; vertical-align: middle;">Password</th>
                        <th style="width: 10%; text-align: center; vertical-align: middle;">Action</th>
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
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Crew ::</i></b>
            </legend>
            <div class="col-md-12 col-xs-12">
                <label for="txtidperson">ID Person :</label>
                <input type="text" class="form-control input-sm" id="txtidperson" value="" placeholder="ID Person">
            </div>
            <div class="col-md-12 col-xs-12">
                <label for="txtfullname">Full Name :</label>
                <input type="text" class="form-control input-sm" id="txtfullname" value="" placeholder="Full Name">
            </div>
            <div class="col-md-12 col-xs-12">
                <label for="txtusername">Username :</label>
                <input type="text" class="form-control input-sm" id="txtusername" value="" placeholder="Username">
            </div>
            <div class="col-md-12 col-xs-12">
                <label for="txtpassword">Password :</label>
                <input type="password" class="form-control input-sm" id="txtpassword" value="" placeholder="Password">
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