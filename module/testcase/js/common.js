var newRowID = 0;
/**
 * Load modules and stories of a product.
 * 
 * @param  int     $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    loadProductModules(productID);
    setStories();
}

/**
 * Load stories of module. 
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    setStories();
}

/**
 * Load module.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductModules(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(link, function(){$(this).find('select').chosen(defaultChosenOptions)});
    setStories();
}

/**
 * Set story field.
 * 
 * @access public
 * @return void
 */
function setStories()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=50');
    $.get(link, function(stories)
    {
        var value = $('#story').val();
        if(!stories) stories = '<select id="story" name="story"></select>';
        $('#story').replaceWith(stories);
        $('#story').val(value);
        $('#story_chosen').remove();
        $("#story").chosen(defaultChosenOptions);
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
 * Create a step row.
 * 
 * @access public
 * @return void
 */
function createRow()
{
    if(newRowID == 0) newRowID = $('.stepID').size();
    newRowID ++;
    var newRow = "<tr class='text-center' id='row" + newRowID + "'>";
    newRow += "<td class='stepID strong'></td>";
    newRow += "<td class='w-p50'><textarea name='steps[]' rows=3 class='form-control'></textarea></td>";
    newRow += "<td><textarea name='expects[]' rows=3 class='form-control'></textarea></td>";
    newRow += "<td class='text-left w-100px'><div class='btn-group-vertical'>";
    newRow += "<input type='button' tabindex='-1' class='addbutton btn' value='" + lblBefore + "' onclick='preInsert("  + newRowID + ")' />";
    newRow += "<input type='button' tabindex='-1' class='addbutton btn' value='" + lblAfter  + "' onclick='postInsert(" + newRowID + ")' />";
    newRow += "<input type='button' tabindex='-1' class='delbutton btn' value='" + lblDelete + "' onclick='deleteRow("  + newRowID + ")' />";
    newRow += "</div></td>";
    return newRow;
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
