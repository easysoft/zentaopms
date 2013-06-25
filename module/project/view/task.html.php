<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isonlybody()) die(include "./tasklist.html.php")?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include './taskheader.html.php';?>
<script language='Javascript'>
var browseType  = '<?php echo $browseType;?>';
</script>
<div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'></div>
<table class='cont-lt2'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $project->name;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('project', 'edit',    "projectID=$projectID", $lang->edit);?>
          <?php common::printLink('project', 'delete',  "projectID=$projectID&confirm=no", $lang->delete, 'hiddenwin');?>
          <?php common::printLink('tree', 'browsetask', "rootID=$projectID&productID=0", $lang->tree->manage);?>
          <?php common::printLink('tree', 'fix',        "root=$projectID&type=task", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <form method='post' id='projectTaskForm'>
      <?php include "./tasklist.html.php"?>
      </form>
    </td>
  </tr>
</table>
<script language='javascript'>
$('#project<?php echo $projectID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
statusActive = '<?php echo isset($lang->project->statusSelects[$browseType]);?>';
if(statusActive) $('#statusTab').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
