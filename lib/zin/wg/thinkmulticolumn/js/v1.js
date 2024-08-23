/**
 * 处理输入框值变化。
 * Handling input box value changes.
 *
 * @access public
 * @return void
 */
window.changeRows = function()
{
    const value = $(this).val();
    const intValue = value < 1 ? 1 : parseInt(value, 10);
    $(this).val(intValue);
}
