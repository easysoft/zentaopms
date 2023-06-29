var itemIndex = 1;

window.toggleReviewer = function(obj)
{
    const $this     = $(obj);
    const isChecked = $this.prop('checked');

    $('#reviewer').attr('disabled', isChecked ? 'disabled' : null).trigger('chosen:updated');
    if(isChecked)
    {
        $('#reviewerBox').closest('.form-row').addClass('hidden');
        $('#needNotReview').val(1);
    }
    else
    {
        $('#reviewerBox').closest('.form-row').removeClass('hidden');
        $('#needNotReview').val(0);
    }
}
toggleReviewer($('#needNotReview[type=checkbox]'));

window.toggleFeedback = function(obj)
{
    if(storyType == 'requirement') return false;

    const $this  = $(obj);
    const source = $this.val();
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
}

$(document).on('change', '#module', function(){loadURS();})

if($("form select[id^='branches']").length == $('.switchBranch #branchBox option').length)
{
    $('.switchBranch .addNewLine').css('pointer-events', 'none')
    $('.switchBranch .addNewLine').addClass('disabled')
}

window.loadProduct = function(e)
{
    const $this     = $(e.target);
    const productID = $this.val();
    loadPage($.createLink('story', 'create', 'productID=' + productID + '&' + createParams))
};

window.loadBranch = function(e)
{
    var branch    = $('#branch').val();
    var productID = $('#product').val();
    if(typeof(branch) == 'undefined') branch = 0;

    loadProductModules(productID, branch);
    loadProductPlans(productID, branch);
};

window.loadProductModules = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('#branch').val();
    if(!branch) branch = 0;

    var currentModule = 0;
    if(config.currentMethod == 'edit') currentModule = $('#module').val();

    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + currentModule);
    var $moduleIdBox = $('#moduleIdBox');
    $moduleIdBox.load(moduleLink, function(){$moduleIdBox. find('#module').chosen()});
};

window.loadBranchRelation = function(e)
{
    const $this       = $(e.target);
    const branch      = $this.val();
    const branchIndex = $this.data('index');
    const productID   = $('#product').val();

    $.ajaxSettings.async = false;
    loadModuleForTwins(productID, branch, branchIndex)
    loadPlanForTwins(productID, branch, branchIndex)
    $.ajaxSettings.async = true;

    disableSelectedBranches()
};

window.addBranchesBox = function(e)
{
    const productID = $('#product').val();
    const $formRow  = $(e.target).closest('.form-row');

    $('#storyNoticeBranch').removeClass('hidden');
    if($("form select[id^='branches']").length == $('.switchBranch #branchBox option').length) return false;

    var selectedVal = [];
    $("form select[id^='branches']").each(function()
    {
        var selectedProduct = $(this).val();
        if(!selectedVal.includes(selectedProduct)) selectedVal.push(selectedProduct);
    });

    var branch = 0;
    $('.switchBranch [id^=branches] option').each(function()
    {
        if(!selectedVal.includes($(this).val()))
        {
            branch = $(this).val();
            return false;
        }
    });

     var $newLine = $('#addBranchesBox').clone();

     $newLine.addClass('newLine').removeClass('hidden').addClass('addBranchesBox' + itemIndex).removeAttr('id');
     $newLine.find('[id^=branches]').attr('name', 'branches[' + itemIndex + ']').attr('id', 'branches[' + itemIndex + ']').attr('data-index', itemIndex).on('change', loadBranchRelation);
     $newLine.find('[id^=modules]').attr('name', 'modules[' + itemIndex + ']').attr('id', 'modules[' + itemIndex + ']');
     $newLine.find('[id^=plans]').attr('name', 'plans[' + itemIndex + ']').attr('id', 'plans[' + itemIndex + ']');
     $newLine.find('.addNewLine').on('click', addBranchesBox);
     $newLine.find('.removeNewLine').on('click', deleteBranchesBox);

     //$('#branches_i__chosen').remove();
     //$('#branches' + itemIndex).chosen();
     //$('#modules_i__chosen').remove();
     //$('#modules' + itemIndex).chosen();
     //$('#plans_i__chosen').remove();
     //$('#plans' + itemIndex).chosen();
     //$('.addBranchesBox' + itemIndex + ' #planIdBox').css('flex', '0 0 ' + gap + 'px');
     $formRow.after($newLine);

     $.ajaxSettings.async = false;
     loadModuleForTwins(productID, branch, itemIndex)
     loadPlanForTwins(productID, branch, itemIndex)
     $.ajaxSettings.async = true;

     disableSelectedBranches();

    if($("form select[id^='branches']").length == $('.switchBranch #branchBox option').length)
    {
        $('.addNewLine').css('pointer-events', 'none')
        $('.addNewLine').addClass('disabled')
    }

    itemIndex ++;
};

