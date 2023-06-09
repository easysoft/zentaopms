$(function()
{
    var $saveButton      = $('#saveButton');
    var $saveDraftButton = $('#saveDraftButton');
    $(document).on('click', '#saveButton', function(e)
    {
        $saveButton.attr('disabled', 'disabled');
        $saveDraftButton.attr('disabled', 'disabled');

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
        if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
        $('#dataform #status').val(storyStatus);

        $dataform = $('#dataform');
        $.ajaxSubmit(
        {
            data: new FormData($dataform[0]),
            url:$dataform.attr('action'),
            onSuccess: function(result)
            {
                location.href = result.load;
            },
        });

        e.preventDefault();

        setTimeout(function()
        {
            if($saveButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.removeAttr('disabled');
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
        $saveButton.attr('disabled', 'disabled');
        $saveDraftButton.attr('disabled', 'disabled');

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
        $('#dataform #status').val(storyStatus);

        $dataform = $('#dataform');
        $.ajaxSubmit(
        {
            data: new FormData($dataform[0]),
            url:$dataform.attr('action'),
            onSuccess: function(result)
            {
                location.href = result.load;
            },
        });

        e.preventDefault();

        setTimeout(function()
        {
            if($saveDraftButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.removeAttr('disabled');
                    $saveDraftButton.removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveButton.removeAttr('disabled');
            }
        }, 100);
    });
})


