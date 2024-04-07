

window.onRenderCell = function(result, {row, col})
{
    if(col.name == 'id')
    {
        const url = $.createLink('metric', 'edit', 'id=' + row.data.id);
        const btnClass = 'class="btn hidden edit-trigger-' + row.data.id + '"';
        const modalTrigger = '<button type="button" ' + btnClass + ' data-toggle="modal" data-type="ajax" data-url=' + url + ' data-data-type="html"></button>';
        result.push({html: modalTrigger});
    }

    if(col.name == 'name' && row.data.type == 'sql')
    {
        var metricHtml = '<div class="dtable-name-flex">';
        metricHtml += '<div><a href="' + $.createLink('metric', 'view', 'metricID=' + row.data.id) + '">' + row.data.name + '</a></div>';
        metricHtml += '<div><span class="label light-pale" title="' + implementType + '" data-placement="bottom" data-type="white" data-class-name="text-gray border border-light" onmouseover="event.stopPropagation();">' + metricSql + '</span></div>';
        metricHtml += '</div>';

        result[0] = {html: metricHtml};
    }

    return result;
}

window.confirmEdit = function(metricID, isOldMetric)
{
    isOldMetric = isOldMetric == 'true' ? true : false;
    const triggerClass = '.edit-trigger-' + metricID;
    if(isOldMetric)
    {
        zui.Modal.confirm(upgradeTip).then((result) =>
        {
            if(result)
            {
                $(triggerClass).trigger('click');
            }
            else
            {
                openUrl($.createLink('metric', 'browse', 'scope=' + scope));
            }
        })
    }
    else
    {
        $(triggerClass).trigger('click');
    }
}

window.confirmDelist = function(metricID, metricName, isUsed = false)
{
    var text = isUsed ? confirmDelistInUsed : confirmDelist;
  console.log(isUsed)
    zui.Modal.confirm(text.replace('%s', metricName)).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('metric', 'delist', 'metricID=' + metricID)});
    });
};

window.getCurrentModal = function()
{
    target = zui.Modal.query().id;
    target = `#${target}`;

    return zui.Modal.query(target);
}

window.loadImplement = function(link)
{
    const modal = window.getCurrentModal();
    if(!modal) return;

    $("#" + modal.id).attr('load-url', link);
    modal.render({url: link});
}

window.confirmRecalculate = function(calcRange= 'all', code = '')
{
    zui.Modal.confirm({
      message: confirmRecalculate,
      icon: 'icon-exclamation-sign',
      iconClass: 'warning-pale rounded-full icon-2x'}).then((res)=>
        {
            if(res) zui.Modal.open({url: $.createLink('metric', 'recalculateSetting', 'calcRange=' + calcRange + '&code=' + code)});
        });
}
