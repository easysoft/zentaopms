<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
if(!isset($config->$module->editor->$method)) return;
$editor = $config->$module->editor->$method;
$editor['id'] = explode(',', $editor['id']);
$editorLangs  = array('en' => 'en', 'zh-cn' => 'zh_CN', 'zh-tw' => 'zh_TW', 'ja' => 'ja');
$editorLang   = isset($editorLangs[$app->getClientLang()]) ? $editorLangs[$app->getClientLang()] : 'en';

/* set uid for upload. */
$uid = uniqid('');
?>
<?php js::import($jsRoot . 'kindeditor/kindeditor.min.js'); ?>
<?php js::import($jsRoot . "kindeditor/lang/{$editorLang}.js");?>
<script>
(function($) {
    var kuid = '<?php echo $uid;?>';
    var editor = <?php echo json_encode($editor);?>;
    var K = KindEditor;

    var bugTools =
    [ 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', '|',
    'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
    'emoticons', 'image', 'code', 'link', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];
    var simpleTools =
    [ 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', '|',
    'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
    'emoticons', 'image', 'code', 'link', 'table', '|', 'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];
    var fullTools =
    [ 'formatblock', 'fontname', 'fontsize', 'lineheight', '|', 'forecolor', 'hilitecolor', '|', 'bold', 'italic','underline', 'strikethrough', '|',
    'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', '|',
    'insertorderedlist', 'insertunorderedlist', '|',
    'emoticons', 'image', 'insertfile', 'hr', '|', 'link', 'unlink', '/',
    'undo', 'redo', '|', 'selectall', 'cut', 'copy', 'paste', '|', 'plainpaste', 'wordpaste', '|', 'removeformat', 'clearhtml','quickformat', '|',
    'indent', 'outdent', 'subscript', 'superscript', '|',
    'table', 'code', '|', 'pagebreak', 'anchor', '|',
    'fullscreen', 'source', 'preview', 'about'];
    var editorToolsMap = {fullTools: fullTools, simpleTools: simpleTools, bugTools: bugTools};

    // Kindeditor default options
    var editorDefaults =
    {
        cssPath: [config.themeRoot + 'zui/css/min.css'],
        width: '100%',
        height: '200px',
        filterMode: true,
        bodyClass: 'article-content',
        urlType: 'absolute',
        uploadJson: createLink('file', 'ajaxUpload', 'uid=' + kuid),
        langType: '<?php echo $editorLang?>',
        cssData: 'html,body {background: none}.article-content{overflow:visible}.article-content, .article-content table td, .article-content table th {line-height: 1.3846153846; font-size: 13px;}.article-content .table-auto {width: auto!important; max-width: 100%;}',
        placeholder: <?php echo json_encode($lang->noticePasteImg);?>,
        placeholderStyle: {fontSize: '13px', color: '#888'},
        pasteImage: {postUrl: createLink('file', 'ajaxPasteImage', 'uid=' + kuid)},
        syncAfterBlur: true,
        spellcheck: false
    };

    window.editor = {};

    // Init kindeditor
    var setKindeditor = function(element, options)
    {
        var $editor  = $(element);
        var pasted   = false;
        var editorID = $editor.attr('id');
        options      = $.extend({}, editorDefaults, $editor.data(), options);
        if(editorID === undefined)
        {
            editorID = 'kindeditor-' + $.zui.uuid();
            $editor.attr('id', editorID);
        }

        var editorTool  = editorToolsMap[options.tools || editor.tools] || simpleTools;

        /* Remove fullscreen in modal. */
        if(config.onlybody == 'yes')
        {
            var newEditorTool = new Array();
            for(i in editorTool)
            {
                if(editorTool[i] != 'fullscreen') newEditorTool.push(editorTool[i]);
            }
            editorTool = newEditorTool;
        }

        $.extend(options,
        {
            items: editorTool,
            placeholder: $editor.attr('placeholder') || options.placeholder || '',
            pasteImage: {postUrl: createLink('file', 'ajaxPasteImage', 'uid=' + kuid), placeholder: $editor.attr('placeholder') || <?php echo json_encode($lang->noticePasteImg);?>},
        });

        try
        {
            var keditor = K.create('#' + editorID, options);
            window.editor['#'] = window.editor[editorID] = keditor;
            $editor.data('keditor', keditor);
            return keditor;
        }
        catch(e){return false;}
    };

    // Init kindeditor with jquery way
    $.fn.kindeditor = function(options)
    {
        return this.each(function()
        {
            setKindeditor(this, options);
        });
    };

    // Init all kindeditor
    var initKindeditor = function(afterInit)
    {
        var $submitBtn = $('form :input[type=submit]');
        if($submitBtn.length)
        {
            $submitBtn.next('#uid').remove();
            $submitBtn.after("<input type='hidden' id='uid' name='uid' value=" + kuid + ">");
        }
        if($.isFunction(afterInit)) afterInit();
        $.each(editor.id, function(key, editorID)
        {
            setKindeditor('#' + editorID);
        });
    };

    // Init all kindeditors when document is ready
    $(initKindeditor);
}(jQuery));
</script>
