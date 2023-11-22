/**
 * 对部分列进行重定义。
 * Redefine the partial column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'default')
    {
        const checked = defaultKey == info.row.data.key ? 'checked' : '';
        result[0] = {html: `<input type='radio' name='default' value=${info.row.data.key} ${checked} class='cursor-pointer'>`};
        return result;
    }

    if(info.col.name == 'actions' && defaultKey == info.row.data.key)
    {
        result[0]['props']['items'][1]['disabled'] = true;
        return result;
    }

    return result;
}

$(document).off('change', 'input[name=default]').on('change', 'input[name=default]', function(e)
{
    $.ajaxSubmit({url: $.createLink('custom', 'setDefaultConcept', 'key=' + $(e.target).val())});
})
