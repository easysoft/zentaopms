$(document).ready(function()
{
    /* 禁用问题组件 */
    /* Disable question components */
    $('.think-result-content .form-control').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control input').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control').off('click');
    $('.think-result-content textarea.form-control').addClass('overflow-hidden line-clamp-4');
    $('.think-result-content textarea.form-control').attr('rows', 4);
});
