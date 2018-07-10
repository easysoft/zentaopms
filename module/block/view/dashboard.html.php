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
<div class='dashboard auto-fade-in fade' id='dashboard' data-confirm-remove-block='<?php  echo $lang->block->confirmRemoveBlock;?>'>
  <div class="row">
    <div class='col-main'>
      <?php foreach($longBlocks as $index => $block):?>
      <?php if(isset($config->block->closed) and strpos(",{$config->block->closed},", ",{$block->source}|{$block->block},") !== false) continue;?>
      <div class='panel block-<?php echo $block->block;?> <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $block->id?>' data-id='<?php echo $block->id?>' data-name='<?php echo $block->title?>' data-order='<?php echo $block->order?>' data-url='<?php echo $block->blockLink?>'>
        <?php $hasHeading = ($block->block != 'welcome');?>
        <?php if($hasHeading):?>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $block->title;?></div>
        <?php endif;?>
          <nav class='panel-actions nav nav-default'>
            <?php if(!empty($block->actionLink)) echo '<li>' . $block->actionLink . '</li>';?>
            <?php if(!empty($block->moreLink)) echo '<li>' . html::a($block->moreLink, '<i class="icon icon-more"></i>', '', "title='{$lang->more}'") . '</li>'; ?>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><a href='javascript:;' class='refresh-panel'><i class='icon-repeat'></i> <?php echo $lang->block->refresh ?></a></li>
                <?php if(!$useGuest):?>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "id=$block->id&module=$module");?>" class='edit-block' data-title='<?php echo $block->title;?>' ><?php echo $lang->edit;?></a></li>
                <?php if(!$block->source and $block->block == 'html'):?>
                <li><a href="javascript:hiddenBlock(<?php echo $index;?>)" class="hidden-panel"><?php echo $lang->block->hidden;?></a></li>
                <?php endif;?>
                <li><a href='javascript:deleteBlock(<?php echo $index;?>);' class='remove-panel'><?php echo $lang->block->remove;?></a></li>
                <?php endif;?>
                <?php if($this->app->user->admin):?>
                <li><?php echo html::a($this->createLink('block', 'close', "blockID={$block->id}"), $lang->block->closeForever, 'hiddenwin', "class='close-block' onclick=\"return confirm('{$lang->block->confirmClose}')\"")?>
                <?php endif;?>
              </ul>
            </li>
          </nav>
        <?php if($hasHeading):?>
        </div>
        <?php endif;?>
        <?php echo $this->fetch('block', 'printBlock', "id=$block->id&module=$module")?>
      </div>
      <?php endforeach;?>
    </div>
    <div class='col-side'>
      <?php foreach($shortBlocks as $index => $block):?>
      <?php if(isset($config->block->closed) and strpos(",{$config->block->closed},", ",{$block->source}|{$block->block},") !== false) continue;?>
      <div class='panel block-sm block-<?php echo $block->block;?> <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $block->id?>' data-id='<?php echo $block->id?>' data-name='<?php echo $block->title?>' data-order='<?php echo $block->order?>' data-url='<?php echo $block->blockLink?>'>
        <?php $hasHeading = ($block->block != 'welcome');?>
        <?php if($hasHeading):?>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $block->title;?></div>
        <?php endif;?>
          <nav class='panel-actions nav nav-default'>
            <?php if(!empty($block->actionLink)) echo '<li>' . $block->actionLink . '</li>';?>
            <?php if(!empty($block->moreLink)) echo '<li>' . html::a($block->moreLink, '<i class="icon icon-more"></i>', '', "title='{$lang->more}'") . '</li>';?>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><a href='javascript:;' class='refresh-panel'><i class='icon-repeat'></i> <?php echo $lang->block->refresh ?></a></li>
                <?php if(!$useGuest):?>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "id=$block->id&module=$module"); ?>" class='edit-block' data-title='<?php echo $block->title; ?>' ><?php echo $lang->edit; ?></a></li>
                <?php if(!$block->source and $block->block == 'html'):?>
                <li><a href="javascript:hiddenBlock(<?php echo $index;?>)" class="hidden-panel"><?php echo $lang->block->hidden; ?></a></li>
                <?php endif;?>
                <li><a href='javascript:deleteBlock(<?php echo $index?>);' class='remove-panel'><?php echo $lang->block->remove; ?></a></li>
                <?php endif;?>
                <?php if($this->app->user->admin):?>
                <li><?php echo html::a($this->createLink('block', 'close', "blockID={$block->id}"), $lang->block->closeForever, 'hiddenwin', "class='close-block' onclick=\"return confirm('{$lang->block->confirmClose}')\"")?>
                <?php endif;?>
              </ul>
            </li>
          </nav>
        <?php if($hasHeading):?>
        </div>
        <?php endif;?>
        <?php echo $this->fetch('block', 'printBlock', "id=$block->id&module=$module")?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<script>
config.ordersSaved = '<?php echo $lang->block->ordersSaved; ?>';
config.confirmRemoveBlock = '<?php echo $lang->block->confirmRemoveBlock; ?>';
var module   = '<?php echo $module?>';
var useGuest = <?php echo $useGuest ? 'true' : 'false';?>;
<?php if(!$useGuest):?>
<?php
$dropmenu  = "<div class='btn-group'>";
$dropmenu .= html::a($this->createLink("block", "admin", "id=0&module=$module"), "<i class='icon icon-plus text-muted'></i> {$lang->block->createBlock}", '', "class='btn' data-toggle='modal' data-type='ajax' data-width='700' data-title='{$lang->block->createBlock}'");
$dropmenu .= "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown' style='padding-bottom:7px;'><span class='caret'></span></button>";
$dropmenu .= "<ul class='dropdown-menu pull-right' role='menu'>";
$dropmenu .= "<li>" . html::a($this->createLink("block", "ajaxReset", "module=$module"), $lang->block->reset, 'hiddenwin') . "</li>";
$dropmenu .= '</ul></div>';
?>
$('#subHeader #pageActions .btn-toolbar:last').append(<?php echo json_encode($dropmenu)?>);
<?php if(!isset($config->$module->block->initVersion) or $config->$module->block->initVersion < '2'):?>
$(function()
{
    if(confirm('<?php echo $lang->block->noticeNewBlock;?>'))
    {
        $('#hiddenwin').attr('src', '<?php echo $this->createLink('block', 'ajaxUseNew', "module=$module&confirm=yes");?>');
    }
    else
    {
        $('#hiddenwin').attr('src', '<?php echo $this->createLink('block', 'ajaxUseNew', "module=$module&confirm=no");?>');
    }
})
<?php endif;?>
<?php endif;?>
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
