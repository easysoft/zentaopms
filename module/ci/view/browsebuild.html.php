<?php
/**
 * The browse view file of jenkins build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     citask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<div id='mainContent' class='main-row'>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='buildList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
                    <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->citask->id); ?></th>
                    <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->citask->name); ?></th>
                    <th class='w-100px text-left'><?php echo $lang->citask->buildStatus; ?></th>
                    <th class='w-100px text-left'><?php echo $lang->citask->buildTime; ?></th>

                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($buildList as $id => $build): ?>
                    <tr>
                        <td class='text-center'><?php echo $id; ?></td>
                        <td class='text' title='<?php echo $build->name; ?>'><?php echo $build->name; ?></td>
                        <td class='text' title='<?php echo $build->status; ?>'><?php echo $build->status; ?></td>
                        <td class='text' title='<?php echo $build->createDate; ?>'><?php echo $build->createdDate; ?></td>

                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('citask', 'viewBuildLogs', "buildID=$id", '', 'list', 'file-text',
                                '', '', '', '', $lang->citask->viewLogs);
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
