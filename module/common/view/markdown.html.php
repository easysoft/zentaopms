<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
if(!isset($config->$module->markdown->$method)) return;
$editor = $config->$module->markdown->$method;
$editor['id'] = explode(',', $editor['id']);
?>
<?php css::import($jsRoot . "markdown/simplemde.min.css");?>
<?php js::import($jsRoot . 'markdown/simplemde.min.js'); ?>
<style>
.CodeMirror,.CodeMirror-scroll{min-height:200px!important;}
.CodeMirror-fullscreen + .editor-preview-side{display:block;}
.CodeMirror-fullscreen, .editor-preview-side{margin-bottom:40px;}
.editor-preview-side table > tbody > tr:last-child td{border:1px solid #e5e5e5 !important}
.editor-toolbar {padding: 1px;}
.editor-toolbar .icon-html:before {content:"HTML"; font-size: 12px; padding: 0 2px;}
.editor-toolbar .icon:before {font-size: 14px;}
.editor-toolbar a.icon:before {line-height: 22px;}
.editor-toolbar a {opacity: 0.8}
.editor-toolbar a:hover {opacity: 1}
.icon-bold:before {content: '\e953';}
.icon-italic:before {content: '\e955';}
.icon-header:before {content: '\e954';}
.icon-quote-left:before {content: '\e96a';}
.icon-list-ul:before {content: '\e956';}
.icon-list-ol:before {content: '\e969';}
.icon-picture:before {content: '\e96c';}
.icon-table:before {content: '\e96d';}
.icon-eye-open:before {content: '\e94e';}
.icon-columns:before {content: '\f0db';}
.icon-expand-full:before {content: '\e96b';}
.icon-question-sign:before {content: '\e968';}
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
                spellChecker: false,
                forceSync: true
            };
            var markdown = new SimpleMDE(options);
            if(!window.markdownEditor) window.markdownEditor = {};
            window.markdownEditor['#'] = window.markdownEditor[markdownEditorID] = markdown;
            // markdown.codemirror.on('focus', function(){window.markdownEditor[markdownEditorID].toggleSideBySide();});
            // markdown.codemirror.on('change', function()
            // {
            //     if($('#' + markdownEditorID).parent().find('.editor-preview-active-side').size() == 0) window.markdownEditor[markdownEditorID].toggleSideBySide();
            // });
        });

        if($.isFunction(afterInit)) afterInit();
    }
    window.initMarkdown = initMarkdown;
    initMarkdown();
});
</script>
