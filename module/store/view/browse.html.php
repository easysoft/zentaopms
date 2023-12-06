<?php
/**
 * The browse view file of store module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   store
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <form id="appSearchForm" method="post" class="cell not-watch load-indicator">
      <div class="input-group">
        <div class="input-control search-box has-icon-left has-icon-right search-example" id="searchboxExample">
          <?php echo html::input('keyword', $keyword, "type='search' placeholder='{$lang->store->searchApp}' autocomplete='off' class='form-control search-input text-left'");?>
        </div>
        <span class="input-group-btn">
          <?php echo html::submitButton('<i class="icon icon-search"></i>', 'type="submit"', 'btn btn-secondary');?>
        </span>
      </div>
      <h5 class="text-left"><?php echo $lang->store->appType;?></h5>
      <div>
        <?php echo html::checkbox('categories', $categories, $postCategories);?>
      </div>
    </form>
  </div>
  <div class='main-cell' id='appContainer'>
    <div>
      <div class="btn-toolbar">
        <?php foreach($lang->store->sortTypes as $sortCode => $sortLabel):?>
        <?php $active = $sortType == $sortCode ? 'btn-active-text' : '';?>
        <?php $label = "<span class='text'>$sortLabel</span>";?>
        <?php echo html::a(inlink('browse', "sortType=$sortCode"), $label, '', "class='sort $active'");?>
        <?php endforeach;?>
      </div>
    </div>
    <?php if(empty($cloudApps)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->store->empty;?></span></p>
    </div>
    <?php else:?>
    <div class="row">
      <?php foreach ($cloudApps as $cloudApp):?>
      <div class='col-xs-4 col-sm-4 col-md-4 col-lg-3' data-id='<?php echo $cloudApp->id;?>'>
        <div class='panel card'>
          <a href="<?php echo $this->createLink('store', 'appview', "id=$cloudApp->id");?>">
            <div class='panel-heading text-center'>
              <div class="app-name"><?php echo $cloudApp->alias;?>&nbsp;</div>
            </div>
            <div class='panel-body'>
              <div class="app-detail">
                <div class='app-logo'>
                  <?php echo html::image($cloudApp->logo ? $cloudApp->logo : '', "referrer='origin'");?>
                </div>
                <p class="app-desc"><?php echo $cloudApp->introduction;?>&nbsp;</p>
              </div>
            </div>
            <div class='panel-footer app-footer'>
              <div class="pull-left"><?php echo $cloudApp->app_version;?></div>
              <div class="pull-right"><?php echo $cloudApp->author;?></div>
            </div>
          </a>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <div class='table-footer pagination' id='pagination'><?php $pager->show('right', 'pagerjs', 4800, 12);?></div>
    <?php endif;?>
  <div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
