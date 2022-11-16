import * as Lib from './lib.js';

// global error state
let globalErrorState = true;
let globalDataStdClass;
let globalApkDataFiles = [];
let globalFmaDataFile = '';

// bind onchange events of file upload inputs
$('#file-apk-dmu').change(function(e)
{
    let file = e.target.files[0];
    const filepath = $(this).val();
    const filename = filepath.split('\\').pop();

    // check if the file is an excel workbench
    if (file.name.split('.').pop() !== 'xlsx')
    {
        alert ('Geselecteerde file is geen Excel (.xlsx) bestand!');
        return ;
    }

    let formData = new FormData();
    formData.append('filename', filename);
     
    formData.append("sheet", file);
    $.ajax({ url: 'upload.php', method: 'post', data: formData, processData: false, contentType: false }).then(function(e) 
    { 
        const response = JSON.parse(e);
        $('#file-name-apk').html(response.message); 

        globalErrorState = response.errorcode;
        if (!globalErrorState)
        {
            $('#apk-dmu-tag').css('display', 'none');
            $('#apk-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin:0 10px 0 0;">${ filepath }</span>`);
            globalApkDataFiles.push(filename);
        }
        else
        {
            $('#apk-dmu-tag').css('display', 'inline');
        }
    });
});

// bind onchange events of file upload inputs
$('#file-fma-dmu').change(function(e)
{
    let file = e.target.files[0];
    const filepath = $(this).val();
    const filename = filepath.split('\\').pop();

    // check if the file is an excel workbench
    if (file.name.split('.').pop() !== 'xlsx')
    {
        alert ('Geselecteerde file is geen Excel (.xlsx) bestand!');
        return ;
    }

    let formData = new FormData();
    formData.append('filename', filename);
     
    formData.append("sheet", file);
    $.ajax({ url: 'upload.php', method: 'post', data: formData, processData: false, contentType: false }).then(function(e) 
    { 
        const response = JSON.parse(e);
        $('#file-name-fma').html(response.message);      

        globalErrorState = response.errorcode;
        if (!globalErrorState)
        {
            $('#fma-dmu-tag').css('display', 'none');
            $('#fma-dmu-tag-file').css('display', 'inline');
            $('#fma-dmu-tag-file').html(`${ filepath }`);
            globalFmaDataFile = filename;
        }
        else
        {
            $('#fma-dmu-tag').css('display', 'inline');
        }
    });
});

// bind click event of report button to execute process script
$('#button-generate-report').click(function(e)
{
    if (!globalErrorState)
    {
        $.ajax({ url: 'process.php', method: 'post', data: { apkData: globalApkDataFiles, fmaData: globalFmaDataFile } }).then(function(response) 
        { 
            globalDataStdClass = response;
            const data = JSON.parse(response);
            data.forEach(function(element)
            {
                $('#table-report-body').append(`
                    <tr>
                        <td>${ element.keyApk }</td>
                        <td>${ element.nameApk }</td>
                        <td>${ element.keyFma }</td>
                        <td>${ element.date }</td>
                        <td>${ element.value }</td>
                        <td style="color:${ element.status[0] === 'C' ? "#008000" : "#ff0000" };font-weight:bold;">${ element.status }</td>
                    </tr>
                `);
            });
        });
    }
    else
    {
        alert ('Error: zijn alle files geuploaded?');
    }
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