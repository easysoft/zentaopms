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
}
