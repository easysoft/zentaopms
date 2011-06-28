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
    <td width='300'>
    <?php echo $tree?>
    <br />
    </td>
    <td valign='top'>
    <span><?php if($filePath) printf($lang->editor->editFile,$filePath)?></span>
    <form method='post' target='hiddenwin' action='<?php echo inlink('save', "filePath=" . helper::safe64Encode($filePath))?>'>
    <?php echo html::textarea('fileContent', $fileContent, "class='area-1' rows=40")?>
    <br/>
    <?php echo html::submitButton()?>
    </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>

