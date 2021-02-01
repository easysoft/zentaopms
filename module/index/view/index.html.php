<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
$this->app->loadConfig('sso');
if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);

js::set('navGroup', $lang->navGroup);
js::set('tabsLang', $lang->index->tab);
js::set('menuItems', commonModel::getMainNavList($app->rawModule));
js::set('defaultOpen', $open);
?>
<?php if(isset($this->config->bizVersion)):?>
<style>#searchbox .dropdown-menu.show-quick-go.with-active {top: -468px; max-height: 465px;}</style>
<?php endif;?>
<div id='menu'>
  <nav id='menuNav' data-group='<?php echo $app->rawModule; ?>'>
    <ul class='nav nav-default' id='menuMainNav'>
    </ul>
    <ul class='nav nav-default'>
      <?php commonModel::printRecentMenu();?>
    </ul>
  </nav>
  <div class="table-col col-right">
    <div id="moreExecution" class="more-execution-show" data-ride="searchList">
      <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input empty">
        <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
        <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
      </div>
      <div class="list-group" id="executionList"></div>
    </div>
  </div>
  <div id='menuFooter'>
    <ul id="userNav" class="nav">
      <li id='menuToggleMenu'><a type='button' class='menu-toggle'><i class='icon icon-sm icon-menu-collapse'></i></a></li>
      <li class='dropdown dropdown-hover has-avatar'><?php common::printUserBar();?></li>
    </ul>
  </div>
</div>
<div id='pages'>
</div>
<div id='pagesBar'>
  <ul id='bars' class='nav nav-default'></ul>
  <div id='poweredBy'>
    <div id="globalBarLogo">
      <a href='javascript:void(0)' id='proLink' class='btn btn-link' style='color: red;'><?php echo $lang->index->upgrade;?> <i class='text-danger icon-pro-version'></i></a>
      <a href='<?php echo $lang->website;?>' class="btn btn-sm btn-link" target='_blank' title='<?php echo $config->version;?>'><i class="icon icon-zentao" style="font-size: 24px;"></i></a>
      <!--<a href='javascript:void(0)' class="btn btn-sm btn-link" type="button"><i class="icon icon-message"></i></a>-->
      <div id="globalSearchDiv">
        <div class="input-group">
          <div id='searchbox'>
            <?php echo common::printSearchBox();?>
          </div>
          <div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example" id="searchboxExample">
            <input id="globalSearchInput" type="search" onclick="this.value=''" onkeydown="if(event.keyCode==13) $.gotoObject();" class="form-control search-input" placeholder="<?php echo $lang->index->search;?>" autocomplete="off">
          </div>
          <span class="input-group-btn" onclick="javascript:$.gotoObject();">
            <button id="globalSearchButton" class="btn btn-secondary" type="button"><i class="icon icon-search"></i></button>
          </span>
        </div>
      </div>
    </div>
    <div id='upgradeContent' class='main-table'>
      <div class='main-header' style='padding: 5px 20px 5px 15px;'>
        <h2>
          <?php echo $lang->index->upgradeVersion;?>
          <span class="label label-badge label-primary label-outline"><?php echo $lang->index->currentVersion . ': ' . $lang->zentaoPMS . $config->version;?></span>
        </h2>
      </div>
      <table class='table has-sort-head'>
        <thead>
          <tr>
            <th class='version-name'><?php echo $lang->index->versionName;?></th>
            <th class='version-date'><?php echo $lang->index->releaseDate;?></th>
            <th class='version-explain'><?php echo $lang->index->explain;?></th>
            <th class='version-actions text-center'><?php echo $lang->index->actions;?></th>
          </tr>
        </thead>
      </table>
      <div id="latestVersionList">
        <?php if(empty($latestVersionList)):?>
        <div class="table-empty-tip" style='padding: 66px 10px;'>
          <h6>
            <?php echo $lang->noData;?>
            <span class="label label-badge label-info label-outline"><?php echo $lang->index->website . ': '. $lang->website;?></span>
          </h6>
        </div>
        <?php else:?>
        <table class='table has-sort-head'>
          <tbody>
            <?php foreach($latestVersionList as $version):?>
            <tr>
              <td class='version-name' title='<?php echo $lang->zentaoPMS . $version->name;?>'><?php echo $lang->zentaoPMS . $version->name;?></td>
              <td class='version-date'><?php echo $version->date;?></td>
              <td class='version-explain' title='<?php echo $version->explain;?>'><?php echo $version->explain;?></td>
              <td class='version-actions text-center'>
                <a href="<?php echo $version->link;?>" class='btn btn-link' target='_blank' style='color: #16a8f8;'><?php echo $lang->index->upgrade;?></a>
                <a href="<?php echo inLink('changeLog', 'version=' . $version->name);?>" class="btn btn-link iframe" data-width="800"><?php echo $lang->index->log;?></strong></a>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php js::set('searchAB', $lang->searchAB);?>
<?php js::set('searchObjectList', implode(',', array_keys($lang->searchObjects)));?>
<?php include '../../common/view/footer.lite.html.php';?>
