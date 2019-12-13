<?php
/**
 * The view branch file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     ci
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php'; ?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include 'menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <form class='main-table' id='ajaxForm' method='post'>
            <table id='branchList' class='table has-sort-head table-fixed'>
                <thead>
                <tr>
                    <th class='w-60px'><?php echo $lang->ci->numb; ?></th>
                    <th class='w-120px'><?php echo $lang->ci->name; ?></th>
                    <th class='w-60px'><?php echo $lang->repo->watch; ?></th>
                    <th class='c-actions-4'><?php echo $lang->actions; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 0;
                    foreach ($branches as $id => $branch): ?>
                    <tr>
                        <td class='text'><?php echo ++$index; ?></td>
                        <td class='text' title='<?php echo $branch; ?>'><?php echo $id; ?></td>
                        <td>
                            <input type='checkbox' id='future' name='future' value='1' />
                        </td>
                        <td class='c-actions text-right'>
                            <?php
                            common::printIcon('ci', 'editRepo', "branchID=$id", '', 'list',  'edit');
                            if (common::hasPriv('ci', 'deleteRepo')) {
                                $deleteURL = $this->createLink('ci', 'deleteRepo', "branchID=$id&confirm=yes");
                                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"branchList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->branch->delete}' class='btn'");
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($branchs): ?>
                <div class='table-footer'><?php $pager->show('rignt', 'pagerjs'); ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
