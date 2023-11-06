/**
 * 预览选中的图表。
 * Preview the selected charts.
 *
 * @access public
 * @return void
 */
function previewCharts()
{
    const checkedList = $('#moduleMenu ul').zui('tree').$.getChecks();
    if(checkedList.length > 0)
    {
        const form = new FormData();
        checkedList.forEach((itemKey, index) => {
            if(itemKey.includes(':') && itemKey.includes('_'))
            {
                const keys = itemKey.split(':')[1].split('_');
                form.append('charts[' + index + '][groupID]', keys[0]);
                form.append('charts[' + index + '][chartID]', keys[1]);
            }
        });

        postAndLoadPage(previewUrl, form, '#chartPanel');
    }
}
