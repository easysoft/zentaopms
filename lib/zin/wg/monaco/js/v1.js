setTimeout(function()
{
    createMonaco(id, action, options, diffContent, onMouseDown, onMouseMove, vsPath, clientLang);
    resize(id);
}, 200);

function createMonaco(id, action, options, diffContent, onMouseDown, onMouseMove, vsPath, clientLang)
{
    require.config({
        paths: {vs: vsPath},
        'vs/nls': {
            availableLanguages: {
                '*': clientLang
            }
        }
    });

    require(['vs/editor/editor.main'], function ()
    {
        if(action == 'diff')
        {
            options.renderSideBySide = $.cookie.get('renderSideBySide') == 'true';

            options.lineNumbers = function(number)
            {
                var newlc = diffContent.line.new;
                return newlc[number - 1];
            };

            modifiedEditor = monaco.editor.createDiffEditor(document.getElementById(id),
            options);
            window.modifiedEditor = modifiedEditor;

            modifiedEditor.setModel({
                original: monaco.editor.createModel(diffContent.code.old.trim("\n"), options.lang),
                modified: monaco.editor.createModel(diffContent.code.new.trim("\n"), options.lang),
            });

            editor = modifiedEditor.getModifiedEditor();

            var getOriginalEditor = modifiedEditor.getOriginalEditor();

            if(onMouseDown) getOriginalEditor.onMouseDown(function(obj){eval(onMouseDown + '(obj)')})
            if(onMouseMove) getOriginalEditor.onMouseMove(function(obj){eval(onMouseMove + '(obj)')})
        }
        else
        {
            editor = monaco.editor.create(document.getElementById(id), options);
        }

        if(onMouseDown) editor.onMouseDown(function(obj){eval(onMouseDown + '(obj)')})
        if(onMouseMove) editor.onMouseMove(function(obj){eval(onMouseMove + '(obj)')})
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