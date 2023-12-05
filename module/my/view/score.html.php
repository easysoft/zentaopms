<?php
/**
 * The score view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        <th class="c-time"><?php echo $lang->score->time; ?></th>
        <th class="c-module"><?php echo $lang->score->module; ?></th>
        <th class="c-method"><?php echo $lang->score->method; ?></th>
        <th class="c-before"><?php echo $lang->score->before; ?></th>
        <th class="c-score"><?php echo $lang->score->score; ?></th>
        <th class="c-after"><?php echo $lang->score->after; ?></th>
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
        <td class="text-ellipsis" title="<?php echo $score->desc;?>"><?php echo $score->desc;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($scores):?>
  <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
