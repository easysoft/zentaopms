<?php
/**
 * The close file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: cancel.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.table-form  tr:first-child td:last-child{width:2%;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span title='<?php echo $lang->zanode->automation;?>'><?php echo $lang->zanode->automation;?>&nbsp;<icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='bottom' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->zanode->automationTips;?>"></icon></span>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <?php if(!$productID):?>
        <tr>
          <th class='w-100px'><?php echo $lang->testcase->product;?></th>
          <td class='required'><?php echo html::select('product', $products, '', "class='form-control picker-select' onchange='loadProduct(this.value)'");?></td>
          <td></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-100px'><?php echo $lang->zanode->common;?></th>
          <td class='required' id='nodeIdBox'><?php echo html::select('node', $nodeList, !empty($automation->node) ? $automation->node : '', "class='form-control picker-select'");?></td>
          <td>
            <?php echo html::a($this->createLink('zanode', 'create'), $lang->zanode->create, '', "class='text-primary' target='_blank'");?>
            <?php echo html::a("javascript:void(0)", "<i class='icon icon-refresh'></i>", '', "class='btn btn-icon refresh' data-toggle='tooltip' title='{$lang->refresh}' onclick='loadNodes()'");?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th>
            <?php echo $lang->zanode->scriptPath;?>
          </th>
          <td class='required'><?php echo html::input('scriptPath', !empty($automation->scriptPath) ? $automation->scriptPath : '', "class='form-control' placeholder='{$lang->zanode->scriptTips}'");?></td>
          <td>
          </td>
        </tr>
        <!-- <tr>
          <th></th>
          <td><?php echo html::checkbox('syncToZentao', array(1 => $lang->zanode->syncToZentao), '');?></td>
        </tr> -->
        <tr>
          <th>
            <?php echo $lang->zanode->shell;?>
          </th>
          <td colspan='2'><?php echo html::textarea('shell', !empty($automation->shell) ? $automation->shell : '', "rows='6' class='form-control' placeholder='{$lang->zanode->shellTips}'");?></td>
          <td>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'>
            <?php if($productID) echo html::hidden('product', $productID);?>
            <?php if($automation) echo html::hidden('id', $automation->id);?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$('[data-toggle="popover"]').popover();

$(function()
{
    if($("#product").length > 0){
        $('#product').change();
    }
})

function loadProduct(obj)
{
    $('#node').data('zui.picker').setValue(<?php echo key($nodeList)?>);
    $('#shell').val('');
    $('#scriptPath').val('');
    var url = createLink('zanode', 'ajaxGetZTFScript', "type=product&objectID=" + obj)
    $.get(url, function(result)
    {
        if(result.result == 'success')
        {
            data = result.data;
            if(!data) return false;
            $('#node').data('zui.picker').setValue(data.node)
            $('#shell').val(data.shell);
            $('#scriptPath').val(data.scriptPath);
            $('#submit').before("<input type='hidden' name='id' value='" + data.id + "'>");
        }
    }, 'json');
}
</script>
<?php include '../../common/view/footer.html.php';?>
