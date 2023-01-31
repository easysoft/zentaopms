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
  <div class="main">
    <div class="settings panel">
      <div class="panel-title"><?php echo $lang->admin->setting?></div>
      <div class="settings-list">
        <div class="setting-box">1</div>
        <div class="setting-box">2</div>
        <div class="setting-box">3</div>
        <div class="setting-box">4</div>
        <div class="setting-box">5</div>
        <div class="setting-box">6</div>
        <div class="setting-box">7</div>
        <div class="setting-box">8</div>
        <div class="setting-box">9</div>
        <div class="setting-box">10</div>
      </div>
    </div>

    <?php if($extensions):?>
    <div class="plug panel">
      <div class="panel-title"><?php echo $lang->admin->pluginRecommendation?></div>
      <div class="plugin-list" <?php if(strpos($clientLang, 'zh') === false) echo 'style="flex-wrap: nowrap"';?>>
        <?php foreach($extensions as $extension):?>
        <div class="plugin-item">
          <a href="<?php echo $extension->viewLink;?>" class='ext-download' target='_blank'><i class='icon icon-import'></i></a>
          <h4 class="plug-title"><?php echo $extension->name;?></h4>
          <p class='extension-desc'><?php echo strip_tags($extension->desc);?></p>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>

    <?php if(strpos($clientLang, 'zh') === 0):?>
    <div class="flex bottom">
      <div class="panel official">
        <div class="panel-title"><?php echo $lang->admin->officialAccount?></div>
	    <div class="flex main-panel">
	      <div class="official-img"></div>
	      <div class="official-content">
              <div class="title"><?php echo $lang->admin->followUs?></div>
              <div class="content"> <?php echo $lang->admin->followUsContent?></div>
          </div>
        </div>
      </div>
      <?php if($publicClass):?>
      <div class="panel publicClass">
        <div class="panel-title"><?php echo $lang->admin->publicClass?></div>
        <div class="classList flex">
          <?php foreach($publicClass as $class):?>
            <div class="classItem flex">
            <div class="classImg" style="background-image: url('<?php echo $class->image;?>');"></div>
            <div class="classContent"><?php echo $class->name;?></div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
  <?php if($hasInternet and strpos($clientLang, 'zh') === 0):?>
  <div class="side panel">
    <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
