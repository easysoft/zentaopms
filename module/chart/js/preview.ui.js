/**
 * 预览选中的图表。
 * Preview the selected charts.
 *
 * @access public
 * @return void
 */
function previewCharts()
{
    const checks = $('#moduleMenu ul').zui('tree').$.getChecks();
    if(checks.length > 0)
    {
        const form = new FormData();
        checks.forEach((id) => {
            if(id.includes(':')) form.append('charts[]', id.split(':')[1]);
        });

        postAndLoadPage(previewUrl, form, '#chartPanel');
    }
}
