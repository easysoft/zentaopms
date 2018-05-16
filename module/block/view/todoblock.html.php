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
<div class='block-todoes' id="block <?php echo ($block->block)?>">
  <div class='panel-body'>
    <div class="todoes-input">
      <div class="todo-form-trigger"><input type="text" placeholder="<?php echo $lang->todo->lblClickCreate?>" class="form-control"></div>
      <form class="form-horizontal todoes-form layer" method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'create', 'date=today&account=&from=block');?>'>
        <h3><?php echo $lang->todo->create . $lang->todo->common;?></h3>
        <div class="form-group">
          <label for="todoName" class="col-sm-2"><?php echo $lang->todo->name?></label>
          <div class="col-sm-9 required"><input type="text" class="form-control" name="name"></div>
        </div>
        <div class="form-group">
          <label for="todoPri" class="col-sm-2"><?php echo $lang->todo->pri?></label>
          <div class="col-sm-4"><?php echo html::select('pri', $lang->todo->priList, '', "class='form-control chosen'");?></div>
        </div>
        <div class="form-group">
          <label for="todoDate" class="col-sm-2"><?php echo $lang->todo->date?></label>
          <div class="col-sm-9 required">
            <div class="input-control has-icon-right">
              <input type="text" class="form-control form-date" id="todoDate" name="date" placeholder="(<?php echo $lang->required;?>)">
              <label for='todoDate' class="input-control-icon-right"><i class="icon icon-delay"></i></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="todoBegin" class="col-sm-2"><?php echo $lang->todo->beginAndEnd?></label>
          <div class="col-sm-4">
            <select name="begin" id="todoBegin" class="form-control chosen-simple">
              <option value=""><?php echo $lang->todo->lblDisableDate;?></option>
            </select>
          </div>
          <label class="col-sm-1 text-center hide-empty-begin" for="todoEnd"> ~ </label>
          <div class="col-sm-4 hide-empty-begin">
            <select name="end" id="todoEnd" class="form-control chosen-simple"></select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <div class="col-sm-10">
            <div class="checkbox-primary">
              <input type="checkbox" name="private" id="private" value="1"> 
              <label for="private"><?php echo $lang->todo->private?></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <div class="col-sm-10">
            <?php echo html::hidden('type', 'custom');?>
            <?php echo html::commonButton($lang->save, "onclick='ajaxCreateTodo(this)'", "btn btn-primary btn-wide");?>
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
        <a href="<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink);?>" class='iframe' <?php echo $appid?>>
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
      // Todoes block
      if(!$.fn.blockTodoes)
      {
          $.fn.blockTodoes = function()
          {
              return this.each(function()
              {
                  var $block = $(this);
                  if($block.data('blockTodoes')) return;
                  $block.data('blockTodoes', 1);
                  var $form = $block.find('form');
                  var $titleInput = $form.find('[name="name"]');
    
                  var toggleForm = function(toggle)
                  {
                      if(toggle === undefined)
                      {
                          toggle = !$block.hasClass('show-form');
                      }
                      $block.toggleClass('show-form', toggle);
                      if(toggle)
                      {
                          setTimeout(function() {$titleInput.focus();}, 50);
                      }
                  };
                  $block.on('click', '.todo-form-trigger', function()
                  {
                      toggleForm($(this).data('trigger'));
                  });
                  $form.timeSpanControl(
                  {
                      onChange: function($control)
                      {
                          $control.trigger('chosen:updated');
                      }
                  }).find('[name="begin"]').trigger('chosen:updated');
              });
          };
      }

      $('.block-todoes').blockTodoes().on('click', '.todo-check', function()
      {
          var $liTag     = $(this).closest('li');
          var isFinished = $liTag.hasClass('active');
          var todoID     = $liTag.data('id');
          var methodName = isFinished ? 'activate' : 'finish';
          $.get(createLink('todo', methodName, "todoID=" + todoID), function()
          {
              if(!isFinished) $liTag.addClass('active');
              if(isFinished) $liTag.removeClass('active');
          });
      });
  });

  function ajaxCreateTodo(obj)
  {
      var $todoes = $(obj).closest('.block-todoes');
      var $form   = $(obj).closest('form');
      $.ajax(
      {
          type: "POST",
          dataType: "json",
          url: $form.attr('action'),
          data: $form.serialize(),
          success: function(todo)
          {
              var item = "<li data-id='" + todo.id + "'>";
              item += '<span class="todo-check icon icon-check-circle"></span>';
              item += '<a href="' + createLink('todo', 'view', "todoID=" + todo.id + "&from=my", 'html', true) + '" class="iframe">';
              item += '<span class="todo-title">' + todo.name + '</span>';
              item += '<span class="todo-pri todo-pri-' + todo.pri + '">' + todo.priName + '</span><span class="todo-time">' + todo.time + '</span></a></li>';
              $todoes.find('ul.todoes').prepend(item);
              $todoes.removeClass('show-form');
              $todoes.find('ul.todoes li:first a').modalTrigger();
              $form.find('input[name="name"]').val('');
              $form.find('#todoDate').val('');
          }
      });
  }
  </script>
</div>
