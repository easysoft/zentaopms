$(function()
{
    $('.form-date').datetimepicker('setEndDate', today);

    $("#recordForm").submit(function()
    {
        $('#recordForm .left').each(function()
        {
            if($(this).val() !== '') left = $(this).val();
        });
        if(left == '0')
        {
            var confirmMsg = confirm(confirmRecord);
            if(confirmMsg == false)
            {
                $('#submit').attr("disabled", false);
                return false;
            }
        }
    });

    $('#recordForm .showinonlybody').each(function()
    {
        $(this).click(function()
        {
            var hasRecord = false;
            $('#recordForm').find('input[name^="consumed"], input[name^="left"], textarea[name^="work"]').each(function()
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
