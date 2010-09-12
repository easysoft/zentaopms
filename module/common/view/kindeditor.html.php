<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script src='<?php echo $jsRoot;?>jquery/kindeditor/kindeditor.js' type='text/javascript'></script>
<script language='javascript'>
var bugTools = [ 'title', 'fontname', 'fontsize',
                    'textcolor', 'bgcolor', 'bold', 'italic','underline', 
                    'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
                    'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'savetemplate'];

var simpleTools = [ 'title', 'fontname', 'fontsize',
                    'textcolor', 'bgcolor', 'bold', 'italic','underline', 
                    'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
                    'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source'];

var tools = ['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|',
             'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', '|',
             'selectall', 'title', 'fontname', 'fontsize', '|',
             'textcolor', 'bgcolor', 'bold', 'italic', 'underline', 'strikethrough', 'removeformat', '|',
             'image', 'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink', '|', 'about'];
</script>
