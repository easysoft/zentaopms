<?php
/**
 * The create view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('noProject', ($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') ? true : false);?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->product->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->product->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control input-product-title' autocomplete='off' required");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->code;?></th>
            <td><?php echo html::input('code', '', "class='form-control input-product-code' autocomplete='off' required");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->line;?></th>
            <td>
              <div class='input-group' id='lineIdBox'>
                <?php
                echo html::select('line', $lines, '', "class='form-control chosen'");
                if(count($lines) == 1)
                {
                    echo "<span class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$rootID&view=line", '', true), $lang->tree->manageLine, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                    echo '&nbsp; ';
                    echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductLines($rootID)'");
                    echo '</span>';
                }
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->product->PO;?></th>
            <td><?php echo html::select('PO', $poUsers, $this->app->user->account, "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->QD;?></th>
            <td><?php echo html::select('QD', $qdUsers, '', "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->RD;?></th>
            <td><?php echo html::select('RD', $rdUsers, '', "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->type;?></th>
            <td>
              <?php
              $proudctTypeList = array();
              foreach($lang->product->typeList as $key => $type) $productTypeList[$key] = $type . zget($lang->product->typeTips, $key, '');
              ?>
              <?php echo html::select('type', $productTypeList, 'normal', "class='form-control'");?>
            </td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='8' class='form-control kindeditor' hidefocus='true' tabindex=''");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->acl;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->product->aclList, 'open', "onclick='setWhite(this.value);'", 'block'));?></td>
          </tr>  
          <tr id='whitelistBox' class='hidden'>
            <th><?php echo $lang->product->whitelist;?></th>
            <td colspan='2'><?php echo html::checkbox('whitelist', $groups, '', '', 'inline');?></td>
          </tr>  
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
