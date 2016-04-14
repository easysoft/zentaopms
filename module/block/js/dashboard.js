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
        else {checkEmpty();}
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

    var ordersMap = [];
    $.each(orders, function(blockId, order) {ordersMap.push({id: blockId, order: order});});
    ordersMap.sort(function(a, b) {return a.order - b.order;});
    var newOrders = $.map(ordersMap, function(order, idx) {return order.id});

    $.getJSON(createLink('block', 'sort', 'orders=' + newOrders.join(',') + '&module=' + module), function(data)
    {
        if(data.result == 'success') $.zui.messager.success(config.ordersSaved);
    });
}

/**
 * Check dashboard wether is empty
 * @access public
 * @return void
 */
function checkEmpty()
{
    var $dashboard = $('#dashboard');
    var hasBlocks = !!$dashboard.children('.row').children().length;
    $dashboard.find('.dashboard-empty-message').toggleClass('hide', hasBlocks);
}


$(function()
{
    var $dashboard = $('#dashboard').dashboard(
    {
        height            : 240,
        draggable         : true,
        shadowType        : false,
        afterOrdered      : sortBlocks,
        afterPanelRemoved : deleteBlock,
        sensitive         : true,
        panelRemovingTip  : config.confirmRemoveBlock
    });

    $dashboard.find('ul.dashboard-actions').addClass('hide').children('li').addClass('right').appendTo($('#modulemenu > .nav'));

    checkEmpty();
});

