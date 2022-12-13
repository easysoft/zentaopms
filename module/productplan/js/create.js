/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}

/**
 * Compute the end date for productplan.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
function computeEndDate(delta)
{
    var beginDate = $('#begin').val();
    if(!beginDate) return;

    var delta = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    var currentBeginDate = $.zui.formatDate(beginDate, 'yyyy-MM-dd');
    var endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');

    $('#begin').val(currentBeginDate);
    $('#end').val(endDate).datetimepicker('update');
}

$('#begin').on('change', function()
{
    $("input:radio[name='delta']").attr("checked",false);
});

$('#end').on('change', function()
{
    $("input:radio[name='delta']").attr("checked", false);
});

$('#future').on('change', function()
{
    if($(this).prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').attr('disabled', 'disabled').parents('tr').hide();
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled').parents('tr').show();
    }
});

$('#parent').change(function()
{
    var parentID        = $(this).val();
    var currentBranches = $('#branch').val() ? $('#branch').val().toString() : '';
    $.post(createLink('productplan', 'ajaxGetParentBranches', "productID=" + productID + "&parentID=" + parentID + "&currentBranches=" + currentBranches), function(data)
    {
        $('#branch').replaceWith(data);
        $('#branch_chosen').remove();
        $('#branch').chosen();
        $('#branch').change();
    })
})

$('#dataform').on('change', '#branch', function()
{
    if(parentPlanID) return;

    var branchIdList = $(this).val();
    if(!branchIdList) return;

    var branchIdList = branchIdList.toString();
    var lastPlanLink = createLink('productplan', 'ajaxGetLast', "productID=" + productID + "&branch=" + branchIdList);
    $.post(lastPlanLink, function(data)
    {
        data = JSON.parse(data);
        var planTitle = data ? '(' + lastLang + ': ' + data.title + ')' : '';
        $('#title').parent().next('td').html(planTitle);
    })
});

$('#submit').click(function()
{
    var parentPlan = $('#parent').val();
    var branches   = $('#branch').val();
    if(parentPlan > 0 && branches)
    {
        link = createLink('productplan', 'ajaxGetDiffBranchesTip', "produtID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches.toString());
        $.post(link, function(diffBranchesTip)
        {
            if((diffBranchesTip != '' && confirm(diffBranchesTip)) || !diffBranchesTip) $('form#dataform').submit();
        });
        return false;
    }
});
