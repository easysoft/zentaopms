window.initThinkResult = function()
{
    /* 禁用问题组件 */
    /* Disable question components */
    $('.think-result-content .form-control').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control input').prop('disabled', true);
    $('.think-result-content .think-check-list .item-control').off('click');
    $('.think-result-content textarea.form-control').addClass('overflow-hidden line-clamp-4');
    $('.think-result-content textarea.form-control').attr('rows', 4);

    $('.think-result-content .think-check-list .item-control textarea.form-control').removeClass('line-clamp-4');
    $('.think-result-content .think-check-list .item-control textarea.form-control').addClass('line-clamp-2');
    $('.think-result-content .think-check-list .item-control textarea.form-control').attr('rows', 2);
    $('.think-result-content .think-check-list .item-control textarea.form-control').each(function()
    {
        $(this).attr('title', $(this).val());
    });
}
