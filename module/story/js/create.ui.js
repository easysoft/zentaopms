var itemIndex = 1;

window.toggleReviewer = function(obj)
{
    const $this     = $(obj);
    const isChecked = $this.prop('checked');

    $reviewer = $('[name^=reviewer]').zui('picker');
    options   = $reviewer.options;
    if(isChecked)
    {
        options.disabled = true;
        $reviewer.render(options);
        $('#reviewerBox').closest('.form-row').addClass('hidden');
        $('#needNotReview').val(1);
        $('input[name=needNotReview]').val(1);
    }
    else
    {
        options.disabled = false;
        $reviewer.render(options);
        $('#reviewerBox').closest('.form-row').removeClass('hidden');
        $('#needNotReview').val(0);
        $('input[name=needNotReview]').val(0);
    }
}

window.toggleFeedback = function(obj)
{
    if(storyType == 'requirement') return false;

    const $this  = $(obj);
    const source = $this.val();
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
}

$(document).on('change', '#module', function(){loadURS();})

if(branchCount <= 1)
{
    $('.switchBranch .addNewLine').css('pointer-events', 'none')
    $('.switchBranch .addNewLine').addClass('disabled')
}

window.loadProduct = function(e)
{
    const $this     = $(e.target);
    const productID = $this.val();
    const $modal    = $this.closest('.modal');
    const inModal   = $modal.length > 0;
    if(inModal)  loadModal($.createLink('story', 'create', 'productID=' + productID + '&' + createParams), $modal.attr('id'));
    if(!inModal) loadPage($.createLink('story', 'create', 'productID=' + productID + '&' + createParams));
};

window.loadBranchRelation = function(e)
{
    const $this       = $(e.target);
    const $picker     = $this.zui('picker');
    const branch      = $picker.$.value;
    const branchIndex = $picker.options.index;
    const productID   = $('[name=product]').val();

    loadModuleForTwins(productID, branch, branchIndex)
    loadPlanForTwins(productID, branch, branchIndex)

    disableSelectedBranches()
};

window.addBranchesBox = function(e)
{
    const productID     = $('[name=product]').val();
    const $formRow      = $(e.target).closest('.form-row');
    const $branchPicker = $('.switchBranch #branchBox .picker-box').zui('picker');
    const $modulePicker = $('.switchBranch #moduleIdBox .picker-box').zui('picker');
    const $planPicker   = $('.switchBranch #planIdBox .picker-box').zui('picker');

    $('#storyNoticeBranch').removeClass('hidden');
    if($("form [name^='branches']").length == $branchPicker.options.items.length) return false;

    var selectedVal = [];
    $("form [name^='branches']").each(function()
    {
        var selectedProduct = $(this).val();
        if(!selectedVal.includes(selectedProduct)) selectedVal.push(selectedProduct);
    });

    var branch = 0;
    $branchPicker.options.items.forEach(function(item)
    {
        if(!selectedVal.includes(item.value))
        {
            branch = item.value;
            return false;
        }
    });

     var $newLine = $('#addBranchesBox').clone();
     $formRow.after($newLine);

     $newLine.addClass('newLine').removeClass('hidden').addClass('addBranchesBox' + itemIndex).removeAttr('id');
     $newLine.find('#branches').addClass('picker-box').attr('id', 'branches_' + itemIndex).attr('data-on', 'change').attr('data-call', 'loadBranchRelation').attr('data-params', 'event').picker($.extend({}, $branchPicker.options, {name: "branches[" + itemIndex + "]", index: itemIndex, afterRender: function(){disableSelectedBranches()}}));
     $newLine.find('#modules').addClass('picker-box').attr('id', 'modules_' + itemIndex).picker($.extend({}, $modulePicker.options, {name: "modules[" + itemIndex + "]"}));
     $newLine.find('#plans').addClass('picker-box').attr('id', 'plans_' + itemIndex).picker($.extend({}, $planPicker.options, {name: "plans[" + itemIndex + "]"}));
     $newLine.find('.addNewLine').on('click', addBranchesBox);
     $newLine.find('.removeNewLine').on('click', deleteBranchesBox);


     loadModuleForTwins(productID, branch, itemIndex)
     loadPlanForTwins(productID, branch, itemIndex)

    if($("form [id^='branches']").length == $branchPicker.options.items.length)
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
     if($('form [name^="branches"]').length < 2) $('#storyNoticeBranch').addClass('hidden');
};

window.loadProductPlans = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    let planID     = $('[name=plan]').val();
    let param      = config.currentMethod == 'edit' ? 'skipParent|forStory' : 'skipParent';
    let expired    = config.currentMethod == 'create' ? 'unexpired' : '';
    let planLink   = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=' + planID + '&fieldID=&needCreate=true&expired='+ expired +'&param=skipParent,forStory,' + config.currentMethod);
    let $planIdBox = $('#planIdBox');

    $.get(planLink, function(data)
    {
        let items = JSON.parse(data);
        let $inputGroup = $planIdBox.closest('.input-group');
        $inputGroup.html("<span id='planIdBox'><div class='picker-box' id='plan'></div></span>")
        new zui.Picker('#planIdBox #plan', {items: items, name: 'plan', defaultValue: planID.toString()});
        if(items.length == 0)
        {
            $inputGroup.append('<a class="btn btn-default" type="button" data-toggle="modal" href="' + $.createLink('productplan', 'create', 'productID=' + productID + '&branch=' + branch) + '"><i class="icon icon-plus"></i></a>');
            $inputGroup.append('<button class="refresh btn" type="button" onclick="loadProductPlans(' + productID + ')"><i class="icon icon-refresh"></i></button>');
        }
    })
};

window.setLane = function(e)
{
    const regionID = $(e.target).val();
    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.get(laneLink, function(lane)
    {
        $('#myPicker').picker(JSON.parse(lane));
    });
};

function loadModuleForTwins(productID, branch, branchIndex)
{
    /* Load module */
    var currentModule = 0;
    var moduleLink    = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + branchIndex + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModule);
    if(branchIndex > 0)
    {
        var $moduleIdBox = $('.addBranchesBox' + branchIndex + ' #moduleIdBox');
    }
    else
    {
        var $moduleIdBox = $('.switchBranch #moduleIdBox');
    }

    $.get(moduleLink, function(data){$moduleIdBox.find('.picker-box').picker(JSON.parse(data));});
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

    $.get(planLink, function(data){$planIdBox.find('.picker-box').picker(JSON.parse(data));});
}

function disableSelectedBranches()
{
    let selectedVal = [];
    let $pickers    = [];
    $("form [name^='branches']").each(function()
    {
        let $picker = $(this).zui('picker');
        let value   = $picker.$.value;

        $pickers.push($picker);
        if(!selectedVal.includes(value)) selectedVal.push(value);
    })

    $pickers.forEach(function($picker)
    {
        let value   = $picker.$.value;
        let options = $picker.options;
        let items   = [];
        options.items.forEach(function(item)
        {
            item = $.extend({}, item, {disabled: false});
            if(value !== item.value && selectedVal.includes(item.value)) item = $.extend({}, item, {disabled: true});
            items.push(item);
        })
        options.items = items;
        delete options.afterRender;

        $picker.render(options);
    });
}
