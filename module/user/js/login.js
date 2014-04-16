function setForm(){}
$(document).ready(function()
{
    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    $('#mobile').popover({html: true, container: 'body'});
})
