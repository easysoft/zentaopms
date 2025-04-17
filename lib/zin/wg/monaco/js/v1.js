setTimeout(function()
{
    $.getLib(config.webRoot + 'js/monaco-editor/min/vs/loader.js', {root: false}, function()
    {
        createMonaco(id, action, options, diffContent, onMouseDown, onMouseMove, vsPath, clientLang);
        resize(id);
    });
}, 200);

function createMonaco(id, action, options, diffContent, onMouseDown, onMouseMove, vsPath, clientLang)
{
    if(!options.minimap) options.minimap = {enabled: false};
    require.config({
        paths: {vs: vsPath},
        'vs/nls': {
            availableLanguages: {
                '*': clientLang
            }
        }
    });

    let decorations = [];
    let programmaticSelectionRange = null;

    require(['vs/editor/editor.main'], function ()
    {
        if(!lineMap && action == 'diff' && diffContent && diffContent.line) lineMap = diffContent.line.new;
        if(lineMap) options.lineNumbers = (line) => {return lineMap[line - 1];};

        $.extend(options, {
            // 关键配置项
            automaticLayout: true,        // 自动调整布局
            scrollBeyondLastLine: false,  // 禁用底部滚动区域
            minimap: {enabled: false},    // 可选：禁用缩略图以节省空间

            // 编辑器尺寸配置
            fontSize: 14,
            lineHeight: 24,
            lineNumbersMinChars: 2,
            glyphMargin: true,  // 启用装饰器边距

            // 内边距设置
            padding: {top: 8, bottom: 8}
        });

        if(action == 'diff')
        {
            options.renderSideBySide = $.cookie.get('renderSideBySide') == 'true';

            modifiedEditor = monaco.editor.createDiffEditor(document.getElementById(id), options);
            window.modifiedEditor = modifiedEditor;

            modifiedEditor.setModel({
                original: monaco.editor.createModel(diffContent.code.old, options.lang),
                modified: monaco.editor.createModel(diffContent.code.new, options.lang),
            });

            editor = modifiedEditor.getModifiedEditor();

            const getOriginalEditor = modifiedEditor.getOriginalEditor();
            getOriginalEditor.updateOptions({
                lineNumbers: function(number)
                {
                    var oldlc = diffContent.line.old;
                    return oldlc[number - 1];
                }
            });

            if(onMouseDown) getOriginalEditor.onMouseDown(function(obj){eval(onMouseDown + '(obj)')})
            if(onMouseMove) getOriginalEditor.onMouseMove(function(obj){eval(onMouseMove + '(obj)')})
        }
        else
        {
            editor = monaco.editor.create(document.getElementById(id), options);
        }
        if(onMouseDown) editor.onMouseDown(function(obj){eval(onMouseDown + '(obj)')})
        if(onMouseMove) editor.onMouseMove(function(obj){eval(onMouseMove + '(obj)')})

        if(selectedLines)
        {
            const lines = selectedLines.split(',');
            let   startLine = parseInt(lines[0]);
            let   endLine   = parseInt(lines[1] || startLine + 1);
            let   startCol  = 1;
            let   endCol    = 0;
            if(lines.length == 4)
            {
                startCol = parseInt(lines[2]);
                endCol   = parseInt(lines[3]);
            }
            if(endCol == 0) endLine += 1;
            if(lineMap)
            {
                lineMap.forEach((line, index) =>
                {
                    if(line == startLine) startLine = index + 1;
                    if(line == endLine) endLine = index + 1;
                });
            }

            const range = new monaco.Range(startLine, startCol, endLine, endCol);
            programmaticSelectionRange = range;
            editor.setSelection(range);
            updateDecorations();
        }

        function updateDecorations()
        {
            editor.deltaDecorations(decorations, []);
            decorations = [];

            if(programmaticSelectionRange)
            {
                decorations.push({
                    range: programmaticSelectionRange,
                    options: {
                        className: selectedClass,
                        isWholeLine: false
                    }
                });
            }

            decorations = editor.deltaDecorations([], decorations);
        }

        editor.onDidChangeCursorSelection(function(e)
        {
            if(!programmaticSelectionRange) return; // 只有当没有程序化选择时才更新装饰器

            if(e.selection.startLineNumber == programmaticSelectionRange.startLineNumber || e.selection.endLineNumber == programmaticSelectionRange.endLineNumber)
            {
                programmaticSelectionRange = null;
                updateDecorations();
            }
        });
    });
}

function resize(id)
{
    var $ = window.$ == undefined ? parent.$ : window.$;
    var windowHeight   = $(window).height();
    var headerHeight   = parseInt($('#header').height());
    var mainNavbar     = parseInt($('#mainNavbar').height());
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    var tabsHeight     = parseInt($('#fileTabs .tabs-navbar').height());

    headerHeight   = headerHeight ? headerHeight : 0;
    appsBarHeight  = appsBarHeight ? appsBarHeight : 0;
    tabsHeight     = tabsHeight ? tabsHeight : 0;
    appTabsHeight  = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar     = mainNavbar ? mainNavbar : 0;

    var codeHeight     = windowHeight - headerHeight - appsBarHeight - tabsHeight - appTabsHeight - mainMenuHeight - mainNavbar;

    if(codeHeight > 0) $.cookie.set(id + 'Height', codeHeight);

    $('#' + id).css('height', $.cookie.get(id + 'Height'));
}
