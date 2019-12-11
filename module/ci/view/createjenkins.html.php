<?php
/**
 * The create view file of jenkins module of ZenTaoPMS.
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
<?php include '../../common/view/form.html.php'; ?>

<?php js::set('type',  'token')?>

<div id='mainContent' class='main-row'>
    <div class='side-col' id='sidebar'>
        <?php include 'menu.html.php'; ?>
    </div>
    <div class='main-col main-content'>
        <div class='center-block'>
            <div class='main-header'>
                <h2><?php echo $lang->jenkins->create; ?></h2>
            </div>
            <form id='jenkinsForm' method='post' class='form-ajax'>
                <table class='table table-form'>
                    <tr>
                        <th class='thWidth'><?php echo $lang->jenkins->type; ?></th>
                        <td style="width:550px"><?php echo html::select('type', $lang->jenkins->typeList, 'account', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->jenkins->name; ?></th>
                        <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->jenkins->username; ?></th>
                        <td><?php echo html::input('username', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="password-field">
                        <th><?php echo $lang->jenkins->password; ?></th>
                        <td><?php echo html::input('password', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>
                    <tr id="token-field">
                        <th><?php echo $lang->jenkins->token; ?></th>
                        <td><?php echo html::input('token', '', "rows='3' class='form-control'"); ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th><?php echo $lang->jenkins->desc; ?></th>
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
