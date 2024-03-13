window.clickSubmit = function(e)
{
    const status = $(e.submitter).data('status');
    if(status === undefined) return;

    const method = config.currentMethod;
    let storyStatus = status;
    if(status == 'active' && method != 'batchcreate')
    {
        storyStatus = !$('[name^=reviewer]').val() || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
    }
    if(status == 'draft' && (method == 'change' || (method == 'edit' && $('#status').val() == 'changing')))
    {
        storyStatus = 'changing';
    }
    $(e.submitter).closest('form').find('[name=status]').val(storyStatus);
};

window.unlinkTwins = function(e)
{
    const $this    = $(e.target).closest('li').find('.relievedTwins');
    const $ul      = $this.closest('ul');
    const postData = new FormData();
    postData.append('twinID', $this.data('id'));
    zui.Modal.confirm({message: relievedTip, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res)
        {
            $.post($.createLink('story', 'ajaxRelieveTwins'), postData, function()
            {
                $this.closest('li').remove();
                if($ul.find('li').length == 0) $ul.closest('.section').remove();
            });
        }
    });
};

window.toggleFeedback = function(obj)
{
    if(storyType == 'requirement') return false;

    const $this  = $(obj);
    const source = $this.val();
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
}

window.loadURS = function(e)
{
    const eventType = typeof e == 'undefined' ? '' : e.type;
    const productID = $('[name=product]').val();

    let requirementList = $('[name=URS]').val();
    requirementList = requirementList ? encodeURIComponent(requirementList.join(',')) : '';

    let moduleID = 0;
    if(eventType == 'change') moduleID = $('[name=module]').val();
    if(eventType == 'click' && !$(e.target).prop('checked')) moduleID = $('[name=module]').val();
    if($('#loadURS').prop('checked')) moduleID = 0;

    let branchID  = 0;
    let $branches = $('input[name^=branches]');
    if($branches.length > 0)
    {
        branchIdList = [];
        $branches.each(function()
        {
            var currentBranch = $(this).val();
            if(currentBranch == '') currentBranch = 0;
            if(!branchIdList.includes(currentBranch)) branchIdList.push(currentBranch);
        })
        branchID = branchIdList.join(',');
    }

    var link = $.createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        $URS = $('#URS').zui('picker');
        $URS.render({items: data.items});
        $URS.$.setValue($URS.$.value);
    })
};

window.loadGrade = function(e)
{
    const parent = e.target.value;
    const link   = $.createLink('story', 'ajaxGetGrade', 'parent=' + parent + '&type=' + storyType);
    $.get(link, function(data)
    {
        const $grade = $('[name=grade]').zui('picker');
        data = JSON.parse(data);
        $grade.render({items: data.items});
        $grade.$.setValue(data.default);
    })
}

window.loadBranchModule = function(productID)
{
    const branch   = $('[name=branch]').val();
    const moduleID = $('[name=module]').val();
    if(!branch) branch = 0;

    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&extra=nodeleted&currentModuleID=' + moduleID);

    const $modulePicker = $('[name^=module]').zui('picker');
    $.getJSON(moduleLink, function(data)
    {
        $modulePicker.render({items: data.items})
        $modulePicker.$.setValue(moduleID);
    });
};

window.loadProductPlans = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    let planID     = $('[name=plan]').val();
    let expired    = config.currentMethod == 'create' ? 'unexpired' : '';
    let planLink   = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=' + planID + '&fieldID=&needCreate=true&expired='+ expired +'&param=skipParent,forStory,' + config.currentMethod);
    let $planIdBox = $('#planIdBox');

    $.get(planLink, function(data)
    {
        if(data && data != '[]')
        {
            let items = JSON.parse(data);
            let $inputGroup = $planIdBox.closest('.input-group');
            $inputGroup.html("<span id='planIdBox'><div class='picker-box' id='plan'></div></span>")
            new zui.Picker('#planIdBox #plan', {items: items, name: 'plan', defaultValue: planID.toString()});
            if(items.length == 0)
            {
                $inputGroup.append('<a class="btn btn-default" type="button" data-toggle="modal" href="' + $.createLink('productplan', 'create', 'productID=' + productID + '&branch=' + branch) + '"><i class="icon icon-plus"></i></a>');
                $inputGroup.append('<button class="refresh btn" type="button" onclick="window.loadProductPlans(' + productID + ')"><i class="icon icon-refresh"></i></button>');
            }
        }
    })
};

window.loadBranch = function()
{
    var branch    = $('[name=branch]').val();
    var productID = $('[name=product]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    window.loadProductPlans(productID, branch);
    window.loadURS();
};
