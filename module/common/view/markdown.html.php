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
</style>

<script language='javascript'>
var markdownEditor = <?php echo json_encode($editor);?>;
$(document).ready(initMarkdown);
function initMarkdown(afterInit)
{
    $.each(markdownEditor.id, function(key, markdownEditorID)
    {
        var options = 
        {
            element:document.getElementById(markdownEditorID),
            status: false
        };
        if(!window.markdownEditor) window.markdownEditor = {};
        markdown = new SimpleMDE(options);
        window.markdownEditor['#'] = window.markdownEditor[markdownEditorID] = markdown;
        console.log(markdown);
        markdown.codemirror.on("focus", function()
        {
            window.markdownEditor[markdownEditorID].toggleSideBySide();
        });
        markdown.codemirror.on("change", function()
        {
            if($('#' + markdownEditorID).parent().find('.editor-preview-active-side').size() == 0)
            {
                window.markdownEditor[markdownEditorID].toggleSideBySide();
            }
        });
    });

    if($.isFunction(afterInit)) afterInit();
}
</script>
