window.renderHeight = function()
{
    return $('.table-side').height();
}

$(document).ready()
{
    renderDTable();
    $(document).on('change', '.checkbox-primary input[type="checkbox"]', function()
    {
        $('.checkbox-primary input[type="checkbox"]').each(function()
        {
            if($(this).is(":checked")) $(this).closest('.checkbox-primary').addClass('metric-current');
            if($(this).is(":not(:checked)")) $(this).closest('.checkbox-primary').removeClass('metric-current');
        });
    });
}

function renderDTable()
{
    $('.dtable').empty();

    if(!resultHeader || !resultData) return;
    new zui.DTable('.dtable',
    {
        responsive: true,
        bordered: true,
        scrollbarHover: true,
        height: function() { return $('.table-side').height(); },
        cols: resultHeader,
        data: resultData,
    });

}
