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
<table class='table tablesorter table-data table-hover block-build table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th>           <?php echo $lang->build->product;?></th>
    <th>           <?php echo $lang->build->name;?></th>
    <th width='80'><?php echo $lang->build->date;?></th>
  </tr>
  </thead>
  <?php foreach($builds as $build):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('build', 'view', "buildID={$build->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $build->id;?></td>
    <td title='<?php echo $build->productName?>'><?php echo $build->productName?></td>
    <td title='<?php echo $build->name?>'><?php echo $build->name?></td>
    <td><?php echo $build->date?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-build').dataTable();
</script>
