$(function()
{
    if($('#batchCreateForm table thead tr th.col-name').width() < 200) $('#batchCreateForm table thead tr th.col-name').width(200);

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        var fields = '';
        $('#formSettingForm > .checkboxes > .checkbox-primary > input:checked').each(function()
        {
            fields += ',' + $(this).val();
        });

        var link = createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields');
        $.post(link, {'fields' : fields}, function()
        {
            showFields = fields;
            showCheckedFields(fields);
            $('#formSetting').parent().removeClass('open');

            var fieldCount = $('#batchCreateForm .table thead>tr>th:visible').length;
            $('.form-actions').attr('colspan', fieldCount);

            if(fieldCount > 8)
            {
                $('#batchCreateForm > .table-responsive').removeClass('scroll-none');
                $('#batchCreateForm > .table-responsive').css('overflow', 'auto');
            }
            else
            {
                $('#batchCreateForm > .table-responsive').addClass('scroll-none');
                $('#batchCreateForm > .table-responsive').css('overflow', 'visible');
            }

            if($('#batchCreateForm table thead tr th.col-name').width() < 200) $('#batchCreateForm table thead tr th.col-name').width(200);
        });

        return false;
    });
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
        $("#module" + num).next('.picker').remove();
        $("#module" + num).chosen();
    });

    planLink = createLink('productPlan', 'ajaxGetProductPlans', 'productID=' + productID + '&branch=' + branchID + '&num=' + num + '&expired=unexpired');
    $.get(planLink, function(plans)
    {
        if(!plans) plans = '<select id="plan' + num + '" name="plan[' + num + ']" class="form-control"></select>';
        $('#plan' + num).replaceWith(plans);
        $("#plan" + num + "_chosen").remove();
        $("#plan" + num).next('.picker').remove();
        $("#plan" + num).chosen();
    });

    /* If the branch of the current row is inconsistent with the one below, clear the module and plan of the nex row. */
    var nextBranchID = $('#branch' + (num + 1)).val();
    if(nextBranchID != branchID)
    {
        $('#module' + (num + 1)).find("option[value='ditto']").remove();
        $('#module' + (num + 1)).trigger("chosen:updated");

        $('#plan' + (num + 1)).find("option[value='ditto']").remove();
        $('#plan' + (num + 1)).trigger("chosen:updated");
    }
}

/* Copy story title as story spec. */
function copyTitle(num)
{
    var title = $('#title\\[' + num + '\\]').val();
    $('#spec\\[' + num + '\\]').val(title);
}

$(document).on('change', "[name*='reviewer']", function()
{
    toggleCheck($(this));
})

/**
 * Toggle checkbox.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function toggleCheck(obj)
{
    var $this  = $(obj);
    var data   = $this.val();
    var $ditto = $this.closest('div').find("input[name*='reviewDitto']");
    if(data == '')
    {
        $ditto.attr('checked', true);
        $ditto.closest('.input-group-addon').show();
    }
    else
    {
        $ditto.removeAttr('checked');
        $ditto.closest('.input-group-addon').hide();
    }
}

/**
 * Set lane.
 *
 * @param  int $regionID
 * @param  int $num
 * @access public
 * @return void
 */
function setLane(regionID, num)
{
    laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lanes&i=' + num);
    $.get(laneLink, function(lanes)
    {
        if(!lanes) lanes = '<select id="lanes' + num + '" name="lanes[' + num + ']" class="form-control"></select>';
        $('#lanes' + num).replaceWith(lanes);
        $("#lanes" + num + "_chosen").remove();
        $("#lanes" + num).next('.picker').remove();
        $("#lanes" + num).chosen();
    });
}
