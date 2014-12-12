<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
js::set('themeRoot', $themeRoot);
if(!isset($config->$module->editor->$method)) return;
$editor = $config->$module->editor->$method;
$editor['id'] = explode(',', $editor['id']);
$editorLangs  = array('en' => 'en', 'zh-cn' => 'zh_CN', 'zh-tw' => 'zh_TW');
$editorLang   = isset($editorLangs[$app->getClientLang()]) ? $editorLangs[$app->getClientLang()] : 'en';
?>
<link rel="stylesheet" href="<?php echo $jsRoot;?>kindeditor/themes/default/default.css" />
<script src='<?php echo $jsRoot;?>kindeditor/kindeditor-min.js' type='text/javascript'></script>
<script src='<?php echo $jsRoot;?>kindeditor/lang/<?php echo $editorLang;?>.js' type='text/javascript'></script>
<script language='javascript'>
var editor = <?php echo json_encode($editor);?>;

var bugTools =
[ 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', '|', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'code', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];

var simpleTools = 
[ 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', '|', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'code', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];

var fullTools = 
[ 'formatblock', 'fontname', 'fontsize', 'lineheight', '|', 'forecolor', 'hilitecolor', '|', 'bold', 'italic','underline', 'strikethrough', '|',
'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', '|',
'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'insertfile', 'hr', '|', 'link', 'unlink', '/',
'undo', 'redo', '|', 'selectall', 'cut', 'copy', 'paste', '|', 'plainpaste', 'wordpaste', '|', 'removeformat', 'clearhtml','quickformat', '|',
'indent', 'outdent', 'subscript', 'superscript', '|',
'table', 'code', '|', 'pagebreak', 'anchor', '|', 
'fullscreen', 'source', 'preview', 'about'];

$(document).ready(initKindeditor);
function initKindeditor(afterInit)
{
    var nextFormControl = 'input:not([type="hidden"]), textarea:not(.ke-edit-textarea), button[type="submit"], select';
    $.each(editor.id, function(key, editorID)
    {
        editorTool = simpleTools;
        if(editor.tools == 'bugTools')  editorTool = bugTools;
        if(editor.tools == 'fullTools') editorTool = fullTools;

        var K = KindEditor, $editor = $('#' + editorID);
        var options = 
        {
            cssPath:[themeRoot + 'zui/css/min.css'],
            width:'100%',
            items:editorTool,
            filterMode: true, 
            bodyClass:'article-content',
            urlType:'relative', 
            uploadJson: createLink('file', 'ajaxUpload'),
            allowFileManager:true,
            langType:'<?php echo $editorLang?>',
            afterBlur: function(){this.sync();$editor.prev('.ke-container').removeClass('focus');},
            afterFocus: function(){$editor.prev('.ke-container').addClass('focus');},
            afterChange: function(){$editor.change().hide();},
            afterCreate : function()
            {
                var doc = this.edit.doc; 
                var cmd = this.edit.cmd; 
                if(!K.WEBKIT && !K.GECKO)
                {
                    var pasted = false;
                    $(doc.body).bind('paste', function(ev)
                    {
                        pasted = true;
                        return true;
                    });
                    setTimeout(function()
                    {
                        $(doc.body).bind('keyup', function(ev)
                        {
                            if(pasted)
                            {
                                pasted = false;
                                return true;
                            }
                            if(ev.keyCode == 86 && ev.ctrlKey) alert('<?php echo $this->lang->error->pasteImg;?>');
                        })
                    }, 10);
                }
                /* Paste in chrome.*/
                /* Code reference from http://www.foliotek.com/devblog/copy-images-from-clipboard-in-javascript/. */
                if(K.WEBKIT)
                {
                    $(doc.body).bind('paste', function(ev)
                    {
                        var $this = $(this);
                        var original =  ev.originalEvent;
                        var file =  original.clipboardData.items[0].getAsFile();
                        var reader = new FileReader();
                        reader.onload = function (evt) 
                        {
                            var result = evt.target.result; 
                            var result = evt.target.result;
                            var arr = result.split(",");
                            var data = arr[1]; // raw base64
                            var contentType = arr[0].split(";")[0].split(":")[1];

                            html = '<img src="' + result + '" alt="" />';
                            $.post(createLink('file', 'ajaxPasteImage'), {editor: html}, function(data){cmd.inserthtml(data);});
                        };

                        reader.readAsDataURL(file);
                    });
                }

                /* Paste in firfox.*/
                if(K.GECKO)
                {
                    K(doc.body).bind('paste', function(ev)
                    {
                        setTimeout(function()
                        {
                            var html = K(doc.body).html();
                            if(html.search(/<img src="data:.+;base64,/) > -1)
                            {
                                $.post(createLink('file', 'ajaxPasteImage'), {editor: html}, function(data){K(doc.body).html(data);});
                            }
                        }, 80);
                    });
                }
                /* End */
            },
            afterTab: function(id)
            {
                var $next = $editor.next(nextFormControl);
                if(!$next.length) $next = $editor.parent().next().find(nextFormControl);
                if(!$next.length) $next = $editor.parent().parent().next().find(nextFormControl);
                $next = $next.first().focus();
                var keditor = $next.data('keditor');
                if(keditor) keditor.focus();
                else if($next.hasClass('chosen')) $next.trigger('chosen:activate');
            }
        };
        try
        {
            if(!window.editor) window.editor = {};
            var keditor = K.create('#' + editorID, options);
            window.editor['#'] = window.editor[editorID] = keditor;
            $editor.data('keditor', keditor);
        }
        catch(e){}
    });

    if($.isFunction(afterInit)) afterInit();
}
</script>
