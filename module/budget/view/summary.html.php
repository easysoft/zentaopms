<?php include '../../common/view/header.html.php';?>
<style>
.summary-active{color: #0c64eb;font-weight: 700;}
.summary td{font-weight: 700;}
.table-title{background: #efefef}
.text-center th{vertical-align: middle;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="pull-left">
    <?php common::printLink('budget', 'summary', '', "<i class='icon-common-report icon-bar-chart muted'></i> " . $lang->budget->summary, '', "class='btn btn-link summary-active'");?>
    <?php common::printLink('budget', 'browse', '', "<i class='icon icon-list-alt muted'></i> " . $lang->budget->list, '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('budget', 'batchcreate', '', "<i class='icon icon-plus'></i>" . $lang->budget->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('budget', 'create', '', "<i class='icon icon-plus'></i>" . $lang->budget->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class="center-block">
    <?php if($summary['total'] != 0):?>
    <table class='table table-bordered'>
      <tr class='text-center table-title'>
        <th rowspan="<?php echo $isChildren ?  2 : 1;?>"><?php echo $lang->budget->subject . '/' . $lang->budget->stage?></th>
        <?php $hasChild = false;?>
        <?php foreach($subjectStructure as $subject => $structure):?>
        <?php if(isset($structure['hasChild'])) $hasChild = true;?>
        <th colspan="<?php echo (count($structure) - 1)?>"><?php echo zget($modules, $subject);?></th>
        <?php endforeach;?>
        <th rowspan="<?php echo $isChildren ? 2 : 1;?>"><?php echo $lang->budget->summary; ?></th>
      </tr>
      <?php if($hasChild):?>
      <tr class='text-center table-title'>
        <?php foreach($subjectStructure as $parentID => $structure):?>
        <?php
        foreach($structure as $subject)
        {
            if($subject == 'hasChild') continue;
            if($subject == $parentID)
            {
                echo "<th></th>";
            }
            else
            {
                echo "<th>" . zget($modules, $subject) . "</th>";
            }
        }
        ?>
        <?php endforeach;?>
      </tr>
      <?php endif;?>
      <?php foreach($stages as $stage):?>
      <tr>
        <td><?php echo zget($stagePairs, $stage);?></td>
        <?php foreach($subjects as $subject):?>
        <td><?php echo isset($summary[$subject][$stage]) ? $summary[$subject][$stage] : 0;?></td>
        <?php endforeach;?>
        <td>
        <?php $rowsum = 0;?>
        <?php foreach($subjects as $subject):?>
        <?php $rowsum += isset($summary[$subject][$stage]) ? $summary[$subject][$stage] : 0;?>
        <?php endforeach;?>
        <?php echo $rowsum;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr class='summary'>
        <td><?php echo $lang->budget->summary;?></td>
        <?php foreach($subjects as $subject):?>
        <td><?php echo isset($summary[$subject]['summary']) ? $summary[$subject]['summary'] : 0;?></td>
        <?php endforeach;?>
        <td>
        <?php $rowsum = 0;?>
        <?php foreach($subjects as $subject):?>
        <?php $rowsum += isset($summary[$subject]['summary']) ? $summary[$subject]['summary'] : 0;?>
        <?php endforeach;?>
        <?php echo $rowsum;?>
        </td>
      </tr>
    </table>
      <div class='alert alert-info mg-0'><?php echo $lang->budget->total . '：' . $summary['total'] . $lang->budget->{$program->budgetUnit};?></div>
    <?php else:?>
    <div class="table-empty-tip">
      <p> 
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
