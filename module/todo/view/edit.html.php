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
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['todo']);?> <strong><?php echo $todo->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('todo', 'view', 'todo=' . $todo->id), $todo->name);?></strong>
      <small class='text-muted'> <?php echo $lang->todo->edit;?></small>
    </div>
  </div>

  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->todo->date;?></th>
        <td class='w-p25-f'>
          <div class='input-group'>
            <?php echo html::input('date', $todo->date, "class='form-control form-date'");?>
            <?php if(empty($todo->cycle)):?>
            <span class='input-group-addon'><input type='checkbox' id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
            <?php endif;?>
          </div>
        </td><td></td>
      </tr>
      <?php if($todo->cycle):?>
      <?php $todo->config = json_decode($todo->config);?>
      <tr class='cycleConfig'>
        <th><?php echo $lang->todo->cycleConfig;?></th>
        <td colspan='2'>
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
          <div class='input-group' style='width:200px; padding-top:5px;'>
          <?php printf($lang->todo->beforeDays, html::input('config[beforeDays]', $todo->config->beforeDays, "class='form-control'"));?>
          </div>
        </td>
      </tr>  
      <tr class='cycleConfig'>
        <th><?php echo $lang->todo->deadline;?></th>
        <td><?php echo html::input("config[end]", $todo->config->end, "class='form-control form-date'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->todo->type;?></th>
        <td><input type='hidden' name='type' value='<?php echo $todo->type;?>' /><?php echo $lang->todo->typeList[$todo->type];?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->pri;?></th>
        <td><?php echo html::select('pri', $lang->todo->priList, $todo->pri, "class='form-control chosen'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->name;?></th>
        <td colspan='2'><div id='nameBox'>
          <?php
          $readType = ($todo->type == 'bug' or $todo->type == 'task') ? 'readonly' : '';
          echo html::input('name', $todo->name, "$readType class='form-control' autocomplete='off'");
          ?>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($todo->desc), "rows=8 class=area-1");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->todo->status;?></th>
        <td><?php echo html::select('status', $lang->todo->statusList, $todo->status, "class='form-control chosen'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->beginAndEnd;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class="form-control chosen" style="width: 50%"') . html::select('end', $times, $todo->end, 'class="form-control chosen" style="width: 50%"');?>
          </div>
        </td>
        <td>
          <input type='checkbox' id='dateSwitcher' onclick='switchDateFeature(this);' <?php if($todo->begin == 2400) echo 'checked';?> > <?php echo $lang->todo->lblDisableDate;?>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->private;?></th>
        <td><input type='checkbox' name='private' id='private' value='1' <?php if($todo->private) echo 'checked';?>></td>
      </tr>  
      <tr>
        <td></td>
        <td>
          <?php echo html::submitButton() . html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include './footer.html.php';?>
<script language='Javascript'>switchDateFeature(document.getElementById('dateSwitcher'));</script>
