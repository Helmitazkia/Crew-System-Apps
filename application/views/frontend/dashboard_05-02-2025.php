<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <script type="text/javascript">
    function displayOnBoard() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailOnBoard"); ?>', {},
            function(data) {
                $("#idBodyModal").empty();
                $("#idBodyModal").append(data.trNya);

                $("#idLblModal").text(data.totalCrew);

                $('#modalReqDetail').modal('show');
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function displayOnLeave() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailOnLeave"); ?>', {}, function(data) {
            if (data) {
                $("#idBodyTotalCrewOnLeave").empty();
                $("#idBodyTotalCrewOnLeave").append(data.trNya);
                $("#idLblModalTotalCrew").text(data.totalCrew);
                $('#modalTotalCrewOnLeave').modal('show');
            } else {
                alert("Gagal memuat data. Silakan coba lagi.");
            }
            $("#idLoading").hide();
        }, "json").fail(function() {
            alert("Terjadi kesalahan. Silakan coba lagi.");
            $("#idLoading").hide();
        });
    }



    $(document).on('click', '.table-row', function() {
        var $detailsRow = $(this).next('.details-row');
        if ($detailsRow.length) {
            $detailsRow.slideToggle();
        }
    });


    function displayNewApplicent() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailCrewNewApplicent"); ?>', {},
            function(data) {
                $("#idBodyModalCrew").empty();
                $("#idBodyModalCrew").append(data.trNya);

                $("#idLblModalTotalCrew").text(data.totalCrew);

                $('#modalShowCrewing').modal('show');
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function getDetailCrew(vslCode) {
        $("#idLoadingModal").show();
        $.post('<?php echo base_url("dashboard/getDetailCrewOnBoard"); ?>', {
                vslCode: vslCode
            },
            function(data) {
                $("#idBodyModalCrewDetail").empty();
                $("#idBodyModalCrewDetail").append(data.trNya);
                $("#idLblModalVesselDetail").text(data.vessel);

                $("#idLoadingModal").hide();
            },
            "json"
        );
    }

    function getDetailCrewName(rank, crewName) {
        $.ajax({
            url: '<?php echo base_url("dashboard/getDetailCrewOnLeave"); ?>',
            type: 'POST',
            data: {
                rank: rank,
                crew_name: crewName,
            },
            dataType: 'json',
            success: function(data) {
                const tbody = $('#idBodyModalCrewDetailOnLeave');
                tbody.html(data.trNya);

                const label = $('#idLblModalCrewNameOnLeave');
                label.html(`${crewName} (${rank})`);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching crew details:', error);
                alert('Failed to fetch crew details. Please try again later.');
            },
        });
    }

    function changeCursor(element, hasData) {
        if (hasData) {
            element.style.cursor = 'pointer';
        } else {
            element.style.cursor = 'default';
        }
    }

    function resetCursor(element) {
        element.style.cursor = 'default';
    }

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/crewBarChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let categories = [];
                let crewCounts = [];
                let maleCounts = [];
                let femaleCounts = [];
                let avgAges = [];

                data.forEach(function(item) {
                    categories.push(item.ship);
                    crewCounts.push(item.crew_count);
                    maleCounts.push(item.male);
                    femaleCounts.push(item.female);
                    avgAges.push(item.avg_age);
                });

                let totalCrewOnboard = crewCounts.reduce((sum, num) => sum + num, 0);

                Highcharts.chart('idDivOverall', {
                    chart: {
                        type: 'bar',
                        height: 900
                    },
                    title: {
                        text: `Crew Distribution by Ship Client (Total: ${totalCrewOnboard.toLocaleString()})`,
                        style: {
                            fontSize: '22px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Ships',
                            style: {
                                fontSize: '20px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '16px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Crew',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '16px'
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        style: {
                            fontSize: '14px'
                        },
                        pointFormat: '<b> {series.name}: {point.y}</b>'
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '14px',
                                    color: 'black'
                                }
                            },
                            groupPadding: 0.01,
                            pointPadding: 1.5,
                            pointWidth: 5.5,
                            cursor: 'pointer',
                            events: {
                                click: function(event) {
                                    let shipIndex = event.point.index;
                                    let shipData = data[shipIndex];

                                    console.log("Ship Data:", shipData);

                                    let modalBody = $(
                                        '#idBodyModalCrewDetailByClientShip');
                                    modalBody.empty();

                                    if (shipData.crew_names && shipData.crew_names
                                        .length > 0) {
                                        shipData.crew_names.forEach((name, index) => {
                                            let rank = shipData.crew_ranks &&
                                                shipData.crew_ranks[index] ?
                                                shipData.crew_ranks[index] :
                                                '-';
                                            console.log(
                                                `Crew ${index + 1}: ${name} - ${rank}`
                                            );

                                            modalBody.append(`
                                            <tr>
                                                <td style="text-align: center;">${index + 1}</td>
                                                <td>${name}</td>
                                                <td>${rank}</td>
                                            </tr>
                                        `);
                                        });
                                    } else {
                                        modalBody.append(`
                                        <tr>
                                            <td colspan="3" style="text-align: center; font-weight: bold;">No Crew Data Available</td>
                                        </tr>
                                    `);
                                    }

                                    $('#detailModalClientShip').modal('show');
                                }
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: true,
                        itemStyle: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    },
                    series: [{
                            name: 'Total Crew',
                            data: crewCounts,
                            color: '#0073e6'
                        },
                        {
                            name: 'Male Crew',
                            data: maleCounts,
                            color: '#28a745'
                        },
                        {
                            name: 'Female Crew',
                            data: femaleCounts,
                            color: '#e63946'
                        },
                        {
                            name: 'Average Age',
                            data: avgAges,
                            color: '#f39c12'
                        }
                    ]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('Gagal mengambil data kru. Silakan coba lagi.');
            }
        });
    });



    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/contractBarChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const crewData = response.crewData;
                const rankSummary = response.rankSummary;

                const months = Object.keys(rankSummary);
                const rankCategories = [...new Set(crewData.map(item => item.rank_name))];

                let seriesData = rankCategories.map(rank => ({
                    name: rank,
                    data: months.map(month => (rankSummary[month] && rankSummary[month][
                        rank
                    ]) ? rankSummary[month][rank] : 0)
                }));

                Highcharts.chart('idDivBarChartRank', {
                    chart: {
                        type: 'column',
                        //backgroundColor: null,
                        height: 1000,
                        width: 1150
                    },
                    title: {
                        text: 'Crew Contract Expiry: Monthly Distribution in 2025',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        categories: months,
                        crosshair: true,
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '14px',
                                color: '#333'
                            }
                        },
                        accessibility: {
                            description: 'Months'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Crew',
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '14px',
                                color: '#333'
                            }
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: '#333',
                                fontSize: '15px'
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        valueSuffix: ' crew members'
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            pointPadding: 0.1,
                            groupPadding: 0.1,
                            borderWidth: 0,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                color: '#FFFFFF',
                                style: {
                                    fontWeight: 'bold',
                                    textOutline: '1px contrast',
                                    fontSize: '13px'
                                }
                            },
                            events: {
                                click: function(event) {
                                    const index = Math.round(event.point.index);
                                    const selectedMonth = months[index];
                                    const crewDetails = crewData.filter(item => item
                                        .month === selectedMonth);

                                    let modalBody = crewDetails.map((item, i) =>
                                        `<tr>
                                        <td style="text-align: center;">${i + 1}</td>
                                        <td style="text-align: left;">${item.crew_name}</td>
                                        <td style="text-align: left;">${item.rank_name}</td>
                                        <td style="text-align: left;">${item.sign_on_date}</td>
                                        <td style="text-align: left;">${item.estimated_signoff_date}</td>
                                    </tr>`
                                    ).join('');

                                    $('#modalTitle').text(
                                        `Crew Distribution for ${selectedMonth}`);
                                    $('#idBodyModalCrewDetailByEstimatedSignOff').html(
                                        modalBody);
                                    $('#detailModalCrewSignoff').modal('show');
                                }
                            }
                        }
                    },
                    series: seriesData,
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom',
                        itemStyle: {
                            fontSize: '14px',
                            fontWeight: 'normal',
                            color: '#333'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching contractBarChart data:', error);
            }
        });
    });




    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url("dashboard/shipDemograph"); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let totalCrewOnboard = data.reduce((sum, item) => sum + item.jumlah_crew_onboard,
                    0);

                Highcharts.chart('idTotalCrewByKapal', {
                    chart: {
                        type: 'bar',
                        //backgroundColor: null,
                        height: 900,
                        width: 500
                    },
                    title: {
                        text: `Crew Distribution by Owned Ship (Total: ${totalCrewOnboard.toLocaleString()})`,
                        align: 'center',
                        style: {
                            fontSize: '20px',
                            color: 'black'
                        }
                    },
                    xAxis: {
                        categories: data.map(item => item.nama_kapal),
                        title: {
                            text: 'Nama Kapal',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Crew / Age',
                            style: {
                                fontSize: '15px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '15px'
                            }
                        }
                    },
                    series: [{
                            name: 'Total Crew Onboard',
                            data: data.map(item => item.jumlah_crew_onboard),
                            color: '#007bff'
                        },
                        {
                            name: 'Male',
                            data: data.map(item => item.total_male),
                            color: '#28a745'
                        },
                        {
                            name: 'Female',
                            data: data.map(item => item.total_female),
                            color: '#dc3545'
                        },
                        {
                            name: 'Average Age',
                            data: data.map(item => item.rata_rata_umur),
                            color: '#fd7e14'
                        }
                    ],
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '15px',
                                    color: 'black'
                                }
                            },
                            pointWidth: 10,
                            point: {
                                events: {
                                    click: function() {
                                        const vslCode = data[this.index].kode_kapal;
                                        const vesselName = this.category;
                                        const status = data[this.index].status;

                                        $("#idLblModalVesselDetailByOwnShip").text(
                                            vesselName);
                                        $("#idLblVesselStatus")
                                            .text(`Status: ${status}`)
                                            .css("color", status === "Properly Manned" ?
                                                "green" : "red");

                                        if (status === "Properly Manned") {
                                            $("#idLblVesselStatus").append(
                                                '<br><span style="font-size: 14px; color: green;">The vessel meets the minimum crew number requirements for safe sailing (≥ 22 crew).</span>'
                                            );
                                        }

                                        $.ajax({
                                            url: '<?php echo base_url("dashboard/getDetailCrewOnBoard"); ?>',
                                            method: 'POST',
                                            data: {
                                                vslCode: vslCode
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                $("#idBodyModalCrewDetailByOwnShip")
                                                    .html(response.trNya);
                                                $("#idModalCrewByOwnShip")
                                                    .modal('show');
                                            },
                                            error: function() {
                                                alert(
                                                    "Failed to load crew details. Please try again."
                                                );
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    },
                    bar: {
                        groupPadding: 0.1
                    },
                    legend: {
                        enabled: true,
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom',
                        itemStyle: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        },
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    }
                });
            },
            error: function() {
                alert("Failed to load data.");
            }
        });
    });



    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url("dashboard/getSchool"); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const categories = data.map(item => item.school);
                const seriesDataOnboard = data.map(item => item.onboard_crew);

                Highcharts.chart('idDivtotalSchool', {
                    chart: {
                        type: 'bar',
                        //backgroundColor: null,
                        height: 800
                    },
                    title: {
                        text: 'Top 10 Schools by Onboard Crew',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Nama Sekolah'
                        },
                        labels: {
                            style: {
                                fontSize: '14px'
                            }
                        },
                        gridLineWidth: 1
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Onboard Crew'
                        },
                        labels: {
                            style: {
                                fontSize: '16px'
                            }
                        }
                    },
                    tooltip: {
                        formatter: function() {
                            return `<b>${data[this.point.index].school}</b><br>
                                Onboard Crew: ${data[this.point.index].onboard_crew}`;
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '16px',
                                    color: 'black',
                                }
                            },
                            groupPadding: 0.1,
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function() {
                                        const schoolData = data[this.index];
                                        const crewDetails = schoolData.crew_details ?
                                            schoolData.crew_details.split(', ') : [];

                                        $('#modalTitle').text(
                                            `Detail Crew for ${schoolData.school}`);
                                        $('.modal-header').css('background-color',
                                            '#078415');

                                        const tbody = $(
                                            '#idBodyModalCrewDetailByInstitution');
                                        tbody.empty();

                                        if (crewDetails.length > 0) {
                                            crewDetails.forEach((crew, i) => {
                                                tbody.append(`
                                                <tr>
                                                    <td style="text-align: center;">${i + 1}</td>
                                                    <td>${crew}</td>
                                                </tr>
                                            `);
                                            });
                                        } else {
                                            tbody.append(`
                                            <tr>
                                                <td colspan="2" style="text-align: center;">No crew data available.</td>
                                            </tr>
                                        `);
                                        }

                                        $('#detailModal').modal('show');
                                    }
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Onboard Crew',
                        data: seriesDataOnboard,
                        color: '#5DADE2'
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', xhr.responseText || error);
                alert(`Failed to load data: ${xhr.status} - ${xhr.statusText}`);
            }
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/getCadangan'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const heatmapData = data.map((item, index) => {
                    var color;
                    if (item.total_onleave > 15) {
                        color = 'rgba(0, 255, 85, 0.8)';
                    } else if (item.total_onleave >= 11) {
                        color = 'rgb(251, 255, 10)';
                    } else {
                        color = 'rgba(255, 0, 0, 0.8)';
                    }
                    return {
                        x: index % 5,
                        y: Math.floor(index / 5),
                        value: item.total_onleave,
                        onboard: item.total_onboard,
                        rank: item.rank,
                        color: color || item.color,
                    };
                });

                Highcharts.chart('idDivHeatMap', {
                    chart: {
                        type: 'heatmap',
                        plotBorderWidth: 1,
                        height: 500,

                    },
                    title: {
                        text: 'Ship Reserves per Rank (Heatmap)',
                        align: 'center',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        labels: {
                            enabled: false,
                        },
                        title: null,
                    },
                    yAxis: {
                        labels: {
                            enabled: false,
                        },
                        title: null,
                    },
                    colorAxis: {
                        stops: [
                            [0, 'rgba(255, 107, 107, 0.8)'],
                            [0.5, 'rgba(255, 223, 107, 0.8)'],
                            [1, 'rgba(72, 239, 128, 0.8)'],
                        ],
                        min: 0,
                        max: 20,
                    },
                    tooltip: {
                        formatter: function() {
                            var categoryLabel = '';
                            if (this.point.value > 15) {
                                categoryLabel = 'Strong';
                            } else if (this.point.value >= 11) {
                                categoryLabel = 'Medium';
                            } else {
                                categoryLabel = 'Low';
                            }

                            return `
                            Crew Off-Duty: ${this.point.value}<br>
                            Crew On-Dutty: ${this.point.onboard}<br>
                            Category: <b>${categoryLabel}</b>`;
                        },
                    },
                    series: [{
                        name: 'Cadangan Kapal',
                        borderWidth: 1,
                        data: heatmapData.map(item => ({
                            x: item.x,
                            y: item.y,
                            value: item.value,
                            onboard: item
                                .onboard,
                            rank: item.rank,
                            color: item.color,
                        })),
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.point.rank;
                            },
                            color: '#fff',
                            style: {
                                fontSize: '12px',
                            },
                        },
                    }],
                    credits: {
                        enabled: false,
                    },
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', status, error);
            },
        });
    });
    </script>
