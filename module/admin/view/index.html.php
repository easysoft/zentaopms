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
    <div class="plug panel">
      <div class="panel-title"><?php echo $lang->admin->pluginRecommendation?></div>
      <div class="plugin-list">
        <div class="plugin-item">1</div>
        <div class="plugin-item">2</div>
        <div class="plugin-item">3</div>
        <div class="plugin-item">4</div>
        <div class="plugin-item">5</div>
        <div class="plugin-item">6</div>
      </div>
    </div>
    <div class="flex bottom">
      <div class="panel official">
        <div class="panel-title"><?php echo $lang->admin->officialAccount?></div>
	<div class="flex main-panel">
	  <div class="official-img"></div>
	  <div class="official-content">
          </div>
        </div>
      </div>
      <div class="panel openClass">
        <div class="panel-title"><?php echo $lang->admin->openClass?></div>
      </div>
    </div>
  </div>
  <div class="side panel">
    <div class="panel-title"><?php echo $lang->admin->zentaoInfo?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
