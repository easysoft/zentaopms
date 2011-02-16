<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <table class='table-1 tablesorter'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-type'><?php echo $lang->typeAB;?></th>
      <th><?php echo $lang->bug->title;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-user'><?php echo $lang->bug->resolvedBy;?></th>
      <th class='w-resolution'><?php echo $lang->bug->resolutionAB;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
      <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
      <td><?php echo $lang->bug->priList[$bug->pri]?></td>
      <td><?php echo $lang->bug->typeList[$bug->type]?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
      <td><?php echo $users[$bug->openedBy];?></td>
      <td><?php echo $users[$bug->resolvedBy];?></td>
      <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div></div>
<?php include '../../common/view/footer.html.php';?>
