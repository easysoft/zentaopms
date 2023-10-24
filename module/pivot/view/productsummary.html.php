<style>
<?php helper::import('../css/productsummary.css');?>
<?php if(common::checkNotCN()):?>
#conditions .col-xs { width: 126px; }
<?php endif;?>
<?php if($this->config->edition != 'open'):?>
#mainContent > .side-col.col-lg{width: 235px}
.hide-sidebar #sidebar{width: 0 !important}
<?php endif;?>
</style>
<div class='cell'>
  <div class='panel'>
    <div class="panel-heading">
      <div class="panel-title">
        <div id='conditions'>
          <div><?php echo $title;?></div>
          <div class='condition-options'>
            <div class="checkbox-primary inline-block">
              <input type="checkbox" name="closedProduct" value="closedProduct" id="closedProduct" <?php if(strpos($conditions, 'closedProduct') !== false) echo "checked='checked'"?> />
              <label for="closedProduct"><?php echo $lang->pivot->closedProduct?></label>
            </div>
            <div class="checkbox-primary inline-block">
              <input type="checkbox" name="overduePlan" value="overduePlan" id="overduePlan" <?php if(strpos($conditions, 'overduePlan') !== false) echo "checked='checked'"?> />
              <label for="overduePlan"><?php echo $lang->pivot->overduePlan?></label>
            </div>
          </div>
        </div>
      </div>
      <nav class="panel-actions btn-toolbar"></nav>
    </div>
  </div>
</div>
<?php if(empty($products)):?>
<div class="cell">
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<div class='cell'>
  <div class='panel'>
    <div data-ride='table'>
      <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='productList'>
        <thead>
          <tr>
            <th><?php echo $lang->product->name;?></th>
            <th class='c-user'><?php echo $lang->pivot->PO;?></th>
            <th><?php echo $lang->productplan->common;?></th>
            <th class="c-date"><?php echo $lang->productplan->begin;?></th>
            <th class="c-date"><?php echo $lang->productplan->end;?></th>
            <th class="c-count"><?php echo $lang->story->statusList['draft'];?></th>
            <th class="c-count"><?php echo $lang->story->statusList['reviewing'];?></th>
            <th class="c-count"><?php echo $lang->story->statusList['active'];?></th>
            <th class="c-count"><?php echo $lang->story->statusList['changing'];?></th>
            <th class="c-count"><?php echo $lang->story->statusList['closed'];?></th>
            <th class="c-count"><?php echo $lang->pivot->total;?></th>
          </tr>
        </thead>
        <tbody>
          <?php $color = false;?>
          <?php foreach($products as $product):?>
          <tr class="text-center">
            <?php $count = isset($product->plans) ? count($product->plans) : 1;?>
            <td class='c-name text-left' title="<?php echo $product->name;?>" rowspan="<?php echo $count;?>"><?php echo "<p>" . html::a($this->createLink('product', 'view', "product=$product->id"), $product->name) . "</p>";?></td>
            <td class='text-left' rowspan="<?php echo $count;?>"><?php echo "<p>" . zget($users, $product->PO) . '</p>';?></td>
            <?php if(isset($product->plans)):?>
            <?php $id = 1;?>
            <?php foreach($product->plans as $plan):?>
              <?php $class = $color ? 'rowcolor' : '';?>
              <?php if($id != 1) echo "<tr class='text-center'>"?>
                <?php $child = (isset($plan->parent) and $plan->parent > 0 and isset($product->plans[$plan->parent])) ? ' child' : '';?>
                <td align='left' class="text-left <?php echo $class . $child;?>" title="<?php echo $plan->title;?>"><?php echo $plan->title;?></td>
                <td class="<?php echo $class;?>"><?php echo $plan->begin == '2030-01-01' ? $lang->productplan->future : $plan->begin;?></td>
                <td class="<?php echo $class;?>"><?php echo $plan->end == '2030-01-01' ? $lang->productplan->future : $plan->end;?></td>
                <?php
                $draftCount     = isset($plan->status['draft'])     ? $plan->status['draft']     : 0;
                $reviewingCount = isset($plan->status['reviewing']) ? $plan->status['reviewing'] : 0;
                $activeCount    = isset($plan->status['active'])    ? $plan->status['active']    : 0;
                $changedCount   = isset($plan->status['changing'])  ? $plan->status['changing']  : 0;
                $closedCount    = isset($plan->status['closed'])    ? $plan->status['closed']    : 0;
                ?>
                <td class="<?php echo $class;?>"><?php echo $draftCount;?></td>
                <td class="<?php echo $class;?>"><?php echo $reviewingCount;?></td>
                <td class="<?php echo $class;?>"><?php echo $activeCount;?></td>
                <td class="<?php echo $class;?>"><?php echo $changedCount;?></td>
                <td class="<?php echo $class;?>"><?php echo $closedCount;?></td>
                <td class="<?php echo $class;?>"><?php echo $draftCount + $reviewingCount + $activeCount + $changedCount + $closedCount;?></td>
              <?php if($id != 1) echo "</tr>"?>
              <?php $id ++;?>
              <?php $color = !$color;?>
            <?php endforeach;?>
            <?php else:?>
              <?php $class = $color ? 'rowcolor' : '';?>
              <td class="<?php echo $class;?>"></td>
              <td class="<?php echo $class;?>"></td>
              <td class="<?php echo $class;?>"></td>
              <td class="<?php echo $class;?>">0</td>
              <td class="<?php echo $class;?>">0</td>
              <td class="<?php echo $class;?>">0</td>
              <td class="<?php echo $class;?>">0</td>
              <td class="<?php echo $class;?>">0</td>
              <td class="<?php echo $class;?>">0</td>
              <?php $color = !$color;?>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif;?>
<script><?php helper::import('../js/productsummary.js');?></script>
