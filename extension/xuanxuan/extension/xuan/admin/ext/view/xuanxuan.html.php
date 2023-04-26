<?php
/**
 * The index view file of index module of XXB.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     index 
 * @version     $Id: index.html.php 4205 2016-10-24 08:19:13Z liugang $
 * @link        http://xuan.im
 */
include $app->getModuleRoot() . "common/view/header.html.php";
?>
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
<?php include $app->getModuleRoot() . "common/view/footer.html.php"; ?>
