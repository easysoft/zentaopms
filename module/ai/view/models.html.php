<?php
/**
 * The ai models view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <table class='table table-form mw-600px'>
    <tr>
      <th><?php echo $lang->ai->models->type;?></th>
      <td><?php echo zget($lang->ai->models->typeList, $modelConfig->type, $lang->ai->models->unconfigured);?></td>
    </tr>
    <tr>
      <th><?php echo $lang->ai->models->apiKey;?></th>
      <td><?php echo empty($modelConfig->key) ? $lang->ai->models->unconfigured : ("<code title='{$lang->ai->models->concealTip}'>" . substr_replace($modelConfig->key, '...', 4, strlen($modelConfig->key) - 8) . '</code>');?></td>
    </tr>
    <tr>
      <th><?php echo $lang->ai->models->proxyType;?></th>
      <td><?php echo zget($lang->ai->models->proxyTypes, $modelConfig->proxyType, $lang->ai->models->unconfigured);?></td>
    </tr>
    <?php if(!empty($modelConfig->proxyType)):?>
    <tr>
      <th><?php echo $lang->ai->models->proxyAddr;?></th>
      <td><?php echo empty($modelConfig->proxyAddr) ? $lang->ai->models->unconfigured : ("<code title='{$lang->ai->models->concealTip}'>" . substr_replace($modelConfig->proxyAddr, '://...', strpos($modelConfig->proxyAddr, ':')) . '</code>');?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->ai->models->description;?></th>
      <td><?php echo $modelConfig->description;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->statusAB;?></th>
      <td><?php echo zget($lang->ai->models->statusList, $modelConfig->status, $lang->ai->models->statusList['off']);?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'>
        <?php if(commonModel::hasPriv('ai', 'editmodel')) echo html::linkButton($lang->ai->models->edit, inlink('editmodel') . '#app=admin', 'self', '', 'btn btn-primary btn-wide');?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
