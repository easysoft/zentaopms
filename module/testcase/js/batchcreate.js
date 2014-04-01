$(document).ready(function()
{
    for(var i = 0; i < testcaseBatchCreateNum; i++) 
    {
        $("#story" + i).chosen(defaultChosenOptions).bind('liszt:showing_dropdown', function()
        {
            index  = $(this).attr('id').substring(5);
            module = $('#module' + index).val();
            if(module == 'same')
            {
                for(var i = index - 1; i >=0; i--)
                {
                    if($('#module' + i).val() != 'same')
                    {
                        module = $('#module' + i).val();
                        break;
                    }
                }
            }
            link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleID=' + module + '&storyID='+ $(this).val() + '&onlyOption=true');
            $(this).load(link, function()
            {
                $('#story' + index).trigger("liszt:updated");
            });
        });
    }
});
