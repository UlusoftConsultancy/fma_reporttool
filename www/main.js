import * as Lib from './lib.js';

// global error state
let globalFmaDataStdClass;
let globalFmaFiles;
let globalApkFiles;
let globalApkIdFiles;

// load excel files and enable reporting in front end
$.ajax({ url: 'loadfiles.php', method: 'get' }).then(function(response)
{
    const files = JSON.parse(response);
    if (files.fma.length > 0)
        $('#fma-dmu-tag').remove();

    globalFmaFiles = files.fma;
    files.fma.forEach(function(file) { $('#fma-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin: 0 10px 10px 0;">${ file }</span>`); });

    if (files.apk.length > 0)
        $('#apk-dmu-tag').remove();

    globalApkFiles = files.apk;
    files.apk.forEach(function(file) { $('#apk-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin: 0 10px 10px 0;">${ file }</span>`); });

    if (files.apkId.length > 0)
        $('#apkId-dmu-tag').remove();

    globalApkIdFiles = files.apkId;
    files.apkId.forEach(function(file) { $('#apkId-dmu-tagbox').append(`<span class="tag is-primary is-light" style="margin: 0 10px 10px 0;">${ file }</span>`); });

    $('#excel-loader').css('display', 'none');
    $('#report-section').css('visibility', 'visible');
});

// load database tables and update front-end
$('#report-loader').css('visibility', 'visible');
$('#report-status').css('visibility', 'visible');
$('#report-status').html('Fma data wordt opgeladen van de database en gesynchroniseerd');
$.ajax({ url: 'fma_synchronize.php', method: 'get' }).then(function(response) 
{ 
    globalFmaDataStdClass = response;
    
    // load fma onto table
    const data = JSON.parse(response);
    for (let index = 0; index < data.fk_fma.length; index++)
    {
        const statusExcel = data.status_excel[index];
        let status_str = '';
        let status_color = '';
        let status_excel_text = '';
        let status_excel_color = '';
        if (statusExcel == 1)
        {
            status_str = 'Geen opvolging nodig';
            status_color = 'gray';
            status_excel_text = 'CORRECT';
            status_excel_color = 'green';
        }
        else
        {
            status_excel_text = 'ONTBREEKT';
            status_excel_color = 'red';
            switch (data.status[index])
            {
                case '1': status_str = 'NAZIEN'; status_color = 'red'; break;
                case '2': status_str = 'OPGESTUURD'; status_color = 'orange'; break;
                case '3': status_str = 'OK'; status_color = 'green'; break;
            }
        }   

        $('#table-report-body').append(`
            <tr>
                <td>${ data.fk_fma[index] }</td>
                <td>${ new Date(data.date[index] * 1000).toLocaleDateString('nl-BE') }</td>
                <td>${ data.ordernr[index] }</td>
                <td fk-orderstatus="${ data.ordernr[index] }" fk-orderkey="${ data.fk_fma[index] }" style="color:${ status_excel_color };${ data.status[index] == '3' ? 'text-decoration:line-through;color:gray;' : '' }">${ status_excel_text }</td>
                <td fk-actionstatus="${ data.ordernr[index] }" fk-actionkey="${ data.fk_fma[index] }" style="color:${ status_color }">${ status_str }</td>
                <td fk-descriptionstatus="${ data.ordernr[index] }" fk-descriptionkey="${ data.fk_fma[index] }" fk-descriptioncontent="${ data.beschrijving[index] }"><p>${ data.beschrijving[index] }</p></td>
                <td><button class="button is-info button-orderstatus" fk-buttonkey="${ data.fk_fma[index] }" fk-buttonstatus="${ data.ordernr[index] }" fk-buttonstatus-status="${ data.status[index] }" fk-buttonstatus-toggle="0" style="${ statusExcel == 1 ? 'visibility: hidden;' : '' }">Wijzigen</button></td>
            </tr>
        `);
    }

    $('#report-loader').css('visibility', 'hidden');
    $('#report-status').html('');
    $('#report-status').css('visibility', 'hidden');

    // bind the change buttons
    $('.button-orderstatus').click(function(e) 
    { 
        if ($(this).attr('fk-buttonstatus-toggle') == '0')
        {
            // change status cell into select
            const actionselect = `<div class="select"><select fk-selectstatus="${ $(this).attr('fk-buttonkey') }">
                <option value="1" ${ $(this).attr('fk-buttonstatus-status') == '1' ? 'selected="selected"' : '' }>NAZIEN</option>
                <option value="2" ${ $(this).attr('fk-buttonstatus-status') == '2' ? 'selected="selected"' : '' }>OPGESTUURD</option>
                <option value="3" ${ $(this).attr('fk-buttonstatus-status') == '3' ? 'selected="selected"' : '' }>OK</option>
            </select></div>`;

            // update select
            $(`[fk-actionkey="${ $(this).attr('fk-buttonkey') }"]`).html(actionselect);

            // update textarea
            const descriptioncontent = $(`[fk-descriptionkey="${ $(this).attr('fk-buttonkey') }"]`).attr('fk-descriptioncontent');
            $(`[fk-descriptionkey="${ $(this).attr('fk-buttonkey') }"]`).html(`<textarea class="textarea" fk-descriptiontextarea="${ $(this).attr('fk-buttonkey') }">${ descriptioncontent }</textarea>`);

            // set check style
            $(this).html('<i class="fas fa-check"></i>');
            $(this).attr('fk-buttonstatus-toggle', '1');   
        }
        else
        {
            // save new status into button
            const orderkey = $(this).attr('fk-buttonkey');
            const newStatus = $(`[fk-selectstatus="${ orderkey }"]`).val();
            $(this).attr('fk-buttonstatus-status', newStatus);
            // cache description
            const newDescription = $(`[fk-descriptiontextarea="${ $(this).attr('fk-buttonkey') }"]`).val();
            // save to database
            $.ajax({ url: 'fma_update.php', method: 'post', data: { fma_key: orderkey, status: newStatus, description: newDescription } });

            // reset style of select
            let status_str = '';
            let status_color = '';
            switch (newStatus)
            {
                case '1': status_str = 'NAZIEN'; status_color = 'red'; break;
                case '2': status_str = 'OPGESTUURD'; status_color = 'orange'; break;
                case '3': status_str = 'OK'; status_color = 'green'; break;
            }

            // reset status 
            $(`[fk-actionkey="${ $(this).attr('fk-buttonkey') }"]`).html(status_str);
            $(`[fk-actionkey="${ $(this).attr('fk-buttonkey') }"]`).css('color', status_color);

            // reset description
            $(`[fk-descriptionkey="${ $(this).attr('fk-buttonkey') }"]`).attr('fk-descriptioncontent', newDescription);
            $(`[fk-descriptionkey="${ $(this).attr('fk-buttonkey') }"]`).html(`<p>${ newDescription }</p>`);

            // reset own style
            $(this).html('Wijzigen');
            $(this).attr('fk-buttonstatus-toggle', '0');   
        }
    });

    // analyse the ordernrs
    analyseApkFiles()
});

// search through apkId files and analyse them
function analyseSingleApkIdFile(filename)
{
    return new Promise(resolve =>
    {
        $('#report-loader').css('visibility', 'visible');
        $('#report-status').css('visibility', 'visible');
        $('#report-status').html(`Apk excel: ${ filename } wordt opgehaald en geanalyseerd`);
        $.ajax({ url: 'apk_id_process.php', method: 'post', data: { apkIdData: filename } }).then(function(response) 
        { 
            if (response[0] === '<')
            {
                console.log(response);
                $('#report-status').html(`Error met file '${ filename }, bekijk de logs...' `);    
                $('#report-status').css('color', 'red');
            }
            else
            {
                // update front-end
                const data = JSON.parse(response);
                data.ordernr.forEach(function(el)
                {
                    $(`[fk-orderstatus="${ el }"]`).html('CORRECT');
                    $(`[fk-orderstatus="${ el }"]`).css('color', 'green');
                    $(`[fk-actionstatus="${ el }"]`).html('Geen opvolging nodig');
                    $(`[fk-actionstatus="${ el }"]`).css('color', 'gray');
                    $(`[fk-buttonstatus="${ el }"]`).css('visibility', 'hidden');

                    // update database with this info
                    $.ajax({ url: 'apk_update.php', method: 'post', data: { ordernr: el, status_excel: 1 } });
                });

                $('#report-status').html('');
                $('#report-status').css('visibility', 'hidden');
            }

            $('#report-loader').css('visibility', 'hidden');

            resolve();
        });  
    })
}

// search through a single apk file and analyse it
function analyseSingleApkFile(filename)
{
    return new Promise(resolve =>
    {
        $('#report-loader').css('visibility', 'visible');
        $('#report-status').css('visibility', 'visible');
        $('#report-status').html(`Apk excel: ${ filename } wordt opgehaald en geanalyseerd`);
        $.ajax({ url: 'apk_process.php', method: 'post', data: { apkData: filename } }).then(async function(response) 
        { 
            if (response[0] === '<')
            {
                console.log(response);
                $('#report-status').html(`Error met file '${ filename }, bekijk de logs...' `);    
                $('#report-status').css('color', 'red');
            }
            else
            {
                // update front-end
                const data = JSON.parse(response);
                for (let index = 0; index < data.ordernr.length; index++)
                {
                    if (data.teaplus[index] == 'OK')
                    {
                        $(`[fk-orderstatus="${ data.ordernr[index] }"]`).html('CORRECT');
                        $(`[fk-orderstatus="${ data.ordernr[index] }"]`).css('color', 'green');
                        $(`[fk-actionstatus="${ data.ordernr[index] }"]`).html('Geen opvolging nodig');
                        $(`[fk-actionstatus="${ data.ordernr[index] }"]`).css('color', 'gray');
                        $(`[fk-buttonstatus="${ data.ordernr[index] }"]`).css('visibility', 'hidden');

                        // update database with this info
                        console.log('started apk_update');
                        await $.ajax({ url: 'apk_update.php', method: 'post', data: { ordernr: data.ordernr[index], status_excel: 1 } }).then(function(response) { console.log('finished apk_update'); });
                    }
                }

                $('#report-status').html('');
                $('#report-status').css('visibility', 'hidden');
            }

            $('#report-loader').css('visibility', 'hidden');

            resolve();
        });  
    })
}

// search through all apk files one by one and update front-end status
async function analyseApkFiles()
{
    if (globalApkFiles)
    {
        for (let apkFile of globalApkFiles)
        {
            await analyseSingleApkFile(apkFile);
        }
    }

    if (globalApkIdFiles)
    {
        // continue to analyse apkId files
        for (let apkIdFile of globalApkIdFiles)
        {
            await analyseSingleApkIdFile(apkIdFile);
        }
    }
}

// bind click event of report button to execute process script
$('#button-synchronize-report').click(function(e)
{
    $('#report-loader').css('visibility', 'visible');
    $('#report-status').css('visibility', 'visible');
    $('#report-status').html('Fma excel wordt opgeladen en gesynchroniseerd');
    $.ajax({ url: 'fma_process.php', method: 'post', data: { fmaData: globalFmaFiles } }).then(function(response) 
    { 
        globalFmaDataStdClass = response;

        $('#report-loader').css('visibility', 'hidden');
        $('#report-status').html('');
        $('#report-status').css('visibility', 'hidden');

        // continue analysis
        analyseApkFiles();
    }).catch(function(response) { console.log(response); });
});

// bind click event of download button to execute report to sheet and download scripts
$('#button-download-report').click(function()
{
    $('#report-loader').css('visibility', 'visible');
    $('#report-status').css('visibility', 'visible');
    $('#report-status').html('Rapport wordt opgemaakt');
    $.ajax({ url: 'reportsheet.php', method: 'get'}).then(function(response) 
    { 
        $('#report-status').html('Rapport wordt gedownload');
        // file is ready for download
        Lib.download('assets/reports/raport_apk_dmu.xlsx', 'raport_apk_dmu.xlsx');
        
        $('#report-loader').css('visibility', 'hidden');
        $('#report-status').html('');
        $('#report-status').css('visibility', 'hidden');
    });
});