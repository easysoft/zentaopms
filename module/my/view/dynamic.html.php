<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <nav class='nav'>
    <?php 
    echo '<li id="today">'      . html::a(inlink('dynamic', "type=today"),      $lang->action->dynamic->today)      . '</li>';
    echo '<li id="yesterday">'  . html::a(inlink('dynamic', "type=yesterday"),  $lang->action->dynamic->yesterday)  . '</li>';
    echo '<li id="twodaysago">' . html::a(inlink('dynamic', "type=twodaysago"), $lang->action->dynamic->twoDaysAgo) . '</li>';
    echo '<li id="thisweek">'   . html::a(inlink('dynamic', "type=thisweek"),   $lang->action->dynamic->thisWeek)   . '</li>';
    echo '<li id="lastweek">'   . html::a(inlink('dynamic', "type=lastweek"),   $lang->action->dynamic->lastWeek)   . '</li>';
    echo '<li id="thismonth">'  . html::a(inlink('dynamic', "type=thismonth"),  $lang->action->dynamic->thisMonth)  . '</li>';
    echo '<li id="lastmonth">'  . html::a(inlink('dynamic', "type=lastmonth"),  $lang->action->dynamic->lastMonth)  . '</li>';
    echo '<li id="all">'        . html::a(inlink('dynamic', "type=all"),        $lang->action->dynamic->all)        . '</li>';
    ?>
  </nav>
</div>

<table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-150px'><?php common::printOrderLink('date',       $orderBy, $vars, $lang->action->date);?></th>
    <th class='w-user'> <?php common::printOrderLink('actor',      $orderBy, $vars, $lang->action->actor);?></th>
    <th class='w-100px'><?php common::printOrderLink('action',     $orderBy, $vars, $lang->action->action);?></th>
    <th class='w-80px'> <?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
    <th class='w-id'>   <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
    <th><?php echo $lang->action->objectName;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($actions as $action):?>
  <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
  <tr class='text-center'>
    <td><?php echo $action->date;?></td>
    <td><?php echo $app->user->realname;?></td>
    <td><?php echo $action->actionLabel;?></td>
    <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
    <td><?php echo $action->objectID;?></td>
    <td class='text-left'><?php echo html::a($action->objectLink, $action->objectName);?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<script>$('#<?php echo $type;?>').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
