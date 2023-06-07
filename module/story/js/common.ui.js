$(function()
{
    var $saveButton      = $('#saveButton');
    var $saveDraftButton = $('#saveDraftButton');
    $(document).on('click', '#saveButton', function(e)
    {
        $saveButton.attr('type', 'submit').attr('disabled', 'disabled');
        $saveDraftButton.attr('type', 'button').attr('disabled', 'disabled');

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
        if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
        $('#dataform #status').val(storyStatus);
        $(this).submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.attr('type', 'button').removeAttr('disabled');
                    $saveDraftButton.removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveDraftButton.removeAttr('disabled');
            }
        }, 100);
    })


    $(document).on('click', '#saveDraftButton', function(e)
    {
        $saveButton.attr('type', 'button').attr('disabled', 'disabled');
        $saveDraftButton.attr('type', 'submit').attr('disabled', 'disabled');

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
        $('#dataform #status').val(storyStatus);
        $(this).submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveDraftButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.removeAttr('disabled');
                    $saveDraftButton.attr('type', 'button').removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveButton.removeAttr('disabled');
            }
        }, 100);
    });
})


