<script type="text/javascript">
function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEditEducation").val();
    var idPerson = $("#txtIdPerson").val();
    var year = $("#slcYearEduc").val();
    var school = $("#txtSchoolEduc").val();
    var course = $("#txtCourseFinishEduc").val();
    var fileUpload = $("#uploadFile").val();

    formData.append('idEdit', idEdit);
    formData.append('idPerson', idPerson);
    formData.append('year', year);
    formData.append('school', school);
    formData.append('course', course);

    formData.append('cekFileUpload', fileUpload);
    formData.append('fileUpload', $("#uploadFile").prop('files')[0]);

    $("#idLoadingForm").show();
    $.ajax("<?php echo base_url('education/saveData'); ?>", {
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response);
            $("#idLoadingForm").hide();
            navProsesCrew();
        }
    });
}

function getDataEdit(id) {
    var idPerson = $("#txtIdPerson").val();
    $("#idLoadingForm").show();

    $.post('<?php echo base_url("education/getDataEdit"); ?>', {
            id: id,
            idPerson: idPerson
        },
        function(data) {
            $("#txtIdEditEducation").val(id);
            $("#slcYearEduc").val(data.year);
            $("#txtSchoolEduc").val(data.school);
            $("#txtCourseFinishEduc").val(data.course);

            $("#idViewFile").empty();
            $("#idViewFile").append(data.btnFile);

            $("#idLoadingForm").hide();
        },
        "json"
    );
}

function delFile(id, file, idPerson) {
    var cfm = confirm("Delete File...??");
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("education/deleteFile"); ?>/', {
                id: id,
                file: file,
                idPerson: idPerson
            },
            function(data) {
                alert(data);
                $("#idViewFile").empty();
            },
            "json"
        );
    }
}

function delData(id, idPerson) {
    var cfm = confirm("Delete data...??");
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("education/deleteData"); ?>/', {
                id: id,
                idPerson: idPerson
            },
            function(data) {
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
        <button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="navProsesCrew();"><i
                class="fa fa-refresh"></i> Refresh</button>
    </div>
</div>
<div class="row" style="margin-top:5px;">
    <div class="col-md-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                style="background-color:#D7EAEC;">
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
            <legend style="margin-bottom:10px;text-align:right;padding-right:10px;"><b><i>:: Form Educational Attainment
                        ::</i></b></legend>
            <div class="col-md-6 col-xs-12">
                <label for="slcYearEduc">Year of Graduate :</label>
                <select class="form-control input-sm" id="slcYearEduc">
                    <?php echo $yearNya; ?>
                </select>
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtSchoolEduc">School :</label>
                <select class="form-control input-sm" id="txtSchoolEduc">
                    <?php echo $getSchoolByOption; ?>
                </select>
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="txtCourseFinishEduc">Course Finished :</label>
                <input type="text" class="form-control input-sm" id="txtCourseFinishEduc" value=""
                    placeholder="Course Finished">
            </div>
            <div class="col-md-8 col-xs-12">
                <label for="uploadFile">File :</label>
                <input type="file" class="form-control" id="uploadFile" value="">
            </div>
            <div class="col-md-4 col-xs-12">
                <label>&nbsp;</label>
                <button class="btn btn-warning btn-xs btn-block" title="Clear File"
                    onclick="$('#uploadFile').val('');">Clear</button>
                <div id="idViewFile" style="margin-top:5px;"></div>
            </div>
        </div>
        <div class="row" style="background-color:#ABABAB;padding-bottom:15px;">
            <div class="col-md-6 col-xs-12" style="padding-top:5px;">
                <input type="hidden" id="txtIdEditEducation" value="">
                <button class="btn btn-primary btn-xs btn-block" onclick="saveData();"><i
                        class="glyphicon glyphicon-saved"></i> Save</button>
            </div>
            <div class="col-md-6 col-xs-12" style="padding-top:5px;">
                <button class="btn btn-danger btn-xs btn-block" onclick="navProsesCrew();"><i
                        class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
            </div>
        </div>
    </div>
</div>