$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();

    if($('#taskList thead th.w-name').width() < 150) $('#taskList thead th.w-name').width(150);
});

function setQueryBar(queryID, title)
{
    var $tagTab = $('#featurebar #calendarTab').size() > 0 ? $('#featurebar #calendarTab') : $('#featurebar #kanbanTab');
    $tagTab.before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('project', 'task', "projectID=" + projectID + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
