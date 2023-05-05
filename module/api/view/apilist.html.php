<style>
.base-url > p {margin: 0;}
.table-empty-tip > div {display: inline-block;}
.detail .list-group-item .heading.GET {
    background-color: #e7f0f7;
}
.detail .list-group-item .heading.GET a {
    color: #0f6ab4;
}

.detail .list-group {
    height: calc(100vh - 215px);
    overflow-y: auto;
}
.detail .list-group-item .heading.OPTIONS {
    background-color: #e7f0f7;
}
.detail .list-group-item .heading.OPTIONS a {
    color: #0f6ab4;
}

.detail .list-group-item .heading.POST {
    background-color: #e7f6ec;
}
.detail .list-group-item .heading.POST a {
    color: #10a54a;
}

.detail .list-group-item .heading.PUT {
    background-color: #f9f2e9;
}
.detail .list-group-item .heading.PUT a {
    color: #c5862b;
}
.detail .list-group-item .heading.PATCH {
    background-color: #f9f2e9;
}
.detail .list-group-item .heading.PATCH a {
    color: #c5862b;
}

.detail .list-group-item .heading.DELETE {
    background-color: #f5e8e8;
    border: 1px solid #e8c6c7;
}
.detail .list-group-item .heading.DELETE a {
    color: #a41e22;
}

.detail .list-group-item a {
    display: flex;
    align-items: center;
}

.detail .list-group-item .path, .detail .list-group-item .desc {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.detail .list-group-item .path {
    padding-left: 10px;
    font-size: 14px;
    color: black;
    flex: 1;
}
.detail .list-group-item .desc {
    width: auto;
    font-size: 14px;
    line-height: 38px;
    float: right;
    padding-right: 10px;
    max-width: 200px;
}
.detail .list-group-item {list-style: none; font-size: 14px; margin-bottom: 10px; line-height: 30px}
.detail .list-group-item span {line-height: 30px; width: 65px;}
.detail .list-group-item .label+.label {margin-left: -4px;}
.detail .list-group-item .label {border-radius: 0;}
</style>
<?php if($apiID):?>
  <?php include './content.html.php';?>
<?php else:?>
<?php if(empty($libs) || empty($apiList)):?>
<div class="cell apiList">
  <div class="detail">
    <li class="detail-title"><?php echo intval($libID) > 0 ? $lang->api->apiList : $lang->api->pageTitle;?></li>
  </div>
  <div class="table-empty-tip">
    <div class="notice text-muted"><?php echo (empty($libs)) ? $lang->api->noLib : $lang->api->noApi;?></div>
    <div class="no-content-button">
      <?php
      if($libID && common::hasPriv('api', 'create'))
      {
          if($app->rawModule == 'doc')
          {
              echo html::a($this->createLink('api', 'create', "libID=$libID&moduleID=$moduleID", '', true), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-info iframe" data-width="95%"');
          }
          else
          {
              echo html::a(helper::createLink('api', 'create', "libID={$libID}&moduleID=$moduleID"), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-info"');
          }
      }
      ?>
    </div>
  </div>
</div>
<?php else:?>
<div class="cell main-col" data-min-width="400">
  <div class="detail base-url">
    <?php $delimiter = strpos($app->clientLang, 'zh') === 0 ? '：' : ': ';?>
    <p><?php echo $lang->api->baseUrl . $delimiter . $lib->baseUrl;?></p>
  </div>
  <div class="detail">
    <ul class="list-group">
      <?php foreach($apiList as $api):?>
      <li class="list-group-item">
        <div class="heading <?php echo $api->method;?>">
          <a href="<?php echo helper::createLink('api', 'index', "libID={$api->lib}&moduleID=0&apiID={$api->id}&version=0");?>" data-app="<?php echo $app->tab;?>">
            <span class="label label-primary"><?php echo $api->method;?></span>
            <span class="path" title="<?php echo $api->path;?>"><?php echo $api->path;?></span>
            <span class="desc" title="<?php echo $api->title;?>"><?php echo $api->title;?></span>
          </a>
        </div>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<?php endif;?>
<?php endif;?>
