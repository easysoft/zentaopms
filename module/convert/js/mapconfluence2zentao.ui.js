window.changeSpace = function(event)
{
    space = $(event.target).val();
    const link = $.createLink('convert', 'ajaxGetZentaoDocLib', 'spaceType=' + space);
    $.getJSON(link, function(data)
    {
        $(event.target).closest('.form-row').find('[name^=zentaoDocLib]').zui('picker').render(data);
        $(event.target).closest('.form-row').find('[name^=zentaoDocLib]').zui('picker').$.setValue(data.defaultValue);
    });
}

window.next = function()
{
    $('button[type=submit]').trigger('click');
}
