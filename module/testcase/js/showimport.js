$(document).on('mouseup', '[id^=story].chosen-with-drop', function()
{
    var select = $(this).prev('select');
    var id     = $(select).attr('id');
    var index    = id.substring(5);
    var moduleID = $('#module' + index).val();
    var branch   = $('#branch' + index).length > 0 ? $('#branch' + index).val() : 0;
    if(moduleID == 'ditto')
    {
        for(var i = index - 1; i >=0; i--)
        {
            if($('#module' + i).val() != 'ditto')
            {
                moduleID = $('#module' + i).val();
                break;
            }
        }
    }
    var link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID='+ ($(select).val() || '0') + '&onlyOption=true&status=noclosed&limit=0&type=null');
    var $story = $('#story' + index);
    if($story.data('loadLink') !== link)
    {
        $story.load(link, function(){$story.data('loadLink', link).trigger("chosen:updated");});
    }
});
