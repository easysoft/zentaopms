window.customSubmit = function(e)
{
    const $saveButton      = $('#saveButton');
    const $saveDraftButton = $('#saveDraftButton');

    $saveButton.attr('disabled', 'disabled');
    $saveDraftButton.attr('disabled', 'disabled');

    var $this = $(e.target);
    if($this.prop('tagName') != 'BUTTON') $this = $this.closest('button');

    var storyStatus = !$('#reviewer').val().join(',') || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
    if($this.attr('id') == 'saveDraftButton')
    {
        storyStatus = 'draft';
        if(config.currentMethod == 'change') storyStatus = 'changing';
        if(config.currentMethod == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
    }
    if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
    $('#dataform #status').val(storyStatus);

    $dataform = $('#dataform');
    $.ajaxSubmit(
    {
        data: new FormData($dataform[0]),
        url: $dataform.attr('action'),
        onSuccess: function(result) {loadPage(result.load)},
        onMessage: function(message) {showMessage(message)},
        onFail: function(result)
        {
            setTimeout(function()
            {
                $saveButton.removeAttr('disabled');
                $saveDraftButton.removeAttr('disabled');
            }, 500);
        },
    });

    e.stopPropagation();
    e.preventDefault();

    setTimeout(function()
    {
        $saveButton.removeAttr('disabled');
        $saveDraftButton.removeAttr('disabled');
    }, 10000);
};

function showMessage(message)
{
    var varType = typeof message;
    if(varType === 'object')
    {
        for(id in message)
        {
            var $this = $('#' + id);
            if($this.length == 0) return zui.Messager.show({"content": message[id], "type": "success circle"});

            $('#' + id + 'Tip').remove();
            $this.addClass('has-error');
            $this.after("<div class='form-tip ajax-form-tip text-danger' id='" + id + "Tip'>" + message[id] + '</div>');
            document.getElementById(id).focus();
        }
    }
    if(varType === 'string') zui.Messager.show({"content": message, "type": "success circle"});
}
