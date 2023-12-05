<?php
/**
 * The edit view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->edit;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->name;?></th>
            <td class='required'><?php echo html::input('name', isset($gitlab->name) ? $gitlab->name : '', "class='form-control' placeholder='{$lang->gitlab->placeholder->name}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->url;?></th>
            <td class='required'><?php echo html::input('url', isset($gitlab->url) ? $gitlab->url : '', "class='form-control' placeholder='{$lang->gitlab->placeholder->url}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->token;?></th>
            <td><?php echo html::input('token', isset($gitlab->token) ? $gitlab->token : '', "class='form-control' placeholder='{$lang->gitlab->placeholder->token}'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
