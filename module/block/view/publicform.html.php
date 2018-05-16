<?php
/**
 * The public form items of block of Zentao.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang<yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div class='form-group'>
  <label for='title' class='col-sm-3'><?php echo $lang->block->name?></label>
  <div class='col-sm-7'><?php echo html::input('title', $block ? $block->title : '', "class='form-control' autocomplete='off'")?></div>
</div>
<div class='form-group'>
  <label for='grid' class='col-sm-3'><?php echo $lang->block->grid;?></label>
  <div class='col-sm-7'>
    <?php echo html::select('grid', $lang->block->gridOptions, $block ? $block->grid : 8, "class='form-control chosen chosen-simple'")?>
  </div>
</div>