$(document).ready(function()
{
    $(document).on('click', '.chosen-with-drop', function()
    {
        var select = $(this).prev('select');
        var id     = $(select).attr('id');
        if(id.indexOf('story') != -1)
        {
            index  = id.substring(5);
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
            link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleID=' + module + '&storyID='+ $(select).val() + '&onlyOption=true&status=noclosed');
            $('#story' + index).load(link, function(){$(this).trigger("chosen:updated");});
        }
        if($(select).val() == 'same')
        {
            var index = $(select).parents('td').index();
            var row   = $(select).parents('tr').index();
            var table = $(select).parents('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'same') break;
            }
            $(select).val(value);
            $(select).trigger("chosen:updated");
        }
    });

    $(document).on('mousedown', 'select', function()
    {
        if($(this).val() == 'same')
        {
            var index = $(this).parents('td').index();
            var row   = $(this).parents('tr').index();
            var table = $(this).parents('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'same') break;
            }
            $(this).val(value);
        }
    });
});
