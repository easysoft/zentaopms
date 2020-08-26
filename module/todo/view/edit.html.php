<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(common::checkNotCN()):?>
<style> label.col-sm-1{width:100px;} </style>
<?php endif;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $todo->id;?></span>
        <?php echo html::a($this->createLink('todo', 'view', 'todo=' . $todo->id), $todo->name);?>
        <small class='text-muted'><?php echo $lang->arrow . $lang->todo->edit;?></small>
      </h2>
    </div>
    <form class='modal-body form-horizontal' target='hiddenwin' method='post' id='dataform'>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->date;?></label>
        <div class="col-sm-10">
          <div class='input-group has-icon-right'>
            <?php echo html::input('date', $todo->date, "class='form-control form-date'");?>
            <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
          </div>
        </div>
      </div>
      <?php if($todo->cycle):?>
      <?php $todo->config = json_decode($todo->config);?>
      <div class="row form-group cycleConfig">
        <label class="col-sm-1"><?php echo $lang->todo->cycleConfig;?></label>
        <div class="col-sm-10">
          <ul class="nav nav-tabs">
            <li <?php if($todo->config->type == 'day')   echo "class='active'"?>><a data-tab data-type='day' href="#day"><?php echo $lang->todo->cycleDay;?></a></li>
            <li <?php if($todo->config->type == 'week')  echo "class='active'"?>><a data-tab data-type='week' href="#week"><?php echo $lang->todo->cycleWeek;?></a></li>
            <li <?php if($todo->config->type == 'month') echo "class='active'"?>><a data-tab data-type='month' href="#month"><?php echo $lang->todo->cycleMonth;?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane <?php if($todo->config->type == 'day') echo 'active'?>" id="day">
              <div class='input-group w-150px'>
                <span class='input-group-addon'><?php echo $lang->todo->every;?></span>
                <?php echo html::input('config[day]', isset($todo->config->day) ? $todo->config->day : 1, "class='form-control'")?>
                <span class='input-group-addon'><?php echo $lang->todo->cycleDay;?></span>
              </div>
            </div>
            <div class="tab-pane <?php if($todo->config->type == 'week') echo 'active'?>" id="week">
              <?php echo html::checkbox('config[week]', $lang->todo->dayNames, isset($todo->config->week) ? $todo->config->week : '')?>
            </div>
            <div class="tab-pane <?php if($todo->config->type == 'month') echo 'active'?>" id="month">
              <?php
              $days = array();
              for($i = 1; $i <= 10; $i ++) $days[$i] = $i;
              echo "<p class='box1-10'>" . html::checkbox('config[month]', $days, isset($todo->config->month) ? $todo->config->month : '') . '</p>';
              $days = array();
              for($i = 11; $i <= 20; $i ++) $days[$i] = $i;
              echo "<p class='box11-20'>" . html::checkbox('config[month]', $days, isset($todo->config->month) ? $todo->config->month : '') . '</p>';
              $days = array();
              for($i = 21; $i <= 31; $i ++) $days[$i] = $i;
              echo "<p class='box21-31'>" . html::checkbox('config[month]', $days, isset($todo->config->month) ? $todo->config->month : '') . '</p>';
              ?>
            </div>
          </div>
          <?php echo html::hidden('config[type]', $todo->config->type)?>
          <div class='input-group' style='width:250px; padding-top:5px;'>
          <?php printf($lang->todo->beforeDays, html::input('config[beforeDays]', $todo->config->beforeDays, "class='form-control'"));?>
          </div>
        </div>
      </div>
      <div class="row form-group cycleConfig">
        <label class="col-sm-1"><?php echo $lang->todo->deadline;?></label>
        <div class="col-sm-10">
          <?php echo html::input("config[end]", $todo->config->end, "class='form-control form-date'");?>
        </div>
      </div>
      <?php endif;?>
      <?php if($todo->type != 'cycle'):?>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->type;?></label>
        <div class="col-sm-2">
          <?php echo html::select('type', $lang->todo->typeList, $todo->type, 'onchange="loadList(this.value);" class="form-control"');?>
        </div>
      </div>
      <?php endif;?>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->pri;?></label>
        <div class="col-sm-2">
          <?php echo html::select('pri', $lang->todo->priList, $todo->pri, "class='form-control chosen'");?>
        </div>
      </div>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->name;?></label>
        <div class="col-sm-10">
          <div id='nameBox' class='hidden'><?php echo html::input('name', $todo->name, "class='form-control'");?></div>
          <div class='nameBox required'><?php echo html::input('name', $todo->name, "class='form-control'");?></div>
        </div>
      </div>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->desc;?></label>
        <div class="col-sm-10">
          <?php echo html::textarea('desc', htmlspecialchars($todo->desc), "rows='8' class='form-control'");?>
        </div>
      </div>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->status;?></label>
        <div class="col-sm-2">
          <?php echo html::select('status', $lang->todo->statusList, $todo->status, "class='form-control'");?>
        </div>
      </div>
      <div class="row form-group">
        <label class="col-sm-1"><?php echo $lang->todo->beginAndEnd;?></label>
        <div class="col-sm-2" style='padding-right:0px'>
          <?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class="form-control chosen" data-drop_direction="up"')?>
        </div>
        <div class="col-sm-2" style='padding-left:0px'>
          <?php echo html::select('end', $times, $todo->end, 'class="form-control chosen" data-drop_direction="up"');?>
        </div>
        <div class="col-sm-4">
          <div class='checkbox-primary dateSwitcher'>
            <input type='checkbox' id='dateSwitcher' onclick='switchDateFeature(this);' <?php if($todo->begin == 2400) echo 'checked';?> >
            <label for='dateSwitcher'><?php echo $lang->todo->lblDisableDate;?></label>
          </div>
        </div>
      </div>
      <div class="row form-group">
        <label class="col-sm-1"></label>
        <div class="col-sm-10">
          <div class='checkbox-primary'>
            <input type='checkbox' name='private' id='private' value='1' <?php if($todo->private) echo 'checked';?> />
            <label for='private'><?php echo $lang->todo->private;?></label>
          </div>
        </div>
      </div>
      <div class="row form-group form-actions">
        <div class="col-sm-offset-2 col-sm-10">
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include './footer.html.php';?>
<script>switchDateFeature(document.getElementById('dateSwitcher'));</script>
