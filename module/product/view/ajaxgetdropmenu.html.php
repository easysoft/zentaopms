<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs > li {padding: 0px 10px; display: inline-block}
#navTabs > li > span {display: inline-block;}
#navTabs > li > a {margin: 0!important; padding: 8px 0px; display: inline-block}

#tabContent {margin-top: 5px; z-index: 900; max-width: 220px}
.productTree ul {list-style: none; margin: 0}
.productTree .products>ul {padding-left: 7px;}
.productTree .products>ul > li > div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.productTree .products>ul > li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
.productTree li a i.icon {font-size: 15px !important;}
.productTree li a i.icon:before {min-width: 16px !important;}
.productTree li .label {position: unset; margin-bottom: 0;}
.productTree li > a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.productTree .tree li > .list-toggle {line-height: 24px;}
.productTree .tree li.has-list.open:before {content: unset;}
.tree.noProgram li {padding-left: 0;}

#swapper li > div.hide-in-search>a:focus, #swapper li > div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li > .list-toggle {top: -1px;}

#subHeader .tree ul {display: block;}
div#closed {width: 90px; height: 25px; line-height: 25px; background-color: #ddd; color: #3c495c; text-align: center; margin-left: 15px; border-radius: 2px;}
#gray-line {width: 230px; height: 1px; margin-left: 10px; margin-bottom:2px; background-color: #ddd;}
#swapper li >.selected {color: #0c64eb!important;background: #e9f2fb!important;}
#dropMenu .col-footer .selected{color: #2e7fff!important;background: #e6f0ff!important; padding: 1px 10px;border-radius: 4px;}
</style>
<?php
$productCounts      = array();
$productNames       = array();
$tabActive          = '';
$myProducts         = 0;
$others             = 0;
$closeds            = 0;
$currentProduct     = '';

foreach($products as $programID => $programProducts)
{
    $productCounts[$programID]['myProduct'] = 0;
    $productCounts[$programID]['others']    = 0;
    $productCounts[$programID]['closed']    = 0;

    foreach($programProducts as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) $productCounts[$programID]['myProduct'] ++;
        if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $productCounts[$programID]['others'] ++;
        if($product->status == 'closed') $productCounts[$programID]['closed'] ++;
        $productNames[] = $product->name;
    }
}
$productsPinYin = common::convert2Pinyin($productNames);

$myProductsHtml     = in_array($config->systemMode, array('ALM', 'PLM')) ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="tree noProgram">';
$normalProductsHtml = in_array($config->systemMode, array('ALM', 'PLM')) ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="tree noProgram">';
$closedProductsHtml = in_array($config->systemMode, array('ALM', 'PLM')) ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="tree noProgram">';

