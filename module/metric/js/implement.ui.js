var methods = [];
$(document).ready(function()
{
    if(!isVerify) verify();
});

function verify(method)
{
    if(!method)
    {
        methods = Object.keys(verifyCustomMethods);
        $('.verify-content').empty();
        method = methods.shift();
    }

    verifyStep(method, function(status, error){
        if(!status) return;

        if(methods.length == 0)
        {
            var url = $.createLink('metric', 'implement', 'metricID=' + metricId + '&isVerify=true');
            var modal = $('.verify-content').closest('.modal');
            modalId = modal.attr('id');
            setTimeout(function(){
                openUrl(url, {load: 'modal', target: modalId});
            }, 1000);
            return;
        }

        method = methods.shift();
        verify(method);
    });
}

function verifyStep(step, callback)
{
    var url = $.createLink('metric', 'ajaxCheck', 'code=' + code + '&step=' + step);
    $.get(url, function(resp){
        resp = JSON.parse(resp);
        var status = resp[0];
        var error  = resp[1];

        var $html = genVerifyResult(verifyCustomMethods[step].text, status, error);
        $('.verify-content').append($html);
        if(typeof callback == 'function') callback(status, error);
    });
}

function genVerifyResult(tip, status, error)
{
    var sentenceClass = status ? 'pass' : 'error';
    var iconClass     = status ? 'check' : 'close';
    var html = '<p class="verify-sentence ' + sentenceClass + '">';
    html += tip;
    html += '<i class="icon icon-' + iconClass + '"></i>';
    if(error && error.length) html += '<input class="bg-danger ml-5 ellipsis w-96" value="' + error  + '"/>';
    html += '</p>';
    return $(html);
}
