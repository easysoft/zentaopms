$.fn.triggerHandler = $.fn.trigger;

$(function()
{
    $('.zin-page-css').appendTo('head');

    function resizeModal()
    {
        const $modal = $('.modal-body');
        const width = $modal.outerWidth();
        const height = $modal.outerHeight();
        $modal.closest('body').height(height);
        const $iframe = parent.$('iframe[name="' + window.name + '"]');
        $iframe.closest('.modal-dialog').css({width: width + 10});
    }

    $.ajax(
    {
        url:     modalOpenUrl,
        headers: {'X-ZUI-Modal': 'true'},
        success: function(data)
        {
            const $body = $('body');
            $body.html(data);
            const resizeOb = new ResizeObserver(resizeModal);
            resizeOb.observe($('.modal-body')[0]);
            resizeModal();
            $body.zuiInit().removeClass('invisible');
        }
    });
});
