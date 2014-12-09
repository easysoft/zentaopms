$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();

    $('.assign-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var val = $(this).val().toLowerCase();
        if(val == '') return $('.assign-menu > .option').removeClass('hide');
        $('.assign-menu > .option').each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toLowerCase().indexOf(val) < 0 || $option.data('key').toLowerCase().indexOf(val) < 0);
        });
    });
})
