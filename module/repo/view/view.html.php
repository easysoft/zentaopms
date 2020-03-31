<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php 
include '../../common/view/header.html.php';
include '../../common/view/form.html.php';
include '../../common/view/kindeditor.html.php';
css::import($jsRoot . 'misc/highlight/styles/github.css');
js::import($jsRoot  . 'misc/highlight/highlight.pack.js');
$encodePath = $this->repo->encodePath($entry);
$version = " <span class=\"label label-info\">$revisionName</span>";
?>
<?php if(!isonlybody()):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->repoList, "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-link'");?>
    <div class="divider"></div>
    <div class="page-title">
      <strong>
        <?php
        echo html::a($this->repo->createLink('browse', "repoID=$repoID"), $repo->name);
        $paths= explode('/', $entry);
        $fileName = array_pop($paths);
        $postPath = '';
        foreach($paths as $pathName)
        {
            $postPath .= $pathName . '/';
            echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID", "path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'));
        }
        echo '/' . ' ' . $fileName;
        echo $version;
        ?>
      </strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&revision=$revision"), $lang->repo->allChanges, '', "class='btn btn-primary'")?>
  </div>
</div>
<?php endif;?>

<?php if(!isonlybody()):?>
<div id="mainContent" class="main-row fade">
<?php endif;?>
  <div class="main-col repoCode main">
    <div class="content panel">
      <div class='panel-heading'>
        <div class='panel-title'><?php echo $entry?></div>
        <div class='panel-actions'>
          <?php if($suffix != 'binary' and strpos($config->repo->images, "|$suffix|") === false):?>
          <?php 
          if(common::hasPriv('repo', 'blame')) echo html::a($this->repo->createLink('blame', "repoID=$repoID&entry=&revision=$revision&encoding=$encoding", "entry=$encodePath"), html::icon('random') . $lang->repo->blame, '', "class='btn btn-sm btn-primary'");
          if(common::hasPriv('repo', 'download')) echo html::a($this->repo->createLink('download', "repoID=$repoID&path=&fromRevision=$revision", "path=$encodePath"), html::icon('download-alt') . $lang->repo->download, 'hiddenwin', "class='btn btn-sm btn-primary'");
          ?>
          <?php endif;?>
          <div class='btn-group'>
            <?php echo html::commonButton(zget($lang->repo->encodingList, $encoding, $lang->repo->encoding) . "<span class='caret'></span>", "id='encoding' data-toggle='dropdown'", 'btn btn-sm btn-primary dropdown-toggle')?>
            <ul class='dropdown-menu' role='menu' aria-labelledby='encoding'>
              <?php foreach($lang->repo->encodingList as $key => $val):?>
              <li><?php echo html::a($this->repo->createLink('view', "repoID=$repoID&entry=&revision=$revision&showBug=$showBug&encoding=$key", "entry=$encodePath", 'html', isonlybody()), $val)?></li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
      <?php if(strpos($config->repo->images, "|$suffix|") !== false):?>
      <div class='image'><img src='data:image/<?php echo $suffix?>;base64,<?php echo $content?>' /></div>
      <?php elseif($suffix == 'binary'):?>
      <div class='binary'><?php echo html::a($this->repo->createLink('download', "repoID=$repoID&path=&fromRevision=$revision", "path=" . $this->repo->encodePath($entry)), "<i class='icon-download'></i>", 'hiddenwin', "title='{$lang->repo->download}'"); ?></div>
      <?php else:?>
      <pre class="<?php echo $config->program->suffix[$suffix];?>"><?php echo trim(htmlspecialchars($content, defined('ENT_SUBSTITUTE') ? ENT_QUOTES | ENT_SUBSTITUTE : ENT_QUOTES));?></pre>
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
    <?php if(!empty($preAndNext->pre))  echo html::a($this->repo->createLink('view', "repoID=$repoID&entry=&revision={$preAndNext->pre}&showBug=$showBug", "entry=$encodePath", 'html', isonlybody()), "<i class='icon-pre icon-chevron-left'></i>", '', "id='prevPage' class='btn btn-info' title='{$preAndNext->pre}'")?>
    <?php if(!empty($preAndNext->next)) echo html::a($this->repo->createLink('view', "repoID=$repoID&entry=&revision={$preAndNext->next}&showBug=$showBug", "entry=$encodePath", 'html', isonlybody()), "<i class='icon-pre icon-chevron-right'></i>", '', "id='nextPage' class='btn btn-info' title='{$preAndNext->next}'")?>
  </nav>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
