<?php
/**
 * The todo view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: todo.html.php 4735 2013-05-03 08:30:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($_GET['ajax']) and $_GET['ajax'] == 'yes') die(include "./todolist.html.php")?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmDelete', $lang->todo->confirmDelete)?>
<form method='post' id='todoform'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php 
      foreach($lang->todo->periods as $period => $label)
      {
          $vars = "date=$period";
          if($period == 'before') $vars .= "&account={$app->user->account}&status=undone";
          echo "<span id='$period'>" . html::a(inlink('todo', $vars), $label) . '</span>';
      }
      echo "<span id='byDate'>" . html::input('date', $date,"class='w-date date' onchange='changeDate(this.value)'") . '</span>';

      if($type == 'bydate') 
      {
          if($date == date('Y-m-d'))
          {
              $type = 'today'; 
          }
          else if($date == date('Y-m-d', strtotime('-1 day')))
          {
              $type = 'yesterday'; 
          }
      }
      ?>
      <script>$('#<?php echo $type;?>').addClass('active')</script>
    </div>
    <div class='f-right'>
      <?php 
      common::printIcon('todo', 'export', "account=$account&orderBy=id_desc");
      common::printIcon('todo', 'batchCreate');
      common::printIcon('todo', 'create', "date=" . str_replace('-', '', $date));
      ?>
    </div>
  </div>
  <div id='todo'>
    <?php include "./todolist.html.php"?>
  </div>
</form>
<?php js::set('listName', 'todoList')?>
<?php include '../../common/view/footer.html.php';?>
