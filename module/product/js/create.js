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

$('#program').change(function()
{
    var programID = $(this).val();

    $.get(createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        $('#line_chosen').remove();
        $('#line').replaceWith(data);
        $('#line').chosen();
    })
})

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
        $line.attr('disabled', 'disabled');
    }
    else
    {
        $('form .line-exist').removeClass('hidden');
        $('form .line-no-exist').addClass('hidden');
        $line.removeAttr('disabled');
    }
}
