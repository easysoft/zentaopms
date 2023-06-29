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

    itemIndex ++;
};

window.deleteBranchesBox = function(e)
{
     $(obj).closest('tr').remove();

     disableSelectedBranches();

     $('.icon-plus').parent().css('pointer-events', 'auto')
     $('.icon-plus').parent().removeClass('disabled')
     if($('select[name^="branches"]').length == 2) $('#storyNoticeBranch').closest('tr').addClass('hidden');
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

    var link = createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);

    $.post(link, function(data)
    {
        $('#URS').replaceWith(data);
        $('#URS_chosen').remove();
        $('#URS').chosen();
    });
};

window.setLane = function(e)
{
    const regionID = $(e.target).val();
    const laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
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
    var moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + branchIndex + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModule);
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
    planLink = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=0&fieldID=' + branchIndex + '&needCreate=false&expired=unexpired&param=skipParent,forStory,' + config.currentMethod);
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
    $("select[id^='branches'] option[disabled='disabled']").removeAttr('disabled');

    var selectedVal = [];
    $("select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        if($.inArray(selectedBranch, selectedVal) < 0) selectedVal.push(selectedBranch);
    })

    $("select[id^='branches']").each(function()
    {
        var selectedBranch = $(this).val();
        $(this).find('option').each(function()
        {
            var optionVal = $(this).attr('value');
            if(optionVal != selectedBranch && $.inArray(optionVal, selectedVal) >= 0) $(this).attr('disabled', 'disabled');
        })
    })

    //$("select[id^=branches]").trigger('chosen:updated');
}
