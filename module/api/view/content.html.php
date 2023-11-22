<div class="main-col" data-min-width="500">
  <div id="mainContent" class="main-row in flex-content">
    <div class="main-col col-8">
      <div class="cell" id="content">
        <div class="no-padding">
          <div class="detail-title no-padding doc-title">
            <div class="http-method label"><?php echo $api->method;?></div>
            <div class="path" title="<?php echo $api->path;?>"><?php echo $api->path;?></div>
            <div class="info">
              <div class="version">
                <div class='btn-group'>
                  <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;">
                    V<?php echo $version ? $version : $api->version;?>
                    <span class="caret"></span>
                  </a>
                  <ul class='dropdown-menu api-version-menu menu-active-primary menu-hover-primary' style='max-height:240px; max-width: 300px; overflow-y:auto'>
                    <?php for($itemVersion = $api->version; $itemVersion > 0; $itemVersion--):?>
                    <li <?php if($version == $itemVersion) echo 'class=active';?> ><a href='javascript:void(0)' data-url='<?php echo $this->createLink('api', 'index', "libID={$api->lib}&moduleID=0&apiID=$apiID&version=$itemVersion&release=$release");?>'>V<?php echo $itemVersion;?></a></li>
                    <?php endfor;?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="actions">
              <?php echo html::a("javascript:fullScreen()", '<span class="icon-fullscreen"></span>', '', "title='{$lang->fullscreen}' class='btn btn-link fullscreen-btn'");?>
              <?php
              if(!$isRelease)
              {
                  if(common::hasPriv('api', 'edit'))   echo html::a($this->createLink('api', 'edit', "apiID=$api->id"), '<i class="icon-edit"></i>', '', "title='{$lang->api->edit}' class='btn btn-link' data-app='{$this->app->tab}'");
                  if(common::hasPriv('api', 'delete')) echo html::a($this->createLink('api', 'delete', "apiID=$api->id"), '<i class="icon-trash"></i>', '', "title='{$lang->api->delete}' class='btn btn-link' target='hiddenwin'");
              }
              ?>
              <a id="hisTrigger" href="###" class="btn btn-link" title=<?php echo $lang->history?>><span class="icon icon-clock"></span></a>
            </div>
          </div>
        </div>
        <div>
          <h2 class="title" title="<?php echo $api->title;?>"><?php echo $api->title;?></h2>
          <div class="desc"><?php echo $api->desc;?></div>
          <?php if($api->params['header']):?>
          <h3 class="title"><?php echo $lang->api->header;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th><?php echo $lang->api->req->type;?></th>
              <th><?php echo $lang->api->req->required;?></th>
              <th><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($api->params['header'] as $param):?>
            <tr>
              <td><?php echo $param['field'];?></td>
              <td>String</td>
              <td><?php echo $lang->api->boolList[$param['required']];?></td>
              <td><?php echo $param['desc'];?></td>
            <tr>
            <?php endforeach;?>
            </tbody>
          </table>
          <?php endif;?>
          <?php if($api->params['query']):?>
          <h3 class="title"><?php echo $lang->api->query;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th><?php echo $lang->api->req->type;?></th>
              <th><?php echo $lang->api->req->required;?></th>
              <th><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($api->params['query'] as $param):?>
            <tr>
              <td><?php echo $param['field'];?></td>
              <td>String</td>
              <td><?php echo $lang->api->boolList[$param['required']];?></td>
              <td><?php echo $param['desc'];?></td>
            <tr>
            <?php endforeach;?>
            </tbody>
          </table>
          <?php endif;?>
          <?php
          function parseTree($data, $typeList, $level = 0)
          {
              global $lang;

              $str   = '<tr>';
              $field = '';
              for($i = 0; $i < $level; $i++) $field .= '&nbsp;&nbsp;'. ($i == $level-1 ? '∟' : '&nbsp;') . '&nbsp;&nbsp;';
              $field .= $data['field'];
              $str   .= '<td>' . $field . '</td>';
              $str   .= '<td>' . zget($typeList, $data['paramsType'], '') . '</td>';
              $str   .= '<td class="text-center">' . zget($lang->api->boolList, $data['required'], '') . '</td>';
              $str   .= '<td>' . $data['desc'] . '</td>';
              $str   .= '</tr>';
              if(isset($data['children']) && count($data['children']) > 0)
              {
                  $level++;
                  foreach($data['children'] as $item) $str .= parseTree($item, $typeList, $level);
              }
              return $str;
          }
          ?>
          <?php if($api->params['params']):?>
          <h3 class="title"><?php echo $lang->api->params;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th class="w-50px"><?php echo $lang->api->req->type;?></th>
              <th class="w-50px text-center"><?php echo $lang->api->req->required;?></th>
              <th class="w-300px"><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody><?php foreach($api->params['params'] as $item) echo parseTree($item, $typeList);?></tbody>
          </table>
          <?php endif;?>
          <?php if($api->paramsExample):?>
          <h3 class="title"><?php echo $lang->api->paramsExample;?></h3>
          <pre><code><?php echo $api->paramsExample;?></code></pre>
          <?php endif;?>
          <?php if($api->response):?>
          <h3 class="title"><?php echo $lang->api->response;?></h3>
          <table class="table table-data">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th class="w-50px"><?php echo $lang->api->req->type;?></th>
              <th class="w-50px text-center"><?php echo $lang->api->req->required;?></th>
              <th class="w-300px"><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
              <?php foreach($api->response as $item) echo parseTree($item, $typeList);?>
            </tbody>
          </table>
          <?php endif;?>
          <?php if($api->responseExample):?>
          <h3 class='title'><?php echo $lang->api->responseExample;?></h3>
          <pre><code><?php echo $api->responseExample;?></code></pre>
          <?php endif;?>
        </div>
      </div>
    </div>
    <div id="history" class='panel hidden' style="margin-left: 2px;">
      <?php
      $canBeChanged = common::canBeChanged('api', $api);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=api&objectID=$api->id");?>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
