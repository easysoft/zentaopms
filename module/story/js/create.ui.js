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
    $('[id=syncToChild]').prop('checked', false);

    const parent = e.target.value;
    const link   = $.createLink('story', 'ajaxGetGrade', 'parent=' + parent + '&type=' + storyType);
    $.getJSON(link, function(data)
    {
        const $grade = $('[name=grade]').zui('picker');
        $grade.render({items: data.items});
        $grade.$.setValue(data.default);
    })
}

window.saveTmpInfo = function(e, controlName)
{
    $('[name=tmp' + controlName + ']').val($(e.target).val());
}

/**
 * 同步至子需求。
 * Sync to child.
 *
 * @access public
 * @return void
 */
window.syncToChild = function()
{
    const parentStoryID = $('[name=parent]').val();
    if(!parentStoryID || parentStoryID == 0) return;

    if($('[id=syncToChild]').prop('checked'))
    {
        $.getJSON($.createLink('story', 'ajaxGetParentStoryInfo', 'storyID=' + parentStoryID), function(data)
        {
            if(data.module)     $('[name=module]').zui('picker').$.setValue(data.module, true);
            if(data.category)   $('[name=category]').zui('picker').$.setValue(data.category, true);
            if(data.source)     $('[name=source]').zui('picker').$.setValue(data.source, true);
            if(data.mailto)     $('[name="mailto[]"]').zui('picker').$.setValue(data.mailto, true);
            if(data.pri)        $('[name=pri]').zui('priPicker').$.setValue(data.pri, true);
            if(data.title)      $('[name=title]').val(data.title);
            if(data.estimate)   $('[name=estimate]').val(data.estimate);
            if(data.sourceNote) $('[name=sourceNote]').val(data.sourceNote);
            if(data.keywords)   $('[name=keywords]').val(data.keywords);
            if(data.spec)       $('zen-editor[name=spec]')[0].setHTML(data.spec);
            if(data.verify)     $('zen-editor[name=verify]')[0].setHTML(data.verify);
            if(data.files)
            {
                $('[name="files[]"]').closest('[data-zui-fileselector]').zui('fileSelector').$.setFiles(Object.values(data.files));
                $('[name=fileList]').val(JSON.stringify(data.files));
            }
        });
    }
    else
    {
        $('[name=module]').zui('picker').$.setValue($('[name=tmpModule]').val() ? $('[name=tmpModule]').val() : 0);
        $('[name=pri]').zui('priPicker').$.setValue($('[name=tmpPri]').val() ? $('[name=tmpPri]').val() : 3);
        $('[name=category]').zui('picker').$.setValue($('[name=tmpCategory]').val());
        $('[name=source]').zui('picker').$.setValue($('[name=tmpSource]').val());
        $('[name="mailto[]"]').zui('picker').$.setValue($('[name=tmpMailto]').val());
        $('[name=title]').val($('[name=tmpTitle]').val());
        $('[name=estimate]').val($('[name=tmpEstimate]').val());
        $('[name=sourceNote]').val($('[name=tmpSourceNote]').val());
        $('[name=keywords]').val($('[name=tmpKeywords]').val());
        $('zen-editor[name=spec]')[0].setHTML($('[name=tmpSpec]').val());
        $('zen-editor[name=verify]')[0].setHTML($('[name=tmpVerify]').val());
        $('[name="files[]"]').closest('[data-zui-fileselector]').zui('fileSelector').$.setFiles([]);
        $('[name=fileList]').val('');
    }
}
