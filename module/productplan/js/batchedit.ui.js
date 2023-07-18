window.renderRowData = function($row, index, plan)
{
    let $branch = $row.find('.form-batch-control[data-name="branch"]');
    let $status = $row.find('.form-batch-control[data-name="status"]');
    let $begin  = $row.find('.form-batch-control[data-name="begin"]');
    let $end    = $row.find('.form-batch-control[data-name="end"]');
    let $future = $row.find('.form-batch-control[data-name="future"]');

    if($branch.length > 0)
    {
        $branchPicker = $branch.find('.picker-box');
        branchOptions = {name: 'branch[' + plan.id + '][]', 'multiple': true, defaultValue: plan.branch, onChange: function(){getConflictStories(index)}, 'items': branchPickerItems};
        if(plan.parent > 0 && typeof parentBranches[plan.parent] != 'undefined') branchOptions = $.extend({}, branchOptions, {items: parentBranches[plan.parent]});
        $branchPicker.picker(branchOptions);
    }

    if($status.length > 0)
    {
        $statusPicker = $status.find('.picker-box');
        statusOptions = {name: 'status[' + plan.id + ']', defaultValue: plan.status, 'items': statusPickerItems};
        if(plan.parent == '-1')   statusOptions = $.extend({}, statusOptions, {disabled: true});
        if(plan.status != 'wait') statusOptions = $.extend({}, statusOptions, {items: noWaitPickerItems});
        $statusPicker.picker(statusOptions);
    }

    let disabled = ((plan.begin == futureConfig && plan.end == futureConfig));
    if(plan.parent == -1 && (plan.begin == futureConfig && plan.end == futureConfig)) disabled = true;
    $beginPicker = $begin.find('#begin');
    $endPicker   = $end.find('#end');
    beginOptions = {defaultValue: plan.begin, name: 'begin[' + plan.id + ']'};
    endOptions   = {defaultValue: plan.end,   name: 'end[' + plan.id + ']'};
    if(disabled)
    {
        $beginOptions = $.extend({}, $beginOptions, {disabled: true});
        $endOptions   = $.extend({}, $endOptions, {disabled: true});
    }
    $beginPicker.datePicker(beginOptions);
    $endPicker.datePicker(endOptions);

    $future.find('[name^=future]').attr('onchange', "changeDate(" + index + ")").prop('checked', (plan.begin == futureConfig && plan.end == futureConfig));
}

window.changeDate = function(index)
{
    let $row    = $('tr[data-index="' + index + '"]');
    let $future = $row.find('.form-batch-control[data-name="future"] [name^=future]');
    let $begin  = $row.find('.form-batch-control[data-name="begin"]');
    let $end    = $row.find('.form-batch-control[data-name="end"]');
    let $beginPicker = $begin.find('[name^=begin]').zui('datePicker');
    let $endPicker   = $end.find('[name^=end]').zui('datePicker');
    if($future.prop('checked'))
    {
        $beginPicker.render($.extend({}, $beginPicker.options, {disabled: true}));
        $endPicker.render($.extend({}, $endPicker.options, {disabled: true}));
    }
    else
    {
        $beginPicker.render($.extend({}, $beginPicker.options, {disabled: false}));
        $endPicker.render($.extend({}, $endPicker.options, {disabled: false}));
    }
}

window.getConflictStories = function(index)
{
    let $row          = $('tr[data-index="' + index + '"]');
    let $branch       = $row.find('.form-batch-control[data-name="branch"]');
    let planID        = parseInt($row.find('.form-batch-control[data-name="id"] .form-control-static[data-name="id"]').text()).toString();
    let $branchPicker = $branch.find('[name^=branch]').zui('picker');
    let newBranch     = $branchPicker.length == 0 ? '' : $branchPicker.$.value.toString();
    $.get($.createLink('productplan', 'ajaxGetConflict', 'planID=' + planID + '&newBranch=' + newBranch), function(conflictStories)
    {
        if(conflictStories != '' && !confirm(conflictStories))
        {
            $branchPicker.render($.extend({}, $branchPicker.options, {defaultValue: oldBranch[planID]}));
        }
    });
}
