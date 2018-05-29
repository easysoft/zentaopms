$(function()
{
    if($('#batchCreateForm table thead tr th.col-name').width() < 200) $('#batchCreateForm table thead tr th.col-name').width(200);
});
$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).closest('td').index();
        var row   = $(select).closest('tr').index();
        var table = $(select).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
});
$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var table = $(this).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(this).val(value);
    }
});

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

if(navigator.userAgent.indexOf("Firefox") < 0)
{
    $(document).on('input keyup paste change', 'textarea.autosize', function()
    {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight + 2) + "px"; 
    });
}

/**
 * Set modules and plans.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
function setModuleAndPlan(branchID, productID, num)
{
    moduleLink = createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=story&branch=' + branchID + '&num=' + num);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="module' + num + '" name="module[' + num + ']" class="form-control"></select>';
        $('#module' + num).replaceWith(modules);
        $("#module" + num + "_chosen").remove();
        $("#module" + num).chosen();
    });

    planLink = createLink('productPlan', 'ajaxGetProductPlans', 'productID=' + productID + '&branch=' + branchID + '&num=' + num);
    $.get(planLink, function(plans)
    {
        if(!plans) plans = '<select id="plan' + num + '" name="plan[' + num + ']" class="form-control"></select>';
        $('#plan' + num).replaceWith(plans);
        $("#plan" + num + "_chosen").remove();
        $("#plan" + num).chosen();
    });
}

/* Copy story title as story spec. */
function copyTitle(num)
{
    var title = $('#title\\[' + num + '\\]').val();
    $('#spec\\[' + num + '\\]').val(title);
}
