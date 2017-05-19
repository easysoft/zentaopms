// Prevent login page show in a iframe modal
if(window.self !== window.top) window.top.location.href = window.location.href;

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
        $(document).one('click', function(){$('#mobile').popover('hide');});
    });
    mBtn.attr('title', mBtn.attr('data-original-title'));
    $('#login-form form').submit(function()
    {
        var password = $('input:password').val();
        if(password.length != 32 && typeof(md5) == 'function') $('input:password').val(md5(password));
    });
})
