<?php
/**
 * The create view file of ci job module of ZenTaoPMS.
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

<?php js::set('triggerType',  'tag')?>

<div id='mainContent' class='main-row'>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->job->create; ?></h2>
            </div>
            <form id='jobForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th><?php echo $lang->job->name; ?></th>
                        <td colspan="3" class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->repo; ?></th>
                        <td colspan="3"><?php echo html::select('repo', $repoList, '', "class='form-control chosen'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->jenkins; ?></th>
                        <td><?php echo html::select('jenkins', $jenkinsList, '', "class='form-control chosen'"); ?></td>

                        <th><?php echo $lang->job->jenkinsJob; ?></th>
                        <td><?php echo html::input('jenkinsJob', '', "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->job->triggerType; ?></th>
                        <td><?php echo html::select('triggerType', $lang->job->triggerTypeList, '',
                                "onchange='triggerTypeChanged(this.value)' class='form-control chosen'"); ?></td>

                        <th class="schedule-fields"><?php echo $lang->job->scheduleType; ?></th>
                        <td class="schedule-fields"><?php echo html::radio('scheduleType', $lang->job->scheduleTypeList, 'cron',
                                "onclick='scheduleTypeChanged(this.value)'");?></td>
                    </tr>
                    <tr class="tag-fields" class="tag-fields">
                        <th><?php echo $lang->job->example; ?></th>
                        <td colspan="3"><?php echo $lang->job->tagEx; ?></td>
                    </tr>
                    <tr class="comment-fields" class="comment-fields">
                        <th><?php echo $lang->job->example; ?></th>
                        <td colspan="3"><?php echo $lang->job->commitEx; ?></td>
                    </tr>

                    <tr class="cron-fields" class="cron-fields">
                        <th><?php echo $lang->job->cronExpression; ?></th>
                        <td><?php echo html::input('cronExpression', '', "class='form-control'"); ?></td>
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
                                    <?php echo html::number('scheduleInterval', '1', "class='form-control'"); ?>
                                </div>
                                <div class="col w-40px">
                                    <?php echo $lang->job->day; ?>，
                                </div>

                                <div class="col <?php echo $this->app->getClientLang() == 'en' ? 'w-100px' : '2-30px'; ?>">
                                    <?php echo $lang->job->at; ?>
                                </div>
                                <div class="col w-150px">
                                    <?php echo html::select('scheduleDay', $lang->job->dayTypeList, '',"class='form-control chosen'"); ?>
                                </div>
                                <div class="col w-100px">
                                    <?php echo html::input('scheduleTime', '2:00',
                                        "class='form-control form-time time-only' min='1'"); ?>
                                </div>
                                 <div class="col w-120px">
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
                        <td class='text-center form-actions'>
                            <?php echo html::submitButton(); ?>
                            <?php echo html::backButton(); ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<?php include '../../common/view/footer.html.php'; ?>
