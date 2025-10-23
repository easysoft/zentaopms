$(".form-actions .btn[type='submit']").on('click', function(e, hasConfirmed)
{
    if(hasConfirmed) return true;

    var $this = $(this);
    $("input[name^='left']").each(function()
    {
        if($(this).val() !== '' && !$(this).prop('readonly')) left = $(this).val();
    });

    if(typeof(left) != 'undefined' && left == '0')
    {
        e.preventDefault();
        zui.Modal.confirm(confirmRecord).then(
            confirmed =>
            {
                if(confirmed) $this.trigger('click', true);
            }
        );
    }
});

$(document).on('click', '.modal-dialog[id^=modal-record-hours-task] .modal-actions > button', function()
{
    loadCurrentPage();
})

window.toggleFold = function()
{
    const fold  = $('#toggleFoldIcon').find('.angle-down').length > 0;
    const $icon = $('#toggleFoldIcon').find('.icon-toggle');
    const $text = $('#toggleFoldIcon').find('.text');

    $.cookie.set('taskEffortFold', fold ? 0 : 1, {expires:config.cookieLife, path:config.webRoot});

    if(fold)
    {
        /* Update icon and text. */
        $icon.removeClass('angle-down').addClass('angle-top');
        $text.text(foldEffort);

        /* Show all efforts. */
        $('.taskEffort > tbody > tr').removeClass('hidden');
    }
    else
    {
        $icon.removeClass('angle-top').addClass('angle-down');
        $text.text(unfoldEffort);

        /* Efforts whose number is greater than 3 are hidden. */
        $('.taskEffort > tbody').children('tr:nth-child(n+5)').addClass('hidden');
    }
}
