<?php
/**
 * The hours view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='nav list-group tab-menu'>
        <a href="#hoursContent" data-toggle="tab" class="hours <?php if($type == 'hours') echo 'active'?>"><?php echo $lang->custom->setHours;?></a>
        <a href="#weekendContent" data-toggle="tab" class="weekend <?php if($type == 'weekend') echo 'active'?>"><?php echo $lang->custom->setWeekend;?></a>
        <?php if(common::hasPriv('holiday', 'browse')) echo html::a($this->createLink('holiday', 'browse'), $lang->custom->setHoliday);?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='tab-content'>
        <div class='tab-pane <?php if($type == 'hours') echo 'active'?>' id='hoursContent'>
          <div class='main-header'>
            <div class='heading'>
              <strong><?php echo $lang->custom->setHours?></strong>
            </div>
          </div>
          <table class='table table-form mw-600px'>
            <tr>
              <th class='w-150px text-left'><?php echo $lang->custom->workingHours;?></th>
              <td><?php echo html::input('defaultWorkhours', $workhours, "class='form-control w-80px'");?></td>
              <td></td>
            </tr>
          </table>
        </div>
        <div class='tab-pane <?php if($type == 'weekend') echo 'active'?>' id='weekendContent'>
          <div class='main-header'>
            <div class='heading'>
              <strong><?php echo $lang->custom->setWeekend?></strong>
            </div>
          </div>
          <table class='table table-form mw-600px'>
            <tr>
              <th class='w-150px text-left'><?php echo $lang->custom->weekendRole;?></th>
              <td class='w-200px'><?php echo html::radio('weekend', $lang->custom->weekendList, $weekend);?></td>
              <td></td>
            </tr>
            <tr id='restDayBox' class='hidden'>
              <th><?php echo $lang->custom->setWeekend;?></th>
              <td><?php echo html::select('restDay', $lang->custom->restDayList, $restDay, "class='form-control chosen'");?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class='table-footer text-left pl-none'>
        <?php echo html::submitButton();?>
        <?php echo html::hidden('type', $type);?>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
