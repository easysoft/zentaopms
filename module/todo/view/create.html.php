<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
<?php js::set('moduleList', $config->todo->moduleList)?>
<?php js::set('objectsMethod', $config->todo->getUserObjectsMethod)?>
<?php js::set('nameBoxLabel', array('custom' => $lang->todo->name, 'objectID' => $lang->todo->objectID));?>
<?php js::set('vision', $config->vision);?>
<?php js::set('noOptions', $lang->todo->noOptions);?>
<?php js::set('chosenType', $lang->todo->typeList);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->todo->create;?></h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->todo->date;?></th>
          <td class='w-400px'>
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
                  <input type='checkbox' id='cycle' name='cycle' value='1' onclick='switchDateTodo(this);' />
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
                <div class='input-group every w-250px'>
                  <span class='input-group-addon'><?php echo $lang->todo->every;?></span>
                  <?php echo html::input('config[day]', '', "class='form-control' id='everyInput'");?>
                  <span class='input-group-addon'><?php echo $lang->todo->cycleDay;?></span>
                  <span class='input-group-addon'>
                    <div class='checkbox-primary w-50px'>
                      <input type='checkbox' name='config[specifiedDate]' id='configSpecify' value='1' onclick='showSpecifiedDate(this);' />
                      <label for='config[specifiedDate]'><?php echo $lang->todo->specify;?></label>
                    </div>
                  </span>
                </div>
                <div class='input-group specify hidden'>
                  <span class='input-group-addon'><?php echo $lang->todo->specify;?></span>
                  <?php echo html::select('config[specify][month]', $lang->datepicker->monthNames, 0, "class='form-control w-80px' onchange='setDays(this.value);'");?>
                  <?php echo html::select('config[specify][day]', $lang->todo->specifiedDay, 1, "class='form-control w-60px' id='specifiedDay'");?>
                  <span class='input-group-addon <?php echo strpos($this->app->getClientLang(), 'zh') !== false ? '' : 'hidden';?>'><?php echo $lang->todo->day;?></span>
                  <span class='input-group-addon'>
                    <div class='checkbox-primary w-50px'>
                      <input type='checkbox' name='config[cycleYear]' id='cycleYear' value='1' />
                      <label for='config[cycleYear]'><?php echo $lang->todo->everyYear;?></label>
                    </div>
                  </span>
                  <span class='input-group-addon'>
                    <div class='checkbox-primary w-50px'>
                      <input type='checkbox' name='configEvery' id='configEvery' value='1' onclick='showEvery(this);' />
                      <label for='configEvery'><?php echo $lang->todo->every;?></label>
                    </div>
                  </span>
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
            <div class='input-group inputGroupWidth'>
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
          <th><?php echo $lang->todo->assignTo;?></th>
          <td><?php echo html::select('assignedTo', $users, $app->user->account, 'class="form-control chosen"');?></td>
        </tr>
        <tr>
          <th id='nameBoxLabel'><?php echo $lang->todo->name;?></th>
          <td colspan='2'>
            <div id='nameBox' class='hidden'><?php echo html::input('name', '', "class='form-control'");?></div>
            <div class='input-group title-group required'>
              <div class='nameBox'><?php echo html::input('name', isset($name) ? $name : '', "class='form-control'");?></div>
              <span class="input-group-addon fix-border br-0"><?php echo $lang->todo->pri;?></span>
              <div class="input-group-btn pri-selector" data-type="pri">
                <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                  <span class="pri-text"><span class="label-pri label-pri-3">3</span></span> &nbsp;<span class="caret"></span>
                </button>
                <div class='dropdown-menu pull-right'>
                  <?php echo html::select('pri', $lang->todo->priList, 3, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', isset($desc) ? $desc : '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->status;?></th>
          <td><?php echo html::select('status', $lang->todo->statusList, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->beginAndEnd;?></th>
          <td>
            <div class='input-group'>
            <?php
              echo html::select('begin', $times, date('Y-m-d') != $date ? key($times) : $time, 'onchange=selectNext(); class="form-control chosen"');
              echo html::select('end', $times, '', 'class="form-control chosen"');
            ?>
            </div>
          </td>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' id='switchTime' onclick='switchDateFeature(this);' />
              <label for='switchTime'><?php echo $lang->todo->lblDisableDate;?></label>
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
            <?php echo html::submitButton();?>
            <?php if(!isonlybody()) echo html::a($this->session->todoList, $lang->goback, '', "class='btn btn-back btn-wide'");?>
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
