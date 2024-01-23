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

    if(parentStory)
    {
        confirmLoadProduct = confirm(moveChildrenTips);
        if(!confirmLoadProduct)
        {
            $product.$.setValue(oldProductID.toString());
            return false;
        }
    }

    loadProductBranches(productID);
    loadProductReviewers(productID);
    loadURS();

    if(storyType == 'story')
    {
        var storyLink = $.createLink('story', 'ajaxGetParentStory', 'productID=' + productID + '&labelName=parent');
        var $parent   = $('#parent').zui('picker');
        $.get(storyLink, function(data)
        {
            $parent.render(JSON.parse(data));
        });
    }
}

window.linkStories = function(e)
{
    var storyIdList = [];
    $('#linkStoriesBox input').each(function()
    {
        storyIdList.push($(this).val());
    });
    storyIdList = storyIdList.join(',');

    var link = $.createLink('story', 'linkStories', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList);
    if(storyType != 'story') link = $.createLink('story', 'linkRequirements', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList);

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
            $branch = new zui.Picker('.branchIdBox #branch', {items: JSON.parse(data), name: 'branch'});
            branch  = $branch.$.value;
        }

        window.loadProductModules(productID, branch);
        window.loadProductPlans(productID, branch);
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
