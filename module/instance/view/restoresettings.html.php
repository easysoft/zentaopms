<?php
/**
 * The restore settings view file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.cn
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/datepicker.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceID', $instance->id);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->instance->restore->autoRestore;?></h2>
    </div>
    <div>
      <form id='restoreSettingForm' method='post' class="cell not-watch load-indicator main-form">
        <h4>
          <?php echo html::checkbox('autoRestore', array('true' => $lang->instance->restore->enableAutoRestore), $restoreSettings->autoRestore ? 'true' : '');?>
        </h4>
        <table class="table table-form restore-settings">
          <tbody>
            <tr>
              <th><?php echo $lang->instance->restore->restoreTime;?></th>
              <td class='required'>
                <div class='input-group'>
                  <div class='datepicker-wrapper datepicker-date'>
                  <?php echo html::input('restoreTime', $restoreSettings->restoreTime, "class='form-control form-time' data-picker-position='bottom-right' maxlength='20'");?>
                  </div>
                </div>
              </td>
              <td></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->instance->restore->cycleDays;?></th>
              <td class='required w-250px'>
                <div class='input-group'>
                  <?php echo html::select('cycleDays', $lang->instance->restore->cycleList, $restoreSettings->cycleDays, "class='form-control' maxlength='20'");?>
                </div>
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <div class="text-center form-actions"><?php echo html::commonButton($lang->save, "id='saveSetting'", 'btn btn-primary');?></div>
      </form>
    </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
