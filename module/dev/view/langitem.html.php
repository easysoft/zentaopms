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
<form class='main-form form-ajax' method='post'>
  <div id='mainContent' class='main-content flex'>
    <div class="side-left">
      <div class="title"><?php echo $lang->dev->default;?></div>
      <div class="label-list">
        <?php foreach($originalLangs as $langKey => $originalLang):?>
        <div labelId="<?php echo "{$moduleName}_{$langKey}"?>" class="input-label h-32 my-12"><?php echo $originalLang?></div>
        <?php endforeach;?>
      </div>
    </div>
    <div class="side-right">
      <div class="title"><?php echo $lang->dev->change?></div>
      <div class="input-list">
        <?php foreach($originalLangs as $langKey => $originalLang):?>
        <div class="input-control h-32 my-12">
          <?php echo html::input("{$moduleName}_{$langKey}", zget($customedLangs, $langKey, ''), "class='form-control shadow-primary-hover' placeholder='{$originalLang}'");?>
          <i iconId="<?php echo "{$moduleName}_{$langKey}"?>" class="icon icon-angle-right text-primary hidden"></i>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class="side-main"></div>
  </div>
  <div class="bottom-btn">
    <?php echo html::submitButton(); ?>
    <?php echo html::a(inlink('resetLang', "type={$type}&module={$moduleName}&language={$language}"), $lang->restore, 'hiddenwin', "id='reset' class='btn btn-wide ml-20'");?>
  </div>
</form>
<?php include '../../common/view/footer.html.php';?>
