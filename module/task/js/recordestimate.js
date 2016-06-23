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
            var hasRecord = false;
            $('#recordForm').find('input[name^="dates"], input[name^="consumed"], input[name^="left"], textarea[name^="work"]').each(function()
            {
                if($(this).val() !== '')
                {
                    hasRecord = true;
                    return false;
                }
            });
            if(hasRecord)
            {
                alert(noticeSaveRecord);
                return false;
            }
        });
    });
})
