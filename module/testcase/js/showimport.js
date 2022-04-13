$("[name^='product']").each(function()
{
    var id = $(this).attr('id');
    $(this).attr('id', 'product' + id.match(/\d+/));
});

$("[name^='branch']").each(function()
{
    var id = $(this).attr('id');
    $(this).attr('id', 'branch' + id.match(/\d+/));
});

$("[name^='story']").each(function()
{
    var id        = $(this).attr('id');
    var num       = id.substring(5);
    var productID = $('#product' + num).val();
    var branchID  = $('#branch' + num).val();
    var moduleID  = $('#module' + num).val();
    var storyID   = $("#story" + num).val();
    var storyLink = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1&executionID=0&number=' + num);
    $.get(storyLink, function(stories)
    {
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        $('#story' + num).replaceWith(stories);
        $('#story' + num + "_chosen").remove();
        $('#story' + num).next('.picker').remove();
        $('#story' + num).attr('name', 'story[' + num + ']').chosen();
        $('#story' + num).val(storyID).trigger('chosen:updated');
    });
});
