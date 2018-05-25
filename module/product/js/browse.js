$(function()
{
    $('.popoverStage').mouseover(function(){$(this).popover('show')});
    $('.popoverStage').mouseout(function(){$(this).popover('hide')});

    if($('#storyList thead th.c-title').width() < 150) $('#storyList thead th.c-title').width(150);
});
