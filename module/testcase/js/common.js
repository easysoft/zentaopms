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
    loadProductBranches(productID)
    loadProductModules(productID);
    setStories();
}

/**
 * Load by branch.
 * 
 * @access public
 * @return void
 */
function loadBranch()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModules($('#product').val(), branch);
    setStories();
}

/**
 * Load product branches.
 * 
 * @param  int $productID 
 * @access public
 * @return void
 */
function loadProductBranches(productID)
{
    $('#branch').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '65px');
        }
    })
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
function loadProductModules(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
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
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=50');
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
    newRow += "<td><textarea name='steps[]' rows=3 class='form-control'></textarea></td>";
    newRow += "<td><textarea name='expects[]' rows=3 class='form-control'></textarea></td>";
    newRow += "<td class='text-left'>";
    newRow += "<button type='button' tabindex='-1' class='addbutton btn' title='" + lblBefore + "' onclick='preInsert("  + newRowID + ")' ><i class='icon icon-double-angle-up'></i></button>";
    newRow += "<button type='button' tabindex='-1' class='addbutton btn' title='" + lblAfter  + "' onclick='postInsert(" + newRowID + ")' ><i class='icon icon-double-angle-down'></i></button>";
    newRow += "<button type='button' tabindex='-1' class='delbutton btn' title='" + lblDelete + "' onclick='deleteRow("  + newRowID + ")' ><i class='icon icon-remove'></i></button>";
    newRow += "</td>";
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
