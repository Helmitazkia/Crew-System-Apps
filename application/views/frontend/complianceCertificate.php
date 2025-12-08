<script type="text/javascript">
	$(document).ready(function(){
		certRegA();
	});

	function certRegA()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC1');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/A"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegB()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC2');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/B"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegC()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC3');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/C"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegD()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC4');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/D"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegE()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC5');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/E"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegF()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC6');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/F"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegG()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC7');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/G"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function certRegH()
	{
		resetNavAttribut();
		buttonAktif('btnNavCC8');
		var idPerson = $("#txtIdPersonCC").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaCC").empty();
		$("#idDivLembarKerjaCC").load('<?php echo base_url("compliance/getDataReg/H"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function buttonAktif(idBtn)
	{
		$("#"+idBtn).css('background-color','#067780');
		$("#"+idBtn).css('color','#FFFFFF');
		$("#"+idBtn).attr('disabled','disabled');
	}

	function resetNavAttribut()
	{
		for (var lan=1;lan<=8;lan++)
		{
			$("#btnNavCC"+lan).css('background-color','');
			$("#btnNavCC"+lan).css('color','');
			$("#btnNavCC"+lan).attr('disabled',false);
		}		
	}

</script>

<div class="row" style="margin-top:5px;">
	<div class="col-md-12 col-xs-12">
		<div class="btn-group btn-group-justified" role="group" aria-label="Navigasi">
			<input type="hidden" id="txtIdPersonCC" value="<?php echo $idPerson; ?>">
			<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC1" onclick="certRegA();">(A) Reg I</button>
		  	</div>
		  	<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC2" onclick="certRegB();">(B) Reg VI / 1</button>
		  	</div>
		  	<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC3" onclick="certRegC();">(C) Reg VI / 2-4</button>
		  	</div>
		  	<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC4" onclick="certRegD();">(D) Reg II / 1-4, III / 1-4</button>
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC5" onclick="certRegE();">(E) Other mandatory</button>
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC6" onclick="certRegF();">(F) GMDSS Certificates</button>
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC7" onclick="certRegG();">(G) Reg V / 1</button>
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavCC8" onclick="certRegH();">(H) V/2 and V/3</button>
			</div>
		</div>
	</div>
</div>
<div style="margin-top:10px;" id="idDivLembarKerjaCC">
</div>