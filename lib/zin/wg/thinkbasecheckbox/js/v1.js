/**
 * 切换选中的交互。
 * Toggle the display of checked interaction.
 *
 * @access public
 * @return void
 */
window.toggleChecked = function()
{
    if($(this).data('type') == 'radio')
    {
        if($(this).find('input').prop('checked')) return;
        $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
        $(this).closest('.think-check-list').find('.item-control').removeClass('is-checked');
        $(this).toggleClass('is-checked', $(this).find('input').is(':checked'));
        $(this).closest('.think-check-list').find('.radio-primary').removeClass('checked');
        $(this).find('.radio-primary').toggleClass('checked');
    }
    else
    {
        $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
        $(this).find('.checkbox-primary').toggleClass('checked', $(this).find('input').is(':checked'));
        $(this).toggleClass('is-checked', $(this).find('input').is(':checked'));
    }

    /* 如果当前项是其他的话，选中时展示输入框，类型为单选时，选择其他项隐藏输入框，多选时取消选中隐藏输入框。*/
    /* If the current item is Other, display the input box when selected. If the type is Single Choice, select Other to hide the input box. If Multiple Choice, uncheck the hide input box. */
    if($(this).hasClass('has-input'))
    {
        $(this).find('textarea').toggleClass('hidden', !$(this).find('input').prop('checked'));
        if($(this).find('input').prop('checked')) $(this).find('textarea').trigger('focus');
    }
    else
    {
        if($(this).data('type') == 'radio')$(this).closest('.think-check-list').find('.has-input textarea').addClass('hidden');
    }
}

/**
 * 输入其他内容。
 * Input other content.
 *
 * @access public
 * @return void
 */
window.inputOther = function()
{
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight > 96 ? 96 : this.scrollHeight) + 'px';
}
