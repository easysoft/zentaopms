<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script src='<?php echo $jsRoot;?>jquery/kindeditor/kindeditor.<?php echo $app->getClientLang();?>.js' type='text/javascript'></script>
<script language='javascript'>
var bugTools =
[ 'title', 'fontname', 'fontsize','textcolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'savetemplate', 'about'];

var simpleTools = 
[ 'title', 'fontname', 'fontsize', 'textcolor', 'bgcolor', 'bold', 'italic','underline', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];

var tools = 
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
</script>
