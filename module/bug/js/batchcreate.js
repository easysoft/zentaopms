$(function()
{
    $(".chosenBox select").chosen(defaultChosenOptions);
})

/**
 * Load project builds 
 * 
 * @param  int $productID 
 * @param  int $projectID 
 * @param  int $index 
 * @access public
 * @return void
 */
function loadProjectBuilds(productID, projectID, index)
{
    if(projectID)
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + "&varName=openedBuilds&build=''&index=" + index);
    }
    else
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=''&index=" + index);
    }

    $.get(link, function(builds)
    {
        var row = $('#buildBox' + index).parents('tbody').find('tr').size()
        do
        {
            var selected = $('#buildBox' + index).find('select').val();
            $('#buildBox' + index).html(builds);
            $('#buildBox' + index).find('select').val(selected);
            $('#openedBuilds' + index + '_chosen').remove();
            $('#buildBox' + index + ' select').removeClass('select-3');
            $('#buildBox' + index + ' select').addClass('select-1');
            $('#buildBox' + index + ' select').attr('name','openedBuilds[' + index + '][]');
            $('#buildBox' + index + ' select').attr('id','openedBuilds' + index);
            $('#buildBox' + index + ' select').chosen(defaultChosenOptions);

            index++;
            if($('#projects' + index).val() != 'ditto') break;
        }while(index < row)
    });
}

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).parents('td').index();
        var row   = $(select).parents('tr').index();
        var table = $(select).parents('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
})
$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).parents('td').index();
        var row   = $(this).parents('tr').index();
        var table = $(this).parents('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(this).val(value);
    }
})

if(navigator.userAgent.indexOf("Firefox") < 0)
{
    $(document).on('input keyup paste change', 'textarea.autosize', function()
    {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight + 2) + "px"; 
    });
}
