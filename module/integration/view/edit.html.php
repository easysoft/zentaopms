<?php
/**
 * The edit view file of integration module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     integration
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<?php js::set('repoTypes', $repoTypes)?>
<?php js::set('triggerType', $job->triggerType);?>
<?php js::set('jobRepo', $job->repo);?>
<?php js::set('svnDir', $job->svnDir);?>
<?php js::set('encodeSVNDir', $this->loadModel('repo')->encodePath($job->svnDir));?>
<?php js::set('jkJob', $job->jkJob);?>
<?php js::set('dirChange', $lang->integration->dirChange);?>
<?php js::set('buildTag', $lang->integration->buildTag);?>

<div id='mainContent' class='main-row'>
  <div class='main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->integration->edit; ?></h2>
      </div>
      <form id='jobForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->integration->name; ?></th>
            <td class='required'><?php echo html::input('name', $job->name, "class='form-control'"); ?></td>
            <td colspan="2" ></td>
          </tr>
          <tr>
            <th><?php echo $lang->integration->repo; ?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('repo', $repoPairs, $job->repo, "class='form-control chosen'");?></div>
                <div id='svnDirBox' class="table-col">
                  <div class='input-group svn-fields hidden'>
                    <span class='input-group-addon'><?php echo $lang->integration->svnDir;?></span>
                    <?php echo html::select('svnDir', array('' => ''), $job->svnDir, "class='form-control chosen'");?>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->integration->triggerType; ?></th>
            <td><?php echo html::select('triggerType', $lang->integration->triggerTypeList, $job->triggerType, "class='form-control chosen'");?></td>
            <td colspan="2"></td>
          </tr>
          <tr class="comment-fields">
            <th><?php echo $lang->integration->comment;?></th>
            <td class='required'><?php echo html::input('comment', '', "class='form-control'");?></td>
            <td colspan='2'><?php echo $lang->integration->commitEx;?></td>
          </tr>
          <tr class="custom-fields">
            <th rowspan='2'></th>
            <td colspan="3"><?php echo html::checkbox('atDay', $lang->datepicker->dayNames, $job->atDay, '', 'inline');?></td>
          </tr>
          <tr class="custom-fields">
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->integration->atTime;?></span>
                <?php echo html::input('atTime', $job->atTime, "class='form-control form-time'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->integration->jkHost; ?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('jkHost', $jkHostList, $job->jkHost, "class='form-control chosen'");?></div>
                <div id='jkJobBox' class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->integration->jkJob; ?></span>
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
              <?php echo html::commonButton($lang->integration->execNow, "onclick=execJob($job->id) data-tip-class='tooltip-success'", "btn btn-info exe-job-button");?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php js::set('sendExec', $lang->integration->sendExec);?>
<?php include '../../common/view/footer.html.php';?>
