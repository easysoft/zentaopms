/**
 * Set duplicate field.
 * 
 * @param  string $resolution 
 * @param  int    $storyID
 * @access public
 * @return void
 */
function setDuplicateAndChild(resolution, storyID)
{
    if(resolution == 'duplicate')
    {
        $('#childStoryBox' + storyID).hide();
        $('#duplicateStoryBox' + storyID).show();
    }
    else if(resolution == 'subdivided')
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).show();
    }
    else
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).hide();
    }
}

function loadBranches(product, branch, storyID)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + product + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + storyID + '&needManage=true');
    $('#modules' + storyID).parent('td').load(moduleLink, function(){$('#modules' + storyID).chosen(defaultChosenOptions);})

    planID = $('#plans' + storyID).val();
    planLink = createLink('product', 'ajaxGetPlans', 'productID=' + product + '&branch=' + branch + '&planID=' + planID + '&fieldID=' + storyID + '&needCreate=true');
    $('#plans' + storyID).parent('td').load(planLink, function(){$('#plans' + storyID).chosen(defaultChosenOptions);});
}

$(document).ready(removeDitto());//Remove 'ditto' in first row.

$(document).on('click', '.chosen-with-drop', function(){oldValue = $(this).prev('select').val();})//Save old value.

/* Set ditto value. */
$(document).on('change', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var tbody = $(this).closest('tr').parent();

        if($(this).attr('name').indexOf('closedReasons') != -1)
        {
            index = $(this).closest('tr').closest('td').index();
            row   = $(this).closest('tr').closest('td').parent().index();
            tbody = $(this).closest('tr').closest('td').parent().parent();
        }

        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = tbody.children('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }

        isModules = $(this).attr('name').indexOf('modules') != -1;
        isPlans   = $(this).attr('name').indexOf('plans')   != -1;

        if(isModules || isPlans)
        {

            var valueStr = ',' + $(this).find('option').map(function(){return $(this).val();}).get().join(',') + ',';
            if(valueStr.indexOf(',' + value + ',') != -1)
            {
                $(this).val(value);
            }
            else
            {
                alert(dittoNotice);
                $(this).val(oldValue);
            }
        }
        else
        {
            $(this).val(value);
        }

        $(this).trigger("chosen:updated");
        $(this).trigger("change");
    }
})
