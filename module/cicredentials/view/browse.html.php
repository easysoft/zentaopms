<?php
/**
 * The browse view file of credentials module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     credentials
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../ci/lang/zh-cn.php'; ?>
<?php include '../../ci/view/header.html.php'; ?>
<?php js::set('confirmDelete', $lang->credentials->confirmDelete); ?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include '../../ci/view/menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='credentialsList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
                    <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->credentials->id); ?></th>
                    <th class='w-120px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->credentials->type); ?></th>
                    <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->credentials->name); ?></th>
                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($credentialsList as $id => $credentials): ?>
                    <tr>
                        <td class='text-center'><?php echo $id; ?></td>
                        <td class='text'><?php echo zget($lang->credentials->typeList, $credentials->type); ?></td>
                        <td class='text' title='<?php echo $credentials->name; ?>'><?php echo $credentials->name; ?></td>
                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('cicredentials', 'edit', "id=$id", '', 'list',  'edit');
                            if (common::hasPriv('cicredentials', 'delete')) {
                                $deleteURL = $this->createLink('cicredentials', 'delete', "id=$id&confirm=yes");
                                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"credentialsList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->credentials->delete}' class='btn'");
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($credentialsList): ?>
                <div class='table-footer'><?php $pager->show('rignt', 'pagerjs'); ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
