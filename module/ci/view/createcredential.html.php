<?php
/**
 * The create view file of credential module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     credential
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php'; ?>
<?php include '../../common/view/form.html.php'; ?>

<?php js::set('type',  'account')?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include 'menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->credential->create; ?></h2>
            </div>
            <form id='credentialForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th class='thWidth'><?php echo $lang->credential->type; ?></th>
                        <td style="width:550px"><?php echo html::select('type', $lang->credential->typeList, 'account', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->credential->name; ?></th>
                        <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->credential->username; ?></th>
                        <td><?php echo html::input('username', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="password-field">
                        <th><?php echo $lang->credential->password; ?></th>
                        <td><?php echo html::input('password', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="privateKey-field">
                        <th><?php echo $lang->credential->privateKey; ?></th>
                        <td><?php echo html::textarea('privateKey', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="passphrase-field">
                        <th><?php echo $lang->credential->passphrase; ?></th>
                        <td><?php echo html::password('passphrase', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->credential->desc; ?></th>
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
