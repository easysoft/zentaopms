/* 开始升级项目报告。*/
window.startUpgradeReports = function(event, reportList, upgradingReportsText, nextText)
{
    const $btn = $('#upgradeReportsBtn');
    if($btn.hasClass('is-finished')) return;

    event.preventDefault();
    const $progressBar = $('#upgradeReportsProgress').addClass('active').find('.progress-bar');
    $btn.attr('disabled', 'disabled').addClass('disabled').removeClass('primary').find('.text').text(upgradingReportsText);
    upgradeReports(reportList,
    {
        onProgress: (current, total) =>
        {
            $progressBar.css('width', (100 * current / total) + '%');
            $btn.find('.text').text(`${upgradingReportsText} (${current}/${total})`);
        }
    }).then(() =>
    {
        $btn.removeAttr('disabled').removeClass('disabled').addClass('primary is-finished').find('.text').text(nextText);
        $('#upgradeReportsProgress').removeClass('active')
    });
};

/* 更新升级项目报告进度。*/
async function upgradeReports(reportList, options)
{
    let current      = 0;
    const total      = reportList.length;
    const onProgress = options.onProgress;
    for(const report of reportList)
    {
        onProgress(current, total);
        await upgradeReport(report);
        await zui.delay(50);
        current ++;
    }
    onProgress(current, total);
    return reportList;
}

/* 升级项目报告。*/
async function upgradeReport(report)
{
    let result;
    await $.post($.createLink('upgrade', 'ajaxUpgradeProjectReport'), {data: report}, (res) =>
    {
        result = res.result === 'success' ? true : res.message;
    }, 'json');
    return result;
}
