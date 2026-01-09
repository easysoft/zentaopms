window.clickSubmit = function(e)
{
    const status = $(e.submitter).data('status');
    if(status === undefined) return;

    const method = typeof(page) !== 'undefined' ? page : config.currentMethod;
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
    zui.Modal.confirm({message: window.relievedTip || $ul.data('relievedTip'), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
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
    if(!source) return;
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
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

    let params     = config.currentMethod == 'create' ? 'unexpired,noclosed' : '';
    let planLink   = $.createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&params=' + params + '&skipParent=true');
    let $planIdBox = $('div[data-name="plan"] #planIdBox');

    $.get(planLink, function(data)
    {
        let items = JSON.parse(data);
        let $inputGroup = $planIdBox.closest('.input-group');
        $inputGroup.html("<span id='planIdBox'><div class='picker-box' id='plan'></div></span>")
        new zui.Picker('#planIdBox #plan', {items: items, name: 'plan', defaultValue: ''});
        if(items.length == 0)
        {
            $inputGroup.append('<a class="btn btn-default" type="button" data-size="lg" data-toggle="modal" href="' + $.createLink('productplan', 'create', 'productID=' + productID + '&branch=' + branch) + '"><i class="icon icon-plus"></i></a>');
            $inputGroup.append(`<button class="refresh btn" type="button" onclick="window.loadProductPlans(${productID},${branch})"><i class="icon icon-refresh"></i></button>`);
        }
    })
};

window.loadBranch = function()
{
    var branch    = $('[name=branch]').val();
    var productID = $('[name=product]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    if($('[name=roadmap]').length)
    {
        window.loadProductRoadmaps(productID, branch);
    }
    else
    {
        window.loadProductPlans(productID, branch);
    }

    window.loadBranchModule(productID);
};

window.setModuleAndPlanByBranch = function(e)
{
    const $branch  = $(e.target);
    const branchID = $branch.val();
    let $row       = $branch.closest('tr');

    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&extra=nodeleted');

    while($row.length)
    {
        const $modulePicker = $row.find('[name^=module]').zui('picker');
        const moduleID      = $row.find('[name^=module]').val();
        $.getJSON(moduleLink, function(data)
        {
            $modulePicker.render({items: data.items})
            $modulePicker.$.setValue(moduleID);
        });

        $row = $row.next('tr');
        if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
    }

    var planLink = $.createLink('productPlan', 'ajaxGetProductPlans', 'productID=' + productID + '&branch=' + branchID);
    let $rows    = $branch.closest('tr');
    while($rows.length)
    {
        const $planPicker = $rows.find('[name^=plan]').zui('picker');
        const planID      = $rows.find('[name^=plan]').val();
        $.getJSON(planLink, function(data)
        {
            $planPicker.render({items: data})
            $planPicker.$.setValue(planID);
        });

        $rows = $rows.next('tr');
        if(!$rows.find('td[data-name="plan"][data-ditto="on"]').length) break;
    }
}
