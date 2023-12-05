<?php
/**
 * The app view file of store module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   store
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('cloudApp', $cloudApp);?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('store', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col cell">
    <div id="appInfoHeader">
      <div class="pull-left app-logo">
          <?php echo html::image($cloudApp->logo ? $cloudApp->logo : '', "referrer='origin'");?>
          <h2 style="display: inline-block;"><?php echo $cloudApp->alias;?></h2>
      </div>
      <div class="btn-group dropdown pull-right">
        <?php echo html::a(helper::createLink('instance', 'install', "id={$cloudApp->id}", '', true), $lang->instance->install, '', "class='iframe btn btn-primary' title='{$lang->instance->install}' data-width='520' data-app='space'");?>
      </div>
      <div class="pull-right">
        <div class="dropdown">
          <button class="btn" type="button" data-toggle="dropdown"><i class='icon icon-info-sign'></i>&nbsp;<?php echo $lang->store->support;?><span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><?php echo html::a(zget($cloudApp, 'git_url', '#'), $lang->store->gitUrl, '_blank', "class='icon icon-github'");?></li>
            <li><?php echo html::a(zget($cloudApp, 'dockerfile_url', '#'), $lang->store->dockerfileUrl, '_blank', "class='icon icon-docker'");?></li>
            <li class='hidden'><?php echo html::a(zget($cloudApp, 'forum_url', '#'), $lang->store->forumUrl, '_blank', "class='icon icon-forum'");?></li>
            <li><?php echo html::a('https://www.qucheng.com/forum/usage.html', $lang->store->forumUrl, '_blank', "class='icon icon-forum'");?></li>
          </ul>
        </div>
      </div>
    </div>
    <hr/>
    <div id='appInfoBody'>
      <?php if(empty($cloudApp)):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->store->empty;?></span></p>
      </div>
      <?php else:?>
      <div class="row">
        <div style='padding-right: 10px;' class='col-lg-8'>
          <h3><?php echo $lang->store->appBasicInfo;?></h3>
          <p><?php echo $cloudApp->desc;?></p>
          <table style="border: solid 1px #ddd;" class="table table-data">
            <tbody>
              <tr>
                <th><?php echo $lang->store->appVersion;?>:</th>
                <td><?php echo $cloudApp->app_version;?></td>
                <th><?php echo $lang->store->author;?>:</th>
                <td><?php echo $cloudApp->author;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->store->releaseDate;?>:</th>
                <td><?php echo (new DateTime($cloudApp->publish_time))->format('Y-m-d');?></td>
                <th><?php echo $lang->store->appType;?>:</th>
                <td><?php echo trim(implode('/', helper::arrayColumn($cloudApp->categories, 'alias')), '/');?></td>
              </tr>
            </tbody>
          </table>
          <h3><?php echo $lang->store->screenshots;?></h3>
          <div class='row screenshotsContainer'>
            <?php if(empty(array_filter($cloudApp->screenshot_urls))):?>
            <div class='col-sm-12'><div class='errorBox'><?php echo $lang->store->noScreenshot;?></div></div>
            <?php else:?>
            <?php $imgUrlList = array_filter($cloudApp->screenshot_urls);?>
            <?php $cols = (count($imgUrlList) % 4) ? 4 : 3;?>
            <?php foreach($imgUrlList as $imgUrl):?>
            <div class='col-xs-6 col-sm-<?php echo $cols;?>'>
              <a href="<?php echo $imgUrl;?>" target="_blank"><?php echo html::image($imgUrl, "class='img-thumbnail'");?></a>
            </div>
            <?php endforeach;?>
            <?php endif;?>
          </div>
        </div>
        <div class='col-lg-4'>
          <h3><?php echo $lang->store->appDynamic;?></h3>
          <div class='dynamicContainer'>
            <table class="table table-striped table-hover table-borderless">
            <?php if(empty($dynamicArticles)):?>
            <tr><td><?php echo $lang->store->noDynamicArticle;?></td></tr>
            <?php else:?>
            <?php foreach($dynamicArticles as $article):?>
            <tr><td><?php echo html::a($article->url, $article->title, "target='_blank'");?></td></tr>
            <?php endforeach;?>
            <?php endif;?>
            </table>
            <?php if(!empty($dynamicArticles)):?>
            <div class="table-footer"><?php $dynamicPager->show('right', 'pagerjs');?></div>
            <?php endif;?>
          </div>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
