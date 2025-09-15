/**
 * 处理保存文档的操作请求，向服务器发送请求并返回保存的文档对象。
 * Handle the save doc operation request, send a request to the server and return the saved doc object.
 */
function handleSaveDoc(doc)
{
    const docApp = getDocApp();

    if(docApp.isSavingDoc) return;
    docApp.isSavingDoc = true;

    const url     = $.createLink('weekly', 'edit', `reportID=${doc.id}`);
    const docData = {
        rawContent : doc.content,
        status     : doc.status || 'normal',
        contentType: doc.contentType,
        type       : 'text',
        title      : doc.title,
        keywords   : doc.keywords,
        content    : doc.html,
        uid        : (doc.uid || `doc${doc.id}`),
    };
    if(doc.fromVersion) docData.fromVersion = doc.fromVersion;

    return new Promise((resolve, reject) => {
        $.ajaxSubmit({'url': url, 'data': docData, 'load': false, 'onComplete': function(result)
        {
            docApp.isSavingDoc = false;

            resolve(result.newDoc);
            docApp.cancelEditDoc();
        }});
    });
}

window._setDocViwerOptions = window.setDocAppOptions;
window.setDocAppOptions = function(_, options)
{
    options = window._setDocViwerOptions(_, options);
    return $.extend(options,
    {
        onSaveDoc: handleSaveDoc
    });
};
