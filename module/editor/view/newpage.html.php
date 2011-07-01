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
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<table class='table-1'>
  <tr>
    <td width='300' valign='top'>
    <?php echo $tree?>
    <br />
    </td>
    <td valign='top'>
      <form method='post' target='hiddenwin'>
      <table class='table-1'>
        <caption><?php echo $lang->editor->newPage?></caption>
        <tr>
          <th class='w-70px'><?php echo $lang->editor->filePath?></th>
          <td><?php echo $filePath?></td>
        </tr>
        <tr>
          <th><?php echo $lang->editor->pageName?></th>
          <td>
          <?php
          echo html::input('fileName', '', "class=text-5");
          echo $lang->editor->examplePHP;
          ?>
          </td>
        </tr>
        <tr><td colspan='2' align='center'><?php echo html::submitButton()?><td></tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
