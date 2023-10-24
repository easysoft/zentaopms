$(function()
{
    $('input[type=file]').change(function()
    {
        var file         = $(this)[0].files[0];
        var fileSize     = file.size;
        var maxSizeBytes = parseInt(maxUploadSize) * 1024 * 1024;
        if(fileSize > maxSizeBytes) alert(exceedLimitMsg);
    })
})
