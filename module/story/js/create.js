$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');

        if($(this).is(':checked'))
        {
            $('#reviewerBox').closest('tr').addClass('hidden');
            $('#reviewerBox').removeClass('required');
            $('#dataform #needNotReview').val(1);
        }
        else
        {
            $('#reviewerBox').closest('tr').removeClass('hidden');
            $('#reviewerBox').addClass('required');
            $('#dataform #needNotReview').val(0);
        }

        getStatus('create', "product=" + $('#product').val() + ",execution=" + executionID + ",needNotReview=" + ($(this).prop('checked') ? 1 : 0));
    });
    $('#needNotReview').change();

    // init pri selector
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    $('#source').on('change', function()
    {
        if(storyType == 'requirement' && systemMode != 'PLM') return false;

        var $sourceBox = $(this).closest('tr.sourceBox');
        var source     = $(this).val();
        if($.inArray(source, feedbackSource) != -1)
        {
            if($sourceBox.length > 0)
            {
                $(this).closest('td').attr('colspan', '1');
                $sourceBox.find('.sourceTd').attr('colspan', '1');
            }
            $('#feedbackBox').removeClass('hidden');
        }
        else
        {
            if($sourceBox.length > 0)
            {
                $(this).closest('td').attr('colspan', '2');
                $sourceBox.find('.sourceTd').attr('colspan', '2');
            }
            $('#feedbackBox').addClass('hidden');
        }
    });

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('createFields');
        return false;
    });

    $('#module').on('change', function(){ loadURS(); });
    if($('form select[id^=branches]').length > 0) loadURS();

    if($(".table-form select[id^='branches']").length == $('.switchBranch #branchBox option').length)
    {
        $('.table-col .icon-plus').parent().css('pointer-events', 'none')
        $('.table-col .icon-plus').parent().addClass('disabled')
    }

    $.get(createLink('product', 'ajaxGetProductById', "productID=" + $('#product').val()), function(data)
    {
        $.cookie('branchSourceName', data.branchSourceName)
        $.cookie('branchName', data.branchName)
    }, 'json')
});

/**
 * Load assignedTo.
 *
 * @access public
 * @return void
 */
function loadAssignedTo()
{
    var assignees = $('#reviewer').val();
    var link      = createLink('story', 'ajaxGetAssignedTo', 'type=create&storyID=0&assignees=' + assignees);
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}

function refreshPlan()
{
    loadProductPlans($('#product').val(), $('#branch').val());
}

/**
 * Set lane.
 *
 * @param  int $regionID
 * @access public
 * @return void
 */
function setLane(regionID)
{
    laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.get(laneLink, function(lane)
    {
        if(!lane) lane = "<select id='lane' name='lane' class='form-control'></select>";
        $('#lane').replaceWith(lane);
        $('#lane' + "_chosen").remove();
        $('#lane').next('.picker').remove();
        $('#lane').chosen();
    });
}

$(window).unload(function(){
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});

/**
 * Add branch box.
 *
 * @param  obj   $obj
 * @access public
 * @return void
 */
 function addBranchesBox(obj)
 {
    $('#storyNoticeBranch').closest('tr').removeClass('hidden');
    if($(".table-form select[id^='branches']").length == $('.switchBranch #branchBox option').length) return false;

    var selectedVal = [];
    $(".table-form select[id^='branches']").each(function()
    {
        var selectedProduct = $(this).val();
        if($.inArray(selectedProduct, selectedVal) < 0) selectedVal.push(selectedProduct);
    });

    var branch = 0;
    $('.table-form #branches0 option').each(function(){
        if($.inArray($(this).val(), selectedVal) < 0)
        {
            branch = $(this).val();
            return false;
        }
    });

     var item = $('#addBranchesBox').html().replace(/%i%/g, itemIndex);
     $(obj).closest('tr').after('<tr class="addBranchesBox' + itemIndex + '">' + item  + '</tr>');
     $('#branches_i__chosen').remove();
     $('#branches' + itemIndex).chosen();
     $('#modules_i__chosen').remove();
     $('#modules' + itemIndex).chosen();
     $('#plans_i__chosen').remove();
     $('#plans' + itemIndex).chosen();
     $('.addBranchesBox' + itemIndex + ' #planIdBox').css('flex', '0 0 ' + gap + 'px');

     $.ajaxSettings.async = false;
     loadBranchForTwins($('#product').val(), branch, itemIndex)
     loadModuleForTwins($('#product').val(), branch, itemIndex)
     loadPlanForTwins($('#product').val(), branch, itemIndex)
     $.ajaxSettings.async = true;
     $('.addBranchesBox' + itemIndex + ' #branchBox .input-group .input-group-addon').html($.cookie('branchSourceName'))

     disableSelectedBranches();

    if($(".table-form select[id^='branches']").length == $('.switchBranch #branchBox option').length)
    {
        $('.table-col .icon-plus').parent().css('pointer-events', 'none')
        $('.table-col .icon-plus').parent().addClass('disabled')
    }

    if(requiredFields.indexOf('module') > 0) $('#modules' + itemIndex + '_chosen').addClass('required')
    if(requiredFields.indexOf('plan') > 0) $('#plans' + itemIndex + '_chosen').addClass('required')

    itemIndex ++;
 }

 /**
  * Delete branch box.
  *
  * @param  obj  $obj
  * @access public
  * @return void
  */
 function deleteBranchesBox(obj)
 {
     $(obj).closest('tr').remove();

     disableSelectedBranches();

     $('.icon-plus').parent().css('pointer-events', 'auto')
     $('.icon-plus').parent().removeClass('disabled')
     if($('select[name^="branches"]').length == 2) $('#storyNoticeBranch').closest('tr').addClass('hidden');
 }

 /**
 * Make the selected branch non clickable.
 *
 * @return void
 */
