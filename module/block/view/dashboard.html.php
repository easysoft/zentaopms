<?php
/**
 * The dashboard view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
if(isset($pageCSS)) css::internal($pageCSS);
?>
<div class='dashboard dashboard-draggable' id='dashboard' data-confirm-remove-block='<?php  echo $lang->block->confirmRemoveBlock;?>'>
  <div class='row'>
    <?php foreach($blocks as $index => $block):?>
    <div class='col-sm-6 col-md-<?php echo $block->grid;?>'>
      <div class='panel <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $index?>' data-id='<?php echo $index?>' data-name='<?php echo $block->title?>' data-url='<?php echo $block->blockLink?>'>
        <div class='panel-heading'>
          <div class='panel-actions'>
            <?php if(isset($block->moreLink)) echo html::a($block->moreLink, $lang->more . "<i class='icon-double-angle-right'></i>", "class='app-btn'");?>
            <button class='btn btn-mini refresh-panel'><i class='icon-repeat'></i></button>
            <div class='dropdown'>
              <button class='btn btn-mini' data-toggle='dropdown'><span class='caret'></span></button>
              <ul class='dropdown-menu pull-right'>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "index=$index&module=$module"); ?>" class='edit-block' data-title='<?php echo $block->title; ?>' data-icon='icon-pencil'><i class='icon-pencil'></i> <?php echo $lang->edit; ?></a></li>
                <li><a href='javascript:;' class='remove-panel'><i class='icon-remove'></i> <?php echo $lang->delete; ?></a></li>
              </ul>
            </div>
          </div>
          <?php echo $block->title;?>
        </div>
        <div class='panel-body no-padding'></div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div class='dashboard-actions'><a class='btn btn-primary' href='<?php echo $this->createLink("block", "admin", "index=0&module=$module"); ?>' data-toggle='modal'><i class='icon-plus'></i> <?php echo $lang->block->createBlock?></a></div>
</div>
<script>
config.ordersSaved = '<?php echo $lang->block->ordersSaved; ?>';
config.confirmRemoveBlock = '<?php echo $lang->block->confirmRemoveBlock; ?>';
var module = '<?php echo $module?>';
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
if(isset($pageJS)) js::execute($pageJS);

/* Load hook files for current page. */
$extPath      = dirname(dirname(dirname(realpath($viewFile)))) . '/common/ext/view/';
$extHookRule  = $extPath . 'footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