</head>

<body>
    <div class="container" style="background-color:;">
        <div class="form-panel" style="margin-top:5px;padding-bottom:15px;">
            <legend style="text-align:right;color:#067780;">
                <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                    style="margin-right:10px;display:none;">
                <b><i>:: DASHBOARD ::</i></b>
            </legend>
            <div class="row">
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-6">
                    <label style="font-size:18px;font-weight:bold;color:#067780;">Total : <?php echo $totalCrew; ?>
                        Person (On Board & On Leave)</label>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#16839B;color:#FFFFFF;border:2px solid #000000;cursor:pointer;border-radius:30px;"
                        onclick="displayOnBoard();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-anchor fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $onBoard; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">On Board</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#078415;color:#FFFFFF;border:2px solid #000000;cursor: pointer;border-radius:30px;"
                        onclick="displayOnLeave();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-circle fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $onLeave; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">On Leave</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#E47100;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-circle-o fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $nonAktif; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">Non Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#C80000;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-secret fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:32px;text-align:center;"><?php echo $notForEmp; ?></p>
                                <p style="font-size:10px;text-align:center;font-weight:bold;">Not for Employeed</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#66007A;color:#FFFFFF;border:2px solid #000000;border-radius:30px;cursor:pointer;"
                        onclick="displayNewApplicent();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa fa-user-plus fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $newApplicent; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">New Applicent</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#7A1A00;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa fa-child fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $cadetOnBoard; ?></p>
                                <p style="font-size:11px;text-align:center;font-weight:bold;">Cadet On Board</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="margin-top: 12px;">
                    <div id="idDivOverall">
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 12px;">
                    <div id="idTotalCrewByKapal">

                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div id="idDivHeatMap"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div id="idDivtotalSchool"></div>
                </div>
            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div>
                        <div id="idDivBarChartRank">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<div class="modal fade" id="idModalCrewByOwnShip" tabindex="-1" role="dialog"
    aria-labelledby="idModalCrewByOwnShipLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#078415;">
                <h5 class=" modal-title" id="idModalCrewByOwnShipLabel" style="color: white;">Crew Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <legend style="text-align: left; margin-bottom: 10px;">
                    <label>Vessel: <span id="idLblModalVesselDetailByOwnShip"></span></label>
                    <br>
                    <span id="idLblVesselStatus" style="font-weight: bold;"></span>
                    <br>
                    <span id="idLblProperlyManned" style="font-weight: bold; color: green;"></span>
                </legend>
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 60%;">Crew Name</th>
                                <th style="text-align: center; width: 35%;">Position</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByOwnShip"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Institution</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: left; width: 60%;">Crew Name & Rank / Position</th>

                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByInstitution"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModalClientShip" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Client Ship</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: left; width: 60%;">Crew Name</th>
                                <th style="text-align: left; width: 35%;">Position</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByClientShip"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModalCrewSignoff" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#078415;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Estimated Sign Off
                </h5>
            </div>
            <div class="modal-body">
                <p id="idRankSummary" style="font-weight: bold; font-size: 16px; margin-bottom: 10px;"></p>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background-color: #078415; color: #FFF; height: 45px;">
                                <th style="width: 5%;">No</th>
                                <th style="width: 30%;">Crew Name</th>
                                <th style="width: 25%;">Rank Name</th>
                                <th style="width: 20%;">Sign On Date</th>
                                <th style="width: 20%;">Estimated Signoff Date</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByEstimatedSignOff"></tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReqDetail" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <button type="button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew On Board ::</i></h4>
            </div>
            <div class="modal-body" id="idModalDetail">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <legend style="text-align: left;margin-bottom:0px;">
                            <label id="lblModal">Total : <span id="idLblModal"></span></label>
                            <img id="idLoadingModal" style="display:none;"
                                src="<?php echo base_url('assets/img/loading.gif'); ?>">
                        </legend>
                        <div class="table-responsive">
                            <table
                                class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                <thead>
                                    <tr style="background-color: #16839B;color: #FFF;height:40px;">
                                        <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                        <th style="vertical-align: middle; width:25%;text-align:center;">Vessel Name
                                        </th>
                                        <th style="vertical-align: middle; width:10%;text-align:center;">Total Crew</th>
                                    </tr>
                                </thead>
                                <tbody id="idBodyModal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div style="border:1px solid black;padding:10px;">
                            <legend style="text-align: left;margin-bottom:0px;">
                                <label id="lblModal">Vessel : <span id="idLblModalVesselDetail"></span></label>
                            </legend>
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #16839B;color: #FFF;height:40px;">
                                            <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                            <th style="vertical-align: middle; width:60%;text-align:center;">Crew Name
                                            </th>
                                            <th style="vertical-align: middle; width:37%;text-align:center;">Posisi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyModalCrewDetail">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTotalCrewOnLeave" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px; background-color:#078415">
                <button type=" button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew On Leave ::</i></h4>
            </div>
            <div class="modal-body" id="idModalDetail">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <legend style="text-align: left;margin-bottom:0px;">
                            <label id="lblTotalCrewOnLeave">Total : <span id="idLblModalTotalCrew"></span></label>
                            <img id="idLoadingModal" style="display:none;"
                                src="<?php echo base_url('assets/img/loading.gif'); ?>">
                        </legend>
                        <div class="table-responsive">
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd;">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color:#078415; color: #FFF; height:40px;">
                                            <th style="vertical-align: middle; width:3%; text-align:center;">No</th>
                                            <th style="vertical-align: middle; width:25%; text-align:center;">Rank Name
                                            </th>
                                            <th style="vertical-align: middle; width:10%; text-align:center;">Crew Name
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyTotalCrewOnLeave">
                                    </tbody>
                                </table>
                            </div>
                            <div id="dataSummary"
                                style="margin-top: 10px; font-size: 14px; font-weight: bold; color: #16839B;"></div>
                        </div>

                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div style="border:1px solid black;padding:10px;">
                            <label id="lblModalCrewNameOnLeave">Crew Name & Rank: <span
                                    id="idLblModalCrewNameOnLeave"></span></label>
                            <div class="table-responsive" style="font-size: 16px;">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color:#078415; color: #FFF; height: 50%;">
                                            <th
                                                style="vertical-align: middle; width: 5%; text-align: center; padding: 10px;">
                                                No</th>
                                            <th
                                                style="vertical-align: middle; width: 60%; text-align: center; padding: 10px;">
                                                Sign off date</th>
                                            <th
                                                style="vertical-align: middle; width: 35%; text-align: center; padding: 10px;">
                                                Last Vessel</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyModalCrewDetailOnLeave">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShowCrewing" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#66007A;">
                <button type="button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew New Applicent ::</i></h4>
            </div>
            <div class="modal-body" id="idDivModalCrewDetail">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <table
                                class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                <thead>
                                    <tr style="background-color: #66007A;color: #FFF;height:40px;">
                                        <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                        <th style="vertical-align: middle; width:50%;text-align:center;">Crew Name</th>
                                        <th style="vertical-align: middle; width:45%;text-align:center;">Apply For</th>
                                    </tr>
                                </thead>
                                <tbody id="idBodyModalCrew">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>