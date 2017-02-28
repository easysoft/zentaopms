var newRowID = 0;
function createRow()
{
    if(newRowID == 0) newRowID = $('.stepID').size();
    newRowID ++;
    var newRow = "<tr class='text-center' id='row" + newRowID + "'>";
    newRow += "<td class='stepID strong'></td>";
    newRow += "<td><textarea name='steps[]' rows=1 class='form-control'></textarea></td>";
    newRow += "<td><textarea name='expects[]' rows=1 class='form-control'></textarea></td>";
    newRow += "<td class='text-left text-top'>";
    newRow += "<button type='button' tabindex='-1' class='addbutton btn' title='" + lblBefore + "' onclick='preInsert("  + newRowID + ")' ><i class='icon icon-double-angle-up'></i></button>";
    newRow += "<button type='button' tabindex='-1' class='addbutton btn' title='" + lblAfter  + "' onclick='postInsert(" + newRowID + ")' ><i class='icon icon-double-angle-down'></i></button>";
    newRow += "<button type='button' tabindex='-1' class='delbutton btn' title='" + lblDelete + "' onclick='deleteRow("  + newRowID + ")' ><i class='icon icon-remove'></i></button>";
    newRow += "</td>";
    return newRow;
}

function loadLibModules(libID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=testlib&branch=0&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
}

/**
 * Delete a step row.
 * 
 * @param  int    $rowID 
 * @access public
 * @return void
 */
function deleteRow(rowID)
{
    if($('.stepID').size() == 1) return;
    $('#row' + rowID).remove();
    updateStepID();
}

/**
 * Insert before the step.
 * 
 * @param  int    $rowID 
 * @access public
 * @return void
 */
function preInsert(rowID)
{
    $('#row' + rowID).before(createRow());
    updateStepID();
}

/**
 * Insert after the step.
 * 
 * @param  int    $rowID 
 * @access public
 * @return void
 */
function postInsert(rowID)
{
    $('#row' + rowID).after(createRow());
    updateStepID();
}

/**
 * Update the step id.
 * 
 * @access public
 * @return void
 */
function updateStepID()
{
    var i = 1;
    $('.stepID').each(function(){$(this).html(i ++)});
}

$(function()
{
    $('[data-toggle=tooltip]').tooltip();
})
