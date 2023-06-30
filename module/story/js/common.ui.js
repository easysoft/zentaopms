window.customSubmit = function(e)
{
    const $saveButton      = $('#saveButton');
    const $saveDraftButton = $('#saveDraftButton');

    $saveButton.attr('disabled', 'disabled');
    $saveDraftButton.attr('disabled', 'disabled');

    var $this = $(e.target);
    if($this.prop('tagName') != 'BUTTON') $this = $this.closest('button');

    var storyStatus = !$('#reviewer').val().join(',') || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
    if($this.attr('id') == 'saveDraftButton')
    {
        storyStatus = 'draft';
        if(config.currentMethod == 'change') storyStatus = 'changing';
        if(config.currentMethod == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
    }
    if($('#dataform #status').length == 0) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('id', 'status').attr('value', storyStatus).appendTo('#dataform .form-actions');
    $('#dataform #status').val(storyStatus);

    $dataform = $('#dataform');
    $.ajaxSubmit(
    {
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
            $this.after("<div class='form-tip ajax-form-tip text-danger' id='" + id + "Tip'>" + message[id] + '</div>');
            document.getElementById(id).focus();
        }
    }
    if(varType === 'string') zui.Messager.show({"content": message, "type": "success circle"});
}

window.unlinkTwins = function(e)
{
    const $this    = $(e.target).closest('li').find('.relievedTwins');
    const postData = new FormData();
    postData.append('twinID', $this.data('id'));
    zui.Modal.confirm({message: relievedTip, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.post($.createLink('story', 'ajaxRelieveTwins'), postData, function(){$this.closest('li').remove()});
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
    var productID       = $('#product').val();
    var branchID        = $('#branch').val();
    var moduleID        = typeof(allURS) == 'undefined' ? $('#module').val() : 0;
    var requirementList = $('#URS').val();
    requirementList     = requirementList ? requirementList.join(',') : '';
    if(typeof(branchID) == 'undefined') branchID = 0;

    var link = $.createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);
    $('.URSBox').load(link);
};

window.loadProductModules = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('#branch').val();
    if(!branch) branch = 0;

    var currentModule = 0;
    if(config.currentMethod == 'edit') currentModule = $('#module').val();

    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + currentModule);
    var $moduleIdBox = $('#moduleIdBox');
    $moduleIdBox.load(moduleLink, function()
    {
        //$moduleIdBox.find('#module').chosen()
    });
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

window.loadBranch = function()
{
    var branch    = $('#branch').val();
    var productID = $('#product').val();
    if(typeof(branch) == 'undefined') branch = 0;

    loadProductModules(productID, branch);
    loadProductPlans(productID, branch);
};
