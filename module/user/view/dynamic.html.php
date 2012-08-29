<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->user->dynamic;?></div>
      <div class='box-content'>
        <?php 
        echo html::a(inLink('dynamic', "period=today&account=$account"),      $lang->action->dynamic->today)      . '<br />';
        echo html::a(inLink('dynamic', "period=yesterday&account=$account"),  $lang->action->dynamic->yesterday)  . '<br />';
        echo html::a(inLink('dynamic', "period=twodaysago&account=$account"), $lang->action->dynamic->twoDaysAgo) . '<br />';
        echo html::a(inLink('dynamic', "period=thisweek&account=$account"),   $lang->action->dynamic->thisWeek)   . '<br />';
        echo html::a(inLink('dynamic', "period=lastweek&account=$account"),   $lang->action->dynamic->lastWeek)   . '<br />';
        echo html::a(inLink('dynamic', "period=thismonth&account=$account"),  $lang->action->dynamic->thisMonth)  . '<br />';
        echo html::a(inLink('dynamic', "period=lastmonth&account=$account"),  $lang->action->dynamic->lastMonth)  . '<br />';
        echo html::a(inLink('dynamic', "period=all&account=$account"),        $lang->action->dynamic->all)        . '<br />';
        ?>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 colored tablesorter'>
        <thead>
        <tr class='colhead'>
          <th class='w-150px'><?php echo $lang->action->date;?></th>
          <th class='w-user'> <?php echo $lang->action->actor;?></th>
          <th class='w-100px'><?php echo $lang->action->action;?></th>
          <th class='w-80px'> <?php echo $lang->action->objectType;?></th>
          <th class='w-id'>   <?php echo $lang->idAB;?></th>
          <th><?php echo $lang->action->objectName;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($actions as $action):?>
        <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
        <tr class='a-center'>
          <td><?php echo $action->date;?></td>
          <td><?php echo $users[$action->actor];?></td>
          <td><?php echo $action->actionLabel;?></td>
          <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
          <td><?php echo $action->objectID;?></td>
          <td class='a-left'><?php echo html::a($action->objectLink, $action->objectName);?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
      </table>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
