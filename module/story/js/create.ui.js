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
        $('#reviewerBox').addClass('hidden');
        $('#needNotReview').val(1);
        $('input[name=needNotReview]').val(1);
    }
    else
    {
        options.disabled = false;
        $reviewer.render(options);
        $('#reviewerBox').removeClass('hidden');
        $('#needNotReview').val(0);
        $('input[name=needNotReview]').val(0);
    }
}

window.toggleFeedback = function(obj)
{
    const $this  = $(obj);
    const source = $this.val();
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
}
waitDom('[name=source]', function(){toggleFeedback($('[name=source]'));});

window.loadProduct = function(e)
{
    const $this     = $(e.target);
    const productID = $this.val();
    const $modal    = $this.closest('.modal');
    const inModal   = $modal.length > 0;
    if(inModal)  loadModal($.createLink('story', 'create', 'productID=' + productID + '&' + createParams), $modal.attr('id'));
    if(!inModal) loadPage($.createLink('story', 'create', 'productID=' + productID + '&' + createParams));
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
        if(items.length == 0) return;

        let $inputGroup = $planIdBox.closest('.input-group');
        $inputGroup.html("<div id='planIdBox' class='picker-box w-full'></div>")
        new zui.Picker('#planIdBox.picker-box', {items: items, name: 'plan', defaultValue: planID.toString()});
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

let formSettingLabelClicked = false;
$(document).on('click', 'form.form-setting-form .row label.state', function()
{
    if(formSettingLabelClicked) return;
    formSettingLabelClicked = true;

    let text = $(this).find('.text-clip').text();
    $('form.form-setting-form .row label.state').each(function()
    {
        if(text == langSource || text == langSourceNote)
        {
            $this = $(this);
            if(text == langSource     && langSourceNote == $this.find('.text-clip').text()) $this[0].click();
            if(text == langSourceNote && langSource     == $this.find('.text-clip').text()) $this[0].click();
        }
    })
    formSettingLabelClicked = false;
});
