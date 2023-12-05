<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
$this->app->loadConfig('sso');
if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);

js::set('vision',        $config->vision);
js::set('navGroup',      $lang->navGroup);
js::set('appsLang',      $lang->index->dock);
js::set('appsMenuItems', commonModel::getMainNavList($app->rawModule));
js::set('defaultOpen',   (isset($open) and !empty($open)) ? $open : '');
js::set('manualText',    $lang->manual);
js::set('manualUrl',     ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme']);
js::set('isAdminUser',   $this->app->user->admin);
js::set('isIntranet',    helper::isIntranet());
?>
<style>
#versionTitle {margin: 8px 3px 0px 0px; background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/version-upgrade.svg';?>);}
.icon-version {width: 20px; height: 24px; margin: -4px 3px 0px 0px; background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/version-new.svg';?>);}
.icon-version:before {content:"";}
.version-hr {margin-top: 15px; margin-bottom: 15px;}

<?php if(empty($latestVersionList)):?>
#upgradeContent {top: -272px; height: 262px;}
#latestVersionList {height: 200px;}
<?php endif;?>

<?php if(commonModel::isTutorialMode()):?>
#menuMoreNav > li.dropdown:hover + .tooltip {display: none!important;}
#menuMoreList > li.active {position: relative;}
#menuMoreList > li.active:before {content: ' '; display: block; position: absolute; left: 100%; border-width: 5px 5px 5px 0; border-style: solid; border-color: transparent; border-right-color: #ff9800; width: 0; height: 0; top: 12px}
#menuMoreList > li.active:after {content: attr(data-tip); display: block; position: absolute; left: 100%; background-color: #f1a325; color: #fff; top: 3px; white-space: nowrap; line-height: 16px; padding: 8px 10px; margin-left: 5px; border-radius: 4px;}
<?php endif;?>

<?php if($config->vision == 'lite' or $config->vision == 'or'):?>
#searchbox .dropdown-menu.show-quick-go.with-active {min-height: 180px;}
<?php endif;?>
</style>
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') === false):?>
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
        <a class='menu-toggle' data-collapse-text='<?php echo $lang->collapseMenu; ?>' data-unfold-text='<?php echo $lang->unfoldMenu; ?>'>
          <i class='icon icon-sm icon-menu-collapse'></i>
        </a>
      </li>
    </ul>
  </div>
</div>
<?php endif;?>
<div id='apps'>
</div>
<div id='appsBar'>
  <ul id='bars' class='nav nav-default'></ul>
  <div id='poweredBy'>
    <div id="globalBarLogo">
      <?php if(trim($config->visions, ',') == 'lite'):?>
      <?php $version     = $config->liteVersion;?>
      <?php $versionName = $lang->liteName . $config->liteVersion;?>
      <?php else:?>
      <?php $version     = $config->version;?>
      <?php $versionName = $lang->pmsName . $config->version;?>
      <?php if($config->systemMode != 'PLM'):?>
      <a href='javascript:void(0)' id='bizLink' class='btn btn-link' style='color: #B57D4F;'><span class='upgrade'><?php echo $lang->bizName;?></span> <i class='text-danger icon-pro-version'></i></a>
      <?php endif;?>
      <?php endif;?>
      <a href='<?php echo $lang->website;?>' class="btn btn-sm btn-link" target='_blank' title='<?php echo $version;?>'>
        <i class="icon icon-zentao" style="font-size: 24px;"></i>
        <span class='version'><?php echo $versionName;?></span>
      </a>
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
          <?php $lastVersionList = (array)$latestVersionList;?>
          <?php $lastVersion     = end($lastVersionList);?>
          <?php foreach($latestVersionList as $versionNumber => $version):?>
          <div class="version-list">
            <div>
              <i class='version-upgrade icon-version'></i>
              <h4><?php echo $version['name'];?></h4>
            </div>
            <div class="version-detail"><?php echo $version['explain'];?></div>
            <div class="version-footer">
              <a href="<?php echo inLink('changeLog', 'version=' . $versionNumber);?>" class="btn btn-link iframe" data-width="800"><?php echo $lang->index->log;?></strong></a>
              <a href='<?php echo $version['link']?>' class='btn btn-primary upgrade-now' style='color: white;' target='_blank'><?php echo $lang->index->upgradeNow;?></a>
            </div>
          </div>
          <?php if($version['name'] != $lastVersion['name']):?>
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
