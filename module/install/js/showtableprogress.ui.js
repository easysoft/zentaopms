window.showProgress = function(offset)
{
    $.getJSON($.createLink('install', 'ajaxShowProgress', 'offset=' + offset), function(data)
    {
        if(!$('.toolbar .toolbar-item.next').hasClass('disabled')) return;
        if(data.log) $('#progress').append(data.log);

        var element = document.getElementById('progress');
        if(element.scrollHeight > 20000) element.innerHTML = element.innerHTML.substr(60000); // Remove old log.
        element.scrollTop = element.scrollHeight;

        if(data.error == '' && data.finish == '') return window.showProgress(data.offset);

        if(data.error) $('#progress').append("<div class='text-danger text-lg font-bold'>" + data.error + '</div>');
        if(data.finish)
        {
            $('#progress').append("<div class='text-success text-lg font-bold'>" + dbFinish + "</div>");
            $('.toolbar .toolbar-item.next').removeClass('disabled');
        }
        element.scrollTop = element.scrollHeight;
    });
}

$(document).on('click', '.next:not(.disabled)', function()
{
    location.href = $.createLink('install', 'step3');
})

$.get($.createLink('install', 'ajaxCreateTable'));
window.showProgress(0);
