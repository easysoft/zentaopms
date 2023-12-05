<?php
/**
 * The public form items of block of Zentao.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div class='form-group'>
  <label for='title' class='col-sm-3'><?php echo $lang->block->name?></label>
  <div class='col-sm-7'><?php echo html::input('title', !empty($block) ? $block->title : '', "class='form-control'")?></div>
</div>
<div class='form-group'>
  <label for='grid' class='col-sm-3'><?php echo $lang->block->grid;?></label>
  <div class='col-sm-7'>
    <?php
    $grid = 8;
    $gridOptions = $lang->block->gridOptions;
    if(!empty($block))
    {
        $module = $block->module;
        $type   = $block->block;
        $grid   = $block->grid;
    }
    if(isset($config->block->longBlock[$dashboard][$module]))
    {
        $grid = 8;
        unset($gridOptions[4]);
    }
    elseif(isset($config->block->shortBlock[$dashboard][$module]))
    {
        $grid = 4;
        unset($gridOptions[8]);
    }
    echo html::select('grid', $gridOptions, $grid, "class='form-control'");
    ?>
  </div>
</div>
