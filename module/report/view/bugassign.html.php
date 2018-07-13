<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg'>
    <?php include 'blockreportlist.html.php';?>
    <div class='panel panel-body' style='padding: 10px 6px'>
      <div class='text proversion'>
        <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo $this->app->getClientLang() == 'en'? $lang->report->proVersionEn : $lang->report->proVersion; ?></span>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class='panel'>
        <div class="panel-heading">
          <div class="panel-title"><?php echo $title;?></div>
          <nav class="panel-actions btn-toolbar"></nav>
        </div>
        <div data-ride='table'>
          <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='bugAssign'>
            <thead>
              <tr class='colhead'>
                <th><?php echo $lang->report->user;?></th>
                <th><?php echo $lang->report->product;?></th>
                <th><?php echo $lang->report->bugTotal;?></th>
                <th><?php echo $lang->report->total;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($assigns as $account => $assign):?>
              <?php if(!array_key_exists($account, $users)) continue;?>
              <tr class="a-center">
                <td rowspan="<?php echo count($assign['bug']);?>"><?php echo $users[$account];?></td>
                <?php $id = 1;?>
                <?php foreach($assign['bug'] as $product => $count):?>
                <?php if($id != 1) echo '<tr class="a-center">';?>
                <td><?php echo html::a($this->createLink('product', 'view', "product={$count['productID']}"), $product);?></td>
                <td><?php echo $count['count'];?></td>
                <?php if($id == 1):?>
                <td rowspan="<?php echo count($assign['bug']);?>">
                    <?php echo $assign['total']['count'];?>
                </td>
                <?php endif;?>
                <?php if($id != 1) echo '</tr>'; $id ++;?>
                <?php endforeach;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