window.deleteBranchesBox = function(e)
{
     $(e.target).closest('.form-row').remove();

     disableSelectedBranches();

     $('.addNewLine').css('pointer-events', 'auto')
     $('.addNewLine').removeClass('disabled')
     if($('form select[name^="branches"]').length == 2) $('#storyNoticeBranch').addClass('hidden');
};

window.loadProductPlans = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    var param      = config.currentMethod == 'edit' ? 'skipParent|forStory' : 'skipParent';
    var expired    = config.currentMethod == 'create' ? 'unexpired' : '';
    var planLink   = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=' + $('#plan').val() + '&fieldID=&needCreate=true&expired='+ expired +'&param=skipParent,forStory,' + config.currentMethod);
    var $planIdBox = $('#planIdBox');

    $planIdBox.load(planLink, function()
    {
        //$planIdBox.find('#plan').chosen();
    });
};

window.loadURS = function(allURS)
{
    var productID       = $('#product').val();
    var branchID        = $('#branch').val();
    var moduleID        = typeof(allURS) == 'undefined' ? $('#module').val() : 0;
    var requirementList = $('#URS').val();
    requirementList     = requirementList ? requirementList.join(',') : '';
    if(typeof(branchID) == 'undefined') branchID = 0;

    var link = $.createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);
    $('.URSBox').load(link);
};

window.setLane = function(e)
{
    const regionID = $(e.target).val();
    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.get(laneLink, function(lane)
    {
        if(!lane) lane = "<select id='lane' name='lane' class='form-control'></select>";
        $('#lane').replaceWith(lane);
        $('#lane' + "_chosen").remove();
        $('#lane').next('.picker').remove();
        $('#lane').chosen();
    });
};

function loadModuleForTwins(productID, branch, branchIndex)
{
    /* Load module */
    var currentModule = 0;
    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + branchIndex + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModule);
    if(branchIndex > 0)
    {
        var $moduleIdBox = $('.addBranchesBox' + branchIndex + ' #moduleIdBox');
    }
    else
    {
        var $moduleIdBox = $('.switchBranch #moduleIdBox');
    }

    $moduleIdBox.load(moduleLink, function()
    {
        //$moduleIdBox.find('[id^=#modules]').chosen();
    });
}

function loadPlanForTwins(productID, branch, branchIndex)
{
    /* Load plan */
    if(branch == '0') branch = '';
    planLink = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=0&fieldID=' + branchIndex + '&needCreate=false&expired=unexpired&param=skipParent,forStory,' + config.currentMethod);
    if(branchIndex > 0)
    {
        var $planIdBox = $('.addBranchesBox'+ branchIndex +' #planIdBox');
    }
    else
    {
        var $planIdBox = $('.switchBranch #planIdBox');
    }
    $planIdBox.load(planLink, function()
    {
        //$planIdBox.find('[id^=plans]').chosen();
    });
}

function disableSelectedBranches()
{
    $("form select[id^='branches'] option[disabled='disabled']").removeAttr('disabled');

    var selectedVal = [];
    $("form select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        if(!selectedVal.includes(selectedBranch)) selectedVal.push(selectedBranch);
    })

    $("form select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        $(this).find('option').each(function()
        {
            var optionVal = $(this).attr('value');
            if(optionVal != selectedBranch && selectedVal.includes(optionVal)) $(this).attr('disabled', 'disabled');
        })
    })

    //$("form select[id^=branches]").trigger('chosen:updated');
}
