<?php
/**
 * The trash view file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['trash']);?> <?php echo $lang->action->trash;?></div>
  <div class='actions'>
    <?php if($type == 'hidden') echo html::a(inLink('trash', "type=all"),    $lang->goback, '', "class='btn'");?>
    <?php if($type == 'all')    echo html::a(inLink('trash', "type=hidden"), "<i class='icon-eye-close'></i> " . $lang->action->dynamic->hidden, '', "class='btn btn-danger'");?>
  </div>
</div>

<table class='table tablesorter'>
  <?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-90px'><?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
    <th class='w-id'>  <?php common::printOrderLink('objectID',   $orderBy, $vars, $lang->idAB);?></th>
    <th><?php echo $lang->action->objectName;?></th>
    <th class='w-100px'><?php common::printOrderLink('actor',     $orderBy, $vars, $lang->action->actor);?></th>
    <th class='w-150px'><?php common::printOrderLink('date',      $orderBy, $vars, $lang->action->date);?></th>
    <th class='w-100px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($trashes as $action):?>
  <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
  <tr class='text-center'>
    <td><?php echo zget($lang->action->objectTypes, $action->objectType, '');?></td>
    <td><?php echo $action->objectID;?></td>
    <td class='text-left'><?php echo html::a($this->createLink($module, 'view', "id=$action->objectID"), $action->objectName);?></td>
    <td><?php echo $users[$action->actor];?></td>
    <td><?php echo $action->date;?></td>
    <td>
      <?php
      common::printLink('action', 'undelete', "actionid=$action->id", $lang->action->undelete, 'hiddenwin');
      if($type == 'all') common::printLink('action', 'hideOne',  "actionid=$action->id", $lang->action->hideOne, 'hiddenwin');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
  <tr>
    <td colspan='6'>
      <?php if($trashes and $type == 'all'):?>
      <div class='table-actions clearfix'>
        <?php echo html::linkButton($lang->action->hideAll, inlink('hideAll'), 'hiddenwin');?>
        <div class='text'><?php echo $lang->action->trashTips;?></div>
      </div>
      <?php endif;?>
      <?php $pager->show();?>
    </td>
  </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
