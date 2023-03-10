<?php include 'header.html.php';?>
<?php js::set('moduleTree', $moduleTree);?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell module-tree'>
      <div class="panel panel-sm with-list">
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->dev->moduleList?></strong></div>
        <div id="moduleTree" class="menu-active-primary menu-hover-primary"></div>
      </div>
    </div>
  </div>
  <div class='main-col main-content module-col'>
    <?php if($selectedModule):?>
    <div class='module-content'>
      <?php foreach($apis as $api):?>
      <div class='detail'>
        <?php
        $methodName = zget($api, 'name', '');
        $params = array();
        if(isset($api['param']))
        {
            foreach($api['param'] as $param) $params[] = "{$param['var']}=[{$param['var']}]";
        }
        $params = implode('&', $params);
        ?>
        <div class='detail-title'>
          <?php
          echo !empty($api['post']) ? 'GET/POST' : 'GET';
          echo '&nbsp;&nbsp;' . $this->createLink($selectedModule, $methodName, $params, 'json');
          ?>
        </div>
        <div class='detail-content'>
          <?php echo zget($api, 'desc', '');?>
          <table class='table table-bordered'>
            <tr>
              <th><?php echo $lang->dev->params?></th>
              <th><?php echo $lang->dev->type?></th>
              <th><?php echo $lang->dev->desc?></th>
            </tr>
            <?php if(isset($api['param'])):?>
            <?php foreach($api['param'] as $param):?>
            <tr>
              <td><?php echo $param['var']?></td>
              <td><?php echo $param['type']?></td>
              <td><?php echo $param['desc']?></td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr><td colspan="3"><?php echo $lang->dev->noParams?></td></tr>
            <?php endif;?>
          </table>
          <?php if(isset($config->dev->postParams[$selectedModule][$methodName])):?>
          <?php
          $this->app->loadLang($selectedModule);
          $this->app->loadConfig($selectedModule);
          ?>
          <table class='table table-bordered'>
            <caption><?php echo $lang->dev->post;?></caption>
            <tr>
              <th><?php echo $lang->dev->params?></th>
              <th><?php echo $lang->dev->type?></th>
              <th><?php echo $lang->dev->desc?></th>
            </tr>
            <?php foreach($config->dev->postParams[$selectedModule][$methodName] as $paramName => $paramType):?>
            <tr>
              <td><?php echo $paramName?></td>
              <td><?php echo $paramType?></td>
              <?php
              $paramDesc = '';
              $listKey   = $paramName . 'List';
              if(isset($lang->$selectedModule->$paramName)) $paramDesc .= $lang->$selectedModule->$paramName . ' ';
              if(isset($lang->$selectedModule->$listKey)) $paramDesc .= sprintf($lang->dev->paramRange, join(' | ', array_keys($lang->$selectedModule->$listKey)));
              if($paramType == 'date')  $paramDesc .= $lang->dev->paramDate;
              if($paramName == 'color') $paramDesc .= $lang->dev->paramColor;
              if(isset($config->$selectedModule->$methodName->requiredFields) and strpos($config->$selectedModule->$methodName->requiredFields, $paramName) !== false) $paramDesc .= "<span class='red'>*{$lang->required}</span>";
              if($paramName == 'product') $paramDesc .= "<span class='red'>*{$lang->required}</span>";
              if($paramName == 'mailto') $paramDesc .= $lang->dev->paramMailto;
              ?>
              <td><?php echo $paramDesc?></td>
            </tr>
            <?php endforeach;?>
          </table>
          <?php endif;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
