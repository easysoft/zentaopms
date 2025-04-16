window.changeModule = function(e)
{
    const module = e.target.value;
    const url    = $.createLink('deliverable', 'ajaxGetModelList', `type=${module}`);

    if(module == 'execution')
    {
        $('[name="method"]').zui('picker').$.setValue('close');
        $('[name="method"]').zui('picker').render({disabled: true});
    }
    else
    {
        $('[name="method"]').zui('picker').$.setValue('');
        $('[name="method"]').zui('picker').render({disabled: false});
    }

    $.getJSON(url, function(data)
    {
        $('[name="model"]').zui('picker').render({items: data});
        $('[name="model"]').zui('picker').$.setValue('');
    });
}
