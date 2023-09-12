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
        const modalTrigger = '<button type="button" class="btn edit-trigger hidden" data-toggle="modal" data-type="ajax" data-url=' + url + ' data-data-type="html"></button>';
        result.push({html: modalTrigger});
    }

    return result;
}

window.confirmEdit = function(metricID, isOldMetric)
{
    isOldMetric = isOldMetric == 'true' ? true : false;
    if(isOldMetric)
    {
        zui.Modal.confirm(upgradeTip).then((result) =>
        {
            if(result)
            {
                $('.edit-trigger').trigger('click');
            }
            else
            {
                openUrl($.createLink('metric', 'browse', 'scope=' + scope));
            }
        })
    }
    else
    {
        $('.edit-trigger').trigger('click');
    }
}
