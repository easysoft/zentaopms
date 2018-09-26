<?php
/**
 * The testtask block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($testtasks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-testtask table-fixed'>
    <thead>
      <tr class='text-center'>
        <?php if($longBlock):?>
        <th class='w-id'><?php echo $lang->idAB?></th>
        <th class='text-left'><?php echo $lang->testtask->product;?></th>
        <?php endif;?>
        <th class='text-left'><?php echo $lang->testtask->name;?></th>
        <?php if($longBlock):?>
        <th class='text-left'><?php echo $lang->testtask->project . '/' . $lang->testtask->build;?></th>
        <?php endif;?>
        <th class='w-date'><?php echo $lang->testtask->begin;?></th>
        <th class='w-date'><?php echo $lang->testtask->end;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($testtasks as $testtask):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('testtask', 'view', "testtaskID={$testtask->id}");
      ?>
      <tr class='text-center' data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <?php if($longBlock):?>
        <td><?php echo sprintf('%03d', $testtask->id);?></td>
        <td class='text-left' title='<?php echo $testtask->productName?>'><?php echo $testtask->productName?></td>
        <?php endif;?>
        <td class='text-left' title='<?php echo $testtask->name?>'><?php echo $testtask->name?></td>
        <?php if($longBlock):?>
        <td class='text-left' title='<?php echo $testtask->projectName . '/' . $testtask->buildName?>'><?php echo $testtask->projectName . '/' . $testtask->buildName?></td>
        <?php endif;?>
        <td><?php echo $testtask->begin?></td>
        <td><?php echo $testtask->end?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
