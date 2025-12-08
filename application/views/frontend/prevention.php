<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>COVID-19 Prevention</title>
</head>

<body style="font-family:'Times New Roman', serif; font-size:11px; margin:15px;">

    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr style="height:70px;">
            <td style="width:70px; vertical-align:middle; text-align:left; padding-left:20px;">
                <img src="<?php echo FCPATH . 'assets/img/Logo_Andhika_2017.jpg'; ?>"
                    style="width:60px; display:block;">
            </td>

            <td style="text-align:center; vertical-align:middle;">
                <div style="font-size:18px; font-weight:bold; line-height:1;">COVID-19</div>
                <div style="font-size:14px; font-weight:bold; line-height:1;">PREVENTION</div>
            </td>

            <!-- Geser sedikit ke kanan dengan menambah width -->
            <td style="width:120px; vertical-align:middle;"></td>
        </tr>
    </table>



    <table style="width:100%; border-collapse:collapse; font-size:11px;">
        <tbody>

            <?php
            $items = array(
                array("Avoid these modes of travel if you have a fever or a cough.",
                 "Hindari perjalanan moda transportasi ini apabila anda sedang sakit demam atau batuk.",
                 "gambar1.jpg"),

                array("Eat only well-cooked food.",
                 "Makanlah makanan yang dimasak matang.",
                 "gambar2.jpg"),

                array("Avoid spitting in public.",
                 "Hindari meludah di keramaian.",
                 "gambar3.jpg"),

                array("Avoid close contact and travel with sick animals, particularly in wet markets.",
                 "Hindari kontak dekat dan bepergian dengan binatang yang sakit, terutama di pasar tradisional.",
                 "gambar4.jpg"),

                array("When coughing and sneezing, cover your mouth and nose with a tissue or flexed elbow.",
                 "Ketika batuk dan bersin, tutuplah mulut dan hidung dengan tisu atau siku.",
                 "gambar5.jpg"),

                array("Frequently clean hands with alcohol-based hand rub or wash with soap at least 20 seconds.",
                 "Sering membersihkan tangan dengan hand sanitizer atau sabun selama 20 detik.",
                 "gambar6.jpg"),

                array("Avoid touching eyes, nose, mouth.",
                 "Hindari menyentuh mata, hidung, dan mulut.",
                 "gambar7.jpg"),

                array("Avoid close contact with people suffering fever or cough.",
                 "Hindari kontak dekat dengan orang yang menderita demam atau batuk.",
                 "gambar8.jpg"),

                array("If wearing a mask, ensure it covers mouth and nose.",
                 "Jika memakai masker, pastikan menutupi mulut dan hidung.",
                 "gambar9.jpg"),

                array("If you become sick while traveling, tell the crew or ground staff.",
                 "Jika sakit saat bepergian, beritahu petugas.",
                 "gambar10.jpg"),

                array("Seek medical care early if you become sick and share history with the provider.",
                 "Cari perawatan medis lebih awal jika sakit.",
                 "gambar11.jpg"),
            );

            foreach ($items as $item): ?>
            <tr>
                <td style="padding:6px; border:1px solid #ccc;">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:72%; vertical-align:top; padding-right:6px;">
                                <?php echo $item[0]; ?><br>
                                <i><?php echo $item[1]; ?></i>
                            </td>

                            <td style="width:28%; text-align:right; vertical-align:top;">
                                <img src="<?php echo FCPATH . 'assets/img/' . $item[2]; ?>" style="width:80px;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>


    <!-- ===================== SIGN SECTION ===================== -->
    <table style="width:100%; margin-top:10px; border-collapse:collapse; font-size:11px; text-align:center;">
        <tr>
            <td colspan="4" style="padding:3px 0;">
                As International Chamber of Shipping Maritime Publications 2020<br>
                Have read, understand and will be implemented.
            </td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:6px; font-weight:bold;">RANK</td>
            <td style="border:1px solid #000; padding:6px; font-weight:bold;">NAME</td>
            <td style="border:1px solid #000; padding:6px; font-weight:bold;">SIGN</td>
            <td style="border:1px solid #000; padding:6px; font-weight:bold;">DATE</td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:6px;"><?php echo $crew->rankname; ?></td>
            <td style="border:1px solid #000; padding:6px;"><?php echo $crew->fullname; ?></td>
            <td style="border:1px solid #000; padding:6px;">&nbsp;</td>
            <td style="border:1px solid #000; padding:6px;"><?php echo $today; ?></td>
        </tr>
    </table>

</body>

</html>