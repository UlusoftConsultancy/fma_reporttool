// global error state
let globalErrorState = true;

// bind onchange events of file upload inputs
$('#file-apk-dmu').change(function(e)
{
    let file = e.target.files[0];
    
    // check if the file is an excel workbench
    if (file.name.split('.').pop() !== 'xlsx')
    {
        alert ('Geselecteerde file is geen Excel (.xlsx) bestand!');
        return ;
    }

    let formData = new FormData();
    formData.append('filename', 'tmpApk');
     
    formData.append("sheet", file);
    $.ajax({ url: 'upload.php', method: 'post', data: formData, processData: false, contentType: false }).then(function(e) 
    { 
        const response = JSON.parse(e);
        $('#file-name-apk').html(response.message); 

        globalErrorState = response.errorcode;
    });
});

// bind onchange events of file upload inputs
$('#file-fma-dmu').change(function(e)
{
    let file = e.target.files[0];
    
    // check if the file is an excel workbench
    if (file.name.split('.').pop() !== 'xlsx')
    {
        alert ('Geselecteerde file is geen Excel (.xlsx) bestand!');
        return ;
    }

    let formData = new FormData();
    formData.append('filename', 'tmpFma');
     
    formData.append("sheet", file);
    $.ajax({ url: 'upload.php', method: 'post', data: formData, processData: false, contentType: false }).then(function(e) 
    { 
        const response = JSON.parse(e);
        $('#file-name-fma').html(response.message);      

        globalErrorState = response.errorcode;
    });
});

// bind click event of report button to execute process script
$('#button-generate-report').click(function(e)
{
    if (!globalErrorState)
    {
        $.ajax({ url: 'process.php', method: 'get' }).then(function(response) 
        { 
            const data = JSON.parse(response);
            data.forEach(function(element)
            {
                $('#table-report-body').append(`
                    <tr>
                        <td>${ element.keyApk }</td>
                        <td>${ element.keyFma }</td>
                        <td>${ element.date }</td>
                        <td>${ element.value }</td>
                        <td>${ element.status }</td>
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
