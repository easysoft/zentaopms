function setForm(){}
$(document).ready(function()
{
    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    var mBtn = $('#mobile');
    mBtn.popover({html: true, container: 'body'}).click(function(event)
    {
        event.stopPropagation();
        $(document).one('click', function()
        {
            $('#mobile').popover('hide');
        });
    });
    mBtn.attr('title', mBtn.attr('data-original-title'));

})
