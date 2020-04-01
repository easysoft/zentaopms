<?php
/**
 * The edit view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<?php js::set('repoTypes', $repoTypes)?>
<?php js::set('triggerType', $job->triggerType);?>
<?php js::set('jkJob', $job->jkJob);?>
<?php js::set('dirChange', $lang->job->dirChange);?>
<?php js::set('buildTag', $lang->job->buildTag);?>

<div id='mainContent' class='main-row'>
  <div class='main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->job->edit; ?></h2>
      </div>
      <form id='jobForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-120px'><?php echo $lang->job->name; ?></th>
            <td class='required'><?php echo html::input('name', $job->name, "class='form-control'"); ?></td>
            <td colspan="2" ></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->repo; ?></th>
            <td><?php echo html::select('repo', $repoPairs, $job->repo, "class='form-control chosen'");?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->product; ?></th>
            <td><?php echo html::select('product', $products, $job->product, "class='form-control chosen'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->frame; ?></th>
            <td><?php echo html::select('frame', $lang->job->frameList, $job->frame, "class='form-control chosen'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->triggerType; ?></th>
            <?php if($repoType == 'Subversion') $lang->job->triggerTypeList['tag'] = $lang->job->dirChange;?>
            <td><?php echo html::select('triggerType', $lang->job->triggerTypeList, $job->triggerType, "class='form-control chosen'");?></td>
            <td colspan="2"></td>
          </tr>
          <tr id='svnDirBox' class='svn-fields'>
            <th><?php echo $lang->job->svnDir;?></th>
            <td colspan='3'>
              <div class='input-group'>
                <?php
                if($repoType == 'Subversion' and $job->svnDir)
                {
                    $path = '';
                    $svnDirs = trim($job->svnDir, '/');
                    $svnDirs = empty($svnDirs) ? '' : $job->svnDir;
                    $svnDirs = explode('/', $svnDirs);
                    foreach($svnDirs as $i => $svnDir)
                    {
                        $path .= '/' . $svnDir;
                        $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
                        if(empty($tags)) continue;

                        $selected  = isset($svnDirs[$i + 1]) ? $path . $svnDirs[$i + 1] : '/';
                        echo "<select id='svnDir{$i}' name='svnDir[]' class='form-control chosen'>";
                        echo "<option value='/' data-encodePath='" . $this->repo->encodePath($path) . "'>/</option>";
                        foreach($tags as $dirPath => $dirName) echo "<option value='{$dirPath}' data-encodePath='" . $this->repo->encodePath($dirPath) . "' " . ($selected == $dirPath ? 'selected' : '') . ">/" . basename($dirPath) . "</option>";
                        echo "</select>";
                    }
                }
                ?>
              </div>
            </td>
          </tr>
          <tr class="comment-fields">
            <th><?php echo $lang->job->comment;?></th>
            <td class='required'><?php echo html::input('comment', $job->comment, "class='form-control'");?></td>
            <td colspan='2'><?php echo $lang->job->commitEx;?></td>
          </tr>
          <tr class="custom-fields">
            <th rowspan='2'></th>
            <td colspan="3"><?php echo html::checkbox('atDay', $lang->datepicker->dayNames, $job->atDay, '', 'inline');?></td>
          </tr>
          <tr class="custom-fields">
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->job->atTime;?></span>
                <?php echo html::input('atTime', $job->atTime, "class='form-control form-time'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->jkHost; ?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('jkHost', $jkHostList, $job->jkHost, "class='form-control chosen'");?></div>
                <div id='jkJobBox' class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->job->jkJob; ?></span>
                    <?php echo html::select('jkJob', array('' => ''), $job->jkJob, "class='form-control chosen'");?>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th></th>
            <td colspan="2" class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php echo html::backButton(); ?>
              <?php echo html::hidden('repoType', zget($repoTypes, $job->repo, 'Git'));?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
