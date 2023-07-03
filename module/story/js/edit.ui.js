window.loadProduct = function()
{
    const productID = $('#product').val();
    if(twins && productID != oldProductID)
    {
        confirmRelievedTwins = confirm(relievedTwinsTip);
        if(!confirmRelievedTwins)
        {
            $('#product').val(oldProductID);
            return false;
        }
    }

    if(parentStory)
    {
        confirmLoadProduct = confirm(moveChildrenTips);
        if(!confirmLoadProduct)
        {
            $('#product').val(oldProductID);
            return false;
        }
    }

    loadProductBranches(productID);
    loadProductReviewers(productID);
    loadURS();

    if(storyType == 'story')
    {
        var storyLink = $.createLink('story', 'ajaxGetParentStory', 'productID=' + productID + '&labelName=parent');
        $.get(storyLink, function(data)
        {
            $('#parent').replaceWith(data);
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
    $this = $(obj);
    $('#reviewer').val($this.prop('checked') ? '' : lastReviewer).attr('disabled', $this.prop('checked') ? 'disabled' : null);
};

window.changeReviewer = function()
{
    if(storyStatus == 'reviewing')
    {
        if(!$('#reviewer').val())
        {
            zui.Modal.alert(reviewerNotEmpty);
            $('#reviewer').val(reviewers);
        }
        else
        {
            reviewers = $('#reviewer').val();
        }
    }
    else
    {
        if(!$('#reviewer').val())
        {
            $('#needNotReview').prop('checked', true);
            changeNeedNotReview($('#needNotReview'));
        }
    }
}

if(!$('#reviewer').val()) changeNeedNotReview($('#needNotReview'));

function loadProductBranches(productID)
{
    var param   = 'all';
    var isTwins = 'no';

    var $product   = $('#product');
    var $branchBox = $product.closest('.row').find('.branchIdBox');
    $branchBox.addClass('hidden');
    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=0&param=" + param + "&projectID=" + executionID + "&withMainBranch=1&isTwins=" + isTwins), function(data)
    {
        if(data)
        {
            $branchBox.html(data).removeClass('hidden');
            $branchBox.find('#branch').attr('onchange', 'loadBranch()');
        }

        var branch = $('#branch').val();
        loadProductModules(productID, $branch);
        loadProductPlans(productID, $branch);
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
