<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='featurebar'>
  <?php 
  echo '<span id="today">'      . html::a(inlink('dynamic', "date=today"),      $lang->action->dynamic->today)      . '</span>';
  echo '<span id="yesterday">'  . html::a(inlink('dynamic', "date=yesterday"),  $lang->action->dynamic->yesterday)  . '</span>';
  echo '<span id="twodaysago">' . html::a(inlink('dynamic', "date=twodaysago"), $lang->action->dynamic->twoDaysAgo) . '</span>';
  echo '<span id="thisweek">'   . html::a(inlink('dynamic', "date=thisweek"),   $lang->action->dynamic->thisWeek)   . '</span>';
  echo '<span id="lastweek">'   . html::a(inlink('dynamic', "date=lastweek"),   $lang->action->dynamic->lastWeek)   . '</span>';
  echo '<span id="thismonth">'  . html::a(inlink('dynamic', "date=thismonth"),  $lang->action->dynamic->thisMonth)  . '</span>';
  echo '<span id="lastmonth">'  . html::a(inlink('dynamic', "date=lastmonth"),  $lang->action->dynamic->lastMonth)  . '</span>';
  echo '<span id="all">'        . html::a(inlink('dynamic', "date=all"),        $lang->action->dynamic->all)        . '</span>';
  ?>
</div>

<table class='table-1 colored tablesorter'>
  <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-150px'><?php common::printOrderLink('date',       $orderBy, $vars, $lang->action->date);?></th>
    <th class='w-user'> <?php common::printOrderLink('actor',      $orderBy, $vars, $lang->action->actor);?></th>
    <th class='w-100px'><?php common::printOrderLink('action',     $orderBy, $vars, $lang->action->action);?></th>
    <th class='w-80px'> <?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
    <th class='w-id'>   <?php common::printOrderLink('objectID',   $orderBy, $vars, $lang->idAB);?></th>
    <th><?php echo $lang->action->objectName;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($actions as $action):?>
  <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
  <tr class='a-center'>
    <td><?php echo $action->date;?></td>
    <td><?php echo $app->user->realname;?></td>
    <td><?php echo $action->actionLabel;?></td>
    <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
    <td><?php echo $action->objectID;?></td>
    <td class='a-left'><?php echo html::a($this->createLink($module, 'view', "id=$action->objectID"), $action->objectName);?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<script>$('#<?php echo $type;?>').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
