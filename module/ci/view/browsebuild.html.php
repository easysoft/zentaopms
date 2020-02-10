<?php
/**
 * The browse view file of jenkins build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     ci
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<div id='mainMenu' class='clearfix'>
    <div class='btn-toolbar pull-left'>
        <div class="page-title">
            <strong>
                <?php echo $lang->job->browseBuild; ?>
            </strong>
        </div>
    </div>
    <div class="btn-toolbar pull-right">
        <?php echo html::a(helper::createLink('ci', "browseJob", ""), "<i class='icon icon-back icon-sm'></i> ". $lang->goback, '', "class='btn btn-secondary'");?>
    </div>
</div>

<div id='mainContent' class='main-row'>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='buildList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <?php $vars = "jobID={$job->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>

                    <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->job->id); ?></th>
                    <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->job->name); ?></th>
                    <th class='w-200px text-left'><?php echo $lang->ci->repo; ?></th>
                    <th class='w-200px text-left'><?php echo $lang->ci->jenkins; ?></th>
                    <th class='w-200px text-left'><?php echo $lang->job->triggerType; ?></th>

                    <th class='w-100px text-left'><?php common::printOrderLink('status', $orderBy, $vars, $lang->job->buildStatus); ?></th>
                    <th class='w-100px text-left'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->job->buildTime); ?></th>

                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($buildList as $id => $build): ?>
                    <tr>
                        <td class='text-center'><?php echo $id; ?></td>
                        <td class='text' title='<?php echo $build->name; ?>'><?php echo $build->name; ?></td>
                        <td class='text' title='<?php echo $build->repoName; ?>'><?php echo $build->repoName; ?></td>
                        <td class='text' title='<?php echo $build->jenkinsName; ?>'><?php echo $build->jenkinsName; ?></td>
                        <td class='text' title='<?php echo $lang->job->triggerTypeList[$build->triggerType]; ?>'>
                            <?php echo $lang->job->triggerTypeList[$build->triggerType]; ?>
                        </td>

                        <td class='text' title='<?php echo $lang->job->buildStatusList[$build->status]; ?>'>
                            <?php echo $lang->job->buildStatusList[$build->status]; ?>
                        </td>
                        <td class='text' title='<?php echo $build->createDate; ?>'><?php echo $build->createdDate; ?></td>

                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('ci', 'viewBuildLogs', "buildID=$id", '', 'list', 'file-text',
                                '', '', '', '', $lang->job->viewLogs);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($buildList): ?>
                <div class='table-footer'><?php $pager->show('rignt', 'pagerjs'); ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
