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
<?php js::set('svnFolder', $job->svnFolder);?>
<?php js::set('encodeSVNFolder', $this->loadModel('repo')->encodePath($job->svnFolder));?>
<?php js::set('jenkinsJob', $job->jenkinsJob);?>

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
            <td><?php echo html::select('repo', $repoPairs, $job->repo, "class='form-control chosen'"); ?></td>
            <th class="svn-fields hidden"><?php echo $lang->integration->svnFolder; ?></th>
            <td class="svn-fields hidden" id='svnFolderBox'></td>
          </tr>
          <tr>
            <th><?php echo $lang->integration->jenkins; ?></th>
            <td><?php echo html::select('jenkins', $jenkinsList, $job->jenkins, "class='form-control chosen'"); ?></td>
            <th><?php echo $lang->integration->jenkinsJob; ?></th>
            <td id='jenkinsJobBox'><?php echo html::select('jenkinsJob', $jenkinsJobs, $job->jenkinsJob, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->integration->triggerType; ?></th>
            <td><?php echo html::select('triggerType', $lang->integration->triggerTypeList, $job->triggerType, "class='form-control chosen'"); ?></td>
            <td colspan="2"></td>
          </tr>
          <tr class="comment-fields">
            <th><?php echo $lang->integration->example; ?></th>
            <?php if(is_string($config->repo->matchComment)) $config->repo->matchComment = json_decode($config->repo->matchComment, true);?>
            <td colspan="3"><?php echo str_replace(array('%build%', '%integration%', '%id%'), array($config->repo->matchComment['integration']['start'], $config->repo->matchComment['module']['integration'], $config->repo->matchComment['id']['mark']), $lang->integration->commitEx);?></td>
          </tr>
          <tr class="custom-fields">
            <th><?php echo $lang->integration->scheduleDay;?></th>
            <td colspan="3"><?php echo html::checkbox('scheduleDay', $lang->datepicker->dayNames, $job->scheduleDay, '', 'inline');?></td>
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
