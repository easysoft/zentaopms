<?php
/**
 * The edit view file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitea
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitea->edit;?></h2>
      </div>
      <form id='giteaForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitea->name;?></th>
            <td class='required'><?php echo html::input('name', isset($gitea->name) ? $gitea->name : '', "class='form-control'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitea->url;?></th>
            <td class='required'><?php echo html::input('url', isset($gitea->url) ? $gitea->url : '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitea->token;?></th>
            <td><?php echo html::input('token', isset($gitea->token) ? $gitea->token : '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton();?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
