/**
 * 提示并下架度量项。
 * Delist metric with tips.
 *
 * @param  int    metricID
 * @param  string metricName
 * @access public
 * @return void
 */
window.confirmDelist = function(metricID, metricName)
{
    zui.Modal.confirm(confirmDelist.replace('%s', metricName)).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('metric', 'delist', 'metricID=' + metricID)});
    });
};

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
        metricHtml += '<div><span class="label light-pale" data-toggle="tooltip" data-title="SQL" data-placement="bottom" data-type="white" data-class-name="text-gray border border-light">' + metricSql + '</span></div>';
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
