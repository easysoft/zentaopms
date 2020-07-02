<style>
.types-line {display: table; padding: 0; margin: 0 auto;}
.types-line > li {display: table-cell;}
.types-line > li > div {position: relative; padding: 18px 5px 5px; text-align: center;}
.types-line > li > div:before {content: ''; display: block; background: #eee;  width: 100%; height: 2px; position: absolute; left: 50%; top: 4px;}
.types-line > li:last-child > div:before {display: none;}
.types-line > li > div:after {content: ''; display: block; background: #FFFFFF; box-shadow: 0 2px 4px 0 rgba(170,170,170,0.50), 0 0 5px 0 rgba(0,0,0,0.1); width: 10px; height: 10px; position: absolute; border-radius: 50%; top: 0; left: 50%; margin-left: -2px;}
.types-line > li > div > small {display: block; color: #A6AAB8;}
.types-line > li > div > span {display: block; color: #CBD0DB; font-size: 16px;}
.product-info {position: relative; height: 65px;}
.product-info + .product-info {margin-top: 10px;}
.product-info .progress {position: absolute; left: 10px; top: 35px; right: 90px;}
.product-info .progress-info {position: absolute; left: 8px; top: 10px; width: 180px; font-size: 12px;}
.product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 100px;}
.product-info .type-value,
.product-info .type-label {font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.product-info .type-value {font-size: 14px;}
.product-info .type-value > strong {font-size: 20px; color: #3C4353;}
.product-info .actions {position: absolute; left: 10px; top: 14px;}
.block-statistic .panel-body {padding-top: 0;}
.block-statistic .tile {margin-bottom: 30px;}
.block-statistic .tile-title {font-size: 18px; color: #A6AAB8;}
.block-statistic .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-statistic .col-nav {border-right: 1px solid #EBF2FB; width: 260px; padding: 0;}
.block-statistic .nav-secondary > li {position: relative}
.block-statistic .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-statistic .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-statistic .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-statistic .nav-secondary > li.active > a:first-child:hover,
.block-statistic .nav-secondary > li.active > a:first-child:focus,
.block-statistic .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px; background: #f5f5f5;}
.block-statistic .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-statistic .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-statistic .nav-secondary > li:hover > a.btn-view {opacity: 1}
.block-statistic .nav-secondary > li.active > a.btn-view {box-shadow: none}
.block-statistic .nav-stacked {overflow: auto; max-height: 247px;}
.block-statistic .progress-pie .progress-info > strong {font-size: 24px;}
.block-statistic .chosen-single {font-size: 16px; font-weight: bold;}
.block-statistic .chosen-container-single .chosen-single div b {top: -2px}
.block-statistic .nav-secondary > li.switch-icon {display: none;}
.block-statistic.block-sm .panel-body {padding-bottom: 10px; position: relative; padding-top: 45px;}
.block-statistic.block-sm .panel-body > .table-row,
.block-statistic.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-statistic.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-statistic.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5; border-radius: 3px;}
.block-statistic.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-statistic.block-sm .nav-secondary > li {display: none}
.block-statistic.block-sm .nav-secondary > li.switch-icon,
.block-statistic.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-statistic.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0,0,0,0.07);}
.block-statistic.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-statistic.block-sm .nav-secondary > li > a:before {display: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-statistic .statistic-menu {height:20px;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.05)}
</style>
<script>
<?php $blockNavId = 'nav-' . uniqid(); ?>
$(function()
{
    var $nav = $('#<?php echo $blockNavId;?>');
    $nav.on('click', '.switch-icon', function(e)
    {
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a').trigger('click');
        e.preventDefault();
    });

    $('[name^=build]').change(function()
    {
        var $tab = $('#bugBox' + $(this).val());
        $tab.removeClass('hidden');
        $tab.find('.progress-pie').progressPie();
        $(this).parents('.tab-pane').find('.table-row').not($tab).addClass('hidden');
    });
});
</script>
<div class="panel-body block-qa">
  <div class="table-row">
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary" id='<?php echo $blockNavId;?>'>
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php $index = 1;?>
        <?php foreach($products as $product):?>
        <li <?php if($product == reset($products)) echo "class='active'";?> productID='<?php echo $product->id;?>'>
          <a href="javascript:;" data-target="#tabQaProduct<?php echo $product->id;?>" data-toggle="tab" title='<?php echo $product->name;?>'><?php echo $product->name;?></a>
        <?php echo html::a(helper::createLink('bug', 'browse', "productID=$product->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view' title={$lang->bug->browse}");?></li>
        <?php $index++;?>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content">
      <?php $index = 1;?>
      <?php foreach($products as $product):?>
      <div class="tab-pane fade <?php if($index == 1) echo 'active';?> in" id="tabQaProduct<?php echo $product->id;?>">
        <div class="statistic-menu input-group space w-400px">
          <div class="input-group-cell">
          <?php if($product->testtask):?>
            <?php echo $product->testtask->name;?>
            <span class="text-muted small"><a href="<?php echo $this->createLink('testtask', 'browse', "productID={$product->id}");?>" class="text-primary"><?php echo $lang->block->lblTesttask;?> <i class="icon icon-right-circle icon-sm"></i></a></span>
          <?php endif;?>
          </div>
        </div>
        <div class="table-row" id='bugBox<?php echo $product->id;?>'>
          <div class="col-5 text-middle text-center">
            <div class="progress-pie inline-block space progress-pie-100" data-value="<?php echo $product->assignedRate;?>" data-doughnut-size="80">
              <canvas width="100" height="100" style="width: 100px; height: 100px;"></canvas>
              <div class="progress-info">
                <small><?php echo $lang->bug->allBugs;?></small>
                <strong><?php echo empty($product->total) ? 0 : html::a($this->createLink('bug', 'browse', "productID={$product->id}&branch=&type=all"), $product->total);?></strong>
              </div>
            </div>
            <div class="text-center small with-padding">
              <span class="label label-dot label-primary"></span> &nbsp; <?php echo $lang->bug->assignToMe;?> &nbsp; <strong><?php echo empty($product->assignedToMe) ? 0 : html::a($this->createLink('bug', 'browse', "productID={$product->id}&branch=&browseType=assigntome"), $product->assignedToMe);?></strong>
            </div>
          </div>
          <div class="col-7">
            <div class="product-info">
              <?php if($product->yesterdayResolved):?>
              <div class="progress-info"><i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->bug->yesterdayResolved;?></span> <strong><?php echo $product->yesterdayResolved;?></strong></div>
              <?php endif;?>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $product->unresolvedRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $product->unresolvedRate;?>%"></div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <span><?php echo $lang->bug->unResolved;?></span>
                </div>
                <div class="type-value">
                  <strong><?php echo empty($product->unresolved) ? 0 : html::a($this->createLink('bug', 'browse', "productID={$product->id}&branch=&browseType=unresolved"), $product->unresolved);?></strong>
                </div>
              </div>
            </div>
            <div class="product-info">
              <?php if($product->yesterdayConfirmed):?>
              <div class="progress-info"><i class="icon icon-exclamation-sign text-danger icon-sm"></i> <span class="text-muted"><?php echo $lang->bug->yesterdayConfirmed;?></span> <strong><?php echo $product->yesterdayConfirmed;?></strong></div>
              <?php endif;?>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $product->unconfirmedRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $product->unconfirmedRate;?>%"></div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <span><?php echo $lang->bug->unconfirmed;?></span>
                </div>
                <div class="type-value">
                  <strong><?php echo empty($product->unconfirmed) ? 0 : html::a($this->createLink('bug', 'browse', "productID={$product->id}&branch=&browseType=unconfirmed"), $product->unconfirmed);?></strong>
                </div>
              </div>
            </div>
            <div class="product-info">
              <?php if($product->yesterdayClosed):?>
              <div class="progress-info"><i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->bug->yesterdayClosed;?></span> <strong><?php echo $product->yesterdayClosed;?></strong></div>
              <?php endif;?>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $product->unclosedRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $product->unclosedRate;?>%"></div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <span><?php echo $lang->bug->unclosed;?></span>
                </div>
                <div class="type-value">
                  <strong><?php echo empty($product->unclosed) ? 0 : html::a($this->createLink('bug', 'browse', "productID={$product->id}&branch=&browseType=unclosed"), $product->unclosed);?></strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php $index++;?>
      <?php endforeach;?>
    </div>
  </div>
</div>
