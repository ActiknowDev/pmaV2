<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="shortcut icon" type="image/png" href="https://actiknow.com/wp-content/uploads/2018/05/favicon.png" /> -->
   <!-- Favicon Start -->
   <link rel="apple-touch-icon" sizes="180x180" href="<?= WEBURL ?>image/fevicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= WEBURL ?>image/fevicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= WEBURL ?>image/fevicon/favicon-16x16.png">
    <link rel="manifest" href="<?= WEBURL ?>image/fevicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Favicon End -->

    <!-- GOOGLE FONTS -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- FONT AWESOME 5 -->
    <?php echo $this->Html->css(array('all.css', 'bootstrap.min.css', 'dataTables.bootstrap4.min.css', 'buttons.bootstrap4.min.css', 'jquery-ui.css', 'jquery.multiselect.css', 'main.css')); ?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <title>Project Management App</title>
    <?= $this->Html->css('style') ?>
</head>

<body data-theme="">
    <!--top nav bar start-->
    <?php echo $this->element('appHeader'); ?>
    <!--top nav bar end-->
    <!--side nav bar start-->
    <?php echo $this->element('appSidebar'); ?>
    <!--side nav bar end-->
    <!--main start-->
    <div class="app-content">

        <?php echo $this->fetch('content'); ?>
    </div>
    <!--main end-->
    <script>
        var baseUrl = '<?= $this->Url->build('/', ['fullBase' => true]); ?>';
        var TOKEN = '<?= $this->request->getAttribute("csrfToken"); ?>'
    </script>
    <?php echo $this->Html->script(array('jquery-3.4.1.js', 'popper.min.js', 'bootstrap.min.js', 'jquery-ui.js', 'jquery.dataTables.min.js', 'dataTables.bootstrap4.min.js', 'jquery.multiselect.js', 'custom.js')); ?>

    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>


    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->


    <!-- loadingoverlay -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/src/loadingoverlay.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/extras/loadingoverlay_progress/loadingoverlay_progress.min.js">
    </script>

