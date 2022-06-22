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
        targetSelector: function($ele, $root)
        {
            var $libs = $ele.closest('#libs');
            return $libs.children('div.lib');
        },
        always: function()
        {
            $('#libs,#libs .is-sorting').removeClass('is-sorting');
        },
        finish: function(e)
        {
            var orders = ',';
            $('#libs').find('div.lib').each(function()
            {
                orders += $(this).attr('data-id') + ',';
            });
            $('#libs #libIdList').attr('value', orders);
        }
    });
});
