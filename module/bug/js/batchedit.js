/**
 * Set duplicate field.
 * 
 * @param  string $resolution 
 * @access public
 * @return void
 */
function setDuplicate(resolution, bugID)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox' + bugID).show();
    }
    else
    {
        $('#duplicateBugBox' + bugID).hide();
    }
}
