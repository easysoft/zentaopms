<?php
/**
 * The browse view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php'; ?>
<?php js::set('confirmDelete', $lang->repo->confirmDelete); ?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include 'menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='repoList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
                    <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->repo->id); ?></th>
                    <th class='w-120px'><?php common::printOrderLink('SCM', $orderBy, $vars, $lang->repo->type); ?></th>
                    <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->repo->name); ?></th>
                    <th class='w-200px text-left'><?php echo $lang->repo->path; ?></th>
                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($repoList as $id => $repo): ?>
                    <tr>
                        <td class='text-center'><?php echo $id; ?></td>
                        <td class='text'><?php echo zget($lang->repo->scmList, $repo->SCM); ?></td>
                        <td class='text' title='<?php echo $repo->name; ?>'><?php echo $repo->name; ?></td>
                        <td class='text' title='<?php echo $repo->path; ?>'><?php echo $repo->path; ?></td>
                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('ci', 'viewRepo', "repoID=$id", '', 'list', 'file-text');
                            common::printIcon('ci', 'editRepo', "repoID=$id", '', 'list',  'edit');
                            if (common::hasPriv('ci', 'deleteRepo')) {
                                $deleteURL = $this->createLink('ci', 'deleteRepo', "repoID=$id&confirm=yes");
                                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"repoList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->repo->delete}' class='btn'");
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($repos): ?>
                <div class='table-footer'><?php $pager->show('rignt', 'pagerjs'); ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
