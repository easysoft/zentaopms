window.loadStatistic = function(event)
{
    event.preventDefault();
    event.stopPropagation();

    const $dom   = $(event.target).closest('.block-statistic-nav-item');
    const target = $dom.data('target');
    if(!target) return false;

    const params = target.split('_');
    if(params.length < 2) return false;

    $('#issueStatistics .block-statistic-nav-item').removeClass('active');
    $dom.addClass('active');

    loadTarget($.createLink('codescan', 'ajaxGetRepoIssueStatistics', `repoID=${params[2]}`), '#repoStatisticChart');
};
