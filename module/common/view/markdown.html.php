<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
if(!isset($config->$module->markdown->$method)) return;
$editor = $config->$module->markdown->$method;
$editor['id'] = explode(',', $editor['id']);
?>
<link rel="stylesheet" href="<?php echo $jsRoot?>markdown/simplemde.min.css"  type='text/css' media='screen' />
<script type="text/javascript" charset="utf-8" src="<?php echo $jsRoot?>markdown/simplemde.min.js"></script>
<style>
.CodeMirror{min-height: 150px; height:150px;}
.CodeMirror-fullscreen + .editor-preview-side{display:block;}
.CodeMirror-fullscreen, .editor-preview-side{margin-bottom:40px;}
.editor-toolbar .icon-html {position: relative; top: -1px;}
.editor-toolbar .icon-html:before {content:"HTML"; font-size: 12px; padding: 0 2px;}
.editor-preview-side table > tbody > tr:last-child td{border:1px solid #e5e5e5 !important}
</style>

<script>
$(function()
{
    var markdownEditor = <?php echo json_encode($editor);?>;
    var toolbar    = ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "code", "table", "|", "preview", "side-by-side", "fullscreen", "|", "guide"];
    var withchange = ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "code", "table", "|", "preview", "side-by-side", "fullscreen", "|", "guide", {name: "html", action: function customFunction(editor){toggleEditor("html")}, className:'icon icon-html', title:"HTML"}];
    function initMarkdown(config, afterInit)
    {
        config = config || markdownEditor;
        $.each(markdownEditor.id, function(key, markdownEditorID)
        {
            if(typeof(markdownEditor.tools) != 'undefined' && markdownEditor.tools == 'withchange') toolbar = withchange;
            var options = 
            {
                toolbar: toolbar,
                element: $('#' + markdownEditorID)[0],
                status:  false,
                spellChecker: false
            };
            var markdown = new SimpleMDE(options);
            if(!window.markdownEditor) window.markdownEditor = {};
            window.markdownEditor['#'] = window.markdownEditor[markdownEditorID] = markdown;
            markdown.codemirror.on('focus', function(){window.markdownEditor[markdownEditorID].toggleSideBySide();});
            markdown.codemirror.on('change', function()
            {
                if($('#' + markdownEditorID).parent().find('.editor-preview-active-side').size() == 0) window.markdownEditor[markdownEditorID].toggleSideBySide();
            });
        });

        if($.isFunction(afterInit)) afterInit();
    }
    window.initMarkdown = initMarkdown;
    initMarkdown();
});
</script>
