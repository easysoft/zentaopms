<?php include '../../common/view/header.html.php';?>
<style>
#story {width:90%}
.delbutton{font-size:12px; color:red; width:80px; padding:0}
.addbutton{font-size:12px; color:darkgreen; width:80px; padding:0}
.searchleft{width:220px}
</style>
<script language='Javascript'>
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
    loadModuleMenu(productID);
    loadStory(productID);
}

/**
 * Load module.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}

/**
 * Load stories.
 * 
 * @param  int     $productID 
 * @access public
 * @return void
 */
function loadStory(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $('#storyIdBox').load(link);
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
    updateID();
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
    updateID();
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
    updateID();
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
    var lblDelete = '<?php echo $lang->testcase->deleteStep;?>';
    var lblBefore = '<?php echo $lang->testcase->insertBefore;?>';
    var lblAfter  = '<?php echo $lang->testcase->insertAfter;?>';
    var newRow    = "<tr class='a-center' id='row" + newRowID + "'>";
    newRow += "<th class='stepID'></th>";
    newRow += "<td class='w-p50'><textarea name='steps[]' rows=3 class='w-p100'></textarea></td>";
    newRow += "<td><textarea name='expects[]' rows=3 class='w-p100'></textarea></td>";
    newRow += "<td class='a-center w-100px'><nobr>";
    newRow += "<input type='button' tabindex='-1' class='addbutton' value='" + lblBefore + "' onclick='preInsert("  + newRowID + ")' /><br />";
    newRow += "<input type='button' tabindex='-1' class='addbutton' value='" + lblAfter  + "' onclick='postInsert(" + newRowID + ")' /><br />";
    newRow += "<input type='button' tabindex='-1' class='delbutton' value='" + lblDelete + "' onclick='deleteRow("  + newRowID + ")' /><br />";
    newRow += "</nobr></td>";
    return newRow;
}

/**
 * Update the step id.
 * 
 * @access public
 * @return void
 */
function updateID()
{
    i = 1;
    $('.stepID').each(function(){$(this).html(i ++)});
}
</script>
