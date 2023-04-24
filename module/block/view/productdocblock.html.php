<style>
.block-productdoc .nav-stacked {overflow:auto; height:220px; max-height:220px; }
.block-productdoc .panel-heading {border-bottom:1px solid #ddd;}
.block-productdoc .panel-body {padding-top: 0; height:240px; padding-right:0px; padding-bottom:0px;}
.block-productdoc .tab-content {padding-right:0px;}
.block-productdoc .tab-pane {max-height:220px; overflow:auto;}
.block-productdoc table.tablesorter th{border-bottom:0px !important;}
.block-productdoc .tile {margin-bottom: 30px;}
.block-productdoc .tile-title {font-size: 18px; color: #A6AAB8;}
.block-productdoc .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-productdoc .col-nav {border-right: 1px solid #EBF2FB; width: 210px; padding: 5px 0 0 0; }
.block-productdoc .nav-secondary > li {position: relative;}
.block-productdoc .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-productdoc .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-productdoc .nav-secondary > li.active > a:first-child {color: #3C4353; box-shadow: none;}
.block-productdoc .nav-secondary > li.active > a:first-child:hover,
.block-productdoc .nav-secondary > li.active > a:first-child:focus,
.block-productdoc .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-productdoc .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-productdoc .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-productdoc .nav-secondary > li:hover > a.btn-view {opacity: 1;}
.block-productdoc .nav-secondary > li.active > a.btn-view {box-shadow: none;}
.block-productdoc .nav-secondary > li.switch-icon {display: none;}
.block-productdoc.block-sm .nav-stacked {height:auto;}
.block-productdoc.block-sm .panel-body {position: relative; border-radius: 3px; height:275px; padding: 45px 8px 10px 8px}
.block-productdoc.block-sm .panel-body > .table-row,
.block-productdoc.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-productdoc.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-productdoc.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-productdoc.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-productdoc.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5; padding: 0px; margin-top:5px;}
.block-productdoc.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-productdoc.block-sm .nav-secondary > li {display: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon,
.block-productdoc.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-productdoc.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0, 0, 0, 0.07);}
.block-productdoc.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-productdoc.block-sm .nav-secondary > li > a:before {display: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-productdoc.block-sm .nav-secondary > li.active > a:first-child:before {display: none}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.1);}

.block-productdoc .data {width: 40%; text-align: left; padding: 10px 0px; font-size: 14px; font-weight: 700;}
.block-productdoc .dataTitle {width: 60%; text-align: right; padding: 10px 0px; font-size: 14px;}
.block-productdoc .executionName {padding: 2px 10px; font-size: 14px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;}
.block-productdoc .lastIteration {padding-top: 6px;}

.forty-percent {width: 40%;}
html[lang="de"] .block-productdoc .c-user{width: 105px;}

.block-productdoc #productType {position: absolute;top: 8px;left: 120px;}
.block-productdoc #productType .btn {border:0px;}
.block-productdoc #productType .btn:hover {background-color:#f5f5f5;}

.block-productdoc .table .c-title > .doc-title {display: inline-block; overflow: hidden; background: transparent; padding-right:0px;}
.block-productdoc .table .c-title > span.doc-title {line-height: 0; vertical-align: inherit;}
.block-productdoc .table .c-title[data-status=draft] > .doc-title {max-width: calc(100% - 35px);}
.block-productdoc .table .c-title > .draft {background-color:rgba(129, 102, 238, 0.12); color:#8166EE;}
</style>
<?php $blockNavId = 'nav-' . uniqid(); ?>
<?php js::set('emptyProducts', empty($products));?>
<?php js::set('emptyInvolveds', empty($involveds));?>
<script>
$(function()
{
    if($('.block-productdoc<?php echo "#block{$block->id}";?> #productType').length > 1);
    {
        count = $('.block-productdoc<?php echo "#block{$block->id}";?> #productType').length;
        $('.block-productdoc<?php echo "#block{$block->id}";?> #productType').each(function()
        {
            if(count == 1) return;
            $(this).remove();
            count --;
        })
    }
});

function switch<?php echo "block{$block->id}";?>Product(obj)
{
    var $this = $(obj);
    var $nav = $this.closest('.nav');
    var isPrev = $this.is('.prev');
    var $activeItem = $nav.children('.active');
    var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
    if($next.length > 0)  $next.find('a[data-toggle="tab"]').trigger('click');
    if($next.length == 0) $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
    return false;
}

function change<?php echo "block{$block->id}";?>ProductType(type)
{
    var hiddenData = type == 'all' ? emptyProducts : emptyInvolveds;
    if(hiddenData)
    {
        $('.block-productdoc<?php echo "#block{$block->id}";?> .dataBlock').addClass('hidden');
        $('.block-productdoc<?php echo "#block{$block->id}";?> .block-statistic > .table-empty-tip').removeClass('hidden');
    }
    else
    {
        $('.block-productdoc<?php echo "#block{$block->id}";?> .dataBlock').removeClass('hidden');
        $('.block-productdoc<?php echo "#block{$block->id}";?> .block-statistic > .table-empty-tip').addClass('hidden');
    }

    $('.block-productdoc<?php echo "#block{$block->id}";?> .nav.products').toggleClass('hidden', type != 'all');
    $('.block-productdoc<?php echo "#block{$block->id}";?> .nav.involveds').toggleClass('hidden', type != 'involved');
    $('.block-productdoc<?php echo "#block{$block->id}";?> #productType .btn').html($('.block-productdoc<?php echo "#block{$block->id}";?> #productType [data-type=' + type + ']').html() + " <span class='caret'></span>");
    var name = type == 'all' ? '.block-productdoc<?php echo "#block{$block->id}";?> .products' : '.block-productdoc<?php echo "#block{$block->id}";?> .involveds';
    var $obj = $(name + ' li.active').length > 0 ? $(name + ' .active:first').find('a') : $(name + ' li:not(.switch-icon):first').find('a');
    $(name + ' li').removeClass('active');
    $obj.closest('li').addClass('active');
    $('.block-productdoc<?php echo "#block{$block->id}";?> .tab-pane').removeClass('active').removeClass('in');
    $('.block-productdoc<?php echo "#block{$block->id}";?> .tab-pane' + $obj.data('target')).addClass('active').addClass('in');

    $('.block-productdoc<?php echo "#block{$block->id}";?> #productType .dropdown-menu li').removeClass('active');
    $('.block-productdoc<?php echo "#block{$block->id}";?> #productType .dropdown-menu li a[data-type=' + type + ']').closest('li').addClass('active');
}

</script>
<div class="dropdown" id='productType'>
  <button class="btn" type="button" data-toggle="dropdown"><?php echo $lang->product->involved;?> <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li class='active'><a href="javascript:change<?php echo "block{$block->id}";?>ProductType('involved')" data-type='involved'><?php echo $lang->product->involved;?></a></li>
    <li><a href="javascript:change<?php echo "block{$block->id}";?>ProductType('all')" data-type='all'><?php echo $lang->product->all;?></a></li>
  </ul>
</div>
<?php $hiddenBlock = empty($involveds) ? 'hidden' : '';?>
<?php $hiddenEmpty = empty($involveds) ? '' : 'hidden';?>
<div class="panel-body">
  <div class="table-row block-statistic">
    <div class="table-empty-tip  <?php echo $hiddenEmpty;?>">
      <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
    </div>
    <div class="col col-nav dataBlock <?php echo $hiddenBlock;?>">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover involveds">
        <li class='switch-icon prev'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Product(this)'><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = key($involveds);?>
        <?php foreach($involveds as $product):?>
        <li <?php if($product->id == $selected) echo "class='active' id='activeProduct'";?> productid='<?php echo $product->id;?>'>
          <a href="###" title="<?php echo $product->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$product->id}";?>' data-toggle="tab"><?php echo $product->name;?></a>
          <?php echo html::a(helper::createLink('doc', 'productSpace', "productID=$product->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view'");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Product(this)'><i class='icon icon-arrow-right'></i></a></li>
      </ul>
      <ul class="nav nav-stacked nav-secondary scrollbar-hover products hidden">
        <li class='switch-icon prev'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Product(this)'><i class='icon icon-arrow-left'></i></a></li>
        <?php foreach($products as $product):?>
        <li productid='<?php echo $product->id;?>'>
          <a href="###" title="<?php echo $product->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$product->id}";?>' data-toggle="tab"><?php echo $product->name;?></a>
          <?php echo html::a(helper::createLink('doc', 'productSpace', "productID=$product->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view'");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Product(this)'><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content dataBlock <?php echo $hiddenBlock;?>">
      <?php foreach($products as $product):?>
      <div class="tab-pane fade<?php if($product->id == $selected) echo ' active in';?>" id='<?php echo "tab3{$blockNavId}Content{$product->id}";?>'>
        <?php if(isset($docGroup[$product->id])):?>
        <div class="table-row">
          <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter'>
            <thead>
              <tr>
                <th class='c-name'><?php echo $lang->doc->title?></th>
                <th class='c-user'><?php echo $lang->doc->addedBy?></th>
                <th class='c-date'><?php echo $lang->doc->addedDate?></th>
                <th class='c-date'><?php echo $lang->doc->editedDate?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($docGroup[$product->id] as $doc):?>
              <tr>
                <td class='c-title' data-status='<?php echo $doc->status?>'>
                  <?php
                  $docType = zget($config->doc->iconList, $doc->type);
                  $icon    = html::image("static/svg/{$docType}.svg", "class='file-icon'");
                  if(common::hasPriv('doc', 'view'))
                  {
                      echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $icon . $doc->title, '', "title='{$doc->title}' class='doc-title' data-app='{$this->app->tab}'");
                  }
                  else
                  {
                      echo "<span class='doc-title' title='{$doc->title}'>$icon {$doc->title}</span>";
                  }
                  if($doc->status == 'draft') echo "<span class='label label-badge draft'>{$lang->doc->draft}</span>";
                  ?>
                </td>
                <td class='c-user'><?php echo zget($users, $doc->addedBy);?></td>
                <td class='c-date'><?php echo substr($doc->addedDate, 0, 10);?></td>
                <td class='c-date'><?php echo substr($doc->editedDate, 0, 10);?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?php else:?>
        <div class="table-empty-tip">
          <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
        </div>
        <?php endif;?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
