/**
 * 预览选中的图表。
 * Preview the selected charts.
 *
 * @access public
 * @return void
 */
function previewCharts()
{
    const form   = new FormData();
    const checks = $('#moduleMenu ul').zui('tree').$.getChecks();

    checks.forEach((id) => {
        if(id.includes(':')) form.append('charts[]', id.split(':')[1]);
    });

    postAndLoadPage(previewUrl, form);
}
