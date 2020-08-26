$(document).ready(function() 
{
    if(config.onlybody != 'yes')$(".runCase").modalTrigger({width:'90%', type:'iframe', afterHide:function(){parent.location.href=parent.location.href;}});
    if(config.onlybody != 'yes')$(".results").modalTrigger({width:'90%', type:'iframe'});

    var isFullscreen = $.cookie('caseFullscreen') == 'true';
    $('body').toggleClass('case-fullscreen', isFullscreen);
    $('.side-col').toggleClass('hidden', isFullscreen);
    $('#mainMenu .fullscreen-btn').attr('title', isFullscreen ? retrack : fullscreen);
    $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + (isFullscreen ? retrack : fullscreen));

    $('#mainMenu .fullscreen-btn').click(function()
    {
        $('.side-col').toggleClass('hidden');
        $('body').toggleClass('case-fullscreen');

        var isFullscreen = $('body').hasClass('case-fullscreen');
        $('#mainMenu .fullscreen-btn').attr('title', isFullscreen ? retrack : fullscreen);
        $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + (isFullscreen ? retrack : fullscreen));
        $.cookie('caseFullscreen', isFullscreen);

        setTimeout($.resetToolbarPosition, 50);
    });
})
