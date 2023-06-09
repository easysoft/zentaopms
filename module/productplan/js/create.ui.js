function resetDelta()
{
    $(".radio-primary > input").attr("checked",false);
}

function computeEndDate()
{
    var beginDate = $('#begin').val();
    if(!beginDate) return;
    console.log(beginDate);

    var delta = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    var currentBeginDate = window.zui.formatDate(beginDate, 'yyyy-MM-dd');
    var endDate = window.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');

    $('#begin').val(currentBeginDate);
    $('#end').val(endDate).datetimepicker('update');
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return new Date(dateString);
}

function toggleDateVisibility()
{
    if($('#future_').prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').attr('disabled', 'disabled').closest('.form-row').hide();
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled').closest('.form-row').show();
    }
}

function getParentBranches()
{
    var parentID        = $('#parent').val();
    var currentBranches = $('#branch').val() ? $('#branch').val().toString() : '';
    $.post(createLink('productplan', 'ajaxGetParentBranches', "productID=" + productID + "&parentID=" + parentID + "&currentBranches=" + currentBranches), function(data)
    {
        $('#branch').replaceWith(data);
        $('#branch_chosen').remove();
        $('#branch').chosen();
        $('#branch').change();
    })
}
