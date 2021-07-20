<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs>li {padding: 0px 10px; display: inline-block}
#navTabs>li>span {display: inline-block;}
#navTabs>li>a {padding: 8px 0px; display: inline-block}
#navTabs>li.active>a {font-weight: 700; color: #0c64eb;}
#navTabs>li.active>a:before {position: absolute; right: 0; bottom: -1px; left: 0; display: block; height: 2px; content: ' '; background: #0c64eb; }
#navTabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border: none;}

#tabContent {margin-top: 10px; z-index: 900;}
#tabContent ul {list-style: none; margin: 0}
#tabContent .tab-pane>ul {padding-left: 7px;}
#tabContent .tab-pane>ul>li.hide-in-search {position: relative;}
#tabContent .tab-pane>ul>li>label+a {padding-left: 55px;}
#tabContent .tab-pane>ul>li label {background: rgba(131,138,157,0.5); position: absolute; top: 0; left: 5px;}
#tabContent li a i.icon {font-size: 15px !important;}
#tabContent li a i.icon:before {min-width: 16px !important;}
#tabContent li .label {margin-top: 2px; position: unset;}
#tabContent li ul {padding-left: 15px;}
#tabContent li>a {margin-top: 5px;display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
#tabContent li>a.selected {color: #e9f2fb; background-color: #0c64eb;}

#swapper li.hide-in-search>a:focus, #swapper li.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li ul li a:focus, #swapper li ul li a:hover, .noProgram li a:focus, .noProgram li a:hover {background: #0c64eb; color: #fff;}
</style>
<?php
$productCounts      = array();
$productNames       = array();
$myProductsHtml     = '';
$normalProductsHtml = '';
$closedProductsHtml = '';
$tabActive          = '';
$myProducts         = 0;
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
    if($programID and $config->systemMode == 'new')
    {
        $programName = zget($programs, $programID);

        if($productCounts[$programID]['myProduct']) $myProductsHtml  .= '<ul><li class="hide-in-search"><label class="label">' . $lang->program->common . '</label> <a class="text-muted" title="' . $programName . '">' . $programName . '</a></li><li><ul>';
        if($productCounts[$programID]['others']) $normalProductsHtml .= '<ul><li class="hide-in-search"><label class="label">' . $lang->program->common . '</label> <a class="text-muted" title="' . $programName . '">' . $programName . '</a></li><li><ul>';
    }
    else
    {
        if($productCounts[$programID]['myProduct']) $myProductsHtml     .= '<ul class="noProgram">';
        if($productCounts[$programID]['others'])    $normalProductsHtml .= '<ul class="noProgram">';
    }

    foreach($programProducts as $index => $product)
    {
        $selected    = $product->id == $productID ? 'selected' : '';
        $productName = $product->line ? zget($lines, $product->line, '') . '/' . $product->name : $product->name;
        $linkHtml    = $this->product->setParamsForLink($module, $link, $projectID, $product->id);

        if($product->status == 'normal' and $product->PO == $this->app->user->account)
        {
            $myProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'") . '</li>';

            if($selected == 'selected') $tabActive = 'myProduct';

            $myProducts++;
        }
        else if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            $normalProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others++;
        }
        else if($product->status == 'closed')
        {
            $closedProductsHtml .= html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'");

            if($selected == 'selected') $tabActive = 'closed';
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
      <?php $tabActive = ($myProducts and ($tabActive == 'closed' or $tabActive == 'myProduct')) ? 'myProduct' : 'other';?>
      <?php if($myProducts): ?>
      <ul class="nav nav-tabs" id="navTabs">
        <li class="<?php if($tabActive == 'myProduct') echo 'active';?>"><?php echo html::a('#myProduct', $lang->product->mine, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="label label-light label-badge"><?php echo $myProducts;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->product->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="label label-light label-badge"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content" id="tabContent">
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

    $('.nav-tabs>li a').click(function()
    {
        $(this).siblings().show();
        $(this).parent().siblings('li').find('span').hide();
        if($(this).attr('class') != 'active') $('#dropMenu').removeClass('show-right-col');
        $("#dropMenu .search-box").width('auto');
    })
})
</script>
