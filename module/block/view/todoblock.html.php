<?php
/**
 * The todo block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
if(!$selfCall) die(include('./todolist.html.php'));
?>
<style>
.block-todoes .panel-body {position: relative; padding-top: 42px; overflow: visible !important; padding-bottom: 0;}
.block-todoes .todoes-input {position: absolute; top: 0; right: 0; left: 0; padding: 0 20px 20px 20px;}
.block-todoes .todoes-input .form-control::-webkit-input-placeholder {font-size: 12px; line-height: 20px;color: #a4a8b6;}
.block-todoes .todoes-input .form-control::-moz-placeholder {font-size: 12px; line-height: 20px; color: #a4a8b6;}
.block-todoes .todoes-input .form-control:-ms-input-placeholder {font-size: 12px; line-height: 20px;color: #a4a8b6;}
.block-todoes .todoes-input .form-control::placeholder {font-size: 12px; line-height: 20px; color: #a4a8b6;}
.block-todoes .todoes {padding: 0 10px 10px 10px; margin: 0 -20px; max-height: 350px; overflow: auto; overflow-x:hidden}
.block-todoes .todoes > li {position: relative; padding: 5px 10px 5px 35px; list-style: none; white-space:nowrap; overflow: auto; overflow-x:hidden; width: 410px;}
.block-todoes .todoes > li:hover {background-color: #e9f2fb;}
.block-todoes .todo-title {padding: 5px 15px 5px 5px;}
.block-todoes .todo-pri {margin: 0 5px;}
.block-todoes .todo-time {display: inline-block; padding: 0 5px; font-size: 12px; color: #8e939a; width: 95px;}
.block-todoes .todo-check {position: absolute; top: 5px; left: 10px; display: block; width: 20px; height: 20px; font-size: 20px; color: transparent; cursor: pointer; background: #fff; border: 2px solid #eee; border-radius: 50%;}
.block-todoes .todo-check:hover {border-color: #8e939a;}
.block-todoes .active > .todo-check {color: #00da88; background: transparent;border: none;}
.block-todoes .todoes-form {position: absolute; top: -45px; right: 0; left: 0; z-index: 1011; max-width: 500px; padding: 12px 20px 20px; visibility: hidden; background: #fff; -webkit-box-shadow: 0 0 20px 0 #bdc9d8; box-shadow: 0 0 20px 0 #bdc9d8; opacity: 0;-webkit-transition: .4s cubic-bezier(.175, .885, .32, 1); -o-transition: .4s cubic-bezier(.175, .885, .32, 1); transition: .4s cubic-bezier(.175, .885, .32, 1); -webkit-transition-property: opacity, visibility; -o-transition-property: opacity, visibility; transition-property: opacity, visibility;}
.block-todoes .todoes-form .form-group > label {padding-left: 0;}
.block-todoes .todoes-form .form-group > label.text-center {text-align: center!important;}
.block-todoes .todoes-form > .form-group:last-child {margin-bottom: 0;}
.block-todoes .todoes-form > h3 {padding: 0 20px 15px; margin: 0 -20px 5px; font-size: 14px; line-height: 20px;}
.block-todoes.show-form .todoes-form {visibility: visible; opacity: 1;}
</style>
<div class='block-todoes'>
  <div class='panel-body'>
    <div class="todoes-input">
      <div class="todo-form-trigger"><input type="text" placeholder="<?php echo $lang->todo->lblClickCreate?>" autocomplete="off" class="form-control"></div>
      <form class="form-horizontal todoes-form layer" method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'create', 'date=today&account=&from=block');?>'>
        <h3><?php echo $lang->todo->create;?></h3>
        <div class="form-group">
          <?php $leftWidth  = common::checkNotCN() ? 'col-sm-3' : 'col-sm-2';?>
          <label for="todoName" class="<?php echo $leftWidth;?>"><?php echo $lang->todo->name?></label>
          <div class="col-sm-9 required"><input type="text" class="form-control" autocomplete="off" name="name"></div>
        </div>
        <div class="form-group">
          <label for="todoPri" class="<?php echo $leftWidth;?>"><?php echo $lang->todo->pri?></label>
          <div class="col-sm-4"><?php echo html::select('pri', $lang->todo->priList, '', "class='form-control chosen'");?></div>
        </div>
        <div class="form-group">
          <label for="todoDate" class="<?php echo $leftWidth;?>"><?php echo $lang->todo->date?></label>
          <div class="col-sm-9">
            <div class="input-control has-icon-right">
              <input type="text" class="form-control date" id="todoDate" name="date" placeholder="" autocomplete='off'>
              <label for='todoDate' class="input-control-icon-right"><i class="icon icon-delay"></i></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="todoBegin" class="<?php echo $leftWidth;?>"><?php echo $lang->todo->beginAndEnd?></label>
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
          <div class="<?php echo $leftWidth;?>"></div>
          <div class="col-sm-9">
            <div class="checkbox-primary">
              <input type="checkbox" name="private" id="private" value="1">
              <label for="private"><?php echo $lang->todo->private?></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12 form-actions no-margin text-center">
            <?php echo html::hidden('type', 'custom');?>
            <?php echo html::commonButton($lang->save, "onclick='ajaxCreateTodo(this)'", "btn btn-primary btn-wide");?>
            <button type="button" class="btn btn-wide todo-form-trigger" data-trigger="false"><?php echo $lang->goback;?></button>
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
          <?php if ($todo->date == '2030-01-01') :?>
          <span class="todo-time"><?php echo $lang->todo->periods['future'] ?></span>
          <?php else:?>
          <span class="todo-time"><?php echo date(DT_DATE4, strtotime($todo->date)) . ' ' . $todo->begin;?></span>
          <?php endif;?>
          <span class="todo-pri label-pri label-pri-<?php echo $todo->pri?>" title="<?php echo zget($lang->todo->priList, $todo->pri);?>"><?php echo zget($lang->todo->priList, $todo->pri);?></span>
          <span class="todo-title" title='<?php echo $todo->name;?>'><?php echo $todo->name;?></span>
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
              $(this).closest('.col-main').css('max-width', ($('#subHeader .container').outerWidth() - $('.container .col-side').outerWidth() - 80) + 'px');
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
                      $block.find('.date').datepicker();
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
      var $name   = $form.find("input[name='name']").val();
      if($name == '')
      {
          $("input[name='name']").addClass('has-error');
          $('#nameLabel').remove();
          $("input[name='name']").after('<div id="nameLabel" class="text-danger help-text"><?php echo sprintf($lang->error->notempty, $lang->todo->name) ?></div>');
          setTimeout('clearError()', 2000)
          return false;
      }
      $.ajax(
      {
          type: "POST",
          dataType: "json",
          url: $form.attr('action'),
          data: $form.serialize(),
          success: function(todo)
          {
              $todoes.removeClass('show-form');
              $todoes.closest('.show-form').removeClass('show-form');
              $todoes.find('.show-form').removeClass('show-form');
              refreshBlock($todoes.parents('div.panel[id^=block]'));
          }
      });
  }

  function clearError()
  {
      $("input[name='name']").removeClass('has-error');
      $('#nameLabel').remove();
  }
  </script>
</div>
