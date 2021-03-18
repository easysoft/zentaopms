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
