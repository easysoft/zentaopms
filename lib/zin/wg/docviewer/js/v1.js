/**
 * 处理保存文档的操作请求，向服务器发送请求并返回保存的文档对象。
 * Handle the save doc operation request, send a request to the server and return the saved doc object.
 */
function handleSaveDoc(doc)
{
    /* See lib/zin/wg/docapp/js/v1.js */
}

window._setDocViwerOptions = window.setDocAppOptions;
window.setDocAppOptions = function(_, options)
{
    options = window._setDocViwerOptions(_, options);
    return $.extend(options,
    {
        handleSaveDoc: handleSaveDoc
    });
};
