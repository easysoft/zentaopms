<?php
/**
 * The manage repo view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->project->manageRepo;?></span></span>
  </div>
</div>
<div id='mainContent'>
  <div class='cell'>
    <form class='main-form form-ajax' method='post' id='reposBox' enctype='multipart/form-data'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->linkedRepo;?></div>
        <div class='detail-content row'>
          <?php foreach($linkedRepos as $repoID => $repoName):?>
          <div class='col-sm-4'>
            <div class='repo checked'>
              <div class="checkbox-primary" title='<?php echo $repoName;?>'>
                <?php echo "<input type='checkbox' name='repos[$repoID]' value='$repoID' checked id='repos{$repoID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'repos' . $repoID;?>' title='<?php echo $repoName;?>'><?php echo $repoName;?></label>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->project->unlinkedRepo;?></div>
        <div class='detail-content row'>
          <?php foreach($unlinkedRepos as $repoID => $repoName):?>
          <div class='col-sm-4'>
            <div class='repo'>
              <div class="checkbox-primary" title='<?php echo $repoName;?>'>
                <?php echo "<input type='checkbox' name='repos[$repoID]' value='$repoID' id='repos{$repoID}'>";?>
                <label class='text-ellipsis checkbox-inline' for='<?php echo 'repos' . $repoID;?>'><?php echo $repoName;?></label>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <div class="detail text-center form-actions">
        <?php echo html::hidden("post", 'post');?>
        <?php echo html::submitButton();?>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
