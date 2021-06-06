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
js::set('appsLang', $lang->index->app);
js::set('appsMenuItems', commonModel::getMainNavList($app->rawModule));
js::set('defaultOpen', $open);
?>
<style>
#versionTitle {margin: 8px 3px 0px 0px; background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/version-upgrade.svg';?>);}
.icon-version {width: 20px; height: 24px; margin: -4px 3px 0px 0px; background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/version-new.svg';?>);}
.version-hr {margin-top: 15px; margin-bottom: 15px;}

<?php if(empty($latestVersionList)):?>
#upgradeContent {top: -272px; height: 262px;}
#latestVersionList {height: 200px;}
<?php endif;?>
</style>
<div id='menu'>
  <nav id='menuNav'>
    <ul class='nav nav-default' id='menuMainNav'>
    </ul>
    <ul class='nav nav-default' id='menuMoreNav'>
      <li class='divider'></li>
      <li class='dropdown dropdown-hover'>
        <a title='<?php echo $lang->more; ?>'><i class='icon icon-more-circle'></i><span class='text'><?php echo $lang->more; ?></span></a>
        <ul id='menuMoreList' class='dropdown-menu fade'></ul>
      </li>
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
    <ul id="flodNav" class="nav">
      <li id='menuToggleMenu'>
        <a type='button' class='menu-toggle'>
          <i class='icon icon-sm icon-menu-collapse'></i>
        </a>
      </li>
    </ul>
  </div>
</div>
<div id='apps'>
</div>
<div id='appsBar'>
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
            <input id="globalSearchInput" type="search" onclick="this.value=''" onkeydown="if(event.keyCode==13) $.gotoObject();" class="form-control search-input" placeholder="<?php echo $lang->index->pleaseInput;?>" autocomplete="off">
          </div>
          <span class="input-group-btn" onclick="javascript:$.gotoObject();">
            <button id="globalSearchButton" class="btn btn-secondary" type="button"><i class="icon icon-search"></i></button>
          </span>
        </div>
      </div>
    </div>
    <div id='upgradeContent' class='main-table'>
      <div class='main-header' style='padding: 5px 20px 5px 15px;'>
        <i class='version-upgrade' id='versionTitle'></i>
        <h2>
          <?php echo $lang->index->upgradeVersion;?>
        </h2>
      </div>
      <div id="latestVersionList">
        <?php if(empty($latestVersionList)):?>
        <div class="table-empty-tip">
          <a href='<?php echo $lang->website;?>' target='_blank'>
            <span class="label label-badge label-info label-outline"><?php echo $lang->index->website . ': '. $lang->website;?></span>
          </a>
        </div>
        <?php else:?>
        <div class='version-content'>
          <?php $lastVersion = end($latestVersionList);?>
          <?php foreach($latestVersionList as $versionNumber => $version):?>
          <div class="version-list">
            <div>
              <i class='version-upgrade icon-version'></i>
              <h4><?php echo $version->name;?></h4>
            </div>
            <div class="version-detail"><?php echo $version->explain;?></div>
            <div class="version-footer">
              <a href="<?php echo inLink('changeLog', 'version=' . $versionNumber);?>" class="btn btn-link iframe" data-width="800"><?php echo $lang->index->log;?></strong></a>
              <a href='<?php echo $version->link?>' class='btn btn-primary upgrade-now' style='color: white;' target='_blank'><?php echo $lang->index->upgradeNow;?></a>
            </div>
          </div>
          <?php if($version->name != $lastVersion->name):?>
          <hr class='version-hr'>
          <?php endif;?>
          <?php endforeach;?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php js::set('searchAB', $lang->searchAB);?>
<?php js::set('searchObjectList', ',' . implode(',', array_keys($lang->searchObjects)) . ',');?>
<?php js::set('searchCommon', $lang->index->search);?>

<script>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
