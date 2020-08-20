<?php include '../../' . 'common/view/header.html.php';?>
<?php
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
          <?php echo $this->fetch('file', 'printFiles', array('files' => $data->files, 'fieldset' => 'true'));?>
        </div>
      </div>
    </div>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($this->session->designList);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$data->deleted):?>
        <?php
        common::printIcon('design', 'commit',"designID=$data->id", $data, 'button', 'link', '', 'iframe showinonlybody', true);
        common::printIcon('design', 'edit', "designID=$data->id", $data, 'button', '', '', '', true);
        common::printIcon('design', 'delete', "designID=$data->id", $data, 'button', 'trash', 'hiddenwin');
        ?>
            <?php endif;?>
      </div>
    </div>
  </div>
  <div class='side-col col4'>
    <div class='cell'>
      <div class="detail">
        <table class='table table-data'>
          <tr>
            <th><?php echo $lang->design->type;?></th>
            <td><?php echo zget($lang->design->typeList, $data->type);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->product;?></th>
            <td><?php echo $data->productName;?></td>
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
<?php include '../../' . 'common/view/footer.html.php';?>
