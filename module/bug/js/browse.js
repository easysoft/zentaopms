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

    var treeMaxHeight = document.documentElement.clientHeight - $('#header').height() - $('#featurebar').height() - $('#footer').height() - $('#treebox .side-body .panel-heading').height() - 120;
    if($('#treebox').height() > $('#bugList').height()) treeMaxHeight -= 20;
    $('#treebox .side-body .panel-body').children().each(function(){if(!$(this).hasClass('tree')) treeMaxHeight -= $(this).height()});
    $('#treebox .tree').css({'max-height':treeMaxHeight, 'overflow-y': 'auto', 'overflow-x':'hidden'})
    if(moduleID > 0)$('#treebox .tree').scrollTop($('#treebox .tree #module' + moduleID).offset().top - $('#treebox .tree li[data-id="1"]').offset().top);
});
