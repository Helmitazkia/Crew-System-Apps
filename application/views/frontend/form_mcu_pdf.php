<!DOCTYPE html>
<html lang="id">

<head>
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

    .box {
      display: inline-block;
      width: 16px;
      height: 16px;
      line-height: 16px;
      border: 1.2px solid #000;
      text-align: center;
      vertical-align: middle;
      margin-right: 6px;
    }

    .signature {
      margin-top: 40px;
      width: 40%;
      text-align: center;
    }
  </style>
</head>

<body <?php if ($status_mcu == 2): ?> style="
        background-image: url('<?= base_url('assets/img/rejected.jpg') ?>');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 200px 200px;
        background-attachment: fixed;
        opacity: 0.1;
    " <?php endif; ?>>

  <div class="page">

    <!-- HEADER -->
    <table width="100%" cellpadding="5" cellspacing="0" style="font-family:'Times New Roman';" border="1">
      <tr>
        <!-- KIRI : LOGO -->
        <td width="6%" align="left" valign="middle">
          <img src="./assets/img/Logo_Andhika_2017.jpg" style="height:50px;">
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
            <img src="./assets/img/Bureau_Veritas_Logo.jpg" style="height:30px;">
            <img src="./assets/img/Iso.jpg" style="height:30px;">
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
      <img src="<?= base_url('assets/imgQRCodeCrewCV/'.$signature_qr); ?>" style="height:60px;"><br>
      <?php endif; ?>
      <strong>Eva Marliana</strong><br>
      Crew Manager
    </div>

  </div>

</body>

</html>