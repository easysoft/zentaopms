var newRowID = 0;
/**
 * Load modules and stories of a product.
 * 
 * @param  int     $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    loadProductBranches(productID)
    loadProductModules(productID);
    setStories();
}

/**
 * Load by branch.
 * 
 * @access public
 * @return void
 */
function loadBranch()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModules($('#product').val(), branch);
    setStories();
}

/**
 * Load product branches.
 * 
 * @param  int $productID 
 * @access public
 * @return void
 */
function loadProductBranches(productID)
{
    $('#branch').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '65px');
        }
    })
}

/**
 * Load stories of module. 
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    setStories();
}

/**
 * Load module.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductModules(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
    setStories();
}

/**
 * Load module.
 * 
 * @param  int    $libID 
 * @access public
 * @return void
 */
function loadLibModules(libID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + libID + '&viewtype=caselib&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
}

/**
 * Set story field.
 * 
 * @access public
 * @return void
 */
function setStories()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=50');
    $.get(link, function(stories)
    {
        var value = $('#story').val();
        if(!stories) stories = '<select id="story" name="story"></select>';
        $('#story').replaceWith(stories);
        $('#story').val(value);
        $('#story_chosen').remove();
        $("#story").chosen(defaultChosenOptions);
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
    if(navigator.userAgent.indexOf("Firefox") < 0)
    {
        $(document).on('input keyup paste change', 'textarea.autosize', function()
        {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight + 2) + "px"; 
        });
    }
    var $steps = $(selector || '#steps');
    var $stepTemplate = $('#stepTemplate').detach().removeClass('template').attr('id', null);
    var initSortableCallTask = null;
    var groupNameText = $steps.data('groupName');
    var insertStepRow = function($row, count, type, notFocus)
    {
        if(count === undefined) count = 1;
        var $step;
        for(var i = 0; i < count; ++i)
        {
            $step = $stepTemplate.clone();
            if($row) $row.after($step);
            else $steps.append($step);
            $step.addClass('step-new');
            if(type) $step.find('.step-type').val(type);
        }
        if(!notFocus && $step) setTimeout(function(){$step.find('.step-steps').focus();}, 10);
    };
    var updateStepType = function($step, type)
    {
        var targetIsGroup = type =='group';
        $step.attr('data-type', type).find('.step-steps').toggleClass('autosize', !targetIsGroup).attr('placeholder', targetIsGroup ? groupNameText : null);
    };
    var getStepsElements = function()
    {
        return $steps.children('.step:not(.drag-shadow)');
    };
    var refreshSteps = function()
    {
        var parentId = 1, childId = 0;
        getStepsElements().each(function(idx)
        {
            var $step = $(this).attr('data-index', idx + 1);
            var type = $step.find('.step-type').val();
            var stepID;
            if(type == 'group')
            {
                $step.removeClass('step-item step-step').addClass('step-group');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                childId = 1;
            }
            else if(type == 'step')
            {
                $step.removeClass('step-item step-group').addClass('step-step');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                childId = 0;
            }
            else // step type is not set
            {
                if(childId) // type as child
                {
                    stepID = (parentId - 1) + '.' + (childId++);
                    $step.removeClass('step-step step-group').addClass('step-item').find('.step-item-id').text(stepID);
                }
                else // type as step
                {
                    $step.removeClass('step-item step-group').addClass('step-step');
                    stepID = parentId++;
                    $step.find('.step-id').text(stepID);
                }
            }
            $step.find('[name^="steps["]').attr('name', "steps[" +stepID + ']');
            $step.find('[name^="stepType["]').attr('name', "stepType[" +stepID + ']');
            $step.find('[name^="expects["]').attr('name', "expects[" +stepID + ']');

            updateStepType($step, type);
        });
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
        var $checkbox = $(this);
        var $step = $checkbox.closest('.step');
        var isChecked = $checkbox.is(':checked');
        var suggestType = isChecked ? 'group' : 'item';
        if(!isChecked) 
        {
            var $prevStep = $step.prev('.step:not(.drag-shadow)');
            var suggestChild = $prevStep.length && $prevStep.is('.step-group') && $step.next('.step:not(.drag-shadow)').length;
            suggestType = suggestChild ? 'item' : 'step';
        }
        $step.find('.step-type').val(suggestType);
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
