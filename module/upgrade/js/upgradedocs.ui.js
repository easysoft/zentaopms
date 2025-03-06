async function upgradeDoc(docID, options)
{
    const newData = await zui.fetchData(options.docFetcher, [{docID, version: 0}]);
    if(!newData || !newData.data) return false;
    const oldContent = newData.data.content;
    let content = '';
    if(options.type === 'html')
    {
        const snap = await zui.Editor.htmlToSnap(oldContent);
        if(!snap) return false;
    }
    else
    {
        try
        {
            content = await zui.Editor.convertToHtml(oldContent, undefined, options.downloadUrl);
        }
        catch(error)
        {
            console.warn('Convert doc to html failed:', error);
        }
    }
    const docData = {content};
    let result;
    await $.post(zui.formatString(options.migrateUrl, {docID: docID}), docData, (res) =>
    {
        result = res.result === 'success' ? true : res.message;
    }, 'json');
    return result;
}

async function upgradeWikis(idList, options)
{
    let result;
    await $.post(options.wikiUrl, {wikis: idList.join(',')}, (res) =>
    {
        result = res.result === 'success' ? true : res.message;
    }, 'json');
    return result;
}

async function upgradeDocs(idList, options)
{
    await zui.Editor.loadModule();
    const {
        onProgress,
        wikiUrl     = $.createLink('upgrade', 'ajaxUpgradeWikis'),
        migrateUrl  = $.createLink('upgrade', 'ajaxUpgradeDoc', 'docID={docID}'),
        docFetcher  = $.createLink('upgrade', 'ajaxUpgradeDoc', 'docID={docID}'),
        downloadUrl = $.createLink('file', 'ajaxQuery', 'fileID={gid}'),
    } = options || {};
    const migrateOptions =
    {
        migrateUrl: migrateUrl,
        docFetcher: docFetcher,
        downloadUrl: downloadUrl,
    };
    let current = 0;
    const total = idList.doc.length + idList.html.length + idList.wiki.length;
    const types = ['doc', 'html'];
    for (const type of types)
    {
        for(const id of idList[type])
        {
            onProgress(current, total);
            await upgradeDoc(id, $.extend({type: type}, migrateOptions));
            await zui.delay(50);
            current++;
        }
    }

    if(idList.wiki.length)
    {
        await upgradeWikis(idList.wiki, {wikiUrl: wikiUrl});
        current += idList.wiki.length;
    }

    onProgress(current, total);
    return idList;
}

window.startUpgradeDocs = function(event, idList, upgradingDocsText, nextText)
{
    const $btn = $('#upgradeDocsBtn');
    if($btn.hasClass('is-finished')) return;

    event.preventDefault();
    const $progressBar = $('#upgradeDocsProgress').addClass('active').find('.progress-bar');
    $btn.attr('disabled', 'disabled').addClass('disabled').removeClass('primary').find('.text').text(upgradingDocsText);
    upgradeDocs(idList,
    {
        onProgress: (current, total) =>
        {
            $progressBar.css('width', (100 * current / total) + '%');
            $btn.find('.text').text(`${upgradingDocsText} (${current}/${total})`);
        }
    }).then(() =>
    {
        $btn.removeAttr('disabled').removeClass('disabled').addClass('primary is-finished').find('.text').text(nextText);
        $('#upgradeDocsProgress').removeClass('active')
    });

};
