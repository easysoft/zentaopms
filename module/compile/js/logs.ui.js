window.refreshLogs = function()
{
    $.ajaxSubmit({
        url: $.createLink('ci', 'checkCompileStatus', 'compileID=' + buildID),
        onComplete: function() {
            loadTarget($.createLink('compile', 'logs', 'compileID=' + buildID));
        }
    });
}
