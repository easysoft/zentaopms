window.loadProduct = function()
{
    const $product  = $('[name=product]').zui('picker');
    const productID = $product.$.value;

    if(twins && productID != oldProductID)
    {
        confirmRelievedTwins = confirm(relievedTwinsTip);
        if(!confirmRelievedTwins)
        {
            $product.$.setValue(oldProductID.toString());
            return false;
        }
    }

    if(isParent == '1')
    {
        zui.Modal.confirm(moveChildrenTips).then((result) => {
            if(!result)
            {
                $product.$.setValue(oldProductID.toString(), true);
                loadProductBranches(oldProductID);
                return false;
            }
        });
    }

    loadProductBranches(productID);
    loadProductReviewers(productID);
}

window.linkStories = function(e)
{
    var storyIdList = [];
    $('#linkStoriesBox input').each(function()
    {
        storyIdList.push($(this).val());
    });
    storyIdList = storyIdList.join(',');

    var link = $.createLink('story', 'linkStories', 'storyID=' + storyID + '&browseType=bySearch&excludeStories=' + storyIdList);
    if(storyType != 'story') link = $.createLink('story', 'linkRequirements', 'storyID=' + storyID + '&browseType=bySearch&excludeStories=' + storyIdList);

    $('#linkStoriesLink').attr('data-url', link);
}

window.changeNeedNotReview = function(obj)
{
    var $this = $(obj);
    var isChecked = $this.prop('checked');
    var $reviewer = $('[name^="reviewer"]').zui('picker');

    if(isChecked)
    {
        $('#needNotReview').val(1);
        $('input[name=needNotReview]').val(1);
        $reviewer.render({disabled: true});
    }
    else
    {
        $('#needNotReview').val(0);
        $('input[name=needNotReview]').val(0);
        $reviewer.render({disabled: false});
    }
};

window.changeReviewer = function()
{
    var $reviewer     = $('[name^="reviewer"]');
    var reviewerCount = $reviewer.val().filter(Boolean).length;
    var $value        = $reviewer.val();

    const filteredArray = reviewedBy.filter(value => value !== '');
    const isContained   = filteredArray.every(element => $value.includes(element));

    if(!isContained)
    {
        zui.Modal.alert(notDeleted);
        $reviewer.zui('picker').$.setValue($value.concat(reviewedBy));
        return;
    }

    if(storyStatus == 'reviewing')
    {
        if(!reviewerCount)
        {
            zui.Modal.alert(reviewerNotEmpty);
            if(typeof(lastSeletedReviewer) == 'undefined') lastSeletedReviewer = storyReviewers.join();
            $reviewer.zui('picker').$.setValue(lastSeletedReviewer);
        }
        else
        {
            lastSeletedReviewer = $reviewer.val();
        }
    }
    else
    {
        if(!reviewerCount)
        {
            $('#needNotReview').prop('checked', true);
            changeNeedNotReview($('#needNotReview'));
        }
    }
}

window.waitDom('[name^="reviewer"]', function(){if(!$('[name^="reviewer"]').val().filter(Boolean).length) changeNeedNotReview($('#needNotReview'));})

function loadProductBranches(productID)
{
    var param   = 'all';
    var isTwins = 'no';
    var branch  = 0;

    var $product   = $('[name=product]');
    var $branchBox = $product.closest('.row').find('.branchIdBox');
    $branchBox.addClass('hidden');
    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=0&param=" + param + "&projectID=" + executionID + "&withMainBranch=1&isTwins=" + isTwins), function(data)
    {
        if(data && data != '[]')
        {
            $branchBox.html("<div class='picker-box' id='branch'></div>").removeClass('hidden');
            $branch = new zui.Picker('.branchIdBox #branch', {items: JSON.parse(data), name: 'branch', defaultValue: 0});
            branch  = $branch.$.value;
        }
    });

    window.loadProductModules(productID, branch);

    if($('[name=roadmap]').length)
    {
        window.loadProductRoadmaps(productID, branch);
    }
    else
    {
        window.loadProductPlans(productID, branch);
    }
}

window.loadProductModules = function(productID, branch)
{
    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=items&fieldID=&extra=nodeleted');
    $.getJSON(link, function(moduleItems)
    {
        let $modulePicker = $('[name=module]').zui('picker');
        $modulePicker.render({items: moduleItems});
        $modulePicker.$.setValue(0);
    });
}

function loadProductReviewers(productID)
{
    var reviewerLink  = $.createLink('product', 'ajaxGetReviewers', 'productID=' + productID + '&storyID=' + storyID);
    var needNotReview = $('#needNotReview').prop('checked');
    $('.reviewerBox').load(reviewerLink, function()
    {
        if(needNotReview) $('.reviewerBox #reviewer').attr('disabled', 'disabled');
    });
}

window.loadGrade = function(e)
{
    let parent = e.target.value;
    let link   = $.createLink('story', 'ajaxGetGrade', 'parent=' + parent + '&type=' + storyType);
    $.getJSON(link, function(options){
        const checkLink = $.createLink('story', 'ajaxCheckGrade', 'id=' + storyID + '&grade=' + options.default);
        $.getJSON(checkLink, function(data){
            if(data.result)
            {
                const $grade = $('[name=grade]').zui('picker');
                $grade.render({items: options.items});
                $grade.$.setValue(options.default);
            }
            else
            {
                zui.Modal.alert(data.message.grade);
                const $parent = $('[name=parent]').zui('picker');
                $parent.$.setValue(oldParent, true);

                let link = $.createLink('story', 'ajaxGetGrade', 'parent=' + oldParent + '&type=' + storyType);
                $.getJSON(link, function(options){
                    const $grade = $('[name=grade]').zui('picker');
                    $grade.render({items: options.items});
                    $grade.$.setValue(oldGrade);
                });
            }
        });
    })
}

window.checkGrade = function(e)
{
    const grade = e.target.value;
    const checkLink = $.createLink('story', 'ajaxCheckGrade', 'id=' + storyID + '&grade=' + grade);
    $.getJSON(checkLink, function(data){
        if(!data.result)
        {
            zui.Modal.alert(data.message.grade);
            const $grade = $('[name=grade]').zui('picker');
            $grade.$.setValue(oldGrade, true);
        }
    });
}
