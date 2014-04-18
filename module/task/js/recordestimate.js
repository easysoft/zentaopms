$(function()
{
    $("#recordForm").submit(function()
    {
        $('#recordForm .left').each(function()
        {
            if($(this).val() !== '') left = $(this).val();
        });
        if(left === '0') return confirm(confirmRecord);
    });
})

