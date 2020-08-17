<?php include '../../../' . 'common/view/header.html.php';?>
<?php include '../lang/zh-cn/cmmi.php';?>
<?php js::set('linkType', '');?>
<?php js::set('viewMode', 'view');?>

<?php
$productID = $data->product;
$data->product = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('name');

$this->loadModel('design');
$relations = $this->loadModel('common')->getRelations('design', $data->id, 'commit');
$data->commit = '';
foreach($relations as $relation) $data->commit .= html::a(helper::createLink('design', 'revision', "repoID=$relation->BID", '', true), "#$relation->BID", '', "class='iframe' data-width='80%' data-height='550'");

$storyTitle  = $this->dao->findByID($data->story)->from(TABLE_STORY)->fetch('title');
$data->story = $storyTitle ? html::a($this->createLink('story', 'view', "id=$data->story"), $storyTitle) : '';

$showAction = empty($_GET['onlybody']) ? 'show' : 'hidden';
js::set('showAction', $showAction);
if(!empty($_GET['onlybody']))
{
    $data->commit = '';
    foreach($relations as $relation) $data->commit .= " #$relation->BID";
    $data->story = $storyTitle;
}
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('design', 'browse', "productID=$productID"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $data->id?></span>
      <span class="text" title="<?php echo $data->name;?>"><?php echo $data->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->design->desc;?></div>
        <div class="detail-content article-content">
          <?php echo $data->desc;?>
        <?php echo $this->fetch('file', 'printFiles', array('files' => $data->files, 'fieldset' => 'false'));?>
        </div>
      </div>
    </div>
    <?php $actions = $this->loadModel('action')->getList($flow->module, $data->id);?>
    <div class='cell'><?php include '../../../common/view/action.html.php';?></div>
    <?php echo $this->flow->buildOperateMenu($flow, $data, $type = 'view');?>
  </div>
  <div class='side-col col4'>
    <div class='cell'>
      <div class="detail">
        <table class='table table-data'>
          <tr>
            <th><?php echo $lang->design->type;?></th>
            <td><?php echo zget($fields['type']->options, $data->type);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->product;?></th>
            <td><?php echo $data->product;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->story;?></th>
            <td><?php echo $data->story;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->commit;?></th>
            <td><?php echo $data->commit;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->createdBy;?></th>
            <td><?php echo zget($users, $data->createdBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->createdDate;?></th>
            <td><?php echo $data->createdDate;?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
$('.main-actions').width($('.main-col').outerWidth());
if(showAction == 'hidden')
{
    $('.main-actions').remove();
    $('.pull-left').children('a').remove();
};
</script>
<?php include '../../../' . 'common/view/footer.html.php';?>
