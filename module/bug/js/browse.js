$(document).ready(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();

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
    setTimeout(function(){fixedTfootAction('#bugForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#bugList')}, 100);

    if(flow == 'onlyTest')
    {
        $('#modulemenu > .nav').append($('#featurebar').html());
        toggleSearch();

        $(".export").modalTrigger({width:650, type:'iframe'});

        $('#modulemenu > .nav > li').removeClass('active');
        $('#modulemenu > .nav > li[data-id=' + browseType + ']').addClass('active');
    }
});
