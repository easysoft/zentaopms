<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg'>
    <?php include 'blockreportlist.html.php';?>
    <div class='panel panel-body' style='padding: 10px 6px'>
      <div class='text proversion'>
        <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->report->proVersionEn : $lang->report->proVersion; ?></span>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class="row" id='conditions'>
        <div class='col-sm-4'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->report->bugOpenedDate;?></span>
            <div class='datepicker-wrapper datepicker-date'><?php echo html::input('begin', $begin, "class='form-control form-date' onchange='changeParams(this)'");?></div>
            <span class='input-group-addon'><?php echo $lang->report->to;?></span>
            <div class='datepicker-wrapper datepicker-date'><?php echo html::input('end', $end, "class='form-control form-date' onchange='changeParams(this)'");?></div>
          </div>
        </div>
        <div class='col-sm-4'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->report->product;?></span>
            <?php echo html::select('product', $products, $product, "class='form-control chosen' onchange='changeParams(this)'");?>
          </div>
        </div>
        <div class='col-sm-4'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->report->project;?></span>
            <?php echo html::select('project', $projects, $project, "class='form-control chosen' onchange='changeParams(this)'");?>
          </div>
        </div>
      </div>
    </div>
    <?php if(empty($bugs)):?>
    <div class="cell">
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
      </div>
    </div>
    <?php else:?>
    <div class='cell'>
      <div class='panel'>
        <div class="panel-heading">
          <div class="panel-title"><?php echo $title;?></div>
          <nav class="panel-actions btn-toolbar"></nav>
        </div>
        <div data-ride='table'>
          <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id="bug">
            <thead>
              <tr class='colhead text-center'>
                <th><?php echo $lang->bug->openedBy;?></th>
                <th><?php echo $lang->bug->unResolved;?></th>
                <?php foreach($lang->bug->resolutionList as $resolutionType => $resolution):?>
                <?php if(empty($resolutionType)) continue;?>
                <th><?php echo $resolution;?></th>
                <?php endforeach;?>
                <th title='<?php echo $lang->report->validRateTips;?>'><?php echo $lang->report->validRate;?></th>
                <th><?php echo $lang->report->total;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($bugs as $user => $bug):?>
              <?php if(!isset($users[$user])) continue;?>
              <tr class="text-center">
                <td><?php echo $users[$user];?></td>
                <td><?php echo isset($bug['']) ? $bug[''] : 0;?></td>
                <?php foreach($lang->bug->resolutionList as $resolutionType => $resolution):?>
                <?php if(empty($resolutionType)) continue;?>
                <td><?php echo isset($bug[$resolutionType]) ? $bug[$resolutionType] : 0;?></td>
                <?php endforeach;?>
                <td title='<?php echo $lang->report->validRateTips;?>'><?php echo round($bug['validRate'] * 100, 2) . '%';?></td>
                <td><?php echo $bug['all'];?></td>
              </tr>
            <?php endforeach;?>
            </tbody>
          </table> 
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
