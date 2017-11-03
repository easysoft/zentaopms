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

    if($('#taskList thead th.w-name').width() < 150) $('#taskList thead th.w-name').width(150);
});

function setQueryBar(queryID, title)
{
    var $tagTab = $('#featurebar #calendarTab').size() > 0 ? $('#featurebar #calendarTab') : $('#featurebar #kanbanTab');
    $tagTab.before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('project', 'task', "projectID=" + projectID + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
