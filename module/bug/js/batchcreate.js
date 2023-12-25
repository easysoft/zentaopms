$(function()
{
    removeDitto();//Remove 'ditto' in first row.

    var $titleCol = $('#batchCreateForm table thead tr th.c-title');
    if($titleCol.width() < 150) $titleCol.width(150);

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

     /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('batchCreateFields', 10, $titleCol, 150);
        return false;
    });

    $('#dropMenu').css('z-index', 9999);

    $("input[name^='uploadImage']").each(function()
    {
        $(this).closest('td').siblings("td[id^='buildBox']").find('select').data('zui.picker').setValue('trunk');
    })
})

/**
 * Set opened builds.
 *
 * @param  string  $link
 * @param  int     $index
 * @access public
 * @return void
 */
function setOpenedBuilds(link, index)
{
    $.get(link, function(builds)
    {
        var row = $('#buildBox' + index).closest('tbody').find('tr').size()
        do
        {
            var selected = $('#buildBox' + index).find('select').val();
            $('#buildBox' + index).html(builds);
            $('#buildBox' + index).find('select').val(selected);
            $('#pickerDropMenu-pk_openedBuild' + index).remove();
            $('#openedBuilds' + index).next('.picker').remove();
            $('#buildBox' + index + ' select').removeClass('select-3');
            $('#buildBox' + index + ' select').addClass('select-1');
            $('#buildBox' + index + ' select').attr('name','openedBuilds[' + index + '][]');
            $('#buildBox' + index + ' select').attr('id','openedBuilds' + index);
            $('#buildBox' + index + ' select').picker({optionRender: markReleasedBuilds, dropWidth: 'auto'});

            index++;
            if($('#executions' + index).val() != 'ditto') break;
        }while(index < row)
    });
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
    laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field=lanes&i=' + num);
    $.get(laneLink, function(lanes)
    {
        if(!lanes) lanes = '<select id="lanes' + num + '" name="lanes[' + num + ']" class="form-control"></select>';
        $('#lanes' + num).replaceWith(lanes);
        $("#lanes" + num + "_chosen").remove();
        $("#lanes" + num).next('.picker').remove();
        $("#lanes" + num).chosen();
    });
}

/**
 * Load execution builds.
 *
 * @param  int    $productID
 * @param  int    $executionID
 * @param  int    $index
 * @access public
 * @return void
 */
function loadExecutionBuilds(productID, executionID, index)
{
    var branch = $('#branches' + index).val() ? $('#branches' + index).val() : 0;
    if(executionID == 'ditto')
    {
        for(var i = index - 1; i > 0, executionID == 'ditto'; i--)
        {
            executionID = $('#executions' + i).val();
        }
    }

    if(executionID != 0)
    {
        link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch + "&index=" + index);
    }
    else
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=&branch=" + branch + "&index=" + index);
    }

    setOpenedBuilds(link, index);
}

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
})
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
})

$(document).keydown(function(event)
{
    if((event.ctrlKey || event.altKey) && event.keyCode == 38)
    {
        event.stopPropagation();
        event.preventDefault();
        selectFocusJump('up');
    }
    else if((event.ctrlKey || event.altKey) && event.keyCode == 40)
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
