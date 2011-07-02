<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<form method='post' target='hiddenwin' action='<?php echo inlink('save', "filePath=$safeFilePath&action=$action")?>'>
<table class='table-1'>
  <?php if($filePath):?>        
  <caption>
    <?php echo "<span class='strong'>{$lang->editor->filePath}</span>"?>
    <?php echo $filePath?>
  </caption>
  <?php endif?>
  <?php if(!empty($showContent)):?>
  <tr>
    <td>
      <?php echo "<span class='strong'>" . $lang->editor->sourceFile . '</span>'?><br />
      <div id='showContent' style='width:90%; overflow:auto'>
      <?php echo "<pre>$showContent</pre>"?>
      </div>
    </td>
  </tr>
  <?php endif?>
  <tr>
    <td colspan='2'>
    <?php echo "<span class='strong'>" . $lang->editor->fileContent . '</span>'?><br />
    <?php echo html::textarea('fileContent', $fileContent, "class='area-1'")?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
    <?php if($action and $action != 'edit' and $action != 'newPage' and $action != 'override' and $action != 'extendControl'):?>
    <?php echo "<span class='strong'>" . $lang->editor->fileName . '</span>'?>
      <span>
      <?php
      echo html::input('fileName', '', "class=text-4");
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
    <?php endif;?>
    <?php if($action and $action != 'edit' and $action != 'newPage'):?>
      <span class='strong'><input type='checkbox' name='override' id='override' /> <?php echo $lang->editor->isOverride?></span>
    <?php endif;?>
    </td>
  </tr>
  <tr><td colspan='2' align='center'><?php echo html::submitButton()?><td></tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
