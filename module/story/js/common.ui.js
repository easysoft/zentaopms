$(function()
{
    if(typeof(storyType) == 'undefined') storyType = '';
    if(typeof(rawModule) == 'undefined') rawModule = 'product';
    if(typeof(rawMethod) == 'undefined') rawMethod = '';
    if(typeof(app)       == 'undefined') app       = '';
    if(typeof(execution) != 'undefined') rawModule = 'projectstory';
    if(['project', 'projectstory'].indexOf(rawModule) === -1 && app != 'qa' && rawMethod != 'batchtotask')
    {
        if(app != 'my') $('#navbar .nav li a').removeClass('active');
        $("#navbar .nav li a[data-id=" + storyType + ']').addClass('active');
        $('#subNavbar li a[data-id="' + storyType + '"]').addClass('active');
    }
});

window.customSubmit = function(e)
{
    const $saveButton      = $('#saveButton');
    const $saveDraftButton = $('#saveDraftButton');

    $saveButton.attr('disabled', 'disabled');
    $saveDraftButton.attr('disabled', 'disabled');

    var $dataform = $('#dataform');
    var $this     = $(e.target);
    if($this.prop('tagName') != 'BUTTON') $this = $this.closest('button');

    var storyStatus = 'active';
    if(!$dataform.hasClass('form-batch')) storyStatus = !$('[name^=reviewer]').val() || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
    if($this.attr('id') == 'saveDraftButton')
    {
        storyStatus = 'draft';
        if(config.currentMethod == 'change') storyStatus = 'changing';
        if(config.currentMethod == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
    }
    if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
    $('#dataform #status').val(storyStatus);

    $.ajaxSubmit(
    {
        headers: $this.closest('.modal').length > 0 ? {'X-Zui-Modal': 'modal'} : {},
        data: new FormData($dataform[0]),
        url: $dataform.attr('action'),
        onSuccess: function(result) {loadPage(result.load)},
        onMessage: function(message) {showMessage(message)},
        onFail: function(result)
        {
            setTimeout(function()
            {
                $saveButton.removeAttr('disabled');
                $saveDraftButton.removeAttr('disabled');
            }, 500);
        },
    });

    e.stopPropagation();
    e.preventDefault();

    setTimeout(function()
    {
        $saveButton.removeAttr('disabled');
        $saveDraftButton.removeAttr('disabled');
    }, 10000);
};

function showMessage(message)
{
    var varType = typeof message;
    if(varType === 'object')
    {
        for(id in message)
        {
            var $this = $('#' + id);
            if($this.length == 0) return zui.Messager.show({"content": message[id], "type": "success circle"});

            $('#' + id + 'Tip').remove();
            $this.addClass('has-error');
            $this.parent().after("<div class='form-tip ajax-form-tip text-danger' id='" + id + "Tip'>" + message[id] + '</div>');
            document.getElementById(id).focus();
        }
    }
    if(varType === 'string') zui.Messager.show({"content": message, "type": "success circle"});
}

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

    var moduleLink   = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + currentModule);
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
