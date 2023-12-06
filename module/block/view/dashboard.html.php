<?php
/**
 * The dashboard view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
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
  <?php if(empty($longBlocks) && empty($shortBlocks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->block->noData;?></span>
      <?php echo html::a($this->createLink("block", "admin", "id=0&module=$module"), "<i class='icon icon-plus'></i> {$lang->block->createBlock}", '', "data-toggle='modal' data-type='ajax' data-width='700' data-title='{$lang->block->createBlock}' class='btn btn-info'")?>
      <?php echo html::a($this->createLink("block", "ajaxReset", "module=$module"), "<i class='icon icon-refresh'></i> {$lang->block->reset}", 'hiddenwin', 'class="btn btn-info"')?>
    </p>
  </div>
  <?php endif;?>
  <div class="row">
    <div class='col-main'>
      <?php foreach($longBlocks as $index => $block):?>
      <?php if(isset($config->block->closed) && strpos(",{$config->block->closed},", ",{$block->source}|{$block->block},") !== false) continue;?>
      <div class='panel block-<?php echo $block->block;?> <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $block->id?>' data-id='<?php echo $block->id?>' data-name='<?php echo $block->title?>' data-order='<?php echo $block->order?>' data-url='<?php echo $block->blockLink?>'>
        <?php $hasHeading = ($block->block != 'welcome');?>
        <?php if($hasHeading):?>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $block->title;?></div>
        <?php endif;?>
          <nav class='panel-actions nav nav-default'>
            <?php if($this->config->vision == 'rnd' && $block->block == 'guide' && !commonModel::isTutorialMode()) echo '<li>' . html::a($this->createLink('tutorial', 'start'), $lang->block->tutorial, '', "title='{$lang->block->tutorial}' class='iframe tutorialBtn'") . '</li>'; ?>
            <?php if(!empty($block->moreLink)) echo '<li>' . html::a($block->moreLink, strtoupper($lang->more), '', "title='{$lang->more}'") . '</li>'; ?>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><a href='javascript:;' class='refresh-panel'><i class='icon-repeat'></i> <?php echo $lang->block->refresh;?></a></li>
                <?php if(!$useGuest):?>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "id=$block->id&module=$module");?>" class='edit-block' data-title='<?php echo $block->title;?>' ><?php echo $lang->edit;?></a></li>
                <li><a href='javascript:deleteBlock(<?php echo $index;?>);' class='hidden-panel'><?php echo $lang->block->hidden;?></a></li>
                <li><?php if($this->app->user->admin):?>
                <?php echo html::a($this->createLink('block','close',"blockID={$block->id}"), $lang->block->closeForever, 'hiddenwin', "class='close-block' onclick=\"return confirm('{$lang->block->confirmClose}')\"")?>
                <?php endif;?></li>
                <?php endif;?>
                <li class="divider"></li>
                <li><?php echo html::a($this->createLink("block", "admin", "id=0&module=$module"), "{$lang->block->createBlock}", '', "data-toggle='modal' data-type='ajax' data-width='700' data-title='{$lang->block->createBlock}'")?></li>
                <li><?php echo html::a($this->createLink("block", "ajaxReset", "module=$module"), "{$lang->block->reset}", 'hiddenwin')?></li>
              </ul>
            </li>
          </nav>
        <?php if($hasHeading):?>
        </div>
        <?php endif;?>
        <div class='panel-body scrollbar-hover'></div>
      </div>
      <?php endforeach;?>
    </div>
    <div class='col-side'>
      <?php foreach($shortBlocks as $index => $block):?>
      <?php if(isset($config->block->closed) && strpos(",{$config->block->closed},", ",{$block->source}|{$block->block},") !== false) continue;?>
      <div class='panel block-sm block-<?php echo $block->block;?> <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $block->id?>' data-id='<?php echo $block->id?>' data-name='<?php echo $block->title?>' data-order='<?php echo $block->order?>' data-url='<?php echo $block->blockLink?>'>
        <?php $hasHeading = ($block->block != 'welcome');?>
        <?php if($hasHeading):?>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $block->title;?></div>
        <?php endif;?>
          <nav class='panel-actions nav nav-default'>
            <?php if(!empty($block->moreLink)) echo '<li>' . html::a($block->moreLink, strtoupper($lang->more), '', "title='{$lang->more}'") . '</li>';?>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><a href='javascript:;' class='refresh-panel'><i class='icon-repeat'></i> <?php echo $lang->block->refresh?></a></li>
                <?php if(!$useGuest):?>
                <li><a data-toggle='modal' href="<?php echo $this->createLink("block", "admin", "id=$block->id&module=$module"); ?>" class='edit-block' data-title='<?php echo $block->title; ?>' ><?php echo $lang->edit; ?></a></li>
                <li><a href='javascript:deleteBlock(<?php echo $index?>);' class='hidden-panel'><?php echo $lang->block->hidden; ?></a></li>
                <?php if($this->app->user->admin):?>
                <li><?php echo html::a($this->createLink('block', 'close', "blockID={$block->id}"), $lang->block->closeForever, 'hiddenwin', "class='close-block' onclick=\"return confirm('{$lang->block->confirmClose}')\"")?>
                <?php endif;?>
                <?php endif;?>
                <li class="divider"></li>
                <li><?php echo html::a($this->createLink("block", "admin", "id=0&module=$module"), "{$lang->block->createBlock}", '', "data-toggle='modal' data-type='ajax' data-width='700' data-title='{$lang->block->createBlock}'")?></li>
                <li><?php echo html::a($this->createLink("block", "ajaxReset", "module=$module"), "{$lang->block->reset}", 'hiddenwin')?></li>
              </ul>
            </li>
          </nav>
        <?php if($hasHeading):?>
        </div>
        <?php endif;?>
        <div class='panel-body scrollbar-hover'></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<script>
config.ordersSaved        = '<?php echo $lang->block->ordersSaved; ?>';
config.confirmRemoveBlock = '<?php echo $lang->block->confirmRemoveBlock; ?>';
config.cannotPlaceInLeft  = '<?php echo $lang->block->cannotPlaceInLeft; ?>';
config.cannotPlaceInRight = '<?php echo $lang->block->cannotPlaceInRight; ?>';

var module   = '<?php echo $module?>';
var useGuest = <?php echo $useGuest ? 'true' : 'false';?>;

<?php $remind = $this->loadModel('misc')->getPluginRemind();?>
<?php if(!empty($remind)):?>
var myModalTrigger = new $.zui.ModalTrigger({title:'<?php echo $lang->misc->expiredTipsTitle;?>', custom: function(){return <?php echo json_encode($remind);?>}, width:'600px'});
var result = myModalTrigger.show();
$('#pluginButton').click(function(){myModalTrigger.close()});
$('#cancelButton').click(function(){myModalTrigger.close()});
<?php endif;?>

<?php /* Check annual remind */ ?>
$(function()
{
    function checkRemind()
    {
        $.getJSON(createLink('misc', 'getRemind'), function(response)
        {
            if(!response || !response.data || !response.data.content) return;

            var myModalTrigger = new $.zui.ModalTrigger(
            {
                title: response.data.title,
                custom: response.data.content,
                width: 600
            });
            $('#showAnnual').click(function(){myModalTrigger.close()});
        });
    }
    setTimeout(checkRemind, 1000);

    $('#dashboard .row .panel').each(function()
    {
        refreshBlock($(this));
    })
});
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
