<?php
/**
 * The edit view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div class='container mw-800px'>
  <div id="titlebar">
    <div class="heading">
      <strong><?php echo $lang->webhook->api;?></strong>
      <small class="text-muted"> <?php echo $lang->webhook->edit;?> <i class="icon-pencil"></i></small>
    </div>
  </div>
  <form id='webhookForm' method='post' class='ajaxForm'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->webhook->name;?></th>
        <td><?php echo html::input('name', $webhook->name, "class='form-control' placeholder='{$lang->webhook->note->name}'");?></td>
        <td class='w-120px'></td>
      </tr>
      <tr>
        <th><?php echo $lang->webhook->url;?></th>
        <td><?php echo html::input('url', $webhook->url, "class='form-control' placeholder='{$lang->webhook->note->url}'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->webhook->requestType;?></th>
        <td><?php echo html::select('requestType', $config->webhook->requestType, $webhook->requestType, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webhook->params;?></th>
        <td><?php echo html::input('params', $webhook->params, "class='form-control' title='{$lang->webhook->note->params}' placeholder='{$lang->webhook->note->params}'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webhook->desc;?></th>
        <td><?php echo html::textarea('desc', $webhook->desc, "rows='3' class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton();?></td>
        <td></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
