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
<?php js::set('arrTypesWithMenu', $config->dev->arrTypesWithMenu); ?>
<?php include 'header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($featureBar as $key => $label):?>
    <?php $active = $type == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('langItem', "type=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div class="flex gap-15">
<?php if(in_array($type, $config->dev->arrTypesWithMenu , false)):?>
  <div class="menu-tree">
    <div class="input-control search-box has-icon-left has-icon-right search-example">
      <input id="searchInputTree" type="search" class="form-control search-input empty">
      <label class="input-control-icon-left search-icon flex align-center justify-center"><i class="icon icon-search"></i></label>
    </div>
    <div id="menuTree" ></div>
  </div>
<?php endif;?>
  <form class='main-form form-ajax flex-1' method='post'>
    <div id='mainContent' class='main-content flex'>
      <div class="side-left">
        <div class="title">默认值</div>
        <div class="label-list">
          <?php foreach($originalLangs as $langKey => $originalLang):?>
          <div labelId="<?php echo "{$moduleName}_{$langKey}"?>" class="input-label h-32 my-12"><?php echo $originalLang?></div>
          <?php endforeach;?>
        </div>
      </div>
      <div class="side-right">
        <div class="title">修改值</div>
        <div class="input-list">
          <?php foreach($originalLangs as $langKey => $originalLang):?>
          <div class="input-control h-32 my-12">
            <?php
            $disabled = '';
            if($type == 'common' and ($langKey == 'URCommon' or $langKey == 'SRCommon')) $disabled = 'disabled';
            ?>
            <?php echo html::input("{$moduleName}_{$langKey}", zget($customedLangs, $langKey, ''), "class='form-control shadow-primary-hover {$disabled}' {$disabled} placeholder='{$originalLang}'");?>
            <i iconId="<?php echo "{$moduleName}_{$langKey}"?>" class="icon icon-angle-right text-primary hidden"></i>
          </div>
          <?php endforeach;?>
        </div>
        </div>
        <div class="side-main"></div>
      </div>
      <div class="bottom-btn">
        <?php echo html::submitButton(); ?>
        <button id="reset" class="btn btn-wide ml-20"><?php echo $lang->restore?> </button>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
