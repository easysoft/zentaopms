function redirect(ranzhiURL, ranzhiCode)
{
    var notInIframe = (self == parent);
    var entryUrl    = encodeURIComponent(window.location.href);
    if(notInIframe) location.href = ranzhiURL + '/sys/index.php?entryID=' + ranzhiCode + '&entryUrl=' + entryUrl;
}

function setOuterBox()
{
    var side   = $('#wrap .outer > .side');
    var resetOuterHeight = function()
    {
        var sideH  = side.length ? (side.outerHeight() + $('#featurebar').outerHeight() + 20) : 0;
        var height = Math.max(sideH, $(window).height() - $('#header').outerHeight() || 0) - 20;
        if(navigator.userAgent.indexOf("MSIE 8.0") >= 0) height -= 40;
        $('#wrap .outer').css('min-height', height);
    }

    side.resize(resetOuterHeight);
    $(window).resize(resetOuterHeight);
    resetOuterHeight();
}

$(function()
{
    $('#searchbox').css('right', $('#topnav').width() - 20);
})
