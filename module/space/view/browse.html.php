<?php
/**
 * The browse view file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     space
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList',  helper::arrayColumn($instances, 'id'));?>
<div id='mainMenu' class='clearfix'>
  <form id="spaceSearchForm" method="post" class="not-watch load-indicator">
    <div class="btn-toolbar pull-left">
      <?php foreach($lang->space->filterList as $type => $label):?>
      <?php $active = $browseType == $type ? 'btn-active-text' : '';?>
      <?php $label = "<span class='text'>$label</span>";?>
      <?php if($browseType == $type) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
      <?php echo html::a(inlink('browse', "spaceID=&browseType=$type"), $label, '', "class='btn btn-link $active'");?>
      <?php endforeach;?>
      <div class="input-control search-box has-icon-left has-icon-right search-example" id="searchboxExample">
        <?php echo html::input('search', $searchName, "type='search' placeholder='{$lang->space->searchInstance}' autocomplete='off' class='form-control search-input text-left'");?>
      </div>
      <span class="input-group-btn">
        <?php echo html::submitButton('<i class="icon icon-search"></i>', 'type="submit"', 'btn btn-secondary');?>
      </span>
    </div>
    <div class="btn-toolbar pull-right">
      <div class="btn-group">
      <?php $url= $this->inLink('browse', "spaceID={$currentSpace->id}"); ?>
      <?php echo html::a($url, "<i class='icon-list'></i>", '', "class='btn btn-icon switchButton " . ($spaceType != 'bylist' ? 'text-primary' : '') . "' title='{$lang->space->byList}' data-type='bylist'");?>
      <?php echo html::a($url, "<i class='icon-cards-view'></i>", '', "class='btn btn-icon switchButton " . ($spaceType == 'bylist' ? 'text-primary' : '') . "' title='{$lang->space->byCard}' data-type='bycard'");?>
      </div>
      <?php common::printLink('store', 'browse', '', '<i class="icon icon-plus"></i>' . $lang->space->install, '', 'class="btn btn-primary create-product-btn"');?>
    </div>
  </form>
</div>
<div id='mainContent' class='main-row'>
<?php
if($spaceType == 'bycard')
{
    include 'browsebycard.html.php';
}
else
{
    include 'browsebylist.html.php';
}
?>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
