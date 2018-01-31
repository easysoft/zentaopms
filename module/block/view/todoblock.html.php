<?php
/**
 * The todo block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<div class='block-todoes'>
  <div class='panel-body'>
    <div class="todoes-input">
      <div class="todo-form-trigger"><input type="text" placeholder="<?php echo $lang->todo->lblClickCreate?>" class="form-control"></div>
      <form class="form-horizontal todoes-form layer" method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'create');?>'>
        <h3><?php echo $lang->todo->create . $lang->todo->common;?></h3>
        <div class="form-group">
          <div class="col-sm-12"><input required type="text" placeholder="<?php echo $lang->todo->name?>" class="form-control" name="name"></div>
        </div>
        <div class="form-group">
          <label for="todoPri" class="col-sm-2"><?php echo $lang->todo->pri?></label>
          <div class="col-sm-4"><?php echo html::select('pri', $lang->todo->priList, '', "class='form-control chosen'");?></div>
        </div>
        <div class="form-group">
          <label for="todoDate" class="col-sm-2"><?php echo $lang->todo->date?></label>
          <div class="col-sm-9">
            <div class="input-control has-icon-right">
              <input type="text" required class="form-control form-date" id="todoDate" name="date" placeholder="(<?php echo $lang->required;?>)">
              <label for="inputPasswordExample1" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="todoBegin" class="col-sm-2"><?php echo $lang->todo->beginAndEnd?></label>
          <div class="col-sm-4">
            <select name="begin" id="todoBegin" class="form-control">
              <option value=""><?php echo $lang->todo->lblDisableDate;?></option>
            </select>
          </div>
          <label class="col-sm-1 text-center hide-empty-begin" for="todoEnd"> ~ </label>
          <div class="col-sm-4 hide-empty-begin">
            <select name="end" id="todoEnd" class="form-control"></select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <div class="col-sm-10">
            <div class="checkbox">
              <label><input type="checkbox"> <?php echo $lang->todo->private?></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <div class="col-sm-10">
            <?php echo html::hidden('type', 'custom');?>
            <?php echo html::submitButton($lang->save, '', "btn btn-primary btn-wide");?>
            <button type="button" class="btn btn-default btn-wide todo-form-trigger" data-trigger="false"><?php echo $lang->goback;?></button>
          </div>
        </div>
      </form>
    </div>
    <ul class="todoes">
      <?php foreach($todos as $id => $todo):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('todo', 'view', "todoID={$todo->id}&from=my", 'html', true);
      ?>
      <li data-id='<?php echo $todo->id?>'>
        <span class="todo-check icon icon-check-circle"></span>
        <a href="<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink);?>" class='iframe'>
          <span class="todo-title"><?php echo $todo->name;?></span>
          <span class="todo-pri todo-pri-<?php echo $todo->pri?>"><?php echo zget($lang->todo->priList, $todo->pri);?></span><span class="todo-time"><?php echo date(DT_DATE4, strtotime($todo->date)) . ' ' . $todo->begin;?></span>
        </a>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
  <script>
  $(function()
  {
      $('ul.todoes li .todo-check').click(function()
      {
          var $liTag     = $(this).closest('li');
          var isFinished = $liTag.hasClass('active');
          var todoID     = $liTag.data('id');
          var methodName = isFinished ? 'activate' : 'finish';
          $liTag.removeClass('active');
          $.get(createLink('todo', methodName, "todoID=" + todoID), function()
          {
              if(!isFinished) $liTag.addClass('active');
          });
      });
  });
  </script>
</div>
