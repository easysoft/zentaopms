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
      <div class="panel-title mt-6"><?php echo $lang->admin->setting?></div>
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
  <div class="side panel" style="background: #FCFDFE">
    <div class="h-56 flex align-center justify-between">
      <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
      <div class="time-count">您已使用禅道<span class="time-block">5</span><?php echo $lang->year?><span class="time-block">9</span><?php echo $lang->admin->mon?><span class="time-block">19</span><?php echo $lang->admin->day?></div>
    </div>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updateDynamics?></div>
        <?php echo html::a($config->admin->downloadURL, "{$lang->more} <i class='icon icon-caret-right pb-3'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <div class="dynamic-block">
        <div class="dynamic-content"><i class="icon icon-horn text-primary pr-4 font-20"></i>禅道18.0发布,新增自动化测试方案、使用帮助及全新IDE风格</div>
        <div class="dynamic-time">2022-03-12</div>
      </div>
      <div class="dynamic-block">
        <div class="dynamic-content"><i class="icon icon-horn text-primary pr-4 font-20"></i>禅道18.0发布,新增自动化测试方案、使用帮助及全新IDE风格</div>
        <div class="dynamic-time">2022-03-12</div>
      </div>
      <div class="dynamic-block">
        <div class="dynamic-content"><i class="icon icon-horn text-primary pr-4 font-20"></i>禅道18.0发布,新增自动化测试方案、使用帮助及全新IDE风格</div>
        <div class="dynamic-time">2022-03-12</div>
      </div>
    </div>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->updatePatch?></div>
        <?php echo html::a($config->admin->patchURL, "{$lang->more} <i class='icon icon-caret-right pb-3'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <?php foreach($this->view->patches as $patch):?>
      <div class="patch-block">
	<div class="title flex justify-between">
	  <div class="panel-title"><?php echo $patch->name?></div>
          <a href="<?php echo $patch->viewLink;?>" class='ext-download flex align-center' target='_blank'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></a>
        </div>
	<div class="patch-content">
           <?php echo $patch->desc?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <div class="border-gray mb-16 radius-4">
      <div class="h-40 pl-16 flex align-center justify-between">
        <div class="panel-title"><?php echo $lang->admin->upgradeRecommend?></div>
        <?php echo html::a($config->admin->downloadURL, "{$lang->more} <i class='icon icon-caret-right pb-3'></i>", '_blank', 'class="more text-muted flex align-center"');?>
      </div>
      <div class="upgrade-block">
        <a class="title text-primary flex justify-between" href="<?php echo $config->admin->downloadURL;?>" target="_blank">
          <div class="panel-title"><i class="icon icon-zentao text-primary pr-4 font-18"></i>禅道企业版</div>
          <div class="flex align-center"><div>查看详情</div><i class="icon icon-caret-right text-primary pb-3"></i></div>
        </a>
        <div class="upgrade-content">工时管理、甘特图、导入导出</div>
        <div class="upgrade-content">40+内置统计报表、自定义报表功能</div>
        <div class="upgrade-content">强大的自定义工作流、反馈管理功能</div>
      </div>
      <div class="upgrade-block">
        <a class="title text-primary flex justify-between" href='<?php echo $config->admin->downloadURL;?>' target="_blank">
          <div class="panel-title"><i class="icon icon-zentao text-primary pr-4 font-18"></i>禅道旗舰版</div>
          <div class="flex align-center"><div>查看详情</div><i class="icon icon-caret-right text-primary pb-3"></i></div>
        </a>
        <div class="upgrade-content">工时管理、甘特图、导入导出</div>
        <div class="upgrade-content">40+内置统计报表、自定义报表功能</div>
        <div class="upgrade-content">强大的自定义工作流、反馈管理功能</div>
        <div class="upgrade-content">工时管理、甘特图、导入导出</div>
      </div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
