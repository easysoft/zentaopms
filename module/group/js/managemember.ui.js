$(function()
{
    $(document).on('change', 'input.check-all', function()
    {
        const checked = $(this).prop('checked');
        $(this).parents('.form-row').find('input[type=checkbox]').prop('checked', checked);
    });

    $(document).on('change', 'input[name^=members]', function()
    {
        let checked = true;
        $(this).closest('.form-group').find('input[name^=members]').each(function()
        {
            if(!$(this).prop('checked')) checked = false;
        });

        $(this).closest('.form-row').find('input.check-all').prop('checked', checked);
    });
});
