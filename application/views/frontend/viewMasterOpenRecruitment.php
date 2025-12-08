<script>
function searchData() {
    var txtSearch = $("#txtSearch").val();

    if (txtSearch == "") {
        alert("Search Text Empty..!!");
        $("#txtSearch").focus();
        return false;
    }

    $("#idLoading").show();

    $.post('<?php echo base_url("master/getDataOpenRecruitment/search"); ?>', {
            txtSearch: txtSearch
        },
        function(data) {
            $('#idTbodyOpenRecruitment').empty();
            $('#idTbodyOpenRecruitment').append(data.trNya);

            $("#idLoading").hide();
        },
        "json"
    );
}

function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEdit").val();
    var txtSubjectName = $("#txtSubjectName").val();
    var txtQualification = $("#txtQualification").val();
    var slcOptionRank = $("#slcOptionRank").val();

    if (slcOptionRank == "") {
        alert("Please select a Rank..!!");
        $("#slcOptionRank").focus();
        return false;
    }

    if (txtSubjectName == "") {
        alert("Subject Name is required..!!");
        $("#txtSubjectName").focus();
        return false;
    }

    formData.append('idEdit', idEdit);
    formData.append('slcOptionRank', slcOptionRank);
    formData.append('txtSubjectName', txtSubjectName);
    formData.append('txtQualification', txtQualification);

    $("#idLoading").show();
    $.ajax("<?php echo base_url('master/saveDataOpenRecruitment'); ?>", {
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
            $("#slcOptionRank").val(data['rsl'][0]['rank']);
            $("#txtSubjectName").val(data['rsl'][0]['subject_name']);
            $("#txtQualification").val(data['rsl'][0]['qualification']);

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

function pubDate(id, type) {
    if (type == 'unPublish') {
        cfm = confirm("Un Publish data...??");
    } else if (type == 'publish') {
        cfm = confirm("Publish data...??");
    }
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("master/pubDate"); ?>/', {
                id: id,
                type: type
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
    parent.buttonMenuMaster('openRecruitment');
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
                        <th style="vertical-align:middle;width:5%;text-align:center;">No</th>
                        <th style="vertical-align:middle;width:45%;text-align:center;">Subject Name</th>
                        <th style="vertical-align:middle;width:25%;text-align:center;">Qualification</th>
                        <th style="vertical-align:middle;width:20%;text-align:center;">Date Publish</th>
                        <th style="vertical-align:middle;width:5%;text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="idTbodyOpenRecruitment">
                    <?php echo $trNya; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 col-xs-12" style="background-color:#ABABAB;padding:5px;">
        <div class="row">
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;">
                <b><i>:: Form Open Recruitment ::</i></b>
            </legend>

            <div class="col-md-12 col-xs-12">
                <label for="slcOptionRank">Rank:</label>
                <select name="slcOptionRank" id="slcOptionRank" class="form-control input-sm">
                    <?php echo $optRank; ?>
                </select>
            </div>

            <div class="col-md-12 col-xs-12">
                <label for="txtSubjectName">Subject Name :</label>
                <input type="text" class="form-control input-sm" id="txtSubjectName" value=""
                    placeholder="Subject Name">
            </div>

            <div class="col-md-12 col-xs-12">
                <label for="txtQualification">Qualification :</label>
                <textarea name="txtQualification" id="txtQualification" class="form-control input-sm"
                    placeholder="Qualification"></textarea>
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