$(document).on('change', "[name^='begin'], [name^='end']", function()
{
    toggleCheck($(this));
})
/**
 * Toggle checkbox.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function toggleCheck(obj)
{
    var $this  = $(obj);
    var date   = $this.val();
    var $ditto = $this.closest('div').find("input[type='checkBox']");
    if(date == '')
    {
        $ditto.attr('checked', true);
        $ditto.closest('.input-group-addon').show();
    }
    else
    {
        $ditto.removeAttr('checked');
        $ditto.closest('.input-group-addon').hide();
    }
}
