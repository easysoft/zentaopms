<?php
/**
 * The index view file of index module of XXB.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     index 
 * @version     $Id: index.html.php 4205 2016-10-24 08:19:13Z liugang $
 * @link        http://xuan.im
 */
include "../../../common/view/header.html.php";
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left"><?php common::printAdminSubMenu('xuanxuan');?></div>
</div>
<div id='dashboardWrapper'>
  <div class='panels-container dashboard' id='dashboard'>
    <div class='row'>
      <?php foreach($blocks as $index => $block):?>
      <div class='col-xs-<?php echo $block->grid;?>'>
        <div class='panel' id='block<?php echo $index?>' data-name='<?php echo $block->title?>'>
          <div class='panel-heading'>
            <div class='panel-title'><?php echo $block->title?></div>
          </div>
          <div class='panel-body no-padding'>
            <?php
            $funcName = 'block' . $block->block;
            echo $this->admin->$funcName($block);
            ?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php include "../../../common/view/footer.html.php"; ?>
