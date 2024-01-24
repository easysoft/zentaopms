window.renderRowData = function($row, index, plan)
{
    let $branch = $row.find('.form-batch-control[data-name="branch"]');
    let $status = $row.find('.form-batch-control[data-name="status"]');
    let $begin  = $row.find('.form-batch-control[data-name="begin"]');
    let $end    = $row.find('.form-batch-control[data-name="end"]');
    let $future = $row.find('.form-batch-control[data-name="future"]');

    if($branch.length > 0)
    {
        let branchOptions = {name: 'branch', 'multiple': true, defaultValue: plan.branch, onChange: function(){getConflictStories(index)}, 'items': branchPickerItems};
        if(plan.parent > 0 && typeof parentBranches[plan.parent] != 'undefined') branchOptions = $.extend({}, branchOptions, {items: parentBranches[plan.parent]});
        $row.find('[data-name="branch"]').find('.picker-box').on('inited', function(e, info)
        {
            let $branch = info[0];
            $branch.render(branchOptions);
            $branch.$.setValue(plan.branch);
        });

    }

    if($status.length > 0)
    {
        let statusOptions = {name: 'status', defaultValue: plan.status, 'items': statusPickerItems, required: true};
        if(plan.parent == '-1')   statusOptions = $.extend({}, statusOptions, {disabled: true});
        if(plan.status != 'wait') statusOptions = $.extend({}, statusOptions, {items: noWaitPickerItems});
        $row.find('[data-name="status"]').find('.picker-box').on('inited', function(e, info)
        {
            let $status = info[0];
            $status.render(statusOptions);
            $status.$.setValue(plan.status);
        });
    }

    let disabled = ((plan.begin == futureConfig && plan.end == futureConfig));
    if(plan.parent == -1 && (plan.begin == futureConfig && plan.end == futureConfig)) disabled = true;
    let $beginPicker = $begin.find('#begin');
    let $endPicker   = $end.find('#end');
    let beginOptions = {defaultValue: plan.begin, name: 'begin[' + plan.id + ']'};
    let endOptions   = {defaultValue: plan.end,   name: 'end[' + plan.id + ']'};
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
    let $branch       = $row.find('[name^="branch"]');
    let planID        = parseInt($row.find('.form-control-static[data-name="idIndex"]').text()).toString();
    let newBranch     = $branch.length == 0 ? '' : $branch.val();
    $.get($.createLink('productplan', 'ajaxGetConflict', 'planID=' + planID + '&newBranch=' + newBranch), function(conflictStories)
    {
        if(conflictStories != '')
        {
            zui.Modal.confirm({message: conflictStories, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(!res)
                {
                    const $branchPicker = $branch.zui('picker');
                    $branchPicker.$.setValue(oldBranch[planID]);
                }
            });
        }
    });
}
