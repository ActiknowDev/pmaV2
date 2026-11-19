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
// $(document).ready(function() {
//     var table = $('#datatable').DataTable({
//         lengthMenu: [[100, "All", 50, 25], [100, "All", 50, 25]],
//         dom: 'Bfrtip',
//         buttons: [
//             {
//                 extend: 'csv',
//                 exportOptions: {
//                     rows: function (idx, data, node) {
//                         // Check if the row is visible (block display)
//                         return $(node).css('display') !== 'none';
//                     }
//                 }
//             },
//             {
//                 extend: 'excelHtml5',
//                 text: 'Excel',

//                 exportOptions: {
//                     stripHtml: false,

//                     format: {
//                         body: function (data, row, column, node) {

//                             var text = $('<div>').html(data).text().trim();

//                             text = text.replace(/\s+/g, ' ');

//                             var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);
//                             var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2}:[0-9]{2})/i);

//                             if (inMatch && outMatch) {
//                                 return 'In : ' + inMatch[1] + '\n' +
//                                     'Out : ' + outMatch[1];
//                             }

//                             return text;
//                         }
//                     }
//                 },

//                 customize: function (xlsx) {

//                     var sheet = xlsx.xl.worksheets['sheet1.xml'];
//                     var styles = xlsx.xl['styles.xml'];

//                     // -------------------------
//                     // Create Wrap Text Style
//                     // -------------------------
//                     var cellXfs = $('cellXfs', styles);

//                     cellXfs.append(
//                         '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyAlignment="1">' +
//                             '<alignment wrapText="1" vertical="center"/>' +
//                         '</xf>'
//                     );

//                     var wrapStyleId = parseInt(cellXfs.attr('count'));
//                     cellXfs.attr('count', wrapStyleId + 1);

//                     // -------------------------
//                     // Set row height
//                     // -------------------------
//                     $('row', sheet).each(function () {
//                         $(this).attr('ht', '35');
//                         $(this).attr('customHeight', '1');
//                     });

//                     // -------------------------
//                     // Apply rich text formatting
//                     // -------------------------
//                     $('row c', sheet).each(function () {

//                         var cell = $(this);

//                         var value = $('is t', cell).text();

//                         if (!value || value.indexOf('In :') === -1) {
//                             return;
//                         }

//                         var lines = value.split('\n');

//                         if (lines.length !== 2) {
//                             return;
//                         }

//                         var inTime = lines[0].replace('In :', '').trim();
//                         var outTime = lines[1].replace('Out :', '').trim();

//                         var lateIn = inTime > '10:00:00';
//                         var earlyOut = outTime < '18:45:00';

//                         var richText = '<is>';

//                         // In Label
//                         richText +=
//                             '<r>' +
//                                 '<t xml:space="preserve">In : </t>' +
//                             '</r>';

//                         // In Time
//                         if (lateIn) {
//                             richText +=
//                                 '<r>' +
//                                     '<rPr>' +
//                                         '<color rgb="FFFF0000"/>' +
//                                     '</rPr>' +
//                                     '<t>' + inTime + '</t>' +
//                                 '</r>';
//                         } else {
//                             richText +=
//                                 '<r>' +
//                                     '<t>' + inTime + '</t>' +
//                                 '</r>';
//                         }

//                         // New Line
//                         richText +=
//                             '<r>' +
//                                 '<t xml:space="preserve">&#10;</t>' +
//                             '</r>';

//                         // Out Label
//                         richText +=
//                             '<r>' +
//                                 '<t xml:space="preserve">Out : </t>' +
//                             '</r>';

//                         // Out Time
//                         if (earlyOut) {
//                             richText +=
//                                 '<r>' +
//                                     '<rPr>' +
//                                         '<color rgb="FFFF0000"/>' +
//                                     '</rPr>' +
//                                     '<t>' + outTime + '</t>' +
//                                 '</r>';
//                         } else {
//                             richText +=
//                                 '<r>' +
//                                     '<t>' + outTime + '</t>' +
//                                 '</r>';
//                         }

//                         richText += '</is>';

//                         cell.attr('t', 'inlineStr');
//                         cell.attr('s', wrapStyleId);

