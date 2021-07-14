<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
.table-row .table-col .list-group .nav-tabs {position: sticky; top: 0; background: #fff; z-index: 950;}
.table-row .table-col .list-group .nav-tabs>li>span {display: inline-block; margin-left: -6px;}
.table-row .table-col .list-group .nav-tabs>li>a {padding: 8px 10px; display: inline-block}
.table-row .table-col .list-group .nav-tabs>li.active>a, .nav-tabs>li.active>span {font-weight: 700; color: #0c64eb;}
.table-row .table-col .list-group .nav-tabs>li.active>a:before {position: absolute; right: 0; bottom: -1px; left: 0; display: block; height: 2px; content: ' '; background: #0c64eb; }
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border: none;}

.table-row .table-col .list-group .tab-content {margin-top: 10px;}
.table-row .table-col .list-group .tab-content ul {list-style: none; margin: 0}
.table-row .table-col .list-group .tab-content .tab-pane>ul {padding-left: 7px;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li.hide-in-search {position: relative;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li a {padding-left: 5px; padding-right: 45px;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li label {background: rgba(131,138,157,0.5); position: absolute; top: 0; right: 0;}
.table-row .table-col .list-group .tab-content li a i.icon {font-size: 15px !important;}
.table-row .table-col .list-group .tab-content li a i.icon:before {min-width: 16px !important;}
.table-row .table-col .list-group .tab-content li .label {margin-top: 2px; position: unset;}
.table-row .table-col .list-group .tab-content li ul {padding-left: 15px;}
.table-row .table-col .list-group .tab-content li>a {margin-top: 5px;display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.table-row .table-col .list-group .tab-content li>a.selected {color: #e9f2fb; background-color: #0c64eb;}
</style>
<?php
$productCounts      = array();
$productNames       = array();
$myProductsHtml     = '';
$normalProductsHtml = '';
$closedProductsHtml = '';
$tabActive          = '';
$iCharges           = 0;
$others             = 0;
$closeds            = 0;

foreach($products as $programID => $programProducts)
{

    $productCounts[$programID]['myProduct'] = 0;
    $productCounts[$programID]['others']    = 0;

    foreach($programProducts as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) $productCounts[$programID]['myProduct']++;
        if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $productCounts[$programID]['others']++;
        if($product->status == 'closed') $closeds++;
        $productNames[] = $product->name;
    }
}
$productsPinYin = common::convert2Pinyin($productNames);

foreach($products as $programID => $programProducts)
{
    /* Add the program name before project. */
    if($programID)
    {
        if($productCounts[$programID]['myProduct']) $myProductsHtml .= '<ul><li class="hide-in-search"><a class="text-muted">' . zget($programs, $programID) . '</a> <label class="label">' . $lang->program->common . '</label></li><li><ul>';
        if($productCounts[$programID]['others']) $normalProductsHtml .= '<ul><li class="hide-in-search"><span class="text-muted">' . zget($programs, $programID) . '</span> <label class="label">' . $lang->program->common . '</label></li><li><ul>';
    }
    else
    {
        if($productCounts[$programID]['myProduct']) $myProductsHtml     .= '<ul>';
        if($productCounts[$programID]['others'])    $normalProductsHtml .= '<ul>';
    }

    foreach($programProducts as $index => $product)
    {
        $selected    = $product->id == $productID ? 'selected' : '';
        $productName = $product->line ? zget($lines, $product->line, '') . '/' . $product->name : $product->name;
        $linkHtml    = $this->product->setParamsForLink($module, $link, $projectID, $product->id);

        if($product->status == 'normal' and $product->PO == $this->app->user->account)
        {
            $myProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='text-muted $selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'") . '</li>';

            if($selected == 'selected') $tabActive = 'myProduct';

            $iCharges++;
        }
        else if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            $normalProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='text-muted $selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others++;
        }
        else if($product->status == 'closed')
        {
            $closedProductsHtml .= html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'");
        }

        /* If the programID is greater than 0, the product is the last one in the program, print the closed label. */
        if($programID and !isset($programProducts[$index + 1]))
        {
            if($productCounts[$programID]['myProduct']) $myProductsHtml     .= '</ul></li>';
            if($productCounts[$programID]['others'])    $normalProductsHtml .= '</ul></li>';
        }
    }

    if($productCounts[$programID]['myProduct']) $myProductsHtml     .= '</ul>';
    if($productCounts[$programID]['others'])    $normalProductsHtml .= '</ul>';
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php if($iCharges): ?>
      <?php $tabActive = ($tabActive == '' or $tabActive == 'myProduct') ? 'myProduct' : 'other';?>
      <ul class="nav nav-tabs">
        <li class="<?php if($tabActive == 'myProduct') echo 'active';?>"><?php echo html::a('#myProduct', $lang->product->mine, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="text-muted"><?php echo $iCharges;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->product->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="text-muted"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content">
        <div class="tab-pane <?php if($tabActive == 'myProduct') echo 'active';?>" id="myProduct">
          <?php echo $myProductsHtml;?>
        </div>
        <div class="tab-pane <?php if($tabActive == 'other') echo 'active';?>" id="other">
          <?php echo $normalProductsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <?php //echo html::a(helper::createLink('product', 'all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->product->all, '', 'class="not-list-item"'); ?>
      <?php //echo html::a(helper::createLink('project', 'browse', 'programID=0&browseType=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->product->closed?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'><?php echo $closedProductsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
<script>
$(function()
{
    $('.nav-tabs li span').hide();
    $('.nav-tabs li.active').find('span').show();

    $('.nav-tabs>li').click(function()
    {
        $(this).find('span').show();
        $(this).siblings('li').find('span').hide();
    })
})
</script>
