<?php
js::import($jsRoot  . '/zui/tabs/tabs.min.js');
js::import($jsRoot . 'misc/base64.js');
$entry   = count($diffs) ? $diffs[0]->fileName : '';
$file    = $entry ? pathinfo($entry) : array();

js::set('diffs', $diffs);
js::set('file', $file);
js::set('entry', $entry);
js::set('openedFiles', array($entry));
js::set('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");
?>
<?php if(!isonlybody()):?>
<div id="mainContent" class="main-row fade">
  <?php $sideWidth = common::checkNotCN() ? '270' : '240';?>
  <div class="side-col" style="width: <?php echo $sideWidth;?>px; padding-top: <?php echo isonlybody() ? 22 : 0;?>px;">
    <div class="side-col file-tree" style="width: <?php echo $sideWidth;?>px;" data-min-width="<?php echo $sideWidth;?>">
      <div id="filesTree" class="cell load-indicator <?php if(isonlybody()) echo 'pull-left';?>">
        <?php echo $this->repo->getFileTree($repo, '', $diffs);?>
      </div>
    </div>
  </div>
<?php endif;?>
  <div class="main-col repoCode main">
    <div class="content panel">
      <div class='btn-toolbar'>
        <?php if(!isonlybody()):?>
        <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
        <?php if(common::hasPriv('repo', 'download')):?>
        <div class="dropdown pull-right">
          <button class="btn" type="button" data-toggle="context-dropdown"><i class="icon icon-ellipsis-v icon-rotate-90"></i></button>
          <ul class="dropdown-menu">
            <?php
            echo '<li>' . html::a($this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevison=$oldRevision&toRevision=$newRevision&type=path"), '<i class="icon icon-download"></i> ' . $lang->repo->downloadDiff, 'hiddenwin') . '</li>';
            ?>
          </ul>
        </div>
        <?php endif;?>
        <div class="btn btn-right  pull-right"><i class="icon icon-chevron-right"></i></div>
        <?php endif;?>
        <div class='panel-title'>
          <div class="tabs w-10" id="fileTabs"></div>
        </div>
      </div>
    </div>
  </div>
<?php if(!isonlybody()):?>
</div>
<?php endif;?>
<a href='' class='iframe' data-width='90%' id='linkObject'></a>