//                         cell.children().remove();
//                         cell.append(richText);
//                     });
//                 }
//             },
//             {
//                 extend: 'pdfHtml5',
//                 text: 'PDF',
//                 orientation: 'landscape',
//                 pageSize: 'A3',
//             exportOptions: {
//                     stripHtml: false,
//             rows: function (idx, data, node) {
//                         return $(node).css('display') !== 'none';
//                     },
//             format: {
//                         body: function (data, row, column, node) {
//             var text = $('<div>').html(data).text().trim();
//                             text = text.replace(/\s+/g, ' ');
//             var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2})/i);
//                             var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2})/i);
//             if (text.includes('Weekend'))
//                                 return 'W';
//             if (text.includes('Absent'))
//                                 return 'A';
//             if (text.includes('Forgot Card'))
//                                 return 'FC';
//             if (text.includes('Holiday'))
//                                 return 'H';
//             if (text.includes('comp_off'))
//                                 return 'CO';
//             if (text.includes('Paid Leave'))
//                                 return 'PL';
//             if (text.includes('Casual Leave'))
//                                 return 'CL';
//             if (text.includes('Sick Leave'))
//                                 return 'SL';
//             if (text.includes('WFH'))
//                                 return 'WFH';
//             if (text.includes('LWP'))
//                                 return 'LWP';
//                             if (inMatch && outMatch) {
//                 var inTime = inMatch[1];
//                                 var outTime = outMatch[1];
//                 return JSON.stringify({
//                                     inTime: inTime,
//                                     outTime: outTime,
//                                     lateIn: inTime > '10:00',
//                                     earlyOut: outTime < '18:45',
//                                     shortLeave: text.includes('Short Leave')
//                                 });
//                             }
//                             if (text.includes('Short Leave'))
//                                 return 'SH-L';
//                             return text;
//                         }
//                     }
//                 },
//                 customize: function (doc) {
//             // Report title
//                     doc.content.unshift({
//                         text: 'Attendance Report - <?= date("F", strtotime($year . "-" . sprintf("%02d",$month) . "-01")) ?> <?= $year ?>',
//                         alignment: 'center',
//                         fontSize: 19,
//                         bold: true,
//                         margin: [0, 0, 0, 10]
//                     });
//             // Page settings
//                     doc.pageMargins = [5, 5, 5, 5];
//             doc.defaultStyle = {
//                         fontSize: 10,
//                         color: '#000000'
//                     };
//             // Header styling
//                     doc.styles.tableHeader = {
//                         fontSize: 12,
//                         bold: true,
//                         alignment: 'center',
//                         fillColor: '#D9D9D9',
//                         color: '#000000'
//                     };
//             // Find attendance table
//                     var tableNode = null;
//             for (var i = 0; i < doc.content.length; i++) {
//                         if (doc.content[i].table) {
//                             tableNode = doc.content[i];
//                             break;
//                         }
//                     }
//                     if (!tableNode) return;
//                     var table = tableNode.table;
//             table.headerRows = 1;
//                     table.keepWithHeaderRows = 1;
//                     table.dontBreakRows = true;
//             // Convert date headers like 01-06-2025 to 1
//                     var headers = table.body[0];
//                     for (var i = 1; i < headers.length; i++) {
//                         var headerCell = headers[i];
//             var headerText = typeof headerCell === 'object'
//                             ? headerCell.text
//                             : headerCell;
//                         if (typeof headerText === 'string') {
//                             var match = headerText.match(/^(\d{2})-/);
//                             if (match) {
//                                 var day = parseInt(match[1], 10).toString();
//             if (typeof headerCell === 'object') {
//                                     headerCell.text = day;
//                                 } else {
//                                     headers[i] = day;
//                                 }
//                             }
//                         }
//                     }
//             // Style attendance cells
//                     for (var r = 0; r < table.body.length; r++) {
//                         for (var c = 0; c < table.body[r].length; c++) {
//             if (typeof table.body[r][c] !== 'object' || table.body[r][c] === null) {
//                                 table.body[r][c] = {
//                                     text: table.body[r][c]
//                                 };
//                             }
//                             var cell = table.body[r][c];
//             var cellText = typeof cell.text === 'string'
//                                 ? cell.text
//                                 : '';
//             // Header row
//                             if (r === 0) {
//             cell.bold = true;
//                                 cell.fillColor = '#D9D9D9';
//                                 cell.color = '#000000';
//                                 cell.alignment = 'center';
//             continue;
//                             }
//             // Default body styling
//                             cell.fillColor = '#FFFFFF';
//                             cell.color = '#000000';
//                             cell.alignment = 'center';
//             // Employee name column
//                             if (c === 0) {
//                                 cell.bold = true;
//                                 cell.fontSize = 11;
//                             }
//             // Parse attendance time information
//                             var cellData = null;
//             try {
//                                 if (cellText) {
//                                     cellData = JSON.parse(cellText);
//                                 }
//                             } catch (e) {
//                                 cellData = null;
//                             }
//             // Entry and exit time formatting
//                             if (
//                                 cellData &&
//                                 cellData.inTime &&
//                                 cellData.outTime
//                             ) {
//             cell.text = [
//                                     {
//                                         text: cellData.inTime + '\n',
//                                         color: cellData.lateIn ? '#FF0000' : '#000000',
//                                         bold: cellData.lateIn,
//                                         fontSize: cellData.lateIn ? 12 : 10
//                                     },
//                                     {
//                                         text: cellData.outTime,
//                                         color: cellData.earlyOut ? '#FF0000' : '#000000',
//                                         bold: cellData.earlyOut,
//                                         fontSize: cellData.earlyOut ? 12 : 10
//                                     },
//                                     {
//                                         text: cellData.shortLeave ? '\nSH-L' : '',
//                                         color: '#0070C0',
//                                         bold: cellData.shortLeave,
//                                         fontSize: 10
//                                     }
//                                 ];
//             continue;
//                             }
//             // Weekend: light blue background
//                             if (cellText === 'W') {
//             cell.fillColor = '#DDEBF7';
//                                 cell.color = '#000000';
//                                 cell.bold = true;
//                             }
//             // Forgot Card: blue text
//                             else if (cellText === 'FC') {
//             cell.color = '#0070C0';
//                                 cell.bold = true;
//                             }
//             // Leave and related attendance codes: blue text
//                             else if (
//                                 ['CL', 'SL', 'PL', 'LWP', 'CO', 'SH-L', 'WFH'].includes(cellText)
//                             ) {
//             cell.color = '#0070C0';
//                                 cell.bold = true;
//                             }
//                         }
//                     }
//             // Stretch table across the page
//                     table.widths = [];
//                     table.widths.push(60);
//             for (var i = 1; i < table.body[0].length; i++) {
//                         table.widths.push('*');
//                     }
//             // Table borders and spacing
//                     tableNode.layout = {
//                         hLineWidth: function () {
//                             return 1;
//                         },
//                         vLineWidth: function () {
//                             return 1;
//                         },
//                         hLineColor: function () {
//                             return '#000000';
//                         },
//                         vLineColor: function () {
//                             return '#000000';
//                         },
//                         paddingLeft: function () {
//                             return 2;
//                         },
//                         paddingRight: function () {
//                             return 2;
//                         },
//                         paddingTop: function () {
//                             return 2;
//                         },
//                         paddingBottom: function () {
//                             return 2;
//                         }
//                     };
//                 }
//             },
//             {
//                 extend: 'print',
//                 exportOptions: {
//                     rows: function (idx, data, node) {
//                         // Check if the row is visible (block display)
//                         return $(node).css('display') !== 'none';
//                     }
//                 }
//             }
//         ]
//     });
// });

