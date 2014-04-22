function setForm(){}
$(document).ready(function()
{
    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    $('#mobile').popover({html: true, container: 'body'}).click(function(event)
    {
        event.stopPropagation();
        $(document).one('click', function()
        {
            $('#mobile').popover('hide');
        });
    });

})
