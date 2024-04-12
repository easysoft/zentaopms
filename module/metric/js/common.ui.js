window.addUnit = function(e)
{
    if($(e.target).prop('checked'))
    {
        var $picker = $('#unitBox').find('.picker').zui('picker').$;
        if($picker.state.open) $picker.toggle();

        setTimeout(function()
        {
          $('#unitBox').addClass('hidden');
          $('#addUnitBox').removeClass('hidden');
          $("[name^='customUnit']").prop('checked', true);

        }, 150);
    }
    else
    {
        $('#unitBox').removeClass('hidden');
        $('#addUnitBox').addClass('hidden');
        $("[name^='customUnit']").prop('checked', false);
    }
}

window.renderDTableCell = function(result, {row, col})
{
    if(Array.isArray(row.data[col.name]))
    {
        var value = row.data[col.name][0];

        var calculatedDate = row.data[col.name][1];
        var calcType       = row.data[col.name][2];
        var calculatedBy   = row.data[col.name][3];
        var title = calcTitleList[calcType].replace('%user%', calculatedBy);
        title = title.replace('%date%', calculatedDate);
    }
    else
    {
         var title = value = row.data[col.name];
    }
    var html  = `<span class="cell-ellipsis" style="font-size: 12px" title="${title}">${value}</span>`;
    result[0] = {html: html};

    return result;
}
