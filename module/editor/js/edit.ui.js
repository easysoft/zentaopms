fileContentEditor = showContentEditor = null;
window.initContentEditor = function()
{
    require.config({
        paths: {vs: jsRoot + 'monaco-editor/min/vs'},
        'vs/nls': {
            availableLanguages:{'*': clientLang}
        }
    });

    if(typeof(monaco) == 'object') return initMonacoEditor();
    require(['vs/editor/editor.main'], function (){initMonacoEditor()});
}

window.initMonacoEditor = function()
{
    if(isShowContent)
    {
        showContentEditor = monaco.editor.create(document.getElementById('showContentEditor'),
        {
            value:           showContent.toString(),
            language:        language,
            readOnly:        true,
            autoIndent:      true,
            contextmenu:     true,
            automaticLayout: true,
            minimap:         {enabled: false},
            scrollBeyondLastLine: false,
            scrollbar: {
                verticalScrollbarSize: 10,
                horizontalScrollbarSize: 10
            }
        });
    }

    fileContentEditor = monaco.editor.create(document.getElementById('fileContentEditor'),
    {
        value:           fileContent.toString(),
        language:        language,
        readOnly:        false,
        autoIndent:      true,
        contextmenu:     true,
        automaticLayout: true,
        minimap:         {enabled: false},
        scrollBeyondLastLine: false,
        scrollbar: {
            verticalScrollbarSize: 10,
            horizontalScrollbarSize: 10
        }
    });
    setHeight();
}

window.syncFileContent = function()
{
    $('#fileContent').val(fileContentEditor.getValue());
};

window.reloadExtendWin = function()
{
    parent.$('#extendWin').attr('src', parent.$('#extendWin').data('url'));
};

window.setHeight = function()
{
    let codeHeight = parent.$('#editWin').height();
    $('.panel').height(codeHeight);

    let headerHeight  = $('.heading').height();
    let footerHeight  = $('.form-actions').height();
    let nameBoxHeight = $('#fileNameBox').height() ? $('#fileNameBox').height() : 0;
    codeHeight -= headerHeight + footerHeight + nameBoxHeight + 52;

    if(isShowContent)
    {
        contentHeight = showContentEditor.getContentHeight();
        if(contentHeight > 300) contentHeight = 300;
        if(contentHeight < 200) contentHeight = 200;
        $('#showContentEditor').height(contentHeight);
        codeHeight -= contentHeight + 30;
        if(codeHeight < 300) codeHeight = 300;
    }
    $('#fileContentEditor').height(codeHeight - 70);
};

initContentEditor();
setHeight();
window.addEventListener('resize', function()
{
    setHeight();
});
