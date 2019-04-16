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
  <div class='col-sm-7'><?php echo html::input('title', $block ? $block->title : '', "class='form-control'")?></div>
</div>
<div class='form-group'>
  <label for='grid' class='col-sm-3'><?php echo $lang->block->grid;?></label>
  <div class='col-sm-7'>
    <?php
    $grid = 8;
    $gridOptions = $lang->block->gridOptions;
    if($block)
    {
        $type   = $block->block;
        $source = $block->source;
        $grid   = $block->grid;
    }
    if(isset($config->block->longBlock[$source][$type]))
    {
        $grid = 8;
        unset($gridOptions[4]);
    }
    elseif(isset($config->block->shortBlock[$source][$type]))
    {
        $grid = 4;
        unset($gridOptions[8]);
    }
    echo html::select('grid', $gridOptions, $grid, "class='form-control'");
    ?>
  </div>
</div>
