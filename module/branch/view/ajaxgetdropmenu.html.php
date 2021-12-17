<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<?php
$activeBranchesHtml = '';
$closedBranchesHtml = '';
$currentBranchID    = $currentBranchID === '' ? '0' : $currentBranchID;
foreach($branches as $branchID => $branch)
{
    $selected = (string)$branchID == $currentBranchID ? 'selected' : '';
    $linkHtml = $this->branch->setParamsForLink($module, $link, $projectID, $productID, $branchID);

    if($branchID == 'all' or empty($branchID) or $statusList[$branchID] == 'active')
    {
        $activeBranchesHtml .= html::a($linkHtml, $branch, '', "class='$selected' data-key='{$branchesPinyin[$branch]}' data-app='{$this->app->tab}'");
    }
    else
    {
        $closedBranchesHtml .= html::a($linkHtml, $branch, '', "class='$selected' data-key='{$branchesPinyin[$branch]}' data-app='{$this->app->tab}'");
    }
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php echo $activeBranchesHtml;?>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->branch->closed;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
    <div class='list-group'><?php echo $closedBranchesHtml;?></div>
  </div>
</div>
<script>
$(function()
{
    $('#currentBranch + #dropMenu .list-group .table-row .table-col a').mouseout(function()
    {
        $(this).removeClass('active');
    })
})
</script>
