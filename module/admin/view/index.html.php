<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('hasInternet', $hasInternet);?>
<div id='mainContent' class='main-content admin'>
  <div class="main <?php if(!$hasInternet) echo 'without-internet';?>">
    <div class="settings panel">
      <div class="panel-title"><?php echo $lang->admin->setting?></div>
      <div class="settings-list <?php if($config->vision == 'lite') echo 'lite-setting';?>">
        <?php foreach($lang->admin->menuList as $menuKey => $menu):?>
        <?php if($config->vision == 'lite' and !in_array($menuKey, $config->admin->liteMenuList)) continue;?>
	<button class="setting-box btn shadow-primary-hover" <?php if($menu['disabled']) echo 'disabled';?> data-link='<?php echo $menu['link'];?>'>
	  <h4><img src="/static/svg/admin-<?php echo $menuKey;?>.svg"/><?php echo $menu['name'];?></h4>
          <p class="text-muted setting-desc" title="<?php echo $menu['desc'];?>"><?php echo $menu['desc'];?></p>
          <?php echo html::a($config->admin->helpURL[$menuKey], "<i class='icon icon-help'></i> {$lang->help}", '_blank', 'class="text-muted setting-help"');?>
        </button>
        <?php endforeach;?>
      </div>
    </div>

    <?php if($plugins):?>
    <div class="plug panel">
      <div class="panel-title">
        <?php echo $lang->admin->pluginRecommendation;?>
        <?php echo html::a($config->admin->extensionURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <div class="plugin-list" <?php if($langNotCN) echo 'style="flex-wrap: nowrap"';?>>
        <?php foreach($plugins as $plugin):?>
        <?php $pluginDesc = preg_replace('/[[:cntrl:]]/mu', '', strip_tags($plugin->desc));?>
        <div class="plugin-item shadow-primary-hover" data-link='<?php echo $plugin->viewLink;?>'>
          <a href="<?php echo $plugin->viewLink;?>" class='ext-download' target='_blank'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></a>
          <h4 class="plug-title" title="<?php echo $plugin->name;?>"><?php echo $plugin->name;?></h4>
          <p class='extension-desc' title="<?php echo $pluginDesc;?>"><?php echo $pluginDesc;?></p>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>

    <?php if(!$langNotCN):?>
      <div class="flex bottom">
        <div class="panel official">
          <div class="panel-title"><?php echo $lang->admin->officialAccount?></div>
	  <div class="flex main-panel">
	    <div class="official-img"></div>
	    <div class="official-content">
            <div class="title">
              <?php echo $lang->admin->followUs?>
              <?php if(!$hasInternet):?>
              <i class="icon follow-us icon-arrow-right text-primary"></i>
              <?php endif;?>
              </div>
            <div class="content"> <?php echo $lang->admin->followUsContent?></div>
          </div>
        </div>
      </div>
      <?php if($publicClass):?>
      <div class="panel publicClass">
        <div class="panel-title">
          <?php echo $lang->admin->publicClass;?>
          <?php echo html::a($config->admin->classURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
        </div>
        <div class="classList flex">
          <?php foreach($publicClass as $class):?>
          <a class="classItem shadow-primary-hover" href='<?php echo $class->viewLink;?>' target='_blank'>
	    <div class="imgBack">
              <div class="classImg" style="background-image: url('<?php echo $class->image;?>');"></div>
            </div>
            <div class="classContent"><?php echo $class->name;?></div>
          </a>
          <?php endforeach;?>
        </div>
      </div>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>

  <?php if($hasInternet and !$langNotCN):?>
  <div class="side panel">
    <div class="h-56 flex align-center justify-between">
      <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
      <div class="time-count color-gray">
        <?php echo $lang->admin->usedTime;?>
        <?php if($usedTime['year'])  echo "<span class='time-block'>{$usedTime['year']}</span>{$lang->year}";?>
        <?php if($usedTime['month']) echo "<span class='time-block'>{$usedTime['month']}</span>{$lang->admin->mon}";?>
        <?php if($usedTime['day'])   echo "<span class='time-block'>{$usedTime['day']}</span>{$lang->admin->day}";?>
      </div>
    </div>
    <div class="border-gray mb-16">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updateDynamics?></div>
        <?php echo html::a($config->admin->dynamicURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php foreach($dynamics as $dynamic):?>
      <div class="dynamic-block">
        <div class="dynamic-content"><i class="icon icon-horn text-primary"></i><?php echo html::a($dynamic->link, $dynamic->title, '_blank');?></div>
        <div class="dynamic-time"><?php echo substr($dynamic->addedDate, 0, 10);?></div>
      </div>
      <?php endforeach;?>
    </div>
    <div class="border-gray mb-16">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updatePatch?></div>
        <?php echo html::a($config->admin->extensionURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php foreach($patches as $patch):?>
      <div class="patch-block">
	<div class="title flex justify-between">
	  <div class="panel-title"><?php echo $patch->name?></div>
          <a href="<?php echo $patch->viewLink;?>" class='ext-download flex align-center' target='_blank'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></a>
        </div>
	<div class="patch-content color-gray">
           <?php echo $patch->desc?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php if($config->edition != 'max'):?>
    <div class="border-gray mb-16">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->upgradeRecommend?></div>
        <?php echo html::a($config->admin->apiRoot, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php if($config->edition != 'biz'):?>
      <div class="upgrade-block">
      	<div class="title text-primary flex justify-between">
          <div><?php echo $lang->admin->bizTag?></div>
          <div class="flex align-center"><div><?php echo html::a($lang->admin->bizInfoURL, $lang->admin->productDetail, '_blank', "class='text-primary'");?></div><i class="icon icon-caret-right text-primary"></i></div>
        </div>
        <?php foreach($lang->admin->productFeature['biz'] as $feature):?>
        <div class="upgrade-content color-gray"><?php echo $feature;?></div>
        <?php endforeach;?>
      </div>
      <?php endif;?>
      <div class="upgrade-block">
    	<div class="title text-primary flex justify-between">
          <div><?php echo $lang->admin->maxTag?></div>
          <div class="flex align-center"><div><?php echo html::a($lang->admin->maxInfoURL, $lang->admin->productDetail, '_blank', "class='text-primary'");?></div><i class="icon icon-caret-right text-primary"></i></div>
        </div>
        <?php foreach($lang->admin->productFeature['max'] as $feature):?>
        <div class="upgrade-content color-gray"><?php echo $feature;?></div>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