$(document).ready(function () {

    var table = $('#datatable').DataTable({

        lengthMenu: [[100, "All", 50, 25], [100, "All", 50, 25]],
        dom: 'Bfrtip',

        buttons: [

            // CSV EXPORT
            {
                extend: 'csv',
                text: 'CSV',
                exportOptions: {
                    columns: ':not(:first-child)',

                    rows: function (idx, data, node) {
                        return exportSelectedRows(idx, data, node);
                    }
                }
            },

            // EXCEL EXPORT
            {
                extend: 'excelHtml5',
                text: 'Excel',

                exportOptions: {
                    columns: ':not(:first-child)',

                    rows: function (idx, data, node) {
                        return exportSelectedRows(idx, data, node);
                    },

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

                // KEEP YOUR EXISTING EXCEL CUSTOMIZE FUNCTION HERE
                customize: function (xlsx) {

                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var styles = xlsx.xl['styles.xml'];

                    var cellXfs = $('cellXfs', styles);

                    cellXfs.append(
                        '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyAlignment="1">' +
                        '<alignment wrapText="1" vertical="center"/>' +
                        '</xf>'
                    );

                    var wrapStyleId = parseInt(cellXfs.attr('count'));
                    cellXfs.attr('count', wrapStyleId + 1);

                    $('row', sheet).each(function () {
                        $(this).attr('ht', '35');
                        $(this).attr('customHeight', '1');
                    });

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

                        richText += '<r><t xml:space="preserve">In : </t></r>';

                        if (lateIn) {
                            richText += '<r><rPr><color rgb="FFFF0000"/></rPr><t>' + inTime + '</t></r>';
                        } else {
                            richText += '<r><t>' + inTime + '</t></r>';
                        }

                        richText += '<r><t xml:space="preserve">&#10;</t></r>';
                        richText += '<r><t xml:space="preserve">Out : </t></r>';

                        if (earlyOut) {
                            richText += '<r><rPr><color rgb="FFFF0000"/></rPr><t>' + outTime + '</t></r>';
                        } else {
                            richText += '<r><t>' + outTime + '</t></r>';
                        }

                        richText += '</is>';

                        cell.attr('t', 'inlineStr');
                        cell.attr('s', wrapStyleId);

                        cell.children().remove();
                        cell.append(richText);
                    });
                }
            },

            // PDF EXPORT
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A3',

                exportOptions: {
                    columns: function (column, data, node) {
                        return column !== 0;
                    },

                    rows: function (idx, data, node) {
                        return exportSelectedRows(idx, data, node);
                    },

                    stripHtml: false,

                    format: {
                        body: function (data, row, column, node) {

                            var text = $('<div>').html(data).text().trim();
                            text = text.replace(/\s+/g, ' ');

                            var inMatch = text.match(/In:\s*([0-9]{2}:[0-9]{2})/i);
                            var outMatch = text.match(/Out:\s*([0-9]{2}:[0-9]{2})/i);

                            if (text.includes('Weekend')) return 'W';
                            if (text.includes('Absent')) return 'A';
                            if (text.includes('Forgot Card')) return 'FC';
                            if (text.includes('Holiday')) return 'H';
                            if (text.includes('comp_off')) return 'CO';
                            if (text.includes('Paid Leave')) return 'PL';
                            if (text.includes('Casual Leave')) return 'CL';
                            if (text.includes('Sick Leave')) return 'SL';
                            if (text.includes('WFH')) return 'WFH';
                            if (text.includes('LWP')) return 'LWP';

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

                            if (text.includes('Short Leave')) return 'SH-L';

                            return text;
                        }
                    }
                },

                // KEEP YOUR EXISTING PDF CUSTOMIZE FUNCTION HERE
                // Do not change its formatting logic.
               customize: function (doc) {
                    // Report title
                    doc.content.unshift({
                        text: 'Attendance Report - <?= date("F", strtotime($year . "-" . sprintf("%02d",$month) . "-01")) ?> <?= $year ?>',
                        alignment: 'center',
                        fontSize: 19,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    });
                    // Page settings
                    doc.pageMargins = [5, 5, 5, 5];
                    doc.defaultStyle = {
                        fontSize: 8,
                        color: '#000000'
                    };
                    // Header styling
                    doc.styles.tableHeader = {
                        fontSize: 10,
                        bold: true,
                        alignment: 'center',
                        fillColor: '#D9D9D9',
                        color: '#000000'
                    };
                    // Find attendance table
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
                    // Convert date headers like 01-06-2025 to 1
                    var headers = table.body[0];
                    for (var i = 1; i < headers.length; i++) {
                        var headerCell = headers[i];
                        var headerText = typeof headerCell === 'object'
                            ? headerCell.text
                            : headerCell;
                        if (typeof headerText === 'string') {
                            var match = headerText.match(/^(\d{2})-/);
                            if (match) {
                                var day = parseInt(match[1], 10).toString();
                        if (typeof headerCell === 'object') {
                                    headerCell.text = day;
                                } else {
                                    headers[i] = day;
                                }
                            }
                        }
                    }
                    // Style attendance cells
                    for (var r = 0; r < table.body.length; r++) {
                        for (var c = 0; c < table.body[r].length; c++) {
                            if (typeof table.body[r][c] !== 'object' || table.body[r][c] === null) {
                                table.body[r][c] = {
                                    text: table.body[r][c]
                                };
                            }
                            var cell = table.body[r][c];
                            var cellText = typeof cell.text === 'string'
                                ? cell.text
                                : '';
                            // Header row
                            if (r === 0) {
                                cell.bold = true;
                                cell.fillColor = '#D9D9D9';
                                cell.color = '#000000';
                                cell.alignment = 'center';
                                continue;
                            }
                            // Default body styling
                            cell.fillColor = '#FFFFFF';
                            cell.color = '#000000';
                            cell.alignment = 'center';
                            // Employee name column
                            if (c === 0) {
                                cell.bold = true;
                                cell.fontSize = 11;
                            }
                            // Parse attendance time information
                            var cellData = null;
                            try {
                                if (cellText) {
                                    cellData = JSON.parse(cellText);
                                }
                            } catch (e) {
                                cellData = null;
                            }
                            // Entry and exit time formatting
                            if (
                                cellData &&
                                cellData.inTime &&
                                cellData.outTime
                            ) {
                                cell.text = [
                                    {
                                        text: cellData.inTime + '\n',
                                        color: cellData.lateIn ? '#FF0000' : '#000000',
                                        bold: cellData.lateIn,
                                        fontSize: cellData.lateIn ? 12 : 10
                                    },
                                    {
                                        text: cellData.outTime,
                                        color: cellData.earlyOut ? '#FF0000' : '#000000',
                                        bold: cellData.earlyOut,
                                        fontSize: cellData.earlyOut ? 12 : 10
                                    },
                                    {
                                        text: cellData.shortLeave ? '\nSH-L' : '',
                                        color: '#0070C0',
                                        bold: cellData.shortLeave,
                                        fontSize: 10
                                    }
                                ];
                                continue;
                            }
                            // Weekend: light blue background
                            if (cellText === 'W') {
                            cell.fillColor = '#DDEBF7';
                                cell.color = '#000000';
                                cell.bold = true;
                            }
                            // Forgot Card: blue text
                            else if (cellText === 'FC') {
                            cell.color = '#0070C0';
                                cell.bold = true;
                            }
                            // Leave and related attendance codes: blue text
                            else if (
                                ['CL', 'SL', 'PL', 'LWP', 'CO', 'SH-L', 'WFH'].includes(cellText)
                            ) {
                            cell.color = '#0070C0';
                                cell.bold = true;
                            }
                        }
                    }
                        // Stretch table across the page
                    table.widths = [];
                    table.widths.push(90);
                    for (var i = 1; i < table.body[0].length; i++) {
                        table.widths.push('*');
                    }
                    // Table borders and spacing
                    tableNode.layout = {
                        hLineWidth: function () {
                            return 1;
                        },
                        vLineWidth: function () {
                            return 1;
                        },
                        hLineColor: function () {
                            return '#000000';
                        },
                        vLineColor: function () {
                            return '#000000';
                        },
                        paddingLeft: function () {
                            return 2;
                        },
                        paddingRight: function () {
                            return 2;
                        },
                        paddingTop: function () {
                            return 2;
                        },
                        paddingBottom: function () {
                            return 2;
                        }
                    };
                }
            },

            // PRINT
            {
                extend: 'print',
                text: 'Print',

                exportOptions: {
                    columns: ':not(:first-child)',

                    rows: function (idx, data, node) {
                        return exportSelectedRows(idx, data, node);
                    }
                }
            }
        ]
    });

    // Check whether an employee is selected for export
    function exportSelectedRows(idx, data, node) {

        var anySelected = $('#datatable .employee-export-checkbox:checked').length > 0;

        var $row = $(node);
        var isVisible = $row.css('display') !== 'none';

        if (anySelected) {
            var isChecked = $row.find('.employee-export-checkbox').prop('checked');
            return isChecked && isVisible;
        }

        return isVisible;
    }


    // Select / Deselect All Employees
    $('#selectAllEmployees').on('change', function () {

        var isChecked = $(this).is(':checked');

        table.rows().nodes().to$()
            .find('.employee-export-checkbox')
            .prop('checked', isChecked);

        $(this).prop('indeterminate', false);
    });


    // Individual Employee Checkbox Selection
    $(document).on('change', '.employee-export-checkbox', function () {

        var allCheckboxes = table.rows().nodes().to$()
            .find('.employee-export-checkbox');

        var checkedCheckboxes = allCheckboxes.filter(':checked');

        $('#selectAllEmployees').prop(
            'checked',
            allCheckboxes.length > 0 &&
            checkedCheckboxes.length === allCheckboxes.length
        );

        $('#selectAllEmployees').prop(
            'indeterminate',
            checkedCheckboxes.length > 0 &&
            checkedCheckboxes.length < allCheckboxes.length
        );
    });
    });
</script>

</body>

</html>