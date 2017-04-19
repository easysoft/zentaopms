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
$useGuest = $this->app->user->account == 'guest';
?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='dashboard' id='dashboard' data-confirm-remove-block='<?php  echo $lang->block->confirmRemoveBlock;?>'>
  <?php if(!$useGuest):?>
  <div class='dashboard-actions'><a href='<?php echo $this->createLink("block", "admin", "id=0&module=$module"); ?>' data-toggle='modal' data-type='ajax' data-width='700' data-title='<?php echo $lang->block->createBlock?>'><i class='icon icon-plus' title='<?php echo $lang->block->createBlock?>' data-toggle='tooltip' data-placement='left'></i></a></div>
  <div class='dashboard-empty-message hide'>
    <a href='<?php echo $this->createLink("block", "admin", "id=0&module=$module"); ?>' data-toggle='modal' data-type='ajax' data-width='700' class='btn btn-primary'><i class='icon icon-plus'></i> <?php echo $lang->block->createBlock?></a>
  </div>
  <?php endif;?>
  <div class='row'>
    <?php foreach($blocks as $index => $block):?>
    <?php if(isset($config->block->closed) and strpos(",{$config->block->closed},", ",{$block->source}|{$block->block},") !== false) continue;?>
    <div class='col-sm-6 col-md-<?php echo $block->grid;?>'>
      <div class='panel panel-block <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $block->id?>' data-id='<?php echo $block->id?>' data-name='<?php echo $block->title?>' data-url='<?php echo $block->blockLink?>' <?php if($block->height) echo "data-height='$block->height'";?>>
        <div class='panel-heading'>
          <div class='panel-actions'>
            <?php if(!empty($block->moreLink)):?>
               <?php echo html::a($block->moreLink, " <i class='icon icon-more'></i>", null, "class='panel-action drag-disabled panel-action-more'"); ?>
            <?php endif; ?>
            <div class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><a href='javascript:;' class='refresh-panel'><i class='icon-repeat'></i> <?php echo $lang->block->refresh ?></a></li>
                <?php if(!$useGuest):?>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "id=$block->id&module=$module"); ?>" class='edit-block' data-title='<?php echo $block->title; ?>' data-icon='icon-pencil'><i class='icon-pencil'></i> <?php echo $lang->edit; ?></a></li>
                <?php if(!$block->source and $block->block == 'html'):?>
                <li><a href="javascript:hiddenBlock(<?php echo $index;?>)" class="hidden-panel"><i class='icon-eye-close'></i><?php echo $lang->block->hidden; ?></a></li>
                <?php endif;?>
                <li><a href='javascript:;' class='remove-panel'><i class='icon-remove'></i> <?php echo $lang->block->remove; ?></a></li>
                <?php endif;?>
                <?php if($this->app->user->admin):?>
                <li><?php echo html::a($this->createLink('block', 'close', "blockID={$block->id}"), "<i class='icon-eye-close'></i> {$lang->block->closeForever}", 'hiddenwin', "class='close-block' onclick=\"return confirm('{$lang->block->confirmClose}')\"")?>
                <?php endif;?>
              </ul>
            </div>
          </div>
          <span class='panel-title'><?php echo $block->title;?></span>
        </div>
        <div class='panel-body no-padding'></div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<script>
config.ordersSaved = '<?php echo $lang->block->ordersSaved; ?>';
config.confirmRemoveBlock = '<?php echo $lang->block->confirmRemoveBlock; ?>';
var module   = '<?php echo $module?>';
var useGuest = <?php echo $useGuest ? 'true' : 'false';?>;
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
