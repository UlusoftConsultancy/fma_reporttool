import * as Lib from './lib.js';

// global error state
let globalDataStdClass;

// load excel files and enable reporting in front end
$.ajax({ url: 'loadfiles.php', method: 'get' }).then(function(response)
{
    $('#file-loader').css('display', 'none');
    $('#report-section').css('visibility', 'visible');

    const files = JSON.parse(response);
    if (files.fma.length > 0)
        $('#fma-dmu-tag').remove();

    files.fma.forEach(function(file) { $('#fma-dmu-tagbox').append(`<span class="tag is-primary is-light">${ file }</span>`); });

    if (files.apk.length > 0)
        $('#apk-dmu-tag').remove();

    files.apk.forEach(function(file) { $('#apk-dmu-tagbox').append(`<span class="tag is-primary is-light">${ file }</span>`); });

});

// bind click event of report button to execute process script
$('#button-generate-report').click(function(e)
{
    $.ajax({ url: 'process.php', method: 'get' }).then(function(response) 
    { 
        console.log(response);
        // globalDataStdClass = response;
        // const data = JSON.parse(response);
        // data.forEach(function(element)
        // {
        //     $('#table-report-body').append(`
        //         <tr>
        //             <td>${ element.keyApk }</td>
        //             <td>${ element.nameApk }</td>
        //             <td>${ element.keyFma }</td>
        //             <td>${ element.date }</td>
        //             <td>${ element.value }</td>
        //             <td style="color:${ element.status[0] === 'C' ? "#008000" : "#ff0000" };font-weight:bold;">${ element.status }</td>
        //         </tr>
        //     `);
        // });
    });
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