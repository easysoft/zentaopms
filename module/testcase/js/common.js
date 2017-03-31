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
    var insertStepRow = function($row, count, type)
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
    var updateStepType = function($step, type)
    {
        var targetIsGroup = type =='group';
        $step.attr('data-type', type).find('.step-steps').toggleClass('autosize', !targetIsGroup).attr('placeholder', targetIsGroup ? groupNameText : null).focus();

        var displayType = (type =='item' && $step.hasClass('step-step')) ? 'step' : type;

        var activeTypeText = $step.find('.step-type-menu > a').removeClass('active').filter('[data-value="' + displayType + '"]').addClass('active').text();
        $step.find('.step-type-current > span').text(activeTypeText);
    };
    var refreshSteps = function()
    {
        var parentId = 1, childId = 0;
        $steps.children('.step:not(.drag-shadow)').each(function(idx)
        {
            var $step = $(this);
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
            else
            {
                if(childId) // as child
                {
                    stepID = (parentId - 1) + '.' + (childId++);
                    $step.removeClass('step-step step-group').addClass('step-item').find('.step-item-id').text(stepID);
                }
                else
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
        });
    }
    $steps.on('click', '.btn-step-add', function()
    {
        insertStepRow($(this).closest('.step'));
        refreshSteps();
    }).on('click', '.btn-step-delete', function()
    {
        if($('tbody#steps tr.step').size() == 1) return false;
        $(this).closest('.step').remove();
        refreshSteps();
    }).on('click', '.step-type-menu a', function()
    {
        var $a = $(this);
        $a.closest('.step').find('.step-type').val($a.data('value'));
        refreshSteps();
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
