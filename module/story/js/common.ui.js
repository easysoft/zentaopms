$(function()
{
    if(typeof(storyType) == 'undefined') storyType = '';
    if(typeof(rawModule) == 'undefined') rawModule = 'product';
    if(typeof(rawMethod) == 'undefined') rawMethod = '';
    if(typeof(execution) != 'undefined') rawModule = 'projectstory';
    app = $.cookie.get('tab');
    if(['project', 'projectstory'].indexOf(rawModule) === -1 && app != 'qa' && rawMethod != 'batchtotask')
    {
        let $storyNavbar    = $("#navbar .nav li a[data-id=" + storyType + ']');
        let $storySubNavbar = $('#subNavbar li a[data-id="' + storyType + '"]')
        if($storyNavbar.length > 0 || $storySubNavbar.length > 0)
        {
            $('#navbar .nav li a').removeClass('active');
            $storyNavbar.addClass('active');
            $storySubNavbar.addClass('active');
        }
    }
});

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
    zui.Modal.confirm({message: relievedTwinsTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
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

window.loadURS = function(allURS)
{
    var productID       = $('[name=product]').val();
    var branchID        = $('[name=branch]').length == 0 ? 0 : $('[name=#branch]').val();
    var moduleID        = typeof(allURS) == 'undefined' ? $('[name=module]').val() : 0;
    var requirementList = $('[name=URS]').val();
    requirementList     = requirementList ? encodeURIComponent(requirementList.join(',')) : '';
    if(typeof(branchID) == 'undefined') branchID = 0;

    var link = $.createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);
    $.get(link, function(data)
    {
        $('.URSBox').html("<div class='picker-box' id='URS'></div>");
        new zui.Picker('.URSBox #URS', JSON.parse(data));
    })
};

window.loadProductModules = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('[name=branch]').val();
    if(!branch) branch = 0;

    var currentModule = 0;
    if(config.currentMethod == 'edit') currentModule = $('[name=module]').val();

    var moduleLink   = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&extra=nodeleted&currentModuleID=' + currentModule);
    var $moduleIdBox = $('#moduleIdBox');
    $.get(moduleLink, function(data)
    {
        let $inputGroup = $moduleIdBox.closest('.input-group');
        data = JSON.parse(data);
        $inputGroup.html("<span id='moduleIdBox'><div class='picker-box' id='module'></div></span>");
        new zui.Picker('#moduleIdBox #module', data);
        if(data.items.length <= 1)
        {
            $inputGroup.append('<a class="btn btn-default" type="button" data-toggle="modal" href="' + $.createLink('tree', 'browse', 'rootID=' + productID + '&view=story&currentModuleID=0&branch=' + branch) + '"><span class="text">' + langTreeManage + '</span></a>');
            $inputGroup.append('<button class="refresh btn" type="button" onclick="loadProductModules(' + productID + ')"><i class="icon icon-refresh"></i></button>');
        }
    })
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

window.loadBranch = function()
{
    var branch    = $('[name=branch]').val();
    var productID = $('[name=product]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    loadProductModules(productID, branch);
    loadProductPlans(productID, branch);
};
