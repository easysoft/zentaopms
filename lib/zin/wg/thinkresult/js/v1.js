$(document).ready(function()
{
    /* 禁用问题组件 */
    /* Disable question components */
    $('.think-result-content .form-control').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control input').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control').off('click');
});
