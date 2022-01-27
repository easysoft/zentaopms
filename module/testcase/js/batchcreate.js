$(document).ready(function()
{
    removeDitto();//Remove 'ditto' in first row.
    if($('#batchCreateForm table thead tr th.c-title').width() < 170) $('#batchCreateForm table thead tr th.c-title').width('170');

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });
});

/**
 * Set modules.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
function setModules(branchID, productID, num)
{
    moduleLink = createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=story&branch=' + branchID + '&num=' + num);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="module' + num + '" name="module[' + num + ']" class="form-control"></select>';
        $('#module' + num).replaceWith(modules);
        $("#module" + num + "_chosen").remove();
        $("#module" + num).next('.picker').remove();
        $("#module" + num).attr('onchange', "loadStories("+ productID + ", this.value, " + num + ")").chosen();
    });

    loadStories(productID, 0, num);

    /* If the branch of the current row is inconsistent with the one below, clear the module and story of the nex row. */
    var nextBranchID = $('#branch' + (num + 1)).val();
    if(nextBranchID != branchID)
    {
        $('#module' + (num + 1)).find("option[value='ditto']").remove();
        $('#module' + (num + 1)).trigger("chosen:updated");

        $('#plan' + (num + 1)).find("option[value='ditto']").remove();
        $('#plan' + (num + 1)).trigger("chosen:updated");
    }
}
