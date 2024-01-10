$(function()
{
    $('#lineName').css('border-left-color', '');
    $('[name=newLine]').change();
})
/**
  * Load product Lines.
  *
  * @param  $rootID
  * @access public
  * @return void
  */
function loadProductLines(rootID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + rootID + '&viewtype=line' + '&branch=0' + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#lineIdBox').load(link, function()
    {
        $(this).find('select').chosen()
    });
}

/**
 * Set parent program.
 *
 * @param  $parentProgram
 * @access public
 * @return void
 */
function setParentProgram(parentProgram)
{
    location.href = createLink('product', 'create', 'programID=' + parentProgram);
}

/**
 * Toggle line.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function toggleLine(obj)
{
    var $obj = $(obj);
    if($obj.length == 0) return false;

    var $line = $obj.closest('table').find('#line');

    if($obj.prop('checked'))
    {
        $('form .line-no-exist').removeClass('hidden');
        $('form .line-exist').addClass('hidden');
        $('#line_chosen').addClass('hidden');
        $line.attr('disabled', 'disabled');
    }
    else
    {
        $('#line').removeClass('hidden');
        $('form .line-no-exist').addClass('hidden');
        $('#line_chosen').removeClass('hidden');
        $line.removeAttr('disabled');
    }
}
