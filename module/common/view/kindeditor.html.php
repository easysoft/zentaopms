<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script src='<?php echo $jsRoot;?>jquery/kindeditor/kindeditor.js' type='text/javascript'></script>
<script language='javascript'>
var bugTools = ['fullscreen', '|', 'title', 'fontname', 'fontsize', '|',
                'textcolor', 'bgcolor', 'bold', 'italic','underline', 'removeformat','undo', 'redo', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', '|',
                'source', 'selectall', 'emoticons', 'image', 'link', 'savetemplate', '|', 'about'];
var simpleTools = [ 'fullscreen', '|', 'title', 'fontname', 'fontsize', '|',
                    'textcolor', 'bgcolor', 'bold', 'italic','underline', 'removeformat','undo', 'redo', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', '|',
                    'source', 'selectall', 'emoticons', 'image', 'link', '|', 'about' ];
var tools = ['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|',
             'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', '|',
             'selectall', 'title', 'fontname', 'fontsize', '|',
             'textcolor', 'bgcolor', 'bold', 'italic', 'underline', 'strikethrough', 'removeformat', '|',
             'image', 'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink', '|', 'about'];
</script>
