window.reportSelectAllFields = function(btnEle)
{
    for (const [key, ele] of Object.entries($(btnEle).closest('form').find('*').children('input'))) {
        if(key === 'length')
        {
            continue;
        }

        $(ele).prop('checked', true);
    }
};

window.reportSubmit = function(btnEle)
{
    const $submitBtn = $(btnEle);
    const form       = $submitBtn.closest('form')[0];
    const formData   = new FormData(form);
    const firstValue = formData.values().next();

    /* Verify if the report fields have been selected. */
    if(firstValue.done)
    {
        return;
    }

    $submitBtn.prop('disabled', true);

    postAndLoadPage($(form).prop('action'), formData, ["#mainPanel"]);
};

$('div.tab-pane').on('show', function(event)
{
    $(event.target).find('*').children('canvas').each(function(idx, canvas)
    {
        zui.ECharts.query(canvas).chart.resize();
    });
});
