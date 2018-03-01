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
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['score']); ?><?php echo $lang->score->record; ?></div>
  <div class='actions'>
    <a class='btn disabled'><strong><?php echo $lang->score->current; ?>:</strong><?php echo $user->score; ?></a>
    <span class='btn disabled hidden'><label><?php echo $lang->score->level; ?>:</label><?php echo $user->scoreLevel; ?></span>
    <?php common::printLink('score', 'rule', '', $lang->my->scoreRule, '', "class='btn btn-primary'");?>
  </div>
</div>
<table class='table tablesorter'>
  <thead>
    <tr class='colhead'>
      <th class="w-200px"><?php echo $lang->score->time; ?></th>
      <th class="w-150px"><?php echo $lang->score->module; ?></th>
      <th class="w-150px"><?php echo $lang->score->method; ?></th>
      <th class="w-100px"><?php echo $lang->score->before; ?></th>
      <th class="w-100px"><?php echo $lang->score->score; ?></th>
      <th class="w-100px"><?php echo $lang->score->after; ?></th>
      <th><?php echo $lang->score->desc; ?></th>
    </tr>
  </thead>
  <?php if(!empty($scores)):?>
  <tbody>
    <?php foreach($scores as $score):?>
    <tr class='text-center'>
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
  <?php endif;?>
  <tfoot>
    <tr>
      <td colspan='7'><?php $pager->show();?></td>
    </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
