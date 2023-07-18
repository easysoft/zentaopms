window.renderRowData = function($row, index, plan)
{
    let $closedReason = $row.find('.form-batch-control[data-name="closedReason"]');

    if($closedReason.length > 0)
    {
        $reasonPicker = $closedReason.find('.picker-box');
        reasonOptions = {name: 'closedReason[' + plan.id + ']', 'items': closedReasonItems};
        $reasonPicker.picker(reasonOptions);
    }
}
