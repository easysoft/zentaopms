<?php
/**
 * The edit view file of ci task module of ZenTaoPMS.
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

<?php js::set('triggerType',  $job->triggerType)?>

<div id='mainContent' class='main-row'>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->job->edit; ?></h2>
            </div>
            <form id='jobForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th><?php echo $lang->job->name; ?></th>
                        <td colspan="3" class='required'><?php echo html::input('name', $job->name, "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->repo; ?></th>
                        <td><?php echo html::select('repo', $repoList, $job->repo, "class='form-control chosen'"); ?></td>

                        <th><?php echo $lang->job->buildType; ?></th>
                        <td><?php echo html::select('buildType', $lang->job->buildTypeList, 'buildAndDeploy', "class='form-control chosen'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->jenkins; ?></th>
                        <td><?php echo html::select('jenkins', $jenkinsList, $job->jenkins, "class='form-control chosen'"); ?></td>

                        <th><?php echo $lang->job->jenkinsTask; ?></th>
                        <td><?php echo html::input('jenkinsTask', $job->jenkinsTask, "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->triggerType; ?></th>
                        <td><?php echo html::select('triggerType', $lang->job->triggerTypeList, $job->triggerType,
                                "onchange='triggerTypeChanged(this.value)' class='form-control chosen'"); ?></td>

                        <th class="schedule-fields"><?php echo $lang->job->scheduleType; ?></th>
                        <td class="schedule-fields"><?php echo html::radio('scheduleType', $lang->job->scheduleTypeList, $job->scheduleType,
                                "onclick='scheduleTypeChanged(this.value)'");?></td>
                    </tr>
                    <tr class="tag-fields">
                        <th><?php echo $lang->job->example; ?></th>
                        <td colspan="3"><?php echo $lang->job->tagEx; ?></td>
                    </tr>
                    <tr class="comment-fields">
                        <th><?php echo $lang->job->example; ?></th>
                        <td colspan="3"><?php echo $lang->job->commitEx; ?></td>
                    </tr>

                    <tr class="cron-fields">
                        <th><?php echo $lang->job->cronExpression; ?></th>
                        <td><?php echo html::input('cronExpression', $job->cronExpression, "class='form-control'"); ?></td>
                        <td colspan="2"><span style="font-style: italic"><?php echo $lang->job->cronSample; ?></span></td>
                    </tr>
                    <tr class="custom-fields">
                        <th><?php echo $lang->job->custom; ?></th>
                        <td colspan="3">
                            <div class="row text-with-input">
                                <div class="col w-50px">
                                    <?php echo $lang->job->scheduleInterval; ?>
                                </div>
                                <div class="col w-100px">
                                    <?php echo html::number('scheduleInterval', $job->scheduleInterval, "class='form-control'"); ?>
                                </div>
                                <div class="col w-30px">
                                    <?php echo $lang->job->day; ?>，
                                </div>

                                <div class="col w-40px">
                                    <?php echo $lang->job->at; ?>
                                </div>
                                <div class="col w-120px">
                                    <?php echo html::select('scheduleDay', $lang->job->dayTypeList, $job->scheduleDay,"class='form-control chosen'"); ?>
                                </div>
                                <div class="col w-100px">
                                    <?php echo html::input('scheduleTime', $job->scheduleTime,
                                        "class='form-control form-time time-only' min='1'"); ?>
                                </div>
                                <div class="col w-60px">
                                    <?php echo $lang->job->exe; ?>。
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->job->desc; ?></th>
                        <td colspan="3"><?php echo html::textarea('desc', '', "rows='3' class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td colspan="2" class='text-center form-actions'>
                            <?php echo html::submitButton(); ?>
                            <?php echo html::backButton(); ?>
                            &nbsp;
                            <?php echo html::commonButton($lang->job->exeNow, "onclick=exeCitask($job->id)  data-tip-class='tooltip-success'", "btn btn-info exe-job-button"); ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
