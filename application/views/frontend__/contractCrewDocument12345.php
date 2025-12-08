<script type="text/javascript">

</script>

<div class="row" style="margin-top:5px;">
	<div class="col-md-1 col-xs-12">
		<button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="navProsesCrew();"><i class="fa fa-refresh"></i> Refresh</button>
	</div>
</div>
<div class="row" style="margin-top:5px;">
	<div class="col-md-6 col-xs-12">
		<div class="table-responsive">
			<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover" style="background-color:#D7EAEC;">
				<thead>
					<tr style="background-color:#067780;color:#FFF;height:30px;">
						<th style="vertical-align:middle;width:7%;text-align:center;">No</th>
						<th style="vertical-align:middle;width:8%;text-align:center;">Group</th>
						<th style="vertical-align:middle;width:60%;text-align:center;">Certificates Name</th>
						<th style="vertical-align:middle;width:20%;text-align:center;">Action</th>
					</tr>
				</thead>
				<tbody id="idTbody">
					<?php //echo $trNya; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6 col-xs-12" >
		<legend style="margin-bottom:10px;"><b><i>:: Certificate / Document ::</i></b></legend>
		<div class="row">
			<div class="col-md-5 col-xs-12">
				<input type="checkbox" id="chkUseThisAllCert" value="Y">
				<label for="chkUseThisAllCert" style="font-size:12px;">(Use this All Certificate / Document)</label>
			</div>
			<div class="col-md-7 col-xs-12">
				<label for="slcMstCertAllCert">Certificate Name :</label>
				<select class="form-control input-sm" id="slcMstCertAllCert">
					<?php echo $optMstCert; ?>
				</select>
			</div>
		</div>
		<legend style="margin-top:10px;margin-bottom:10px;"><b><i>:: Certificate / Document Description ::</i></b></legend>
		<div class="row">
			<div class="col-md-4 col-xs-12">				
				<input type="checkbox" id="chkDisplayAllCert" value="Y">
				<label for="chkDisplayAllCert" style="font-size:12px;">Display</label>
			</div>
			<div class="col-md-4 col-xs-12">
				<label for="slcLicenseAllCert">License :</label>
				<select class="form-control input-sm" id="slcLicenseAllCert">
					<option value="-">-</option>
					<option value="COC" >COC</option>
					<option value="Endorsement" >Endorsement</option>
				</select>
			</div>
			<div class="col-md-4 col-xs-12">
				<label for="slcLevelAllCert">Level :</label>
				<select class="form-control input-sm" id="slcLevelAllCert">
					<option value="-">-</option>
					<option value="Incharge" >Incharge</option>
					<option value="Asst." >Asst.</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<label for="slcRedSingAllCert">Red sign :</label>
				<select class="form-control input-sm" id="slcRedSingAllCert">
					<option value="N">NO</option>
					<option value="Y">YES</option>
				</select>
			</div>
			<div class="col-md-4 col-xs-12">
				<input type="hidden" id="txtIdPersonAllCertificate" value="">
				<input type="hidden" id="txtIdEditAllCertificate" value="">
				<label>&nbsp</label>
				<button class="btn btn-primary btn-sm btn-block" onclick="saveData();"><i class="glyphicon glyphicon-saved"></i> Submit</button>
			</div>
			<div class="col-md-4 col-xs-12">
				<label>&nbsp</label>
				<button class="btn btn-danger btn-sm btn-block" onclick="navProsesCrew();"><i class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>