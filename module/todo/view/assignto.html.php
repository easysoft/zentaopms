<?php
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21 $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $lang->todo->assignedTo;?></h2>
        </div>
        <form class='load-indicator main-form' method='post' target='hiddenwin' id="todoAssignForm">
          <table class='table table-form'>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->assignedTo;?></th>
              <td><?php echo html::select('assignedTo', $members, $todo->assignedTo, "class='form-control chosen'");?></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->date;?></th>
              <td>
                <div class='input-group has-icon-right'>
                  <?php echo html::input('date', date('Y-m-d', strtotime($todo->date)), "class='form-control form-date'");?>
                  <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
                </div>
              </td>
              <td>
                <div class='checkbox-primary'>
                  <input type='checkbox' name="future" id='switchDate' onclick='switchDateTodo(this);' />
                  <label for='switchDate'><?php echo $lang->todo->periods['future'];?></label>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td>
                <div class='w-p50 pull-left'>
                  <?php echo html::select('begin', $times, date('Y-m-d') != $todo->date ? key($times) : $time, 'onchange=selectNext(); class="form-control chosen"');?>
                </div>
                <div class='w-p50 pull-left'>
                  <?php echo html::select('end', $times, '', 'class="form-control chosen" margin-left:-1px"');?>
                </div>
              </td>
              <td>
                <div class='checkbox-primary'>
                  <input type='checkbox' id='switchDate' onclick='switchDateFeature(this);' name="lblDisableDate">
                  <label for='switchDate'><?php echo $lang->todo->lblDisableDate;?></label>
                </div>
              </td>
            </tr>
            <tfoot>
            <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td></tr>
            </tfoot>
          </table>
          <hr class='small' />
          <div class='main'><?php include '../../common/view/action.html.php';?></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
