$(document).ready(function()
{
    removeDitto();//Remove 'ditto' in first row.
    if($('#batchCreateForm table thead tr th.c-title').width() < 170) $('#batchCreateForm table thead tr th.c-title').width('170');

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });

    $('#customField').click(function()
    {
        $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
        {
            var field    = ',' + $(this).val() + ',';
            var required = ',' + requiredFields + ',';
            if(required.indexOf(field)  >= 0) $(this).attr('disabled', 'disabled');
        });
    });

    $('#formSettingForm .btn-primary').click(function()
    {
        $('#formSettingForm > .checkboxes > .checkbox-primary > input').removeAttr('disabled');
    });
});
