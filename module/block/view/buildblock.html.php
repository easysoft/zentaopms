<?php
/**
 * The build block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php if(empty($builds)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-builds'>
    <thead>
      <tr>
        <th class='w-id text-center'><?php echo $lang->idAB?></th>
        <?php if($longBlock):?>
        <th><?php echo $lang->build->product;?></th>
        <?php endif;?>
        <th><?php echo $lang->build->name;?></th>
        <th class='w-date'><?php echo $lang->build->date;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($builds as $build):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('build', 'view', "buildID={$build->id}");
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='text-center'><?php echo sprintf('%03d', $build->id);?></td>
        <?php if($longBlock):?>
        <td title='<?php echo $build->productName?>'><?php echo $build->productName?></td>
        <?php endif;?>
        <td title='<?php echo $build->name?>'><?php echo $build->name?></td>
        <td><?php echo $build->date?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>