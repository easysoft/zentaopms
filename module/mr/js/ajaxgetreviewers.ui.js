window.loadApprovalsBlock = function()
{
    $('.reviewerCount').text(mrLang.approvalReviewer + ': ' + reviewerCount);
    if(reviewerCount >= minReviewers)
    {
        $('.reviewerCountIcon').addClass('text-success icon-check').removeClass('text-danger icon-close');
    }
    else
    {
        $('.reviewerCountIcon').addClass('text-danger icon-close').removeClass('text-success icon-check');
    }

    if(reviewResult)
    {
        $('#approvalLabel').addClass('success').removeClass('danger').text(mrLang.checkStatusList['success']);
        $('.reviewResultIcon').addClass('text-success icon-check').removeClass('text-danger icon-close');
        $('.reviewResult').text(mrLang.reviewStatus + ': ' + mrLang.checkStatusList['success']);
    }
    else
    {
        $('#approvalLabel').addClass('danger').removeClass('success').text(mrLang.checkStatusList['fail']);
        $('.reviewResultIcon').addClass('text-danger icon-close').removeClass('text-success icon-check');
        $('.reviewResult').text(mrLang.reviewStatus + ': ' + mrLang.checkStatusList['fail']);
        if(typeof $('.checkMerge .border-success') != 'undefined')
        {
           $('.checkMerge .border-success').removeClass('border-success').addClass('border-danger').removeClass('border-success').addClass('border-danger');
           $('.checkMerge .bg-success').removeClass('bg-success').addClass('bg-danger').removeClass('bg-success').addClass('bg-danger');
           $('.checkMerge .text-success').removeClass('text-success').addClass('text-danger').removeClass('text-success').addClass('text-danger').text(mrLang.checkFailed);
        }
    }
}
