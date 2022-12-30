var runCase = false;
/**
 * Define triggerModal hidden event.
 *
 * @access public
 * @return void
 */
function triggerHidden()
{
    $('#triggerModal').on('hidden.zui.modal', function()
    {
        if(runCase == true) window.location.reload();
    });
}
