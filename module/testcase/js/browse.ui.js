/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checks
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    if(checks.length)
    {
        runCaseCount = 0;
        checks.forEach((id) => {
            const scene = element.getRowInfo(id).data;
            if(scene.isCase == 1 && scene.lastRunResult != '') runCaseCount++;
        });
        return zui.formatString(checkedSummary, {
            checked: checks.length,
            run: runCaseCount
        });
    }

    return pageSummary;
}