foreach($products as $programID => $programProducts)
{
    /* Add the program name before project. */
    if($programID and in_array($config->systemMode, array('ALM', 'PLM')))
    {
        $programName = zget($programs, $programID);

        if($productCounts[$programID]['myProduct']) $myProductsHtml  .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $programName . '">' . $programName . '</a> <label class="label">' . $lang->program->common . '</label></div><ul>';
        if($productCounts[$programID]['others']) $normalProductsHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $programName . '">' . $programName . '</a> <label class="label">' . $lang->program->common . '</label></div><ul>';
        if($productCounts[$programID]['closed']) $closedProductsHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $programName . '">' . $programName . '</a> <label class="label">' . $lang->program->common . '</label></div><ul>';
    }

    foreach($programProducts as $index => $product)
    {
        if($product->id == $productID) $currentProduct = $product;
        $selected    = $product->id == $productID ? 'selected' : '';
        $productName = (in_array($config->systemMode, array('ALM', 'PLM')) and $product->line) ? zget($lines, $product->line, '') . ' / ' . $product->name : $product->name;
        $linkHtml    = $this->product->setParamsForLink($module, $link, $projectID, $product->id);
        $locateTab   = ($module == 'testtask' and $method == 'browseUnits' and $app->tab == 'project') ? '' : "data-app='$app->tab'";

        if($product->status == 'normal' and $product->PO == $this->app->user->account)
        {
            $myProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected clickable' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$app->tab'") . '</li>';

            if($selected == 'selected') $tabActive = 'myProduct';

            $myProducts ++;
        }
        else if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            $normalProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected clickable' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$app->tab'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others ++;
        }
        else if($product->status == 'closed')
        {
            $closedProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected clickable' title='$productName' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$app->tab'") . '</li>';

            if($selected == 'selected') $tabActive = 'closed';
        }

        /* If the programID is greater than 0, the product is the last one in the program, print the closed label. */
        if($programID and !isset($programProducts[$index + 1]))
        {
            if($productCounts[$programID]['myProduct']) $myProductsHtml     .= '</ul></li>';
            if($productCounts[$programID]['others'])    $normalProductsHtml .= '</ul></li>';
            if($productCounts[$programID]['closed'])    $closedProductsHtml .= '</ul></li>';
        }
    }
}
$myProductsHtml     .= '</ul>';
$normalProductsHtml .= '</ul>';
$closedProductsHtml .= '</ul>';
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php $tabActive = ($myProducts and ($tabActive == 'closed' or $tabActive == 'myProduct')) ? 'myProduct' : 'other';?>
      <?php if($myProducts): ?>
      <ul class="nav nav-tabs  nav-tabs-primary" id="navTabs">
        <li class="<?php if($tabActive == 'myProduct') echo 'active';?>"><?php echo html::a('#myProduct', $lang->product->mine, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="label label-light label-badge"><?php echo $myProducts;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->product->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="label label-light label-badge"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content productTree" id="tabContent">
        <div class="tab-pane products <?php if($tabActive == 'myProduct') echo 'active';?>" id="myProduct">
          <?php echo $myProductsHtml;?>
        </div>
        <div class="tab-pane products <?php if($tabActive == 'other') echo 'active';?>" id="other">
          <?php echo $normalProductsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <?php //echo html::a(helper::createLink('product', 'all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->product->all, '', 'class="not-list-item"'); ?>
      <?php //echo html::a(helper::createLink('project', 'browse', 'programID=0&browseType=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->product->closed?><i class='icon icon-angle-right'></i></a>
      <?php if($this->app->tab == 'feedback'):?>
      <?php $selected = $productID == 'all' ? 'selected' : '';?>
      <?php if($module == 'feedback'):?>
      <?php echo html::a(helper::createLink('feedback', 'admin', 'browseType=byProduct&param=all'), $lang->product->all, '', "class='not-list-item pull-left toggle-left-col $selected'"); ?>
      <?php endif;?>
      <?php if($module == 'ticket'):?>
      <?php echo html::a(helper::createLink('ticket', 'browse', 'browseType=byProduct&param=all'), $lang->product->all, '', "class='not-list-item pull-left toggle-left-col $selected'"); ?>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
  <div id="gray-line" hidden></div>
  <div id="closed" hidden><?php echo $lang->product->closedProduct?></div>
  <div class="table-col col-right productTree">
   <div class='list-group products'><?php echo $closedProductsHtml;?></div>
  </div>
</div>
<script>
$(function()
{
    <?php if($currentProduct and $currentProduct->status == 'closed'):?>
    $('.col-footer .toggle-right-col').click(function(){ scrollToSelected(); })
    <?php else:?>
    scrollToSelected();
    <?php endif;?>

    $('.nav-tabs li span').hide();
    $('.nav-tabs li.active').find('span').show();

    $('.nav-tabs > li a').click(function()
    {
        if($('#swapper input[type="search"]').val() == '')
        {
            $(this).siblings().show();
            $(this).parent().siblings('li').find('span').hide();
        }
    })

    $('#swapper [data-ride="tree"]').tree('expand');

    $('#swapper #dropMenu .search-box').on('onSearchChange', function(event, value)
    {
        if(value != '')
        {
            $('div.hide-in-search').siblings('i').addClass('hide-in-search');
            $('.nav-tabs li span').hide();
        }
        else
        {
            $('div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('li.has-list div.hide-in-search').removeClass('hidden');
            $('.nav-tabs li.active').find('span').show();
        }
        if($('.form-control.search-input').val().length > 0)
        {
            $('#closed').attr("hidden", false);
            $('#gray-line').attr("hidden", false);
        }
        else
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }
    });

    $('#swapper #dropMenu').on('onSearchComplete', function(event, value)
    {
        if(!value) return;

        if($("#myProduct .clickable.search-list-item").not(".hidden").length > 0)
        {
            $("#navTabs a[href='#myProduct']").tab('show');
        }
        else if($("#other .clickable.search-list-item").not(".hidden").length > 0)
        {
            $("#navTabs a[href='#other']").tab('show');
        }
        if($('ul.tree-angles').height() == 0)
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }
    });
})
</script>
