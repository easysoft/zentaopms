<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Yanyi Cao
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
js::set('jsRoot', $jsRoot);
js::set('clientLang', $app->clientLang);
js::set('fileExt', $this->config->repo->fileExt);
js::set('codeContent', trim($content));
js::set('file', $pathInfo);
js::import($jsRoot  . 'monaco-editor/min/vs/loader.js');
?>
<div id="codeContainer" style="height: 600px; padding 10px 0;"></div>
<script>
$(function()
{
    require.config({
        paths: {vs: jsRoot + 'monaco-editor/min/vs'},
        'vs/nls': {
            availableLanguages: {
                '*': clientLang
            }
        }
    });

    require(['vs/editor/editor.main'], function ()
    {
        var lang = 'php';
        $.each(fileExt, function(langName, ext)
        {
            if(ext.indexOf('.' + file.extension) !== -1) lang = langName;
        });

        var editor = monaco.editor.create(document.getElementById('codeContainer'),
        {
            autoIndent: true,
            value: codeContent,
            language: lang,
            contextmenu: true,
            EditorMinimapOptions: {
                enabled: false
            },
            readOnly: true,
            automaticLayout: true
        });
    });
});
</script>
