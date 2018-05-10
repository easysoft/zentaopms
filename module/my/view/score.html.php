<?php
/**
 * The score view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->score->record;?></span></span>
  </div>
  <div class="btn-toolbar pull-right">
    <span class='btn text'><strong><?php echo $lang->score->current; ?>:</strong><?php echo $user->score; ?></span>
    <span class='btn text hidden'><label><?php echo $lang->score->level; ?>:</label><?php echo $user->scoreLevel; ?></span>
    <?php common::printLink('score', 'rule', '', $lang->my->scoreRule, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <table class="table table-fixed">
    <thead>
      <tr>
        <th class="w-200px"><?php echo $lang->score->time; ?></th>
        <th class="w-150px"><?php echo $lang->score->module; ?></th>
        <th class="w-150px"><?php echo $lang->score->method; ?></th>
        <th class="w-100px"><?php echo $lang->score->before; ?></th>
        <th class="w-100px"><?php echo $lang->score->score; ?></th>
        <th class="w-100px"><?php echo $lang->score->after; ?></th>
        <th><?php echo $lang->score->desc; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($scores as $score):?>
      <tr>
        <td><?php echo $score->time; ?></td>
        <td><?php echo $lang->score->modules[$score->module]; ?></td>
        <td><?php echo $lang->score->methods[$score->module][$score->method]; ?></td>
        <td><?php echo $score->before; ?></td>
        <td><?php echo $score->score; ?></td>
        <td><?php echo $score->after; ?></td>
        <td><?php echo $score->desc; ?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($scores):?>
  <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
