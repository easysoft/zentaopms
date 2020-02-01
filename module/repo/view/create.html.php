<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<div id='mainContent' class='main-row'>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->repo->create; ?></h2>
            </div>
            <form id='repoForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th class='thWidth'></th>
                        <td colspan="2"><?php echo $lang->repo->tips; ?></td>
                    </tr>
                    <tr>
                        <th class='thWidth'><?php echo $lang->repo->type; ?></th>
                        <td style="width:550px"><?php echo html::select('SCM', $lang->repo->scmList, 'git', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->name; ?></th>
                        <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->path; ?></th>
                        <td class='required'><?php echo html::input('path', '', "class='form-control'"); ?></td>
                        <td class='muted'><?php echo $lang->repo->example->path;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->encoding; ?></th>
                        <td class='required'><?php echo html::input('encoding', 'utf-8', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->client;?></th>
                        <td class='required'><?php echo html::input('client', '', "class='form-control'")?></td>
                        <td class='muted'><?php echo $lang->repo->example->client;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->account;?></th>
                        <td><?php echo html::input('account', '', "class='form-control'");?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->repo->password;?></th>
                        <td>
                            <?php echo html::password('password', '', "class='form-control'");?>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->repo->acl;?></th>
                        <td class='acl'>
                            <div class='input-group mgb-10'>
                                <span class='input-group-addon'><?php echo $lang->repo->group?></span>
                                <?php echo html::select('acl[groups][]', $groups, '', "class='form-control chosen' multiple")?>
                            </div>
                            <div class='input-group'>
                                <span class='input-group-addon user-addon'><?php echo $lang->repo->user?></span>
                                <?php echo html::select('acl[users][]', $users, '', "class='form-control chosen' multiple")?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->repo->desc; ?></th>
                        <td><?php echo html::textarea('desc', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
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
