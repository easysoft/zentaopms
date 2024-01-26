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

$(document).on('click', '.modal-actions > button', function()
{
    loadCurrentPage();
})
