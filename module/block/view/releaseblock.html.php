<?php
/**
 * The release block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<table class='table tablesorter table-data table-hover block-release table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th>           <?php echo $lang->release->product;?></th>
    <th>           <?php echo $lang->release->name;?></th>
    <th>           <?php echo $lang->release->build;?></th>
    <th width='80'><?php echo $lang->release->date;?></th>
    <th width='70'><?php echo $lang->release->status;?></th>
  </tr>
  </thead>
  <?php foreach($releases as $release):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('release', 'view', "releaseID={$release->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $release->id;?></td>
    <td class='text-left' title='<?php echo $release->productName?>'><?php echo $release->productName?></td>
    <td class='text-center' title='<?php echo $release->name?>'><?php echo $release->name?></td>
    <td class='text-center' title='<?php echo $release->buildName?>'><?php echo $release->buildName?></td>
    <td class='text-center'><?php echo $release->date?></td>
    <td class='text-center'><?php echo $lang->release->statusList[$release->status]?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-release').dataTable();
</script>
