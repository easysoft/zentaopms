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
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
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
  <div class='main-content'>
    <table class='table table-form'>
      <?php if(!empty($showContent)):?>
      <tr>
        <td>
          <?php echo "<span class='strong'>" . $lang->editor->sourceFile . '</span>'?><br />
          <textarea id='showContent' class="form-control"><?php echo $showContent?></textarea>
        </td>
      </tr>
      <?php endif?>
      <tr>
        <td><?php echo html::textarea('fileContent', str_replace('&', '&amp;', $fileContent), "class='form-control'")?></td>
      </tr>
      <tr>
        <td>
          <?php if($action and $action != 'edit' and $action != 'newPage' and $action != 'override' and $action != 'extendControl'):?>
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
          <?php endif;?>
          <?php if($action and $action != 'edit' and $action != 'newPage'):?>
          <div class='checkbox-primary'>
            <input type='checkbox' name='override' id='override' />
            <label for='override'><?php echo $lang->editor->isOverride?></span>
          </div>
          <?php endif;?>
        </td>
      </tr>
      <tr><td align='center'><?php echo html::submitButton()?></td></tr>
    </table>
  </div>
</form>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
