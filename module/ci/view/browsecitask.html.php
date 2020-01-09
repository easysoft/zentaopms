<?php
/**
 * The browse view file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     jenkins
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php'; ?>
<?php js::set('confirmDelete', $lang->jenkins->confirmDelete); ?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include 'menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='jenkinsList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
                    <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->citask->id); ?></th>
                    <th class='w-200px text-left'>
                        <?php common::printOrderLink('name', $orderBy, $vars, $lang->citask->name); ?></th>
                    <th class='w-150px text-left'>
                        <?php common::printOrderLink('repo', $orderBy, $vars, $lang->citask->jenkins); ?></th>
                    <th class='w-120px text-left'><?php echo $lang->citask->jenkinsTask; ?></th>
                    <th class='w-100px text-left'><?php echo $lang->citask->buildType; ?></th>
                    <th class='w-100px text-left'><?php echo $lang->citask->triggerType; ?></th>
                    <th class='w-200px text-left'><?php echo $lang->citask->lastExe; ?></th>

                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (citaskList as $id => $task): ?>
                    <tr>
                        <td class='text-center'><?php echo $id; ?></td>
                        <td class='text' title='<?php echo $task->name; ?>'><?php echo $task->name; ?></td>
                        <td class='text' title='<?php echo $task->jenkinsServer; ?>'><?php echo $task->jenkinsServer; ?></td>
                        <td class='text' title='<?php echo $task->jenkinsTask; ?>'><?php echo $task->jenkinsTask; ?></td>
                        <td class='text' title='<?php echo $lang->citask->buildTypeList[$task->buildType]; ?>'>
                            <?php echo $lang->citask->buildTypeList[$task->buildType]; ?></td>
                        <td class='text' title='<?php echo $lang->citask->triggerTypeList[$task->triggerType]; ?>'>
                            <?php echo $lang->citask->triggerTypeList[$task->triggerType]; ?></td>
                        <td class='text' title='<?php echo $lang->citask->lastBuild; ?>'><?php echo $task->lastBuild; ?></td>

                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('ci', 'editJenkins', "jenkinsID=$id", '', 'list',  'edit');
                            if (common::hasPriv('ci', 'deleteJenkins')) {
                                $deleteURL = $this->createLink('ci', 'deleteJenkins', "jenkinsID=$id&confirm=yes");
                                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"jenkinsList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->jenkins->delete}' class='btn'");
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($jenkinss): ?>
                <div class='table-footer'><?php $pager->show('rignt', 'pagerjs'); ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
