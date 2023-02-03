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
          <p class="text-muted setting-desc"><?php echo $menu['desc'];?></p>
          <?php echo html::a($config->admin->helpURL[$menuKey], "<i class='icon icon-help'></i> {$lang->help}", '_blank', 'class="text-muted setting-help"');?>
        </button>
        <?php endforeach;?>
      </div>
    </div>

    <?php if($extensions):?>
    <div class="plug panel">
      <div class="panel-title">
        <?php echo $lang->admin->pluginRecommendation;?>
        <?php echo html::a($config->admin->extensionURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted"');?>
      </div>
      <div class="plugin-list" <?php if($langNotCN) echo 'style="flex-wrap: nowrap"';?>>
        <?php foreach($extensions as $extension):?>
        <div class="plugin-item shadow-primary-hover">
          <a href="<?php echo $extension->viewLink;?>" class='ext-download' target='_blank'><i class='icon icon-download-alt text-primary bg-primary-100 pd-3'></i></a>
          <h4 class="plug-title"><?php echo $extension->name;?></h4>
          <p class='extension-desc'><?php echo strip_tags($extension->desc);?></p>
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
            <div class="title"><?php echo $lang->admin->followUs?><i class="icon follow-us icon-arrow-right text-primary"></i></div>
            <div class="content"> <?php echo $lang->admin->followUsContent?></div>
          </div>
        </div>
      </div>
      <?php if($publicClass):?>
      <div class="panel publicClass">
        <div class="panel-title">
          <?php echo $lang->admin->publicClass;?>
          <?php echo html::a($config->admin->classURL, "{$lang->more} <i class='icon icon-caret-right'></i>", '_blank', 'class="more text-muted"');?>
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
  <div class="side panel hidden">
    <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
  </div>
  <?php endif;?>

</div>
<?php include '../../common/view/footer.html.php';?>
