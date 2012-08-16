<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
if(!isset($config->$module->editor->$method)) return;
$editor = $config->$module->editor->$method;
$editor['id'] = explode(',', $editor['id']);
?>
<script src='<?php echo $jsRoot;?>jquery/kindeditor/kindeditor.js' type='text/javascript'></script>
<script language='javascript'>
var editor = <?php echo json_encode($editor);?>;

var bugTools =
[ 'title', 'fontname', 'fontsize','textcolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'savetemplate', 'about'];

var simpleTools = 
[ 'title', 'fontname', 'fontsize', 'textcolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];

var fullTools = 
[ 'title', 'fontname', 'fontsize','textcolor', '|',
'bgcolor', 'bold', 'italic','underline', '|',
'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', '|',
'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image','link', 'unlink', '|',
'removeformat','undo', 'redo',  'fullscreen', 'source', 'about', '-',
'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|',
'indent', 'outdent', 'subscript', 'superscript', '|',
'selectall', 'strikethrough', 'removeformat', '|',
'flash', 'media', 'advtable', 'hr', 'print'];

$(document).ready(function() 
{
    $.each(editor.id, function(key, editorID)
    {
        editorTool = simpleTools;
        if(editor.tools == 'bugTools')  editorTool = bugTools;
        if(editor.tools == 'fullTools') editorTool = fullTools;
        KE.show({id:editorID, items:editorTool, filterMode:true, urlType:'relative', imageUploadJson: createLink('file', 'ajaxUpload')});

        $('form').submit(function() 
        {
            KE.util.setData(editorID);
        })
    })
})  
</script>
