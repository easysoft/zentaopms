<?php
/**
 * The reprot file of sonarqubemodule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: reprotview.html.php 935 2022-01-25 10:52:24Z liyuchun@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <?php unset($_GET['onlybody']);?>
  <?php if(empty($measures)): ?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->sonarqube->noReport;?></span></p>
  </div>
  <?php else:?>
  <div class="main-header">
    <div class="page-title">
      <span class='text' title="<?php echo $projectName;?>">
        <h4>
          <?php echo common::hasPriv('sonarqube', 'browseIssue') ? html::a($this->createLink('sonarqube', 'browseIssue', "sonarqubeID={$sonarqubeID}&project=" . str_replace('-', '*', $projectKey)), $projectName, '_parent') : $projectName;?>
          <?php if(!empty($qualitygate->projectStatus->status) and $qualitygate->projectStatus->status != 'NONE'):?>
          <span class="label label-badge label-<?php echo zget($config->sonarqube->projectStatusClass, $qualitygate->projectStatus->status);?>">
            <?php echo zget($lang->sonarqube->qualitygateList, $qualitygate->projectStatus->status);?>
          </span>
          <?php endif;?>
        </h4>
      </span>
    </div>
  </div>
  <table class="table table-data table-report">
    <thead>
      <tr class="text-center">
        <th><?php echo "<span class='table-nest-icon icon icon-bug'></span>" . $lang->sonarqube->report->bugs;?></th>
        <th class="w-140px"><?php echo "<span class='table-nest-icon icon icon-unlock'></span>" . $lang->sonarqube->report->vulnerabilities;?></th>
        <th class="w-180px"><?php echo "<span class='table-nest-icon icon icon-shield'></span>" . $lang->sonarqube->report->security_hotspots_reviewed;?></th>
        <th class="w-130px"><?php echo "<span class='table-nest-icon icon icon-frown'></span>" . $lang->sonarqube->report->code_smells;?></th>
        <th><?php echo $lang->sonarqube->report->coverage;?></th>
        <th><?php echo $lang->sonarqube->report->duplicated_lines_density;?></th>
        <th><?php echo $lang->sonarqube->report->ncloc;?></th>
      </tr>
    </thead>
    <tbody>
      <tr class="text-center">
        <td><?php echo zget($measures, 'bugs', 0);?></td>
        <td><?php echo zget($measures, 'vulnerabilities', 0);?></td>
        <td><?php echo zget($measures, 'security_hotspots_reviewed', '0.0%');?></td>
        <td><?php echo zget($measures, 'code_smells', 0);?></td>
        <td><?php echo zget($measures, 'coverage', '0.0%');?></td>
        <td><?php echo zget($measures, 'duplicated_lines_density', '0.0%');?></td>
        <td><?php echo zget($measures, 'ncloc', 0);?></td>
      </tr>
    </tbody>
  </table>
  <?php endif;?>
</div>
