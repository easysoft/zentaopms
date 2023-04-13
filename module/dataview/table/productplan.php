<?php
$this->app->loadLang('productplan');
$this->app->loadLang('product');

$schema = new stdclass();

$schema->primaryTable = 'productplan';

$schema->tables = array();
$schema->tables['productplan'] = 'zt_productplan';
$schema->tables['product']     = 'zt_product';

$schema->joins = array();
$schema->joins['product'] = 'productplan.product = product.id';

$schema->fields = array();
$schema->fields['product'] = array('type' => 'object', 'name' => $this->lang->productplan->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['title']   = array('type' => 'string', 'name' => $this->lang->productplan->title);
$schema->fields['status']  = array('type' => 'option', 'name' => $this->lang->productplan->status, 'options' => $this->lang->productplan->statusList);
$schema->fields['desc']    = array('type' => 'string', 'name' => $this->lang->productplan->desc);
$schema->fields['begin']   = array('type' => 'date',   'name' => $this->lang->productplan->begin);
$schema->fields['end']     = array('type' => 'date',   'name' => $this->lang->productplan->end);

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->name);
