function switchMode(mode)
{
    $.post($.createLink('custom', 'mode'), {mode}, function(result)
    {
        parent.location.reload();
    });
}

function switchVision(vision)
{
    $.get($.createLink('my', 'ajaxSwitchVision', 'vision=' + vision), function(result)
    {
        parent.location.reload();
    })
}

$('.mode-switch .block').on('click', '.mode-block.state', function()
{
    const mode = $(this).data('mode');
    zui.Modal.confirm({message: changeModeTips, onResult: function(result)
    {
        if(result) switchMode(mode);
    }});
})

$('.vision-switch .block').on('click', '.vision-block.state', function()
{
    const vision = $(this).data('vision');
    switchVision(vision);
})

$('.theme-switch .block').on('click', '.theme-block.state', function()
{
    selectTheme($(this).attr('data-theme'));
})


$('.guide-block').on('click', '.guide-tab', function()
{
    const tab = $(this).data('tab');
    localStorage.setItem('guideblock', tab);
})

if(localStorage.getItem('guideblock'))
{
    const tab = localStorage.getItem('guideblock'); 
    $('.guide-block a.guide-tab[data-tab=' + tab + ']').trigger('click');;
}

