import * as Lib from './lib.js';

// global error state
let globalDataStdClass;
let globalFmaFiles;
let globalApkFiles;

// load excel files and enable reporting in front end
$.ajax({ url: 'loadfiles.php', method: 'get' }).then(function(response)
{
    const files = JSON.parse(response);
    if (files.fma.length > 0)
        $('#fma-dmu-tag').remove();

    globalFmaFiles = files.fma;
    files.fma.forEach(function(file) { $('#fma-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin: 0 10px 0 0;">${ file }</span>`); });

    if (files.apk.length > 0)
        $('#apk-dmu-tag').remove();

    globalApkFiles = files.apk;
    files.apk.forEach(function(file) { $('#apk-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin: 0 10px 0 0;">${ file }</span>`); });

    if (globalFmaFiles.length > 0 && globalApkFiles.length > 0)
    {
        $('#excel-loader').css('display', 'none');
        $('#report-section').css('visibility', 'visible');
    }
});

// bind click event of report button to execute process script
$('#button-generate-report').click(function(e)
{
    $('#report-loader').css('visibility', 'visible');
    $('#report-status').css('visibility', 'visible');
    $('#report-status').html('Fma excel wordt opgeladen en geanalyseerd');
    $.ajax({ url: 'fma_process.php', method: 'post', data: { fmaData: globalFmaFiles } }).then(function(response) 
    { 
        globalDataStdClass = response;
        
        // load fma onto table
        const data = JSON.parse(response);
        for (let index = 0; index < data.fk_fma.length; index++)
        {
            $('#table-report-body').append(`
                <tr>
                    <td>${ data.fk_fma[index] }</td>
                    <td>${ data.date[index] }</td>
                    <td>${ data.ordernr[index] }</td>
                    <td fk-orderstatus="${ data.ordernr[index] }" style="color:red;">ONTBREEKT</td>
                    <td fk-actionstatus="${ data.ordernr[index] }">
                        <div class="select">
                            <select>
                                <option value="1" style="color:red;">NAZIEN</option>
                                <option value="2" style="color:orange;">VERSTUURD</option>
                                <option value="3" style="color:green;">OK</option>
                            </select>
                        </div>
                    </td>
                </tr>
            `);
        }

        $('#report-loader').css('visibility', 'hidden');
        $('#report-status').html('');
        $('#report-status').css('visibility', 'hidden');

        // continue analysis

    });

    // <td style="color:${ element.status[0] === 'C' ? "#008000" : "#ff0000" };font-weight:bold;">${ element.status }</td>
});

// bind click event of download button to execute report to sheet and download scripts
$('#button-download-report').click(function()
{
    if (!globalErrorState)
    {
        $.ajax({ url: 'reportsheet.php', method: 'post', data: { 'data': globalDataStdClass } }).then(function(response) 
        { 
            // file is ready for download
            Lib.download('assets/reports/raport_apk_dmu.xlsx', 'raport_apk_dmu.xlsx');
        });
    }
    else
    {
        alert ('Error: zijn alle files geuploaded?');
    }
});