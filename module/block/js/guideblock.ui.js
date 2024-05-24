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

$('.mode-switch .block.state').on('click', '.mode-block', function()
{
    const mode = $(this).data('mode');
    zui.Modal.confirm({message: changeModeTips, onResult: function(result)
    {
        if(result) switchMode(mode);
    }});
})

$('.vision-switch .block.state').on('click', '.vision-block', function()
{
    const vision = $(this).data('vision');
    switchVision(vision);
})

$('.theme-switch .block').off('click').on('click', '.theme-block', function()
{
    $(this).closest('.tab-pane').find('.block').removeClass('active');
    selectTheme($(this).attr('data-theme'));
    $(this).closest('.block').addClass('active');
})
