$(function()
{
    $('#dashboard').dashboard(
    {
        height            : 240,
        draggable         : true,
        shadowType        : false,
        afterOrdered      : sortBlocks,
        afterPanelRemoved : deleteBlock,
        sensitive         : true,
        panelRemovingTip  : config.confirmRemoveBlock
    });
});

/**
 * Delete block.
 * 
 * @param  int    $index 
 * @access public
 * @return void
 */
function deleteBlock(index)
{
    $.getJSON(createLink('block', 'delete', 'index=' + index + '&module=' + module), function(data)
    {   
        if(data.result != 'success')
        {   
            alert(data.message);
            return false;
        }   
    })  
}

/**
 * Sort blocks.
 * 
 * @param  object $orders  format is {'block2' : 1, 'block1' : 2, oldOrder : newOrder} 
 * @access public
 * @return void
 */
function sortBlocks(orders)
{
    var oldOrder = new Array();
    var newOrder = new Array();
    for(i in orders)
    {   
        oldOrder.push(i.replace('block', ''));
        newOrder.push(orders[i]);
    }

    $.getJSON(createLink('block', 'sort', 'oldOrder=' + oldOrder.join(',') + '&newOrder=' + newOrder.join(',') + '&module=' + module), function(data)
    {

        if(data.result != 'success') return false;

        $('#dashboard .panel').each(function()
        {
            var index = $(this).data('order');
            /* Update new index for block id edit and delete. */
            $(this).attr('id', 'block' + index).attr('data-id', index).data('url', createLink('block', 'printBlock', 'index=' + index));
            $(this).find('.panel-actions .edit-block').attr('href', createLink('block', 'admin', 'index=' + index));
        });

        $.zui.messager.success(config.ordersSaved);
    });
}
