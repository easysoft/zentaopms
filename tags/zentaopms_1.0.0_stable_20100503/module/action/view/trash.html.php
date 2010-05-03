<?php
/**
 * The trash view file of action module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div class='yui-d0'>
  <table class='table-1 colored tablesorter'>
    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <thead>
    <tr class='colhead'>
      <th class='w-80px'><?php common::printOrderLink('objectType',  $orderBy, $vars, $lang->action->objectType);?></th>
      <th class='w-id'><?php common::printOrderLink('objectID', $orderBy, $vars, $lang->idAB);?></th>
      <th><?php echo $lang->action->objectName;?></th>
      <th class='w-100px'><?php common::printOrderLink('actor',       $orderBy, $vars, $lang->action->actor);?></th>
      <th class='w-150px'><?php common::printOrderLink('date',        $orderBy, $vars, $lang->action->date);?></th>
      <th class='w-80px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($trashes as $action):?>
    <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
    <tr class='a-center'>
      <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
      <td><?php echo $action->objectID;?></td>
      <td class='a-left'><?php echo html::a($this->createLink($module, 'view', "id=$action->objectID"), $action->objectName);?></td>
      <td><?php echo $users[$action->actor];?></td>
      <td><?php echo $action->date;?></td>
      <td><?php common::printLink('action', 'undelete', "actionid=$action->id", $lang->action->undelete, 'hiddenwin');?>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='a-right'><?php $pager->show();?></div>
</div>  
<?php include '../../common/view/footer.html.php';?>
