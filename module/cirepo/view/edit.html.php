<?php
/**
 * The edit view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../ci/lang/zh-cn.php'; ?>
<?php include '../../ci/view/header.html.php'; ?>
<?php include '../../common/view/form.html.php'; ?>

<?php js::set('type',  $repo->type)?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include '../../ci/view/menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->repo->edit; ?></h2>
            </div>
            <form id='repoForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th class='thWidth'></th>
                        <td colspan="2"><?php echo $tips; ?></td>
                    </tr>
                    <tr>
                        <th class='thWidth'><?php echo $lang->repo->type; ?></th>
                        <td style="width:550px"><?php echo html::select('SCM', $lang->repo->scmList, $repo->SCM, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->name; ?></th>
                        <td class='required'><?php echo html::input('name', $repo->name, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->path; ?></th>
                        <td class='required'><?php echo html::input('path', $repo->path, "class='form-control'"); ?></td>
                        <td class='muted'><?php echo $lang->repo->example->path;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->encoding; ?></th>
                        <td class='required'><?php echo html::input('encoding', $repo->encoding, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->client;?></th>
                        <td class='required'><?php echo html::input('client',  $repo->client, "class='form-control'")?></td>
                        <td class='muted'><?php echo $lang->repo->example->client;?></td>
                    </tr>
                    <tr id="credentials-field">
                        <th class='thWidth'><?php echo $lang->credentials->common; ?></th>
                        <td class='required' style="width:550px"><?php echo html::select('credentials', $credentialsList, $repo->credentials, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->repo->acl;?></th>
                        <td>
                            <div class='input-group mgb-10'>
                                <span class='input-group-addon'><?php echo $lang->repo->group?></span>
                                <?php echo html::select('acl[groups][]', $groups, empty($repo->acl->groups) ? '' : join(',', $repo->acl->groups), "class='form-control chosen' multiple")?>
                            </div>
                            <div class='input-group'>
                                <span class='input-group-addon user-addon'><?php echo $lang->repo->user?></span>
                                <?php echo html::select('acl[users][]', $users, empty($repo->acl->users) ? '' : join(',', $repo->acl->users), "class='form-control chosen' multiple")?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->repo->desc; ?></th>
                        <td><?php echo html::textarea('desc', $repo->desc, "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th></th>
                        <td class='text-center form-actions'>
                            <?php echo html::submitButton(); ?>
                            <?php echo html::backButton() ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
