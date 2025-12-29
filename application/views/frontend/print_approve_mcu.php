<!DOCTYPE html>
<html lang="id">

<head>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <meta charset="UTF-8">
  <title>Medical Check Up (MCU)</title>
  <style>
    body {
      font-family: "Times New Roman", serif;
      font-size: 12px;
      margin: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td,
    th {
      vertical-align: top;
      padding: 4px;
    }

    .border td,
    .border th {
      border: 1px solid #000;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .mt {
      margin-top: 15px;
    }

    /* Checkbox aman mPDF */
    .box {
      display: inline-block;
      width: 16px;
      height: 16px;
      line-height: 16px;
      /* KUNCI */
      border: 1.2px solid #000;
      text-align: center;
      vertical-align: middle;
      margin-right: 6px;
      /* JANGAN LEBIH BESAR */
    }


    .signature {
      margin-top: 40px;
      width: 40%;
      text-align: center;
    }
  </style>

  <style>
    .header-section {
  background: #0d6efd; /* Biru Bootstrap */
  color: #fff;
  padding: 12px 20px;
  margin-bottom: 15px;
}
  </style>
</head>

<body>
    <section class="header-section">

    </section>
  <div class="card">

    <!-- HEADER -->
    <table width="100%" cellpadding="5" cellspacing="0" style="font-family:'Times New Roman';">
      <tr>
        <!-- KIRI : LOGO -->
        <td width="6%" align="left" valign="middle">
          <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="height:50px;">
          <!-- <img src="base_urlassets/img/Logo_Andhika_2017.jpg" style="height:50px;"> -->
        </td>

        <!-- TENGAH : JUDUL -->
        <td width="50%" align="left" valign="middle" style="padding-top:27px;">
          <div style="font-size:17px; font-weight:bold;">
            PT. ANDHINI EKA KARYA SEJAHTERA
          </div>
        </td>

        <!-- KANAN : LISENSI + LOGO -->
        <td width="25%" align="right" valign="middle">
          <div style="font-size:11px; font-weight:bold;">
            SRPS LICENSE NO:
          </div>
          <div style="font-size:10px;">
            SIUPPAK 12.12 Tahun 2014
          </div>
          <div>
            <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>" style="height:30px;">
            <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="height:30px;">
          </div>
        </td>
      </tr>
    </table>

    <!-- TUJUAN -->
    <table class="mt">
      <tr>
        <td>
          Kepada Yth:<br>
          <?php echo $clinic_name; ?><br>
          Jl. Cilincing Raya No. 74<br>
          Tanjung Priok - Jakarta Utara<br>
          Telp: (021) 4411281<br>
          Fax: (021) 44830763
        </td>
        <td class="right">
          Jakarta, <?php echo date('d M Y', strtotime($date_mcu)); ?>
        </td>
      </tr>
    </table>

    <p class="center"><strong>TOP URGENT<br>_______________________________</strong></p>

    <p>Dengan hormat,<br>Bersama ini kami mohon agar dapat dilakukan pemeriksaan:</p>

    <!-- MCU LIST -->
    <table style="width:700px;">
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;word-wrap: break-word;"><?php echo ($mcu->mcu1==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          1. Medical Check Up Standart Perla
        </td>
      </tr>
      <tr>
        <td>
          <span class="box" style="font-family: 'DejaVu Sans'; font-size: 16px;"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu2==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          2. Medical Check Up Kerajaan Malaysia
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu3==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          <strong>3. Medical Check Up Panama + ECG + Renal Function + Lever Function + Glukosa at Random</strong>
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu4==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          4. Pemeriksaan Gigi & Gusi (Dental+Gum)
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu5==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          <strong>5. Drug & Alcoholic Test 6 (six) items</strong>
          <table class="table table-borderless table-sm mt-2">
            <tr>
              <td class="fw-bold ps-4" style="width:55%;padding-left:38px;">
                Pemeriksaan no. 5,6,7,8 dilakukan JIKA<br>
                SUDAH FIT dan biayanya dibebankan<br>
                kepada PT. Andhini Eka Karya Sejahtera
              </td>
              <td style="width:55%;">
                Cocain metabolic<br>
                Marijuana metabolic<br>
                Morphine / Opiates<br>
                Pencyclidine<br>
                Amphetamine<br>
                Alcohol metabolic
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu6==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          6. HIV Test
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu7==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          7. Chemical Contamination Test
        </td>
      </tr>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu8==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          8. Sleep Apnea Syndrome
        </td>
      </tr>
    </table>

    <p class="mt">Pemeriksaan dilaksanakan untuk crew kami:</p>

    <!-- CREW -->
    <table class="border">
      <tr class="center">
        <th>No</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Kapal</th>
      </tr>

      <?php $no = 1; foreach ($persons as $p): ?>
      <tr>
        <td class="center"><?php echo $no++; ?></td>
        <td><?php echo $p->name_person; ?></td>
        <td><?php echo $p->rank; ?></td>
        <td><?php echo $p->vessel_name; ?></td>
      </tr>
      <?php endforeach; ?>
    </table>


    <p class="mt">Harap biaya dibebankan pada:</p>

    <table>
      <tr>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu9==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          PT. Andhini Eka Karya Sejahtera

        </td>
        <td>
          <span class="box"
            style="font-family: 'DejaVu Sans'; font-size: 16px;"><?php echo ($mcu->mcu10==1)?'✓':'&nbsp;&nbsp;&nbsp;'; ?></span>
          Crew yang bersangkutan
        </td>
      </tr>
    </table>

    <div class="signature">
      <p>Hormat Kami,</p>
      <?php if ($status_mcu == 1 || $status_mcu == 2): ?>
      <img src="<?php echo base_url('assets/imgQRCodeCrewCV/'.$signature_qr); ?>" style="height:50px;"><br>
      <?php endif; ?>
      <strong>Eva Marliana</strong><br>
      Crew Manager
    </div>


    <!-- APPROVE BUTTON -->
    <?php if ($status_mcu == 0): ?>
    <div style="margin-top:30px; text-align:right;">
      <div style="display:inline-flex; gap:10px;">

        <!-- APPROVE -->
        <form method="post" action="<?php echo base_url('report/approve_mcu') ?>">
          <input type="hidden" name="id_report" value="<?php echo $id_report ?>">
          <input type="hidden" name="hash_id" value="<?php echo $hash_id ?>">
          <button type="submit" style="
          background:#28a745;
          color:#fff;
          border:none;
          padding:10px 18px;
          font-size:14px;
          border-radius:5px;
          cursor:pointer;">
            ✔ APPROVE MCU
          </button>
        </form>

        <!-- REJECT (OPEN MODAL) -->
        <button type="button" id="btnRejectMCU" style="
        background:#dc3545;
        color:#fff;
        border:none;
        padding:10px 18px;
        font-size:14px;
        border-radius:5px;
        cursor:pointer;">
          ✖ REJECT MCU
        </button>

      </div>
    </div>
    <?php endif; ?>

    <?php if ($status_mcu == 1 || $status_mcu == 2): ?>
    <div style="margin-top:30px; text-align:right;">
      <div style="display:inline-flex; gap:10px;">

        <button type="button" class="view-btn" data-id="<?php echo $id_report ?>" style="
              background:#0080ff;
              color:#fff;
              border:none;
              padding:10px 18px;
              font-size:14px;
              border-radius:5px;
              cursor:pointer;
              display:flex;
              align-items:center;
              gap:8px;">

          <span>Print MCU PDF</span>

        </button>

      </div>
    </div>
    <?php endif; ?>


    <div class="modal fade" id="modalRejectMCU" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <form method="post" action="<?php echo base_url('report/reject_mcu') ?>">
            <div class="modal-body">
              <input type="hidden" name="id_report" value="<?php echo $id_report ?>">
              <input type="hidden" name="hash_id" value="<?php echo $hash_id ?>">

              <div class="form-group">
                <label style="padding-bottom:10px;"><strong>Reason for Rejection</strong></label>
                <br>
                <textarea name="remarks_reject" class="form-control" rows="4" required></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">
                Submit Reject
              </button>
              <button type="button" class="btn btn-danger" id="btnCloseModal">Tutup</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('#modalRejectMCU').modal({
      show: false
    });

    $('#btnRejectMCU').click(function () {
      $('#modalRejectMCU').modal('show');
    });

    $('#btnCloseModal').click(function () {
      $('#modalRejectMCU').modal('hide');
    });


    $(document).on('click', '.view-btn', function () {
      var idReport = $(this).data('id');
      // console.log("View MCU ID:", idReport);
      // return false;
      if (!idReport) {
        alert('ID Report tidak ditemukan');
        return;
      }

      $.ajax({
        url: "<?php echo base_url('report/get_report_mcu_detail'); ?>",
        type: "POST",
        dataType: "json",
        data: {
          id_report: idReport
        },
        // beforeSend: function () {
        //     // console.log("Loading MCU detail...");
        // },
        success: function (res) {
          console.log(res, "MCU DETAIL");

          if (!res.success) {
            alert(res.message);
            return;
          }
          PrintviewFileMcu(res.data);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          alert('Gagal mengambil data MCU');
        }
      });
    });

  });


  function PrintviewFileMcu(data) {
    if (!data || !data.report || !data.persons) {
      alert("Data MCU tidak valid");
      return;
    }

    let report = data.report;
    let persons = data.persons;
    let date_mcu = report.date_mcu;
    let clicnic_name = report.clinic_name;
    let status_mcu = report.status_mcu;
    let signature_qr = report.signature_qr;

    if (persons.length === 0) {
      alert("Data crew kosong");
      return;
    }

    // MCU checkbox array
    let mcuArr = [];
    for (let i = 1; i <= 10; i++) {
      mcuArr.push(report['answer_' + i]);
    }


    let postData = {
      mcu: mcuArr.join(','),
      persons: JSON.stringify(persons), // ⬅️ kirim SEMUA crew
      date_mcu: date_mcu,
      clinic_name: clicnic_name,
      status_mcu: status_mcu,
      signature_qr: signature_qr
    };

    // console.log("POST PDF MCU (ALL CREW):", postData);

    let form = $('<form>', {
      action: "<?php echo base_url('report/generatePDF_MCU'); ?>",
      method: "POST",
      target: "_blank"
    });

    $.each(postData, function (key, val) {
      $('<input>', {
        type: 'hidden',
        name: key,
        value: val
      }).appendTo(form);
    });

    $('body').append(form);
    form.submit();
    form.remove();
  }
</script>


<style>
  /* CARD WRAPPER */
  .card {
    max-width: 900px;
    margin: 30px auto;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
    padding: 20px;
  }

  /* SECTION */
  .section {
    margin-top: 20px;
  }

  .section-title {
    font-weight: bold;
    font-size: 13px;
    border-bottom: 2px solid #000;
    padding-bottom: 4px;
    margin-bottom: 10px;
  }

  /* APPROVE FOOTER */
  .approve-footer {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  /* Hide approve when print / pdf */
  @media print {
    .approve-footer {
      display: none;
    }
  }
</style>