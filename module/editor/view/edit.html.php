<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inlink('save', "filePath=$safeFilePath&action=$action")?>'>
<div class='panel panel-sm'>
  <div class='panel-heading'><i class='icon-edit'></i> 
  <?php if($filePath):?>        
  <strong>
    <?php echo $lang->editor->filePath;?>
  </strong> 
  <code><?php echo $filePath?></code>
  <?php endif?>  
  </div>
  <div class='panel-body'>
    <table class='table table-form'>
      <?php if(!empty($showContent)):?>
      <tr>
        <td>
          <?php echo "<span class='strong'>" . $lang->editor->sourceFile . '</span>'?><br />
          <textarea id='showContent' class="form-control"> <?php echo $showContent?></textarea>
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
              <?php echo html::input('fileName', '', "class='form-control' autocomplete='off'");?>
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
          <span class='strong'><input type='checkbox' name='override' id='override' /> <?php echo $lang->editor->isOverride?></span>
        <?php endif;?>
        </td>
      </tr>
      <tr><td align='center'><?php echo html::submitButton()?></td></tr>
    </table>
  </div>
</div>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
