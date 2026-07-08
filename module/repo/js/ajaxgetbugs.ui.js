$(function()
{
    setTimeout(function()
    {
        window.arrowTabs('bugTabs', -2);
    }, 300);
});

window.editSubmit = function(dom)
{
    const $parentDom = $(dom).closest('.comment-block');
    $.ajaxSubmit({
        url: $.createLink('repo', 'editComment', 'id=' + $parentDom.data('id')),
        data: {commentText: $parentDom.find('.comment-form-div').find('textarea').val(), loadPage: currentLink.replace('%s', $parentDom.data('bug'))}
    });
}

window.editComment = function(dom)
{
    $(dom).hide();

    const $parentDom = $(dom).closest('.comment-block');
    $parentDom.find('.comment-content').hide();
    $parentDom.find('.comment-form-div').removeClass('hidden');
}

window.saveComment = function(dom)
{
    const $parentDom = $(dom).closest('.comment-block');
    const bugID     = $parentDom.data('id');
    $.ajaxSubmit({
        url: $.createLink('repo', 'addComment'),
        data: {comment: $parentDom.find('textarea').val(), objectID: bugID, loadPage: currentLink.replace('%s', bugID)}
    });
}

window.closeTabs = function()
{
    parent.removeReviewBug();
}