<!-- <script src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        lengthMenu: [[100, "All", 50, 25], [100, "All", 50, 25]],
        dom: 'Bfrtip',
        buttons: [
            // {
            //     extend: 'copy',
            //     exportOptions: {
            //         rows: function (idx, data, node) {
            //             // Check if the row is visible (block display)
            //             return $(node).css('display') !== 'none';
            //         }
            //     }
            // },
            {
                extend: 'csv',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            },
            //{
               // extend: 'excel',
               // exportOptions: {
                   // rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                       // return $(node).css('display') !== 'none';
                   // }
               // }
            //},
            // {
            //     extend: 'excelHtml5',
            //     text: 'Excel',

            //     exportOptions: {
            //         stripHtml: false,

            //         format: {
            //             body: function (data, row, column, node) {

            //                 var text = $('<div>').html(data).text().trim();

            //                 text = text.replace(/\s+/g, ' ');

            //                 var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);
            //                 var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);

            //                 if (inMatch && outMatch) {
            //                      return 'In : ' + inMatch[1] + '\n' +
            //                         'Out : ' + outMatch[1];
            //                 }

            //                 return text;
            //             }
            //         }
            //     },

            //     customize: function (xlsx) {

            //         var sheet = xlsx.xl.worksheets['sheet1.xml'];
            //         var styles = xlsx.xl['styles.xml'];

            //         // Create red font
            //         var fonts = $('fonts', styles);
            //         fonts.attr('count', parseInt(fonts.attr('count')) + 1);

            //         fonts.append(
            //             '<font>' +
            //                 '<sz val="11"/>' +
            //                 '<color rgb="FFFF0000"/>' +
            //                 '<name val="Calibri"/>' +
            //             '</font>'
            //         );

            //         var redFontId = parseInt(fonts.attr('count')) - 1;

            //         // Create style using red font
            //         var cellXfs = $('cellXfs', styles);

            //         cellXfs.append(
            //             '<xf numFmtId="0" fontId="' + redFontId + '" fillId="0" borderId="0" applyFont="1"/>'
            //         );

            //         var redStyleId = parseInt(cellXfs.attr('count'));
            //         cellXfs.attr('count', redStyleId + 1);

            //         $('row c', sheet).each(function () {

            //             var cell = $(this);
            //             var value = $('is t', cell).text();

            //             if (!value || value.indexOf('\n') === -1) {
            //                 return;
            //             }

            //           var lines = value.split('\n');

            //             if (lines.length !== 2) {
            //                 return;
            //             }

            //             var inTime = lines[0].replace('In :', '').trim();
            //             var outTime = lines[1].replace('Out :', '').trim();

            //             var lateIn = inTime > '10:00:00';
            //             var earlyOut = outTime < '18:45:00';

            //            var richText = '<is>';

            //             // IN label (always black)
            //             richText +=
            //                 '<r>' +
            //                     '<t xml:space="preserve">In : </t>' +
            //                 '</r>';

            //             // IN time
            //             if (lateIn) {
            //                 richText +=
            //                     '<r>' +
            //                         '<rPr>' +
            //                             '<color rgb="FFFF0000"/>' +
            //                         '</rPr>' +
            //                         '<t>' + inTime + '</t>' +
            //                     '</r>';
            //             } else {
            //                 richText +=
            //                     '<r>' +
            //                         '<t>' + inTime + '</t>' +
            //                     '</r>';
            //             }

            //             // New line
            //             richText +=
            //                 '<r>' +
            //                     '<t xml:space="preserve">&#10;</t>' +
            //                 '</r>';

            //             // OUT label (always black)
            //             richText +=
            //                 '<r>' +
            //                     '<t xml:space="preserve">Out : </t>' +
            //                 '</r>';

            //             // OUT time
            //             if (earlyOut) {
            //                 richText +=
            //                     '<r>' +
            //                         '<rPr>' +
            //                             '<color rgb="FFFF0000"/>' +
            //                         '</rPr>' +
            //                         '<t>' + outTime + '</t>' +
            //                     '</r>';
            //             } else {
            //                 richText +=
            //                     '<r>' +
            //                         '<t>' + outTime + '</t>' +
            //                     '</r>';
            //             }

            //             richText += '</is>';

            //             cell.attr('t', 'inlineStr');
            //             cell.children().remove();
            //             cell.append(richText);
            //         });
            //     }
            // },
            {
                extend: 'excelHtml5',
                text: 'Excel',

                exportOptions: {
                    stripHtml: false,

                    format: {
                        body: function (data, row, column, node) {

                            var text = $('<div>').html(data).text().trim();

                            text = text.replace(/\s+/g, ' ');

                            var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);
                            var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);

                            if (inMatch && outMatch) {
                                return 'In : ' + inMatch[1] + '\n' +
                                    'Out : ' + outMatch[1];
                            }

                            return text;
                        }
                    }
                },

                customize: function (xlsx) {

                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var styles = xlsx.xl['styles.xml'];

                    // -------------------------
                    // Create Wrap Text Style
                    // -------------------------
                    var cellXfs = $('cellXfs', styles);

                    cellXfs.append(
                        '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyAlignment="1">' +
                            '<alignment wrapText="1" vertical="center"/>' +
                        '</xf>'
                    );

                    var wrapStyleId = parseInt(cellXfs.attr('count'));
                    cellXfs.attr('count', wrapStyleId + 1);

                    // -------------------------
                    // Set row height
                    // -------------------------
                    $('row', sheet).each(function () {
                        $(this).attr('ht', '35');
                        $(this).attr('customHeight', '1');
                    });

                    // -------------------------
                    // Apply rich text formatting
                    // -------------------------
                    $('row c', sheet).each(function () {

                        var cell = $(this);

                        var value = $('is t', cell).text();

                        if (!value || value.indexOf('In :') === -1) {
                            return;
                        }

                        var lines = value.split('\n');

                        if (lines.length !== 2) {
                            return;
                        }

                        var inTime = lines[0].replace('In :', '').trim();
                        var outTime = lines[1].replace('Out :', '').trim();

                        var lateIn = inTime > '10:00:00';
                        var earlyOut = outTime < '18:45:00';

                        var richText = '<is>';

                        // In Label
                        richText +=
                            '<r>' +
                                '<t xml:space="preserve">In : </t>' +
                            '</r>';

                        // In Time
                        if (lateIn) {
                            richText +=
                                '<r>' +
                                    '<rPr>' +
                                        '<color rgb="FFFF0000"/>' +
                                    '</rPr>' +
                                    '<t>' + inTime + '</t>' +
                                '</r>';
                        } else {
                            richText +=
                                '<r>' +
                                    '<t>' + inTime + '</t>' +
                                '</r>';
                        }

                        // New Line
                        richText +=
                            '<r>' +
                                '<t xml:space="preserve">&#10;</t>' +
                            '</r>';

                        // Out Label
                        richText +=
                            '<r>' +
                                '<t xml:space="preserve">Out : </t>' +
                            '</r>';

                        // Out Time
                        if (earlyOut) {
                            richText +=
                                '<r>' +
                                    '<rPr>' +
                                        '<color rgb="FFFF0000"/>' +
                                    '</rPr>' +
                                    '<t>' + outTime + '</t>' +
                                '</r>';
                        } else {
                            richText +=
                                '<r>' +
                                    '<t>' + outTime + '</t>' +
                                '</r>';
                        }

                        richText += '</is>';

                        cell.attr('t', 'inlineStr');
                        cell.attr('s', wrapStyleId);

                        cell.children().remove();
                        cell.append(richText);
                    });
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A3',

                exportOptions: {
                    stripHtml: false,

                    rows: function (idx, data, node) {
                        return $(node).css('display') !== 'none';
                    },

                    format: {
                        body: function (data, row, column, node) {

                            var text = $('<div>').html(data).text().trim();

                            text = text.replace(/\s+/g, ' ');

                            var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2})/i);
                            var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2})/i);

                            if (text.includes('Weekend'))
                                return 'W';

                            if (text.includes('Absent'))
                                return 'A';

                            if (text.includes('Forgot Card'))
                                return 'FC';

                            if (text.includes('Holiday'))
                                return 'H';

                            if (text.includes('comp_off')) {
                                return 'CO';
                            }

                            if (text.includes('Paid Leave'))
                                return 'PL';

                            if (text.includes('Casual Leave'))
                                return 'CL';

                            if (text.includes('Sick Leave'))
                                return 'SL';

                            if (text.includes('WFH'))
                                return 'WFH';

                            if (text.includes('LWP'))
                                return 'LWP';

                            if (inMatch && outMatch) {

                                var inTime = inMatch[1];
                                var outTime = outMatch[1];

                                return JSON.stringify({
                                    inTime: inTime,
                                    outTime: outTime,
                                    lateIn: inTime > '10:00',
                                    earlyOut: outTime < '18:45',
                                    shortLeave: text.includes('Short Leave')
                                });
                            }

                            return text;
                        }
                    }
                },

                customize: function (doc) {

                    doc.content.unshift({
                        text: 'Attendance Report - <?= date("F", strtotime($year . "-" . sprintf("%02d",$month) . "-01")) ?> <?= $year ?>',
                        alignment: 'center',
                        fontSize: 19,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    });

                    doc.pageMargins = [5, 5, 5, 5];

                    doc.defaultStyle = {
                        fontSize: 10,
                        // bold: true,
                        color: '#000000'
                    };


                    doc.styles.tableHeader = {
                        fontSize: 12,
                        bold: true,
                        alignment: 'center',
                        fillColor: '#D9D9D9',
                        color: '#000000'
                    };

                    var tableNode = null;

                    for (var i = 0; i < doc.content.length; i++) {
                        if (doc.content[i].table) {
                            tableNode = doc.content[i];
                            break;
                        }
                    }

                    if (!tableNode) return;

                    var table = tableNode.table;

                    table.headerRows = 1;
                    table.keepWithHeaderRows = 1;
                    table.dontBreakRows = true;

                    // Convert headers like 01-06-2025 => 1
                    var headers = table.body[0];

                    for (var i = 1; i < headers.length; i++) {

                        var txt = headers[i].text || headers[i];

                        var match = txt.match(/^(\d{2})-/);

                        if (match) {
                            headers[i].text = parseInt(match[1], 10).toString();
                        }
                    }

                    for (var r = 0; r < table.body.length; r++) {

                        for (var c = 0; c < table.body[r].length; c++) {

                            if (typeof table.body[r][c] !== 'object') {
                                table.body[r][c] = {
                                    text: table.body[r][c]
                                };
                            }

                            if (r === 0) {

                                table.body[r][c].bold = true;
                                table.body[r][c].fillColor = '#D9D9D9';
                                table.body[r][c].color = '#000000';

                            } else {

                                if (c === 0) {
                                    table.body[r][c].bold = true;
                                    table.body[r][c].fontSize = 11; // or 11 if you want more emphasis
                                }

                                table.body[r][c].fillColor = '#FFFFFF';
                                table.body[r][c].color = '#000000';

                                try {

                                    var cellData = JSON.parse(table.body[r][c].text);

                                    table.body[r][c] = {
                                        alignment: 'center',
                                        fillColor: '#FFFFFF',
                                        color: '#000000',
                                        text: [
                                            {
                                                text: cellData.inTime + '\n',
                                                bold: cellData.lateIn,
                                                fontSize: cellData.lateIn ? 12 : 10
                                            },
                                            {
                                                text: cellData.outTime,
                                                bold: cellData.earlyOut,
                                                fontSize: cellData.earlyOut ? 12 : 10
                                            },
                                            {
                                                text: cellData.shortLeave ? '\nSH-L' : '',
                                                bold: false
                                            }
                                        ]
                                    };

                                } catch (e) {
                                    // Normal values like A, W, PL, CO etc.
                                }
                            }
                        }
                    }

                    // Stretch table
                    table.widths = [];

                    table.widths.push(60);

                    for (var i = 1; i < table.body[0].length; i++) {
                        table.widths.push('*');
                    }

                    tableNode.layout = {
                        hLineWidth: function () { return 1; },
                        vLineWidth: function () { return 1; },
                        hLineColor: function () { return '#000000'; },
                        vLineColor: function () { return '#000000'; },
                        paddingLeft: function () { return 2; },
                        paddingRight: function () { return 2; },
                        paddingTop: function () { return 2; },
                        paddingBottom: function () { return 2; }
                    };
                }
            },
            // {
            //     extend: 'pdf',
            //     exportOptions: {
            //         rows: function (idx, data, node) {
            //             // Check if the row is visible (block display)
            //             return $(node).css('display') !== 'none';
            //         }
            //     }
            // },
            {
                extend: 'print',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            }
        ]
    });
});
</script>

</body>

</html>