<script type="text/javascript">
	$(document).ready(function(){
		others1();
	});

	function others1()
	{
		resetNavAttribut();
		buttonAktif('btnNavOther1');
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaOthers").empty();
		$("#idDivLembarKerjaOthers").load('<?php echo base_url("others/getDataOthers/others1"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function others2()
	{
		resetNavAttribut();
		buttonAktif('btnNavOther2');
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaOthers").empty();
		$("#idDivLembarKerjaOthers").load('<?php echo base_url("others/getDataOthers/others2"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
			$("[id^=txtDate]").datepicker({dateFormat:'yy-mm-dd',showButtonPanel:true,changeMonth:true,changeYear:true,yearRange: "-70:+4"});
		});
	}

	function others3()
	{
		resetNavAttribut();
		buttonAktif('btnNavOther3');
		var idPerson = $("#txtIdPerson").val();
		$("#idLoadingForm").show();

		$("#idDivLembarKerjaOthers").empty();
		$("#idDivLembarKerjaOthers").load('<?php echo base_url("others/getDataOthers/others3"); ?>'+'/'+idPerson, function() {
			$("#idLoadingForm").hide();
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
		for (var lan=1;lan<=3;lan++)
		{
			$("#btnNavOther"+lan).css('background-color','');
			$("#btnNavOther"+lan).css('color','');
			$("#btnNavOther"+lan).attr('disabled',false);
		}		
	}

</script>

<div class="row" style="margin-top:5px;">
	<div class="col-md-12 col-xs-12">
		<div class="btn-group btn-group-justified" role="group" aria-label="Navigasi">
			<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavOther1" onclick="others1();">OTHER CERTIFICATE (MARINA)</button>
		  	</div>
		  	<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavOther2" onclick="others2();">PHYSICAL INSPECTION, YELLOW CARD</button>
		  	</div>
		  	<div class="btn-group" role="group">
		  		<button type="button" class="btn btn-default btn-sm" style="font-weight:bold;" id="btnNavOther3" onclick="others3();">CREW MATRIX</button>
		  	</div>
		</div>
	</div>
</div>
<div style="margin-top:10px;" id="idDivLembarKerjaOthers">
</div>