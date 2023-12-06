<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($filePath)) die();?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php
$browser = helper::getBrowser();
if($browser['name'] == 'ie')
{
    include 'ieedit.html.php';
    die();
}

js::set('jsRoot', $jsRoot);
js::set('clientLang', $app->clientLang);
js::import($jsRoot . 'monaco-editor/min/vs/loader.js');
?>
<div class='main-header'>
  <div class='heading'>
    <i class='icon-edit'></i>
    <?php if($filePath):?>
    <strong><?php echo $lang->editor->filePath;?></strong>
    <code><?php echo $filePath?></code>
    <?php endif?>
  </div>
</div>
<form method='post' target='hiddenwin' action='<?php echo inlink('save', "filePath=$safeFilePath&action=$action")?>'>
    <table class='table table-form'>
      <?php if(!empty($showContent)):?>
      <tr>
        <td>
          <?php echo "<span class='strong'>" . $lang->editor->sourceFile . '</span>'?><br />
          <div id='showContentEditor'></div>
        </td>
      </tr>
      <?php endif?>
      <tr>
        <td>
          <div id='fileContentEditor'></div>
          <?php echo html::hidden('fileContent');?>
        </td>
      </tr>
      <?php if($action and $action != 'edit' and $action != 'newPage' and $action != 'override' and $action != 'extendControl'):?>
      <tr id='fileNameBox'>
        <td>
          <div class='form-group'>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->editor->fileName;?></span>
              <?php echo html::input('fileName', '', "class='form-control'");?>
              <span class='input-group-addon'>
                <?php
                if($action == 'newHook')
                {
                    echo $lang->editor->exampleHook;
                }
                elseif($action and $action == 'extendOther' and strpos(basename($filePath), '.js') !== false or $action == 'newJS')
                {
                    echo $lang->editor->exampleJs;
                }
                elseif($action and $action == 'extendOther' and strpos(basename($filePath), '.css') !== false or $action == 'newCSS')
                {
                    echo $lang->editor->exampleCss;
                }
                else
                {
                    echo $lang->editor->examplePHP;
                }
                ?>
              </span>
            </div>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr class='footer'>
        <td class='text-center'>
          <?php echo html::submitButton()?>
          <?php if($action and $action != 'edit' and $action != 'newPage'):?>
          <div class='checkbox-primary'>
            <input type='checkbox' name='override' id='override' />
            <label for='override'><?php echo $lang->editor->isOverride?></span>
          </div>
          <?php endif;?>
        </td>
      </tr>
    </table>
</form>
<?php if(!empty($showContent)) js::set('showContent', $showContent);?>
<?php js::set('fileContent', $fileContent);?>
<?php js::set('language', $fileExtension == 'js' ? 'javascript' : $fileExtension);?>
<script>
$(function()
{
    fileContentEditor = showContentEditor = null;
    require.config({
        paths: {vs: jsRoot + 'monaco-editor/min/vs'},
        'vs/nls': {
            availableLanguages:{'*': clientLang}
        }
    });
    require(['vs/editor/editor.main'], function ()
    {
        <?php if(!empty($showContent)):?>
        showContentEditor = monaco.editor.create(document.getElementById('showContentEditor'),
        {
            value:           showContent.toString(),
            language:        language,
            readOnly:        true,
            autoIndent:      true,
            contextmenu:     true,
            automaticLayout: true,
            minimap:         {enabled: false},
            scrollBeyondLastLine: false,
            scrollbar: {
                verticalScrollbarSize: 10,
                horizontalScrollbarSize: 10
            }
        });
        <?php endif;?>
        fileContentEditor = monaco.editor.create(document.getElementById('fileContentEditor'),
        {
            value:           fileContent.toString(),
            language:        language,
            readOnly:        false,
            autoIndent:      true,
            contextmenu:     true,
            automaticLayout: true,
            minimap:         {enabled: false},
            scrollBeyondLastLine: false,
            scrollbar: {
                verticalScrollbarSize: 10,
                horizontalScrollbarSize: 10
            }
        });
        var codeHeight    = parent.$('#editWin').height();
        var headerHeight  = $('.main-header').outerHeight();
        var footerHeight  = $('.footer').height();
        var nameBoxHeight = $('#fileNameBox').height() ? $('#fileNameBox').height() : 0;
        codeHeight -= headerHeight + footerHeight + nameBoxHeight;
        <?php if(!empty($showContent)):?>
        contentHeight = showContentEditor.getContentHeight();
        if(contentHeight > 300) contentHeight = 300;
        if(contentHeight < 200) contentHeight = 200;
        $('#showContentEditor').height(contentHeight);
        codeHeight -= contentHeight + 30;
        if(codeHeight < 300) codeHeight = 300;
        <?php endif;?>
        $('#fileContentEditor').height(codeHeight - 30);
    });
    $('#submit').click(function()
    {
        $('#fileContent').val(fileContentEditor.getValue());
    })
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
