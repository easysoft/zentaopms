<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
if(!isset($config->$module->editor->$method)) return;
$editor = $config->$module->editor->$method;
$editor['id'] = explode(',', $editor['id']);
$editorLangs  = array('en' => 'en', 'zh-cn' => 'zh_CN', 'zh-tw' => 'zh_TW');
$editorLang   = isset($editorLangs[$app->getClientLang()]) ? $editorLangs[$app->getClientLang()] : 'en';
?>
<link rel="stylesheet" href="<?php echo $jsRoot;?>jquery/kindeditor/themes/default/default.css" />
<script src='<?php echo $jsRoot;?>jquery/kindeditor/kindeditor-min.js' type='text/javascript'></script>
<script src='<?php echo $jsRoot;?>jquery/kindeditor/lang/<?php echo $editorLang;?>.js' type='text/javascript'></script>
<script language='javascript'>
var editor = <?php echo json_encode($editor);?>;

var bugTools =
[ 'formatblock', 'fontname', 'fontsize','forecolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'code', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'savetemplate', 'about'];

var simpleTools = 
[ 'formatblock', 'fontname', 'fontsize', 'forecolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'code', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];

var fullTools = 
[ 'formatblock', 'fontname', 'fontsize','forecolor', '|',
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

        KindEditor.ready(function(K){
            editor = K.create('#'+editor.id,{
                items:editorTool,
                filterMode:true, 
                htmlTags :{
                'pre' : ['class'],
                'p,span,h1,h2,h3,h4,em,u,strong,br,ol,ul,li,img,a': ['/']
                },
                cssPath:['<?php echo $jsRoot?>jquery/kindeditor/plugins/code/prettify.css'],
                urlType:'relative', 
                uploadJson: createLink('file', 'ajaxUpload'),
                allowFileManager:true,
                langType:'<?php echo $editorLang?>'
            });

            $('form').submit(function() 
            {
                K.sync('#'+editor.id);
            });
        });
    })
})
</script>
