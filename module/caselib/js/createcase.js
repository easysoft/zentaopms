var newRowID = 0;

function loadLibModules(libID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen()
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
}

/**
 * Init testcase steps in form
 *
 * @param  string selector
 * @access public
 * @return void
 */
function initSteps(selector)
{
    /* Auto adjust textarea height. */
    $('textarea.autosize').each(function()
    {
        $.autoResizeTextarea(this);
    });

    var $steps = $(selector || '#steps');
    var $stepTemplate = $('#stepTemplate').detach().removeClass('template').attr('id', null);
    var groupNameText = $steps.data('groupName');
    var insertStepRow = function($row, count, type, notFocus)
    {
        if(count === undefined) count = 1;
        var $step;
        for(var i = 0; i < count; ++i)
        {
            $step = $stepTemplate.clone();
            if($row) $row.after($step);
            if(!$row) $steps.append($step);
            $step.addClass('step-new');
            if(type) $step.find('.step-type').val(type);
        }
        if(!notFocus && $step) setTimeout(function(){$step.find('.step-steps').focus();}, 10);
    };
    var updateStepType = function($step, type)
    {
        var targetIsGroup = type =='group';
        $step.attr('data-type', type).find('.step-steps').addClass('autosize').attr('placeholder', targetIsGroup ? groupNameText : null);
    };
    var getStepsElements = function()
    {
        return $steps.children('.step:not(.drag-shadow)');
    };
    var refreshSteps = function(skipAutoAddStep)
    {
        var parentId = 1, childId = 0;
        getStepsElements().each(function(idx)
        {
            var $step = $(this).attr('data-index', idx + 1);
            var type = $step.find('.step-type').val();
            var stepID;
            if(type == 'group')
            {
                $step.removeClass('step-item').removeClass('step-step').addClass('step-group');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                childId = 1;
            }
            else if(type == 'step')
            {
                $step.removeClass('step-item').removeClass('step-group').addClass('step-step');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                childId = 0;
            }
            else // step type is not set
            {
                if(childId) // type as child
                {
                    stepID = (parentId - 1) + '.' + (childId++);
                    $step.removeClass('step-step').removeClass('step-group').addClass('step-item').find('.step-item-id').text(stepID);
                }
                else // type as step
                {
                    $step.removeClass('step-item').removeClass('step-group').addClass('step-step');
                    stepID = parentId++;
                    $step.find('.step-id').text(stepID);
                }
            }
            $step.find('[name^="steps["]').attr('name', "steps[" +stepID + ']');
            $step.find('[name^="stepType["]').attr('name', "stepType[" +stepID + ']');
            $step.find('[name^="expects["]').attr('name', "expects[" +stepID + ']');

            updateStepType($step, type);
        });

        /* Auto insert step to group without any steps */
        if(!skipAutoAddStep)
        {
            var needRefresh = false;
            getStepsElements().each(function(idx)
            {
                var $step = $(this).attr('data-index', idx + 1);
                if($step.attr('data-type') !== 'group') return;
                var $nextStep = $step.next('.step:not(.drag-shadow)');
                if(!$nextStep.length || $nextStep.attr('data-type') !== 'item')
                {
                    insertStepRow($step, 1, 'item', true);
                    needRefresh = true;
                }
            });

            if(needRefresh) refreshSteps(true);
        }
    };
    var initSortable = function()
    {
        var isMouseDown = false;
        var $moveStep = null, moveOrder = 0;
        $steps.on('mousedown', '.btn-step-move', function()
        {
            isMouseDown = true;
            $moveStep = $(this).closest('.step').addClass('drag-row');

            $(document).off('.sortable').one('mouseup.sortable', function()
            {
                isMouseDown = false;
                $moveStep.removeClass('drag-row');
                $steps.removeClass('sortable-sorting');
                $moveStep = null;
                refreshSteps();
            });
            $steps.addClass('sortable-sorting');
        }).on('mouseenter', '.step:not(.drag-row)', function()
        {
            if(!isMouseDown) return;
            var $targetStep = $(this);
            getStepsElements().each(function(idx)
            {
                $(this).data('order', idx);
            });
            moveOrder = $moveStep.data('order');
            var targetOrder = $targetStep.data('order');
            if(moveOrder === targetOrder) return;
            else if(targetOrder > moveOrder)
            {
                $targetStep.after($moveStep);
            }
            else if(targetOrder < moveOrder)
            {
                $targetStep.before($moveStep);
            }
        });
    }
    $steps.on('click', '.btn-step-add', function()
    {
        insertStepRow($(this).closest('.step'));
        refreshSteps();
    }).on('click', '.btn-step-delete', function()
    {
        if($steps.children('.step').length == 1) return;
        $(this).closest('.step').remove();
        refreshSteps();
    }).on('change', '.step-group-toggle', function()
    {
        var $checkbox   = $(this);
        var $step       = $checkbox.closest('.step');
        var isChecked   = $checkbox.is(':checked');
        var suggestType = isChecked ? 'group' : 'item';
        if(!isChecked)
        {
            var $prevStep    = $step.prev('.step:not(.drag-shadow)');
            var suggestChild = $prevStep.length && $prevStep.is('.step-group') && $step.next('.step:not(.drag-shadow)').length;
            suggestType      = suggestChild ? 'item' : 'step';
        }
        $step.find('.step-type').val(suggestType);

        /* Auto insert step to group without any steps */
        if(suggestType === 'group')
        {
            var $nextStep = $step.next('.step:not(.drag-shadow)');
            if(!$nextStep.length || $nextStep.find('.step-type').val() !== 'item')
            {
                insertStepRow($step, 1, 'item', true);
            }
        }

        refreshSteps();
    }).on('change', '.form-control', function()
    {
        var $control = $(this);
        if($control.val())
        {
            var $step = $control.closest('.step');
            if($step.data('index') === getStepsElements().length)
            {
                insertStepRow($step, 1, 'step', true);
                if($step.is('.step-item,.step-group')) insertStepRow($step, 1, 'item', true);
                refreshSteps();
            }
        }
    });
    initSortable();
    refreshSteps();
}

/**
 * Update the step id.
 *
 * @access public
 * @return void
 */
function updateStepID()
{
    var i = 1;
    $('.stepID').each(function(){$(this).html(i ++)});
}

$(function()
{
    $('[data-toggle=tooltip]').tooltip();
    initSteps();

    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
})
