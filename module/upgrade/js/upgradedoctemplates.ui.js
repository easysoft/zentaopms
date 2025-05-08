async function upgradeTemplateContent(docID, options)
{
    const template = await zui.fetchData(options.processUrl, [{docID}]);
    if(!template || !template.data) return false;

    const oldContent = template.data.content;
    const snap = await zui.Editor.htmlToSnap(oldContent);
    if(!snap) return false;

    let result;
    await $.post(zui.formatString(options.processUrl, {docID: docID}), {type: 'html'}, (res) =>
    {
        result = res.result === 'success' ? true : res.message;
    }, 'json');
    return result;
}

async function upgradeDocTemplates(idList, options)
{
    await zui.Editor.loadModule();
    const {onProgress} = options || {};
    let current = 0;
    const total = idList.html.length;
    for(const id of idList['html'])
    {
        onProgress(current, total);
        await upgradeTemplateContent(id, {processUrl: $.createLink('upgrade', 'ajaxUpgradeDocTemplate', 'docID={docID}')});
        await zui.delay(50);
        current++;
    }
    return idList;
}

window.startUpgradeDocTemplates = function(event, idList, upgradingDocTemplatesText, nextText)
{
    const $btn = $('#upgradeDocTemplatesBtn');
    if($btn.hasClass('is-finished')) return;

    event.preventDefault();
    const $progressBar = $('#upgradeDocTemplatesProgress').addClass('active').find('.progress-bar');
    $btn.attr('disabled', 'disabled').addClass('disabled').removeClass('primary').find('.text').text(upgradingDocTemplatesText);
    upgradeDocTemplates(idList,
    {
        onProgress: (current, total) =>
        {
            $progressBar.css('width', (100 * current / total) + '%');
            $btn.find('.text').text(`${upgradingDocTemplatesText} (${current}/${total})`);
        }
    }).then(() =>
    {
        $btn.removeAttr('disabled').removeClass('disabled').addClass('primary is-finished').find('.text').text(nextText);
        $('#upgradeDocTemplatesProgress').removeClass('active')
    });

};
