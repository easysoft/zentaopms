$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
    setTimeout(function(){fixedTfootAction('#projectTaskForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#taskList')}, 100);

    $('.dropdown-menu .with-search .menu-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var val = $(this).val().toLowerCase();
        var $options = $(this).closest('.dropdown-menu.with-search').find('.option');
        if(val == '') return $options.removeClass('hide');
        $options.each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toString().toLowerCase().indexOf(val) < 0 && $option.data('key').toString().toLowerCase().indexOf(val) < 0);
        });
    });
});

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
