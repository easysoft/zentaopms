<?php
/**
 * The hours view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
        <a href="#hoursContent" data-toggle="tab" class="active"><?php echo $lang->custom->setHours;?></a>
        <a href="#weekendContent" data-toggle="tab" class=""><?php echo $lang->custom->setWeekend;?></a>
        <?php echo html::a($this->createLink('holiday', 'browse'), $lang->custom->setHoliday);?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->workingHour?></strong>
        </div>
      </div>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->workingHours;?></th>
          <td><?php echo html::input('defaultWorkhours', $workhours, "class='form-control w-80px'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->custom->weekend;?></th>
          <td><?php echo html::radio('weekend', $lang->custom->weekendList, $weekend);?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

