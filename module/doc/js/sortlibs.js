$(function()
{
    /* Make libs sortable. */
    $('#libs').sortable(
    {
        trigger: '.lib > .icon-move, .lib > .lib-name',
        dropToClass: 'sort-to',
        stopPropagation: true,
        nested: true,
        selector: 'div',
        dragCssClass: 'drop-here',
        canMoveHere: function($ele, $target)
        {
            var maxTop = $('#libs .libList').height() - $ele.height();
            if(parseFloat($('.drag-shadow').css('top')) < 0) $('.drag-shadow').css('top', '0');
            if(parseFloat($('.drag-shadow').css('left')) != 0) $('.drag-shadow').css('left', '0');
            if(parseFloat($('.drag-shadow').css('top')) > maxTop) $('.drag-shadow').css('top', maxTop + 'px');
            if(parseFloat($('.drag-shadow').css('bottom')) > $('#libs').height()) $('.drag-shadow').css('bottom', '0');
            return true;
        },
        targetSelector: function($ele, $root)
        {
            var $libs = $ele.closest('#libs .libList');
            return $libs.children('div.lib');
        },
        always: function()
        {
            $('#libs,#libs .is-sorting').removeClass('is-sorting');
        },
        finish: function(e)
        {
            var orders = ',';
            $('#libs .libList').find('div.lib').each(function()
            {
                orders += $(this).attr('data-id') + ',';
            });
            $('#libIdList').attr('value', orders);
        }
    });
});
