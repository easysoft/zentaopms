/**
 * 升级文档模板内容。
 * Upgrade doc template content.
 *
 * @param  int    $docID
 * @param  object $options
 * @access public
 * @return bool|string
 */
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

/**
 * 处理wiki类型的文档模板。
 * Process templates of wiki.
 *
 * @param  object $idList
 * @access public
 * @return object
 */
async function upgradeWikiTemplates(idList)
{
    let result;
    await $.post($.createLink('upgrade', 'ajaxUpgradeWikiTemplates'), {wikis: idList.join(',')}, (res) =>
    {
        result = res.result === 'success' ? true : res.message;
    }, 'json');
    return result;
}

/**
 * 依次处理文档模板内容。
 * Process doc template content.
 *
 * @param  object $idList
 * @param  object $options
 * @access public
 * @return object
 */
async function upgradeDocTemplates(idList, options)
{
    await zui.Editor.loadModule();
    const {onProgress} = options || {};
    let current = 0;
    const total = idList.html.length + idList.wiki.length;
    for(const id of idList['html'])
    {
        await upgradeTemplateContent(id, {processUrl: $.createLink('upgrade', 'ajaxUpgradeDocTemplate', 'docID={docID}')});
        await zui.delay(50);
        current++;
        onProgress(current, total);
    }

    if(idList.wiki.length)
    {
        await upgradeWikiTemplates(idList.wiki);
        current += idList.wiki.length;
    }
    onProgress(current, total);
    return idList;
}

/**
 * 开始升级文档模板。
 * Start upgrade doc templates.
 *
 * @param  object $event
 * @param  object $idList
 * @param  string $upgradingDocTemplatesText
 * @param  string $nextText
 * @access public
 * @return void
 */
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
