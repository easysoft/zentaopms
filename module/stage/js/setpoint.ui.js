window.refreshApproval = function()
{
    $.getJSON($.createLink('review', 'ajaxGetApproval'), function(data)
    {
        $('[name^=flow]').each(function()
        {
            $(this).zui('picker').render({items: data});
        });
    });
}
