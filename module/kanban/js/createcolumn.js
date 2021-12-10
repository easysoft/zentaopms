$(function()
{
    initColorPicker();
    $('#noLimit').click(function()
    {   
        if($(this).attr('checked') == 'checked')
        {   
            $('#WIPCount').val('');
            $('#WIPCount').attr('disabled', true);
        }   
        else
        {   
            $('#WIPCount').removeAttr('disabled');
        }   
    })  
})

function setWIPLimit()
{
    var count = $('#WIPCount').val();
    if($('#noLimit').attr('checked') == 'checked') count = -1;

    $('#limit').val(count);
}
