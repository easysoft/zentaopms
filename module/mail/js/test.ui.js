window.sendTest = function()
{
    $.post($.createLink('mail', 'test'), new FormData($('#mainContent form')[0]), function(data)
    {
        data = JSON.parse(data);
        if (data.result == 'success')
        {
            zui.Modal.alert({message: data.message, onClickAction: function(){loadCurrentPage()}});
            return;
        }

        $('#resultWin').html(data.message.error).removeClass('hidden');
    })
}
