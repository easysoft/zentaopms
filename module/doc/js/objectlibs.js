
/**
 * Ajax delete doc.
 *
 * @param  string $link
 * @param  replaceID $replaceID
 * @param  string $notice
 * @access public
 * @return void
 */
function ajaxDeleteDoc(link, replaceID, notice)
{
    if(confirm(notice))
    {
        $.get(link, function(data)
        {
            location.href = JSON.parse(data).locate;
        })
    }
}

/**
 * Delete a file.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
}
