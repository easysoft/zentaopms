<?php
/**
 * The testtask block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<table class='table tablesorter table-data table-hover block-testtask table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th>           <?php echo $lang->testtask->product;?></th>
    <th>           <?php echo $lang->testtask->name;?></th>
    <th>           <?php echo $lang->testtask->project . '/' . $lang->testtask->build;?></th>
    <th width='80'><?php echo $lang->testtask->begin;?></th>
    <th width='80'><?php echo $lang->testtask->end;?></th>
  </tr>
  </thead>
  <?php foreach($testtasks as $testtask):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('testtask', 'view', "testtaskID={$testtask->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $testtask->id;?></td>
    <td title='<?php echo $testtask->productName?>'><?php echo $testtask->productName?></td>
    <td title='<?php echo $testtask->name?>'><?php echo $testtask->name?></td>
    <td class='text-center' title='<?php echo $testtask->projectName . '/' . $testtask->buildName?>'><?php echo $testtask->projectName . '/' . $testtask->buildName?></td>
    <td><?php echo $testtask->begin?></td>
    <td><?php echo $testtask->end?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-testtask').dataTable();
</script>
