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

    var treeMaxHeight = document.documentElement.clientHeight - $('#header').height() - $('#featurebar').height() - $('#footer').height() - $('#taskTree .side-body .panel-heading').height() - 120;
    if($('#taskTree').height() > $('#taskList').height()) treeMaxHeight -= 20;
    $('#taskTree .side-body .panel-body').children().each(function(){if(!$(this).hasClass('tree')) treeMaxHeight -= $(this).height()});
    $('#taskTree .tree').css({'max-height':treeMaxHeight, 'overflow-y': 'auto', 'overflow-x':'hidden'})
    if(moduleID > 0)$('#taskTree .tree').scrollTop($('#taskTree .tree #module' + moduleID).offset().top - $('#taskTree .tree li[data-id="1"]').offset().top);
});

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
