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
    <div class="todoes-input dropdown">
    <div data-toggle="dropdown"><input type="text" placeholder="<?php echo $lang->todo->lblClickCreate?>" class="form-control"></div>
      <div class="dropdown-menu">
        <header>
        <div class="title"><?php echo $lang->todo->lblClickCreate;?></div>
        </header>
      </div>
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
