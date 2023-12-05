<?php
/**
 * The create view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<?php js::set('urlNote', $lang->webhook->note->typeList);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->create;?></h2>
    </div>
    <form id='webhookForm' method='post' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->webhook->type;?></th>
          <td style="width:550px" ><?php echo html::select('type', $lang->webhook->typeList, '', "class='form-control chosen'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->webhook->name;?></th>
          <td><?php echo html::input('name', '', "class='form-control'");?></td>
          <td></td>
        </tr>
        <tr id='urlTR'>
          <th><?php echo $lang->webhook->url;?></th>
          <td><?php echo html::input('url', '', "class='form-control'");?></td>
          <td id='urlNote'><?php echo $lang->webhook->note->typeList['default'];?></td>
        </tr>
        <tr id='secretTR'>
          <th><?php echo $lang->webhook->secret;?></th>
          <td><?php echo html::input('secret', '', "class='form-control'");?></td>
        </tr>
        <tr class='dinguserTR'>
          <th><?php echo $lang->webhook->dingAgentId;?></th>
          <td class='required'><?php echo html::input('agentId', '', "class='form-control'");?></td>
          <td><?php echo $lang->webhook->note->dingHelp;?></td>
        </tr>
        <tr class='dinguserTR'>
          <th><?php echo $lang->webhook->dingAppKey;?></th>
          <td class='required'><?php echo html::input('appKey', '', "class='form-control'");?></td>
        </tr>
        <tr class='dinguserTR'>
          <th><?php echo $lang->webhook->dingAppSecret;?></th>
          <td class='required'><?php echo html::input('appSecret', '', "class='form-control'");?></td>
        </tr>
        <tr class="wechatTR">
          <th><?php echo $lang->webhook->wechatCorpId;?></th>
          <td class='required'><?php echo html::input('wechatCorpId', '', "class='form-control'")?></td>
          <td><?php echo $lang->webhook->note->wechatHelp;?></td>
        </tr>
        <tr class="wechatTR">
          <th><?php echo $lang->webhook->wechatCorpSecret;?></th>
          <td class='required'><?php echo html::input('wechatCorpSecret', '', "class='form-control'")?></td>
          <td></td>
        </tr>
        <tr class="wechatTR">
          <th><?php echo $lang->webhook->wechatAgentId;?></th>
          <td class='required'><?php echo html::input('wechatAgentId', '', "class='form-control'")?></td>
          <td></td>
        </tr>
        <tr class='feishuTR'>
          <th><?php echo $lang->webhook->feishuAppId;?></th>
          <td class='required'><?php echo html::input('feishuAppId', '', "class='form-control'");?></td>
        </tr>
        <tr class='feishuTR'>
          <th><?php echo $lang->webhook->feishuAppSecret;?></th>
          <td class='required'><?php echo html::input('feishuAppSecret', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->webhook->domain;?></th>
          <td><?php echo html::input('domain', common::getSysURL(), "class='form-control'");?></td>
          <td></td>
        </tr>
        <tr id='sendTypeTR'>
          <th><?php echo $lang->webhook->sendType;?></th>
          <td><?php echo html::select('sendType', $lang->webhook->sendTypeList, '', "class='form-control'");?></td>
          <td><?php echo $lang->webhook->note->async;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->webhook->product;?></th>
          <td><?php echo html::select('products[]', $products, '', "class='form-control chosen' multiple");?></td>
          <td><?php echo $lang->webhook->note->product;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->webhook->execution;?></th>
          <td><?php echo html::select('executions[]', $executions, '', "class='form-control chosen' multiple");?></td>
          <td><?php echo $lang->webhook->note->execution;?></td>
        </tr>
        <tr id='paramsTR'>
          <th>
            <div class='checkbox-primary'>
              <input type='checkbox' id='allParams' name='allParams'>
              <label for='allParams'><?php echo $lang->webhook->params;?></label>
            </div>
          </th>
          <td class='labelWidth' colspan='2'><?php echo html::checkbox('params', $lang->webhook->paramsList, 'text');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->webhook->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows='3' class='form-control'");?></td>
        </tr>
        <tr>
          <th></th>
          <td colspan='2' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
