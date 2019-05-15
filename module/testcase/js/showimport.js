$(document).on('change', '.moduleChange', function()
{
    var moduleID = $(this).val();
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    var id    = $(this).attr('id');
    var index = id.substring(6);
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID='+ '' + '&onlyOption=true&status=noclosed');
    $('#story' + index).load(link, function(){$(this).trigger("chosen:updated");});
})

$(document).on('change', '.storyChange', function()
{
     var storyID = $(this).val();
     if(typeof(storyID) == 'undefined') storyID = 0;
     var link  = createLink('testcase', 'ajaxGetStoryModule', 'storyID=' + storyID);
     var id    = $(this).attr('id');
     var index = id.substring(5);
     $.get(link, function(json)
     {
        var obj = JSON.parse(json);
        $("#module" + index).val(obj.moduleID);
        $('#module' + index).trigger("chosen:updated");
     })
})
