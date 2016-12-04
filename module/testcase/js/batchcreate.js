$(document).ready(function()
{
    removeDitto();//Remove 'ditto' in first row.

    $(document).on('click', '.chosen-with-drop', function()
    {
        var select = $(this).prev('select');
        var id     = $(select).attr('id');
        if(id.indexOf('story') != -1)
        {
            index  = id.substring(5);
            module = $('#module' + index).val();
            branch = $('#branch' + index).val();
            if(module == 'ditto')
            {
                for(var i = index - 1; i >=0; i--)
                {
                    if($('#module' + i).val() != 'ditto')
                    {
                        module = $('#module' + i).val();
                        break;
                    }
                }
            }
            link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + module + '&storyID='+ $(select).val() + '&onlyOption=true&status=noclosed');
            $('#story' + index).load(link, function(){$(this).trigger("chosen:updated");});
        }
        if($(select).val() == 'ditto')
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
            $(select).val(value);
            $(select).trigger("chosen:updated");
        }
    });

    $(document).on('mousedown', 'select', function()
    {
        if($(this).val() == 'ditto')
        {
            var index = $(this).closest('td').index();
            var row   = $(this).closest('tr').index();
            var table = $(this).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
            $(this).val(value);
        }
    });
});

/**
 * Set modules.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
function setModules(branchID, productID, num)
{
    moduleLink = createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=story&branch=' + branchID + '&num=' + num);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="module' + num + '" name="module[' + num + ']" class="form-control"></select>';
        $('#module' + num).replaceWith(modules);
        $("#module" + num + "_chosen").remove();
        $("#module" + num).chosen(defaultChosenOptions);
    });
}
