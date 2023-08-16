$(function()
{
    $('#subNavbar a[data-toggle=dropdown]').parent().addClass('dropdown dropdown-hover');

    if(window.flow != 'full')
    {
        $('.querybox-toggle').click(function()
        {
            $(this).parent().toggleClass('active');
        });
    }
})

const isCreate = (config.currentMethod == 'create' || config.currentMethod == 'createScene');
const isEdit   = (config.currentMethod == 'edit'   || config.currentMethod == 'editScene');

/**
 * Load branches of a product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadAll(productID)
{
    loadProductBranches(productID);
}

/**
 * Load modules, stories and scenes of a branch.
 *
 * @access public
 * @return void
 */
function loadBranch()
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    let result = true;
    if(branch && typeof(testtasks) !== 'undefined')
    {
        for(taskID in testtasks)
        {
            if(branch != oldBranch && testtasks[taskID]['branch'] != branch)
            {
                var tip = confirmUnlinkTesttask.replace("%s", caseID);
                result  = confirm(tip);
                if(!result) $('#branch').val(oldBranch).trigger("chosen:updated");
                break;
            }
        }
    }

    if(result) loadProductModules($('#product').val(), branch);
}

/**
 * Load branches, modules, stories and scenes  of a product.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function loadProductBranches(productID)
{
    $('#branch').remove();

    var oldBranch  = isEdit ? caseBranch : 0;
    var browseType = isCreate ? 'active' : 'all';
    var param      = "productID=" + productID + "&oldBranch=" + oldBranch + "&browseType=" + browseType;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get(createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', isCreate ? '120px' : '95px');
        }

        loadProductModules(productID);
        setStories();
        setScenes();
    })
}

/**
 * Load modules, stories and scenes of a product and a branch.
 *
 * @param  int    $productID
 * @param  string $branch
 * @access public
 * @return void
 */
function loadProductModules(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('#branch').val();
    if(!branch) branch = 0;
    const moduleID = isEdit ? $('#module').val() : 0;
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + moduleID);
    $('#moduleIdBox').load(link, function()
    {
        var $inputGroup = $(this);
        $inputGroup.find('select').chosen();
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
        $inputGroup.fixInputGroup();

        setStories();
        setScenes();
    });
}

/**
 * Load modules of a caselib and a branch.
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
        $(this).find('select').chosen();
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
    });
}

/**
 * Load stories and scenes.
 *
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    setStories();
    setScenes();
}

/**
 * Load stories of a product and a module.
 *
 * @param  int    productID
 * @param  int    moduleID
 * @param  int    num
 * @access public
 * @return void
 */
function loadStories(productID, moduleID, num)
{
    var branchIDName = (config.currentMethod == 'batchcreate' || config.currentMethod == 'showimport') ? '#branch' : '#branches';
    var branchID     = $(branchIDName + num).val();
    if(!branchID) branchID = 0;

    var storyLink = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&objectID=0&number=' + num);
    $.get(storyLink, function(stories)
    {
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        if(config.currentMethod == 'batchcreate')
        {
            for(var i = num; i <= rowIndex ; i ++)
            {
                if(i != num && $('#module' + i).val() != 'ditto') break;
                var nowStories = stories.replaceAll('story' + num, 'story' + i);
                $('#story' + i).replaceWith(nowStories);
                $('#story' + i + "_chosen").remove();
                $('#story' + i).next('.picker').remove();
                $('#story' + i).attr('name', 'story[' + i + ']');
                $('#story' + i).picker();
            }
        }
        else
        {
            $('#story' + num).replaceWith(stories);
            $('#story' + num + "_chosen").remove();
            $('#story' + num).next('.picker').remove();
            $('#story' + num).attr('name', 'story[' + num + ']');
            $('#story' + num).picker();
        }
    });
}

/**
 * Set modules.
 *
 * @param  int    $productID
 * @param  int    $branchID
 * @param  int    $num
 * @access public
 * @return void
 */
function setModules(productID, branchID, num)
{
    moduleLink = createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=case&branch=' + branchID + '&num=' + num);
    $.get(moduleLink, function(modules)
    {
        if(!modules) modules = '<select id="module' + num + '" name="module[' + num + ']" class="form-control"></select>';
        $('#module' + num).replaceWith(modules);
        $("#module" + num + "_chosen").remove();
        $("#module" + num).next('.picker').remove();
        $("#module" + num).attr('onchange', "loadStories("+ productID + ", this.value, " + num + ")").chosen();
    });

    loadStories(productID, 0, num);

    /* If the branch of the current row is inconsistent with the one below, clear the module and story of the nex row. */
    var nextBranchID = $('#branch' + (num + 1)).val();
    if(nextBranchID != branchID)
    {
        $('#module' + (num + 1)).find("option[value='ditto']").remove();
        $('#module' + (num + 1)).trigger("chosen:updated");

        $('#plan' + (num + 1)).find("option[value='ditto']").remove();
        $('#plan' + (num + 1)).trigger("chosen:updated");
    }
}

/**
 * Set story field.
 *
 * @access public
 * @return void
 */
function setStories()
{
    if($('#story').length == 0) return false;

    productID = $('#product').val();
    branch    = $('#branch').val();
    moduleID  = $('#module').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=0&objectID=' + objectID);

    $.get(link, function(stories)
    {
        var value = $('#story').val();
        if(!stories) stories = '<select id="story" name="story"></select>';
        $('#story').replaceWith(stories);
        $('#story').val(value).attr('onchange', 'setPreview()');
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $("#story").picker();
    });
}

/* Set the story priview link. */
function setPreview()
{
    if($('#story').val() == 0)
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        if(!isonlybody)
        {
            var concat = storyLink.indexOf('?') < 0 ? '?'  : '&';
            storyLink  = storyLink + concat + 'onlybody=yes';
        }

        $('#preview').addClass('iframe');
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}

function setScenes()
{
    if($('#sceneIdBox').length == 0) return false;

    productID = $('#product').val();
    branch    = $('#branch').val();
    moduleID  = $('#module').val();
    if(typeof(branch) == 'undefined') branch = 0;
    if(typeof(sceneID) == 'undefined') sceneID = 0;
    element = (config.currentMethod == 'createscene' || config.currentMethod == 'editscene') ? 'parent' : 'scene';
    link = createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&element=' + element + '&sceneID=' + sceneID);

    $('#sceneIdBox').load(link, function()
    {
        $(this).find('select').chosen();
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
    /* Fix task #4832. Auto adjust textarea height. */
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
            else $steps.append($step);
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
