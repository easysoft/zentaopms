<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/form.html.php';
include '../../common/view/kindeditor.html.php';
css::import($jsRoot . 'misc/highlight/styles/code.css');
js::import($jsRoot  . 'misc/highlight/highlight.pack.js');
$encodePath = $this->repo->encodePath($entry);
$version = " <span class=\"label label-info\">$revisionName</span>";
?>
<?php if(!isonlybody()):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->repoList, "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary' data-app='{$app->tab}'");?>
    <div class="divider"></div>
    <div class="page-title">
      <strong>
        <?php
        $base64BranchID = helper::safe64Encode(base64_encode($branchID));
        echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID"), $repo->name, '', "data-app='{$app->tab}'");
        $paths= explode('/', $entry);
        $fileName = array_pop($paths);
        $postPath = '';
        foreach($paths as $pathName)
        {
            $postPath .= $pathName . '/';
            echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'), '', "data-app='{$app->tab}'");
        }
        echo '/' . ' ' . $fileName;
        echo $version;
        ?>
      </strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=$objectID&revision=$revision"), $lang->repo->allChanges, '', "class='btn btn-primary' data-app='{$app->tab}'")?>
  </div>
</div>
<?php endif;?>

<?php if(!isonlybody()):?>
<div id="mainContent" class="main-row fade">
<?php endif;?>
  <div class="main-col repoCode main">
    <div class="content panel">
      <div class='panel-heading'>
        <div class='btn-toolbar'>
          <div class='panel-title'>
            <?php echo $pathInfo['basename'];?>
            <div class='btn-group'>
              <div class='btn-group'>
                <?php echo html::commonButton(zget($lang->repo->encodingList, $encoding, $lang->repo->encoding) . "<span class='caret'></span>", "data-toggle='dropdown'", 'btn dropdown-toggle btn-sm')?>
                <ul class='dropdown-menu' role='menu'>
                  <?php foreach($lang->repo->encodingList as $key => $val):?>
                  <li><?php echo html::a($this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision=$revision&showBug=$showBug&encoding=$key", 'html', isonlybody()), $val, '', "data-app='{$app->tab}'")?></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class='panel-actions'>
          <?php if($suffix != 'binary' and strpos($config->repo->images, "|$suffix|") === false):?>
          <?php
          if(common::hasPriv('repo', 'blame')) echo html::a($this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision=$revision&encoding=$encoding"), html::icon('random') . $lang->repo->blame, '', "class='btn btn-sm btn-primary' data-app='{$app->tab}'");
          if(common::hasPriv('repo', 'download')) echo html::a($this->repo->createLink('download', "repoID=$repoID&path=$encodePath&fromRevision=$revision"), html::icon('download-alt') . $lang->repo->download, 'hiddenwin', "class='btn btn-sm btn-primary'");
          ?>
          <?php endif;?>
        </div>
      </div>
      <?php if(strpos($config->repo->images, "|$suffix|") !== false):?>
      <div class='image'><img src='data:image/<?php echo $suffix?>;base64,<?php echo $content?>' /></div>
      <?php elseif($suffix == 'binary'):?>
      <div class='binary'><?php echo html::a($this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevision=$revision"), "<i class='icon-download'></i>", 'hiddenwin', "title='{$lang->repo->download}'"); ?></div>
      <?php else:?>
      <pre class="<?php echo $config->program->suffix[$suffix];?>"><?php echo trim(htmlSpecialString($content, defined('ENT_SUBSTITUTE') ? ENT_QUOTES | ENT_SUBSTITUTE : ENT_QUOTES));?></pre>
      <?php endif;?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-right"></i></div>
    <div class='side-body'><?php include 'ajaxsidecommits.html.php';?></div>
  </div>
  <?php endif;?>
<?php if(!isonlybody()):?>
</div>
<?php endif;?>
<?php if(!isonlybody()):?>
<div id="mainActions" class='main-actions'>
  <nav class="container">
    <?php if(!empty($preAndNext->pre))  echo html::a($this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision={$preAndNext->pre}&showBug=$showBug", 'html', isonlybody()), "<i class='icon-pre icon-chevron-left'></i>", '', "id='prevPage' class='btn btn-info' data-app='{$app->tab}' title='{$preAndNext->pre}'")?>
    <?php if(!empty($preAndNext->next)) echo html::a($this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision={$preAndNext->next}&showBug=$showBug", 'html', isonlybody()), "<i class='icon-pre icon-chevron-right'></i>", '', "id='nextPage' class='btn btn-info' data-app='{$app->tab}' title='{$preAndNext->next}'")?>
  </nav>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
