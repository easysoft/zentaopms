function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group').clone();
    $inputgroup.find('input').val('');
    $(obj).closest('.input-group').after($inputgroup);
}

function deleteItem(obj)
{
    if($(obj).closest('#newbranches').find("input[id^='newbranch']").size() <= 1) return;
    $(obj).closest('.input-group').remove();
}

$(function()
{
    $('#branchTableList').addClass('sortable').sortable(
    {
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $('#branchTableList').find('.sort-handler').length ? '.sort-handler' : null,

        canMoveHere: function($ele, $target)
        {
            return $target.data('id') != 0;
        },

        finish: function(e)
        {
            var branches = '';
            e.list.each(function()
            {
                branches += $(this.item).data('id') + ',';
            });

            $.post(createLink('branch', 'sort'), {'branches': branches, 'orderBy': orderBy});
        }
    });

    $('td.c-name.flex').mouseenter(function()
    {
        $(this).find('.setDefault').removeClass('hidden');
    }).mouseleave(function()
    {
        $(this).find('.setDefault').addClass('hidden');
    })
});
