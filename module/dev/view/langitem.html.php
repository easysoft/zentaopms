<?php
/**
 * The editor view file of dev module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dev
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php js::set('type', $type); ?>
<?php js::set('navTypes', $config->dev->navTypes); ?>
<?php js::set('menuTree', $menuTree)?>
<?php js::set('language', $language)?>
<div id='mainMenu' class='clearfix menu-secondary'>
  <div class="btn-toolbar pull-left">
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
    <?php echo html::a(inlink('langItem', "type=$key&module=&method=&language=$language"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div class="flex main-box">
  <?php if(in_array($type, $config->dev->navTypes)):?>
  <div class="menu-tree">
    <div class="input-control search-box has-icon-left has-icon-right search-example">
      <input type="search" class="form-control search-input"/>
      <label class="input-control-icon-left search-icon flex align-center justify-center"><i class="icon icon-search"></i></label>
    </div>
    <div id="menuTree" class="menu-active-primary menu-hover-primary"></div>
  </div>
  <?php endif;?>

  <form class='main-form form-ajax flex-1' method='post' id="data-form-<?php echo $type?>">
    <div class="main-content">
      <div class="title-content flex">
        <?php if(str_replace('-', '_', $this->app->getClientLang()) != $language):?>
          <div class="title"><?php echo $lang->dev->currentLang;?> </div>
        <?php endif;?>
        <div class="title"><?php echo $lang->dev->defaultValue?></div>
        <div class="title title-input"><?php echo $lang->dev->modifyValue?></div>
      </div>
      <div class="form-item-content form-active-primary">
        <?php $isCurrentLang = str_replace('-', '_', $this->app->getClientLang()) == $language;?>
        <?php foreach($originalLangs as $langKey => $originalLang):?>
        <?php
        if(isset($config->custom->commonLang[$originalLang])) continue;
        $itemKey = "{$moduleName}_{$langKey}";
        if(!$isCurrentLang) $currentLangs[$langKey] = strtr($currentLangs[$langKey], $currentCommonLang);
        $defaultValue = $this->dev->parseCommonLang($originalLang);
        $customedLang = $this->dev->parseCommonLang(zget($customedLangs, $langKey, ''));
        $originalLang = strtr($originalLang, $config->custom->commonLang);
        ?>
        <div data-id="<?php echo $itemKey?>" class="form-item flex <?php if(!$isCurrentLang):?>w-expand<?php endif;?>">
          <?php if(!$isCurrentLang):?>
          <div data-id="<?php echo $itemKey?>" class="label h-full" title="<?php echo $currentLangs[$langKey]?>"><?php echo $currentLangs[$langKey]?></div>
          <?php endif;?>
          <div data-id="<?php echo $itemKey?>" class="label h-full <?php if($language != 'zh-cn')  echo 'lg'?>" title="<?php echo $originalLang?>"><?php echo $originalLang?></div>
          <div class="input-group flex">
            <i class="icon icon-angle-right text-primary"></i>
            <?php $originalLangChanged = $this->dev->isOriginalLangChanged($defaultValue, $customedLang);?>
            <?php if(($originalLangChanged and is_array($customedLang)) or (!$originalLangChanged and is_array($defaultValue))):?>
            <?php $foreachLang = $originalLangChanged ? $customedLang : $defaultValue;?>
            <?php foreach($foreachLang as $i => $subLang):?>
            <?php if(isset($config->custom->commonLang[$subLang])):?>
            <div class='input-group-addon flex-center' title=<?php echo $config->custom->commonLang[$subLang]?> ><?php echo $config->custom->commonLang[$subLang] . html::hidden("{$itemKey}[]", $subLang);?></div>
            <?php else:?>
            <?php
            $placeholder     = $originalLangChanged ? '' : "placeholder='{$subLang}'";
            $customedSubLang = $subLang;
            if(!$originalLangChanged) $customedSubLang = empty($customedLang) ? '' : zget($customedLang, $i, '');
            echo html::input("{$itemKey}[]", $customedSubLang, "class='form-control shadow-primary-hover' $placeholder");
            ?>
            <?php endif;?>
            <?php endforeach;?>
            <?php else:?>
            <?php echo html::input($itemKey, $customedLang, "class='form-control shadow-primary-hover' placeholder='{$originalLang}'");?>
            <?php endif;?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class="bottom-btn">
      <?php echo html::submitButton(); ?>
      <?php echo html::a(inlink('resetLang', "type={$type}&module={$moduleName}&method={$method}&language={$language}"), $lang->restore, 'hiddenwin', "id='reset' class='btn btn-wide reset-btn'");?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
