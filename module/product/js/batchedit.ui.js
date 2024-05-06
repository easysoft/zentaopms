window.renderRowData = function($row, index, row)
{
    const product = row;
    $row.find('[data-name="line"]').find('.picker-box').on('inited', function(e, info)
    {
        let $line     = info[0];
        let lineItems = [];
        if(lines[product.program] != undefined)
        {
            linePairs = lines[product.program];
            $.each(linePairs, function(lineID, lineName)
            {
                lineItems.push({value: lineID, text: lineName});
            });
        }
        $line.render({items: lineItems});
    });
}

/**
 * Load product lines by program.
 *
 * @param  event  e
 * @access public
 * @return void
 */
function loadProductLines(e)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const programID   = $currentRow.find('input[name^=program]').val();
    const productID   = $currentRow.find('input[name^=productIdList]').val();
    const lineID      = $currentRow.find('input[name^=line]').val();
    const link        = $.createLink('product', 'ajaxGetLine', 'programID=' + programID + '&productID=' + productID);
    $.getJSON(link, function(lines)
    {
        $currentRow.find('input[name^="line"]').zui('picker').render({items: lines.items});
        $currentRow.find('input[name^="line"]').zui('picker').$.setValue(lineID);
    });
}
