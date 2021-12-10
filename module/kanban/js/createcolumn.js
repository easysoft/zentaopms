$(document).ready(function()
{
    $.setAjaxForm('#createColumnForm');

    initColorPicker();
    
    $(document).on('click', '#noLimit', function()
    {
        if($(this).prop('checked'))
        {
            $(this).parents('td').find('input[name^=limit]').val('');
            $(this).parents('td').find('input[name^=limit]').attr('readonly', true);
        }
        else
        {
            $(this).parents('td').find('input[name^=limit]').removeAttr('readonly');
        }
    });
});

function setWIPLimit()
{
    var count = $('#WIPCount').val();
    if($('#noLimit').attr('checked') == 'checked') count = -1;;

    $('#limit').val(count);
}
