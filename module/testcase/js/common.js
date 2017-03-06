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
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
    setStories();
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
    var insertStepRow = function($row, count)
    {
        if(count === undefined) count = 1;
        for(var i = 0; i < count; ++i)
        {
            var $step = $stepTemplate.clone();
            if($row) $row.after($step);
            else $steps.append($step);
            $step.addClass('step-new');
            setTimeout(function(){$step.find('.step-steps').focus();}, 10);

        }
    };
    var toggleStepRowType = function($row, toggleisGroup)
    {
        if(toggleisGroup === undefined) targetIsGroup = $row.find('.step-type').is(':checked');
        $row.toggleClass('step-group', targetIsGroup);
        $row.find('.step-steps').toggleClass('autosize', !targetIsGroup).attr('placeholder', targetIsGroup ? groupNameText : null).focus();
    };
    var refreshStepsID = function()
    {
        var parentId = 1, childId = 0;
        $steps.children('.step:not(.drag-shadow)').each(function(idx)
        {
            var $step = $(this);
            var isGroup = $step.find('.step-type').is(':checked');
            var stepID;
            if(isGroup || !childId)
            {
                $step.removeClass('step-item');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                if(isGroup) childId = 1;
            }
            else
            {
                stepID = (parentId - 1) + '.' + (childId++);
                $step.addClass('step-item').find('.step-item-id').text(stepID);
            }
            $step.find('[name^="steps["]').attr('name', "steps[" +stepID + ']');
            $step.find('[name^="stepType["]').attr('name', "stepType[" +stepID + ']');
            $step.find('[name^="expects["]').attr('name', "expects[" +stepID + ']');
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
            });
            $steps.addClass('sortable-sorting');
        }).on('mouseenter', '.step:not(.drag-row)', function()
        {
            if(!isMouseDown) return;
            var $targetStep = $(this);
            $steps.children('.step').each(function(idx)
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
            refreshStepsID();
        });
    }
    $steps.on('click', '.btn-step-add', function()
    {
        insertStepRow($(this).closest('.step'));
        refreshStepsID();
    }).on('click', '.btn-step-delete', function()
    {
        if($('tbody#steps tr.step').size() == 1) return false;
        $(this).closest('.step').remove();
        refreshStepsID();
    }).on('change', '.step-type', function()
    {
        toggleStepRowType($(this).closest('.step'));
        refreshStepsID();
    });
    initSortable();
    refreshStepsID();
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
