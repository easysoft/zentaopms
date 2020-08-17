<?php
/**
 * The browse view file of flow module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php
if(!empty($dataList))
{
    foreach($dataList as $data)
    {   
        $relations = $this->loadModel('common')->getRelations('design', $data->id, 'commit');
        $data->commit = ''; 
        foreach($relations as $relation) $data->commit .= html::a(helper::createLink('design', 'revision', "repoID=$relation->BID", '', true), "#$relation->BID", '', "class='iframe' data-width='80%' data-height='550'");
    }   
}
?>
<?php include '../../../' . 'common/view/header.html.php';?>
<style>
#featurebar{display:inline-block;}
.modal-dialog{width:70% !important}
table td{white-space:nowrap;text-overflow:ellipsis;overflow:hidden;}
</style>
<?php $needOrder = ($flow->module == 'process' || $flow->module == 'activity' || $flow->module == 'output');?>
<?php if($needOrder):?>
<?php include '../../../common/view/sortable.html.php';?>
<?php endif;?>
<?php js::set('productID', $productID);?>
<?php js::set('mode', $mode);?>
<?php js::set('module', $flow->module);?>
<?php js::set('label', $label);?>
<?php
$showSubHeader = $program->category == 'single' ? 'hidden' : 'show';
js::set('showSubHeader', $showSubHeader);
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-right'>
    <?php
    if(common::hasPriv('design', 'submit')) echo html::a($this->createLink('design', 'submit', "productID=$productID&designID=0&allLabel=$allLabel&designType=$designType"), "<i class='icon icon-plus'></i> {$lang->design->submit}", '', "class='btn btn-secondary iframe' data-width='30%' id='toReview'");
    echo $this->flow->buildOperateMenu($flow, $data = null, $type = 'menu');
    ?>
  </div>
</div>
<div id="mainContent" class="main-row fade in">
  <div class="main-col">
    <div class='main-table' data-ride='table'>
      <?php if($batchActions && $dataList):?>
      <form id='batchOperateForm' method='post'>
      <?php endif;?>
      <table class='table has-sort-head' id="<?php echo $flow->module;?>Table">
        <thead>
          <tr class='text-center'>
            <?php $vars = "productID=$productID&mode=$mode&label=$label&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
            <?php $index = 1;?>
            <?php foreach($fields as $field):?>
            <?php if(!$field->show) continue;?>
            <?php $width = $field->width && $field->width != 'auto' ? $field->width . 'px' : $field->width;?>
            <th class="text-<?php echo $field->position;?>" style="width:<?php echo $width;?>">
              <?php if($field->field == 'id' && $allLabel == 0):?>
              <div class='checkbox-primary check-all' title='<?php echo $this->lang->selectAll;?>'><label></label></div>
              <?php endif;?>
              <?php
              if($field->field == 'desc' || $field->field == 'asc' || $field->field == 'actions')
              {
                  echo $field->name;
              }
              else
              {
                  commonModel::printOrderLink($field->field, $orderBy, $vars, $field->name, $flow->module, 'browse');
              }
              ?>
            </th>
            <?php $index++;?>
            <?php endforeach;?>
            <?php if($needOrder):?>
            <th class='w-60px sort-default text-left'><?php echo $lang->flow->orderAB;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody <?php if($needOrder) echo "class='sortable' id='orderTableList'"?>>
          <?php foreach($dataList as $data):?>
          <tr <?php if($needOrder) echo "data-id=$data->id data-order=$data->order"?>>
            <?php $index = 1;?>
            <?php foreach($fields as $field):?>
            <?php if(!$field->show || $field->field == 'actions') continue;?>
            <?php
            $output = '';
            if(is_array($data->{$field->field}))
            {
                foreach($data->{$field->field} as $value) $output .= zget($field->options, $value) . ' ';
            }
            else
            {
                if($field->field == 'id')
                {
                    if(commonModel::hasPriv($flow->module, 'view'))
                    {
                        $output = baseHTML::a(helper::createLink($flow->module, 'view', "dataID={$data->id}"), $data->id);
                    }
                    else
                    {
                        $output = $data->id;
                    }
                }
                elseif($field->field == 'name')
                {
                    $output = html::a(helper::createLink($flow->module, 'view', "id={$data->id}"), $data->{$field->field});
                }
                else
                {
                    $output = zget($field->options, $data->{$field->field});
                }
            }
            ?>
            <?php if($field->field == 'id' && $allLabel == 0):?>
              <td><?php echo html::checkbox('auditIDList', array($data->id => '')) . html::a($this->createLink('design', 'view', "id=$data->id"), sprintf('%03d', $data->id))?></td>
            <?php else:?>
              <td class="text-<?php echo $field->position;?>" title='<?php echo strip_tags(str_replace("</p>", "\n", str_replace(array("\n", "\r"), "", $output)));?>'>
                <?php if($index == 1 && $batchActions):?>
                <div class='checkbox-primary'><input type='checkbox' name='dataIDList[]' value='<?php echo $data->id;?>' id='dataIDList<?php echo $data->id;?>'>
                  <label for='dataIDList<?php echo $data->id;?>'></label>
                </div>
                <?php endif;?>
                <?php echo $output;?>
              </td>
            <?php endif;?>
            <?php $index++;?>
            <?php endforeach;?>
            <td class="nowrap text-center"><?php echo $this->flow->buildOperateMenu($flow, $data, $type = 'browse');?></td>
            <?php if($needOrder):?>
            <td class='sort-handler'><i class="icon icon-move"></i></td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($batchActions && $dataList):?>
        <div class='checkbox-primary check-all'><label><?php echo $lang->selectAll?></label></div>
        <div class='table-actions btn-toolbar'>
          <?php echo $batchActions;?>
        </div>
        <?php endif;?>
        <?php if($summary):?>
        <div class='table-statistic'>
          <?php echo $lang->workflowlayout->totalShow . '(' . rtrim($summary, ',') . ')';?>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php if($batchActions && $dataList):?>
      </form>
      <?php endif;?>
    </div>
  </div>
</div>
<?php 
js::set('orderBy', $orderBy);
js::set('designCreateLink', $this->createLink('design', 'create', "product=$productID"));
?>
<script>
$('#mainMenu .pull-right a[href*="batchcreate"]').remove();
link = designCreateLink;
$('#mainMenu .pull-right a[href*="create"]').attr('href', link);

$('#toReview').click(function()
{
    var designID = '';
    $('input:checkbox[name="auditIDList[]"]:checked').each(function(i)
    {
        if(i == 0)
        {
            designID = $(this).val();
        }
        else
        {
            designID += (',' + $(this).val());
        }
    });
    $.cookie('submitDesignID', designID, { expires: 1, path: '/' });
});

if(showSubHeader == 'hidden') $("#subHeader").remove();
</script>
<?php include '../../../' . 'common/view/footer.html.php';?>
