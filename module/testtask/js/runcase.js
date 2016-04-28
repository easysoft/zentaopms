$(document).on('keyup', 'form textarea', function()
{
    var preSelect = $(this).closest('table').parent().prev().find('select');
    if($(this).val() == '' && $(preSelect).val() == 'fail')
    {
        $(preSelect).val('pass');
    }
    else if($(this).val() != '' && $(preSelect).val() == 'pass')
    {
        $(preSelect).val('fail').parent().addClass('has-error');
        setTimeout(function(){$(preSelect).parent().removeClass('has-error');},'1000');
    }
})

/* Delete a file. */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('file', 'delete', 'fileID=' + fileID);
}

/* Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it. */
function downloadFile(fileID)
{
    if(!fileID) return;
    var sessionString = '<?php echo $sessionString;?>';
    var url = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + sessionString;
    window.open(url, '_blank');
    return false;
}
