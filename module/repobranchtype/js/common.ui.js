
/**
 * 添加前缀输入框
 * @param {HTMLElement} obj 按钮元素
 */
window.addPrefixItem = function()
{
    const $obj    = $(event.target);
    const newLine = $obj.closest('.form-row').clone();

    newLine.addClass('newLine');
    newLine.find('.form-label').remove();
    const index = $('.prefixes').length;
    if(index >= 5)
    {
        zui.Messager.show(maxPrefixesTip, {type: 'warning', time: 3000});
        return false;
    }

    newLine.find('.form-group').attr('data-name', 'prefixes[' + index + ']').find('input').attr('name', 'prefixes[' + index + ']').attr('id', 'prefixes[' + index + ']').val('');

    /* 新添加的行需要显示删除按钮。 */
    const btnGroup = newLine.find('.btn-group');
    if(btnGroup.find('.icon-trash').length == 0)
    {
        btnGroup.append('<button type="button" class="btn btn-link" onclick="deletePrefixItem(this)"><i class="icon icon-trash"></i></button>');
    }

    $obj.closest('.form-row').after(newLine);
};

/**
 * 删除前缀输入框
 * @param {HTMLElement} obj 按钮元素
 */
window.deletePrefixItem = function()
{
    const obj     = $(event.target);
    const formRow = obj.closest('.form-row');

    /* 第一行（带有标签的行）不允许删除。 */
    if(formRow.find('.form-label').length > 0)
    {
        zui.Messager.show(minPrefixesTip, {type: 'warning', time: 3000});
        return false;
    }

    const index = $('.prefixes').length;

    /* 检查是否只剩1个（不允许删除到少于1个）。 */
    if(index <= 1)
    {
        zui.Messager.show(minPrefixesTip, {type: 'warning', time: 3000});
        return false;
    }
    formRow.remove();
};
