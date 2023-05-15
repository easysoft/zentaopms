function switchMode(mode)
{
    $.post($.createLink('custom', 'mode'), {mode}, function(result)
    {
        parent.location.reload();
    });
}
$('.mode-switch .block').on('click', '.mode-block.state', function()
{
    const mode = $(this).data('mode');
    zui.Modal.confirm({message: changeModeTips, onResult: function(result)
    {
        if(result) switchMode(mode);
    }});
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

