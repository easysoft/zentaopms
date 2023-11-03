window.confirmChanges = function()
{
    var picker = $('#userSelect').zui('picker')
    var items  = picker.options.items;
    var value  = picker.$.value;
    var text   = '';

    for(i in items)
    {
        if(items[i].value == value)
        {
            text = items[i].text;
            break;
        }
    }

    var spanText  = text ? '<span class="label primary-outline circle">' + text + '</span>' : '';
    var inputName = $("#saveInput").children('input').eq(0).attr("name");

    var $nameInput = $(".bindUsersList").find("input[name='" + inputName + "']");
    $nameInput.val(value);
    $nameInput.closest('td').find('.username').html(spanText);

    $('#userList').modal('hide');
};

window.showBindModal = function(e)
{
    var $obj = $(e.target);
    var inputName = $obj.attr("data-value");
    if(typeof(inputName) == 'undefined') inputName = $obj.closest('[data-value]').attr('data-value');

    $("#saveInput").html("<input type='hidden' name='" + inputName + "' value=''>");
    $('#userSelect').picker('setValue', '');
};
