<?php
/**
 * The edit view file of credential module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     credential
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../ci/lang/zh-cn.php'; ?>
<?php include '../../ci/view/header.html.php'; ?>
<?php include '../../common/view/form.html.php'; ?>

<?php js::set('type',  $credentials->type)?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include '../../ci/view/menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->credentials->edit; ?></h2>
            </div>
            <form id='credentialForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th class='thWidth'><?php echo $lang->credentials->type; ?></th>
                        <td style="width:550px"><?php echo html::select('type', $lang->credentials->typeList, $credentials->type, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->credentials->name; ?></th>
                        <td class='required'><?php echo html::input('name', $credentials->name, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->credentials->username; ?></th>
                        <td><?php echo html::input('username', $credentials->username, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="password-field">
                        <th><?php echo $lang->credentials->password; ?></th>
                        <td><?php echo html::input('password', $credentials->password, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="privateKey-field">
                        <th><?php echo $lang->credentials->privateKey; ?></th>
                        <td><?php echo html::textarea('privateKey', $credentials->privateKey, "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="passphrase-field">
                        <th><?php echo $lang->credentials->passphrase; ?></th>
                        <td><?php echo html::password('passphrase', $credentials->passphrase, "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="token-field">
                        <th><?php echo $lang->credentials->token; ?></th>
                        <td><?php echo html::input('token', $credentials->token, "class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->credentials->desc; ?></th>
                        <td><?php echo html::textarea('desc', $credentials->desc, "rows='3' class='form-control'"); ?></td>
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
