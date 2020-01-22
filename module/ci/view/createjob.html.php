<?php
/**
 * The create view file of ci task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     citask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../ci/lang/zh-cn.php'; ?>
<?php include '../../ci/view/header.html.php'; ?>
<?php include '../../common/view/form.html.php'; ?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include '../../ci/view/menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->citask->create; ?></h2>
            </div>
            <form id='citaskForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th><?php echo $lang->citask->name; ?></th>
                        <td colspan="3" class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->citask->repo; ?></th>
                        <td><?php echo html::select('repo', $repoList, '', "class='form-control chosen'"); ?></td>

                        <th><?php echo $lang->citask->buildType; ?></th>
                        <td><?php echo html::select('buildType', $lang->citask->buildTypeList, 'buildAndDeploy', "class='form-control chosen'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->citask->jenkins; ?></th>
                        <td><?php echo html::select('jenkins', $jenkinsList, '', "class='form-control chosen'"); ?></td>

                        <th><?php echo $lang->citask->jenkinsTask; ?></th>
                        <td><?php echo html::input('jenkinsTask', '', "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->citask->triggerType; ?></th>
                        <td><?php echo html::select('triggerType', $lang->citask->triggerTypeList, '',
                                "onchange='triggerTypeChanged(this.value)' class='form-control chosen'"); ?></td>

                        <th class="schedule-fields"><?php echo $lang->citask->scheduleType; ?></th>
                        <td class="schedule-fields"><?php echo html::radio('scheduleType', $lang->citask->scheduleTypeList, 'cron',
                                "onclick='scheduleTypeChanged(this.value)'");?></td>
                    </tr>
                    <tr class="tag-fields">
                        <th><?php echo $lang->citask->example; ?></th>
                        <td colspan="3"><?php echo $lang->citask->tagEx; ?></td>
                    </tr>
                    <tr class="comment-fields">
                        <th><?php echo $lang->citask->example; ?></th>
                        <td colspan="3"><?php echo $lang->citask->commitEx; ?></td>
                    </tr>

                    <tr class="cron-fields">
                        <th><?php echo $lang->citask->cronExpression; ?></th>
                        <td><?php echo html::input('cronExpression', '', "class='form-control'"); ?></td>
                        <td colspan="2"><span style="font-style: italic"><?php echo $lang->citask->cronSample; ?></span></td>
                    </tr>
                    <tr class="custom-fields">
                        <th><?php echo $lang->citask->custom; ?></th>
                        <td colspan="3">
                            <div class="row text-with-input">
                                <div class="col w-50px">
                                    <?php echo $lang->citask->scheduleInterval; ?>
                                </div>
                                <div class="col w-100px">
                                    <?php echo html::number('scheduleInterval', '1', "class='form-control'"); ?>
                                </div>
                                <div class="col w-30px">
                                    <?php echo $lang->citask->day; ?>，
                                </div>

                                <div class="col w-40px">
                                    <?php echo $lang->citask->at; ?>
                                </div>
                                <div class="col w-120px">
                                    <?php echo html::select('scheduleDay', $lang->citask->dayTypeList, '',"class='form-control chosen'"); ?>
                                </div>
                                <div class="col w-100px">
                                    <?php echo html::input('scheduleTime', '2:00',
                                        "class='form-control form-time time-only' min='1'"); ?>
                                </div>
                                 <div class="col w-60px">
                                    <?php echo $lang->citask->exe; ?>。
                                 </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->citask->desc; ?></th>
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
