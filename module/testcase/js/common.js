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
                $step.removeClass('step-child');
                stepID = parentId++;
                $step.find('.step-id').text(stepID);
                if(isGroup) childId = 1;
            }
            else
            {
                stepID = (parentId - 1) + '.' + (childId++);
                $step.addClass('step-child').find('.step-child-id').text(stepID);
            }
            $step.find('.step-id-control').val(stepID);
        });
    };
    var initSortable = function()
    {
        clearTimeout(initSortableCallTask);
        initSortableCallTask = setTimeout(function()
        {
            var $oldSteps = $steps.children('.step');
            var $newSteps = $oldSteps.clone();
            $oldSteps.remove();
            $steps.append($newSteps);
            $steps.sortable(
            {
                selector: 'tr.step',
                dragCssClass: 'drag-row',
                trigger: '.btn-step-move',
                finish: function(e)
                {
                    e.element.addClass('drop-success');
                    setTimeout(function(){$steps.find('.drop-success').removeClass('drop-success');}, 800);
                    refreshStepsID();
                }
            });
            $steps.children('.step-new').removeClass('step-new').last().find('textarea:first').focus();
        }, 100);
    }
    $steps.on('click', '.btn-step-add', function()
    {
        insertStepRow($(this).closest('.step'));
        initSortable();
        refreshStepsID();
    }).on('click', '.btn-step-delete', function()
    {
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
