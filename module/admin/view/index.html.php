<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('hasInternet', $zentaoData->hasData);?>
<?php js::set('isIntranet',  $isIntranet);?>
<div id='mainContent' class='main-content admin'>
  <div class="main <?php if(!$zentaoData->hasData) echo 'without-internet';?>">
    <div class="settings panel">
      <div class="panel-title mt-6"><?php echo $lang->admin->setting?></div>
      <div class="settings-list <?php if($config->vision == 'lite') echo 'lite-setting';?>">
        <?php foreach($lang->admin->menuList as $menuKey => $menu):?>
        <?php if($config->vision == 'lite' and !in_array($menuKey, $config->admin->liteMenuList)) continue;?>
        <div class="setting-box" <?php if($menu['disabled']) echo "title={$lang->admin->noPriv}";?> data-id="<?php echo $menuKey;?>">
          <button class="btn shadow-primary-hover" <?php if($menu['disabled']) echo 'disabled';?>  data-link='<?php echo $menu['link'];?>'>
            <h4 class="flex align-center w-full">
              <div class="flex align-center">
                <img src="static/svg/admin-<?php echo $menuKey;?>.svg"/>
                <?php echo $menu['name'];?>
              </div>
              <?php echo html::a($config->admin->helpURL[$menuKey], "<i title='{$lang->help}' class='icon icon-help'></i> ", '_blank', 'class="text-muted setting-help" onclick="window.event.cancelBubble=true;"');?>
            </h4>
            <p class="text-muted setting-desc" title="<?php echo $menu['desc'];?>"><?php echo $menu['desc'];?></p>
          </button>
        </div>
        <?php endforeach;?>
      </div>
    </div>

    <?php if($zentaoData->plugins):?>
    <div class="plug panel">
      <div class="panel-title">
        <?php echo $lang->admin->pluginRecommendation;?>
        <?php echo html::a($config->admin->extensionURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <div class="plugin-list" <?php if($langNotCN) echo 'style="flex-wrap: nowrap"';?>>
        <?php foreach($zentaoData->plugins as $plugin):?>
        <?php $pluginDesc = preg_replace('/[[:cntrl:]]/mu', '', strip_tags($plugin->abstract));?>
        <div class="plugin-item shadow-primary-hover" onclick="window.open('<?php echo $plugin->viewLink;?>')">
          <span class='ext-download'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></span>
          <h4 class="plug-title" title="<?php echo $plugin->name;?>"><?php echo $plugin->name;?></h4>
          <p class='extension-desc text-muted' title="<?php echo $pluginDesc;?>"><?php echo $pluginDesc;?></p>
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
                <?php if(!$zentaoData->hasData):?>
                <i class="icon follow-us icon-arrow-right text-primary"></i>
                <?php endif;?>
              </div>
              <div class="content"> <?php echo $lang->admin->followUsContent?></div>
            </div>
            </div>
            <?php if(!$bind and !$ignore and $zentaoData->hasData and common::hasPriv('admin', 'register')):?>
              <div class="panel-link"> <?php echo sprintf($lang->admin->notice->register, html::a(inlink('register'), $lang->admin->registerNotice->submitHere, '', 'class="text-primary"'));?></div>
            <?php endif;?>
        </div>
      <?php if($zentaoData->classes):?>
      <div class="panel publicClass">
        <div class="panel-title">
          <?php echo $lang->admin->publicClass;?>
          <?php echo html::a($config->admin->classURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted flex align-center"');?>
        </div>
        <div class="classList flex">
          <?php foreach($zentaoData->classes as $class):?>
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

  <?php if($zentaoData->hasData and !$langNotCN):?>
  <div class="side panel" style="background: #FCFDFE">
    <div class="h-56 flex align-center justify-between">
      <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
      <div class="time-count">
        <?php echo $lang->admin->zentaoUsed;?>
        <?php if($dateUsed->year):?>
        <span class="time-block"><?php echo $dateUsed->year;?></span><?php echo $lang->year;?>
        <?php endif;?>
        <?php if($dateUsed->month):?>
        <span class="time-block"><?php echo $dateUsed->month;?></span><?php echo $lang->admin->mon;?>
        <?php endif;?>
        <span class="time-block"><?php echo $dateUsed->day;?></span><?php echo $lang->admin->day;?>
      </div>
    </div>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updateDynamics?></div>
        <?php echo html::a($config->admin->downloadURL, "{$lang->more} <i class='icon icon-caret-right pb-3'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php foreach($zentaoData->dynamics as $dynamic):?>
      <div class="dynamic-block">
        <div class="dynamic-content" title=<?php echo $dynamic->title ?>><i class="icon icon-horn text-primary pr-4 font-20"></i><?php echo html::a($dynamic->link, $dynamic->title, '_blank');?></div>
        <div class="dynamic-time"><?php echo substr($dynamic->addedDate, 0, 10);?></div>
      </div>
      <?php endforeach;?>
    </div>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updatePatch?></div>
        <?php echo html::a($config->admin->patchURL, "{$lang->more} <i class='icon icon-caret-right pb-3'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php foreach($zentaoData->patches as $patch):?>
      <div class="patch-block">
	    <div class="title flex justify-between">
	      <div class="panel-title"><?php echo $patch->name?></div>
          <a href="<?php echo $patch->viewLink;?>" class='ext-download flex align-center' target='_blank'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></a>
        </div>
	    <div class="patch-content"><?php echo $patch->desc?></div>
      </div>
      <?php endforeach;?>
    </div>
    <?php if(!in_array($config->edition, array('max', 'ipd'))):?>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->upgradeRecommend?></div>
      </div>
      <?php if($config->edition != 'biz'):?>
      <div class="upgrade-block">
        <a class="title text-primary flex justify-between" href="<?php echo $config->admin->apiRoot;?>" target="_blank">
          <div class="panel-title"><i class="icon icon-zentao text-primary pr-4 font-18"></i><?php echo $lang->admin->bizTag?></div>
          <div class="flex align-center"><div><?php echo $lang->admin->productDetail?></div><i class="icon icon-caret-right text-primary pb-3"></i></div>
        </a>
        <?php foreach($lang->admin->productFeature['biz'] as $feature):?>
        <div class="upgrade-content color-gray"><?php echo $feature;?></div>
        <?php endforeach;?>
      </div>
      <?php endif;?>
      <div class="upgrade-block">
        <a class="title text-primary flex justify-between" href='<?php echo $config->admin->apiRoot;?>' target="_blank">
          <div class="panel-title"><i class="icon icon-zentao text-primary pr-4 font-18"></i><?php echo $lang->admin->maxTag?></div>
          <div class="flex align-center"><div><?php echo $lang->admin->productDetail?></div><i class="icon icon-caret-right text-primary pb-3"></i></div>
        </a>
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
