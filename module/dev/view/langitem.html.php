<?php
/**
 * The editor view file of dev module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dev
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php js::set('type', $type); ?>
<?php js::set('navTypes', $config->dev->navTypes); ?>
<?php js::set('menuTree', $menuTree)?>
<?php include 'header.html.php';?>
<div id='mainMenu' class='clearfix menu-secondary'>
  <div class="btn-toolBar pull-left">
    <div class="dropdown">
      <button class="btn" type="button" data-toggle="dropdown"><?php printf($lang->dev->language, $config->langs[str_replace('_', '-', $language)]);?> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php foreach($config->langs as $key => $value):?>
        <?php
        $key    = str_replace('-', '_', $key);
        $active = $key == $language ? 'active' : '';
        ?>
        <li class='<?php echo $active?>'><?php echo html::a(inlink('langItem', "type=$type&module=$module&method=$method&language=$key"), $value);?></li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php foreach($lang->dev->featureBar['langItem'] as $key => $label):?>
    <?php $active = $type == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('langItem', "type=$key&module=$module&method=$method&language=$language"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div class="flex gap-15">
  <?php if(in_array($type, $config->dev->navTypes)):?>
  <div class="menu-tree">
    <div class="input-control search-box has-icon-left has-icon-right search-example">
      <input type="search" class="form-control search-input"/>
      <label class="input-control-icon-left search-icon flex align-center justify-center"><i class="icon icon-search"></i></label>
    </div>
    <div id="menuTree" class="menu-active-primary menu-hover-primary"></div>
  </div>
  <?php endif;?>

  <form class='main-form form-ajax flex-1' method='post'>
    <div class="main-content">
      <div class="title-content flex">
        <?php if(str_replace('-', '_', $this->app->getClientLang()) != $language):?>
          <div class="title"><?php echo $lang->dev->currentLang;?> </div>
        <?php endif;?>
        <div class="title"><?php echo $lang->dev->defaultValue?></div>
        <div class="title"><?php echo $lang->dev->modifyValue?></div>
      </div>
      <div class="form-item-content">
        <?php foreach($originalLangs as $langKey => $originalLang):?>
        <div itemid="<?php echo "{$moduleName}_{$langKey}"?>" class="form-item flex">
          <?php if(str_replace('-', '_', $this->app->getClientLang()) != $language):?>
          <div data-id="<?php echo "{$moduleName}_{$langKey}"?>" class="input-label h-full"><?php echo $originalLang?></div>
          <?php endif;?>
          <div data-id="<?php echo "{$moduleName}_{$langKey}"?>" class="input-label h-full"><?php echo $originalLang?></div>
          <i class="icon icon-angle-right text-primary"></i>
          <div class="input-control">
            <?php echo html::input("{$moduleName}_{$langKey}", zget($customedLangs, $langKey, ''), "class='form-control shadow-primary-hover' placeholder='{$originalLang}'");?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>

    <div class="bottom-btn">
      <?php echo html::submitButton(); ?>
      <?php echo html::a(inlink('resetLang', "type={$type}&module={$moduleName}&language={$language}"), $lang->restore, 'hiddenwin', "id='reset' class='btn btn-wide ml-20'");?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
