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

    $('#recordForm .showinonlybody').each(function()
    {
        $(this).click(function()
        {
            var saveRecord = false;
            $('#recordForm .form-date').each(function()
            {
                if($(this).val() !== '')
                {
                    saveRecord = confirm(confirmSaveRecord);
                    return false;
                }
            });
            if(saveRecord)
            {
                $.cookie('reload2Parent', true, {path:config.webRoot});
                return false;
            }
        });
    });
})
