<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('noTodo', $lang->todo->noTodo);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->todo->create;?></h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'> 
        <tr>
          <th class='w-80px'><?php echo $lang->todo->date;?></th>
          <td class='w-300px'>
            <div class='input-group'>
              <?php echo html::input('date', $date, "class='form-control form-date'");?>
              <span class='input-group-addon switchDate'>
                <div class='checkbox-primary'>
                  <input type='checkbox' id='switchDate' onclick='switchDateTodo(this);' />
                  <label for='switchDate'><?php echo $lang->todo->periods['future'];?></label>
                </div>
              </span>
              <span class='input-group-addon'>
                <div class='checkbox-primary'>
                  <input type='checkbox' id='cycle' name='cycle' value='1' />
                  <label for='cycle'><?php echo $lang->todo->cycle;?></label>
                </div>
              </span>
            </div>
          </td><td></td>
        </tr>
        <tr class='cycleConfig hidden'>
          <th><?php echo $lang->todo->cycleConfig;?></th>
          <td colspan='2'>
            <ul class="nav nav-tabs">
            <li class="active"><a data-tab data-type='day' href="#day"><?php echo $lang->todo->cycleDay;?></a></li>
              <li><a data-tab data-type='week' href="#week"><?php echo $lang->todo->cycleWeek;?></a></li>
              <li><a data-tab data-type='month' href="#month"><?php echo $lang->todo->cycleMonth;?></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="day">
                <div class='input-group w-150px'>
                  <span class='input-group-addon'><?php echo $lang->todo->every;?></span>
                  <?php echo html::input('config[day]', 1, "class='form-control'")?>
                  <span class='input-group-addon'><?php echo $lang->todo->cycleDay;?></span>
                </div>
              </div>
              <div class="tab-pane clearfix" id="week">
                <?php echo html::checkbox('config[week]', $lang->todo->dayNames)?>
              </div>
              <div class="tab-pane clearfix" id="month">
                <?php
                $days = array();
                for($i = 1; $i <= 10; $i ++) $days[$i] = $i;
                echo html::checkbox('config[month]', $days);
                $days = array();
                for($i = 11; $i <= 20; $i ++) $days[$i] = $i;
                echo html::checkbox('config[month]', $days);
                $days = array();
                for($i = 21; $i <= 31; $i ++) $days[$i] = $i;
                echo html::checkbox('config[month]', $days);
                ?>
              </div>
            </div>
            <?php echo html::hidden('config[type]', 'day')?>
            <div class='input-group' style='width:200px; padding-top:5px;'>
            <?php printf($lang->todo->beforeDays, html::input('config[beforeDays]', 0, "class='form-control'"));?>
            </div>
          </td>
        </tr>  
        <tr class='cycleConfig hidden'>
          <th><?php echo $lang->todo->deadline;?></th>
          <td><?php echo html::input("config[end]", '', "class='form-control form-date'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->type;?></th>
          <td><?php echo html::select('type', $lang->todo->typeList, '', 'onchange="loadList(this.value);" class="form-control"');?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->pri;?></th>
          <td><?php echo html::select('pri', $lang->todo->priList, '', "class='form-control'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->name;?></th>
          <td colspan='2'>
            <div class='nameBox hidden'><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></div>
            <div class='nameBox required'><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></div>
          </td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows='8' class='form-control'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->status;?></th>
          <td><?php echo html::select('status', $lang->todo->statusList, '', "class='form-control chosen'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->beginAndEnd;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('begin', $times, date('Y-m-d') != $date ? key($times) : $time, 'onchange=selectNext(); class="form-control chosen" style="width: 50%;"') . html::select('end', $times, '', 'class="form-control chosen" style="width: 50%; margin-left:-1px"');?>
            </div>
          </td>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' id='switchDate' onclick='switchDateFeature(this);' />
              <label for='switchDate'><?php echo $lang->todo->lblDisableDate;?></label>
            </div>
          </td>
        </tr>  
        <tr>
          <th><?php echo $lang->todo->private;?></th>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' name='private' id='private' value='1' />
              <label for='private'></label>
            </div>
          </td>
        </tr>  
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::backButton('', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<script>
var nowTime = '<?php echo $time?>';
var today   = '<?php echo date('Y-m-d')?>';
var start   = '<?php echo key($times)?>';
</script>
<?php include './footer.html.php';?>
