<?php
/**
 * The dir view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dir
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<table class='table-1'>
  <tr>
    <td width='300' valign='top'>
    <?php echo $tree?>
    <br />
    </td>
    <td valign='top'>
      <form method='post' target='hiddenwin' action='<?php echo inlink('save', "filePath=$safeFilePath&action=$action")?>'>
      <table class='table-1'>
        <caption>
        <?php 
        if(empty($action)) echo '';
        else echo isset($lang->editor->$action) ? $lang->editor->$action : $lang->editor->extend;
        ?>
        </caption>
        <?php if($filePath):?>        
        <tr>
          <td><?php echo $lang->editor->filePath?></td>
          <td><?php echo $filePath?></td>
        </tr>
        <?php endif?>
        <tr>
          <td class='w-80px'><?php echo $lang->editor->fileContent?></td>
          <td>
          <?php if(isset($showContent))
          {
              echo "<pre>$showContent</pre>";
              $rows = 20;
          }
          else
          {
              $rows = 40;
          }
          ?>
          <?php echo html::textarea('fileContent', $fileContent, "class='area-1' rows=$rows")?>
          </td>
        </tr>
        <?php if($action and $action != 'edit' and $action != 'extendControl' and $action != 'override'):?>
        <tr>
          <td><?php echo $lang->editor->fileName?></td>
          <td>
          <?php
          echo html::input('fileName', '', "class=text-5");
          if($action == 'newHook')
          {
              echo $lang->editor->exampleHook;
          }
          elseif(strpos(basename($filePath), '.js') !== false)
          {
              echo $lang->editor->exampleJs;
          }
          elseif(strpos(basename($filePath), '.css') !== false)
          {
              echo $lang->editor->exampleCss;
          }
          else
          {
              echo $lang->editor->examplePHP;
          }
          ?>
          </td>
        </tr>
        <?php endif;?>
        <?php if($action != 'edit'):?>
        <tr>
          <td><?php echo $lang->editor->overrideFile?></td>
          <td><input type='checkbox' name='override' id='override' /> <?php echo $lang->editor->isOverride?></td>
        </tr>
        <?php endif;?>
        <tr><td colspan='2' align='center'><?php echo html::submitButton()?><td></tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>