function disableSelectedBranches()
{
    $(".table-form select[id^='branches'] option[disabled='disabled']").removeAttr('disabled');

    var selectedVal = [];
    $(".table-form select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        if($.inArray(selectedBranch, selectedVal) < 0) selectedVal.push(selectedBranch);
    })

    $(".table-form select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        $(this).find('option').each(function()
        {
            var optionVal = $(this).attr('value');
            if(optionVal != selectedBranch && $.inArray(optionVal, selectedVal) >= 0) $(this).attr('disabled', 'disabled');
        })
    })

    $(".table-form select[id^=branches]").trigger('chosen:updated');
    loadURS();
}

 /**
 * Load branch for multi branch or multi platform.
 *
 * @param  int   $branch
 * @param  int   $branchIndex
 * @access public
 * @return void
 */
function loadBranchRelation(branch, branchIndex)
{
    var productID = $('#product').val();
    if(typeof(branch) == 'undefined') branch = 0;

    $.ajaxSettings.async = false;
    loadModuleForTwins(productID, branch, branchIndex)
    loadPlanForTwins(productID, branch, branchIndex)
    loadURS()
    $.ajaxSettings.async = true;

    disableSelectedBranches()
}

/**
 * Load branch for twins.
 *
 * @paran  int   $procutID
 * @param  int   $branch
 * @param  int   $branchIndex
 * @access public
 * @return void
 */
function loadBranchForTwins(productID, branch, branchIndex)
{
    var isTwins = storyType == 'story' ? 'yes' : 'no';
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=" + branch + "&param=active&projectID=" + executionID + "&withMainBranch=1&isTwins=" + isTwins + "&fieldID=" + branchIndex), function(data)
    {
        if(data)
        {
            /* reload branch */
            $('#branches' + branchIndex).replaceWith(data);
            $('#branches' + branchIndex + "_chosen").remove();
            $('#branches' + branchIndex).next('.picker').remove();
            $('#branches' + branchIndex).chosen();
        }
    })
}

/**
 * Load module for twins.
 *
 * @paran  int   $procutID
 * @param  int   $branch
 * @param  int   $branchIndex
 * @access public
 * @return void
 */
function loadModuleForTwins(productID, branch, branchIndex)
{
    /* Load module */
    var currentModule = 0;
    var moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + branchIndex + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModule);
    if(branchIndex > 0)
    {
        var $moduleIDBox = $('.addBranchesBox'+ branchIndex +' #moduleIdBox');
    }
    else
    {
        var $moduleIDBox = $('.switchBranch #moduleIdBox');
    }
    $moduleIDBox.load(moduleLink, function()
    {
        $moduleIDBox.find('#modules' + branchIndex).chosen();
        if(branchIndex == 0)
        {
            $('.switchBranch #moduleIdBox > span:first-child').remove()
        }
        $moduleIDBox.prepend("<span class='input-group-addon fix-border'>" + storyModule + "</span>" );

        $moduleIDBox.fixInputGroup();
    });

    if(requiredFields.indexOf('module') > 0) $('#moduleIdBox #modules' + branchIndex + '_chosen').addClass('required')
}

/**
 * Load plan for twins.
 *
 * @paran  int   $procutID
 * @param  int   $branch
 * @param  int   $branchIndex
 * @access public
 * @return void
 */
function loadPlanForTwins(productID, branch, branchIndex)
{
    /* Load plan */
    if(branch == '0') branch = '';
    planLink = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=0&fieldID=' + branchIndex + '&needCreate=false&expired=unexpired&param=skipParent,forStory,' + config.currentMethod);
    if(branchIndex > 0)
    {
        var $planIdBox = $('.addBranchesBox'+ branchIndex +' #planIdBox');
    }
    else
    {
        var $planIdBox = $('#planIdBox');
    }
    $planIdBox.load(planLink, function()
    {
        $planIdBox.find('#plans' + branchIndex).chosen();
        $planIdBox.prepend("<span class='input-group-addon fix-border'>" + storyPlan + "</span>");
        $planIdBox.fixInputGroup();
    });
}
