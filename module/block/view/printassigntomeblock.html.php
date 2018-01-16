<?php
/**
 * The assigntome block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div class="row-table">
  <?php $active = key($hasViewPriv);?>
  <div class="col-table" style='padding-right:0px;width:60px;'>
    <ul class="nav nav-tabs nav-stacked">
      <?php foreach($hasViewPriv as $type => $bool):?>
      <li<?php if($type == $active) echo " class='active'"?>><a href="###" data-target="#<?php echo $type?>" data-toggle="tab"><?php echo $lang->block->assignToMeList[$type]?></a></li>
	  <?php endforeach;?>
    </ul>
  </div>
  <div class="col-table" style='padding:0px;'>
    <div class="tab-content" style='padding:0px;'>
      <?php foreach($hasViewPriv as $type => $bool):?>
      <div class="tab-pane fade<?php if($type == $active) echo " active in"?>" id="<?php echo $type?>">
		<?php include "{$type}block.html.php";?>
      </div>
	  <?php endforeach;?>
    </div>
  </div>
</div>
<script>
$(function()
{
    setTimeout(function()
    {
        $('.table-header-fixed').remove();
        $('#todo table thead').removeAttr('style');
    }, 500);
})
</script>
