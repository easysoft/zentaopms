/**
 * Toggle checked status in the same column.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleColumnChecked(event)
{
    let $target = $(event.target);
    let index = $target.closest('th').index();
    if($target.prop('checked'))
    {
        $target.closest('table').find('tbody').find('tr').each(function()
        {
            $(this).find('td').eq(index).find('.checkbox-primary input[type="checkbox"]').prop('checked', true);
        })
    }
    else
    {
        $target.closest('table').find('tbody').find('tr').each(function()
        {
            $(this).find('td').eq(index).find('.checkbox-primary input[type="checkbox"]').prop('checked', false);
        })
    }
}

/**
 * Toggle checked status in the same lane.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleLaneChecked(event)
{
    let $target = $(event.target);
    if($target.prop('checked'))
    {
        $target.closest('tr').find('.checkbox-primary input[type="checkbox"]').prop('checked', true);
    }
    else
    {
        $target.closest('tr').find('.checkbox-primary input[type="checkbox"]').prop('checked', false);
    }
}
