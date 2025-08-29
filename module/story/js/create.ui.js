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
    if(!source) return;
    $('.feedbackBox').toggleClass('hidden', !feedbackSource.includes(source));
}
waitDom('[name=source]', function(){toggleFeedback($('[name=source]'));});

window.loadProduct = function(e)
{
    const $this     = $(e.target);
    const productID = $this.val();
    const $modal    = $this.closest('.modal');
    const inModal   = $modal.length > 0;
    if(inModal)  loadModal($.createLink(storyType, 'create', 'productID=' + productID + '&' + createParams), $modal.attr('id'));
    if(!inModal) loadPage($.createLink(storyType, 'create', 'productID=' + productID + '&' + createParams));
};

window.setLane = function(e)
{
    const regionID = $(e.target).val();
    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.getJSON(laneLink, function(data)
    {
        const laneID = data.items.length > 0 ? data.items[0].value : '';
        $('[name=lane]').zui('picker').render(data);
        $('[name=lane]').zui('picker').$.setValue(laneID);
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

window.loadGrade = function(e)
{
    const parent = e.target.value;
    const link   = $.createLink('story', 'ajaxGetGrade', 'parent=' + parent + '&type=' + storyType);
    $.getJSON(link, function(data)
    {
        const $grade = $('[name=grade]').zui('picker');
        $grade.render({items: data.items});
        $grade.$.setValue(data.default);
    })
}
