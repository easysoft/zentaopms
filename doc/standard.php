<?php
# 一、开发节奏安排

    上午 8:30 小组内部讨论当天重构的功能；
    晚上 8:00 以小组为单位分享当天的进展；
# 二、 开发规范

## 1. 统一使用PHP8.1进行开发，强类型声明
为了让PHP强制使用严格类型，需要在每个编写的文件头部填写 declare(strict_types=1);
```php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      XX<xx@easycorp.ltd>
 * @package     example
 * @link        http://www.zentao.net
 */
```
版权信息修改：
	example 为当前模块，author修改成自己的账号，package写当前模块名；

## 2. 模块目录约定
最新的目录结构
```
task/
	config/
		form.php     #表单的配置项,用于form::use()->create();
		table.php    #列表页面表格的配置
	css/
		create.ui.css
	js/
		create.ui.js
	lang/
		zh-cn.php
	test/             #单元测试用例
		control/
			create.php
		model/
			create.php
		task.class.php
	ui/
		create.html.php   #新版view页面
	view/
		create.html.php   #旧版view页面，全部改版完成后删除
	config.php
	control.php
	model.php
	tao.php
	zen.php

```

## 3. 使用TDD方式进行开发
 先编写单元测试用例，然后编写功能代码
## 4. 区分public和protected方法
 外部模块可访问的方法使用public，只有自己模块调用的方法使用protected修饰
## 5. 代码分为ui/control/zen/model/tao，共5个层次；
   1）ui: 新版Zin代码
   2）control/zen:
	   public方法放在control，protected放在zen；
	   control层只负责获取web请求的数据和变量，比如从$\_GET、 $\_POST、 $\_COOKIE、 $\_SESSION获取数据，其他层(zen、model、tao)禁止获取这些全局变量，只能传参使用；
	   zen层对这些数据进行加工，业务逻辑处理，调用model、tao方法（zen、tao里的方法均为protected）
	   注意：页面跳转，js输出等需要放到control层
   3）model/tao: public方法放在model，protected放在tao；
	  model里为对外提供的方法，为public。tao的操作必须为单一的，一次查询、插入、一个表。
## 6. POST表单的数据处理统一使用form类
 1)在module/xxx/config/form中定义
 ```
$config->example->form->create = array();
$config->example->form->create['name']                = array('type' => 'string', 'r e q u i r ed' => true, 'filter' => 'trim');
$config->example->form->create['PO']                    = array('type' => 'account', 'r e q u i r ed' => false, 'default' => '');
$config->example->form->create['createdDate']     = array('type' => 'date', 'r e q u i r ed' => false, 'default' => helper::now());
$config->example->form->create['createdVersion'] = array('type' => 'string', 'r e q u i r ed' => false, 'default' => $this->config->version);
```
 2)调用form进行处理

`$data = form::use($this->config->example->create)->create();`

-   注意代码编写时位置关系：select、insert
## 7. Control层作为入口，处理请求参数
```
public function edit($projectID)
{
	if(!empty($_POST))
	{
		$data = form::use($this->config->example->create)->create();
		return $this->projectZen->edit($projectID, $data);
	}

	......
	$this->zen->buildEditForm($projectID);
}
```

在调用zen的过程中，如果业务比较复杂，推荐使用以下形式
$this->projectZen->beforeEdit();
$this->projectZen->edit();
$this->projectZen->afterEdit();

## 8. zen层的方法，再次拆分后使用private修饰
```php
protected function create()
{
		$this->story->createBranch();
}
private function createBranch()
{
		...
}
```
## 9. 缩写在语言项都放在一起
```php
#之前的缩写是使用下面这个形式：

$lang->bug->story = '研发需求';
$lang->bug->storyAB = '需求';

#现在改为：
$lang->bug->story = '研发需求';
$lang->bug->abbr = new stdclass();
$lang->bug->abbr->story = '需求';
```
## 10. 方法要细分，每个函数的代码行数不超过50行
## 11. MySQL的sql_mode使用strict模式
 1) 对于GROUP BY聚合操作,如果在SELECT中的列,没有在GROUP BY中出现,那么这个SQL是不合法的,因为列不在GROUP BY从句中
 2) 不允许日期和月份为零，必须使用NULL
 3) TEXT类型不能有默认值，建议设为NULL
 4) 不能用双引号来引用字符串,必须使用单引号
## 12. 错误和提醒的语言项，统一在一起编写

$lang->example->error = new stdclass();
$lang->example->error->nameEmpty = '名称不能为空';

$lang->example->notice = new stdclass();
$lang->example->notice->nameEmpty = '请填写名称';

## 13. 代码注释使用中英文两种语言
每个模块重构的方法注释使用中英文两种语言，方法内部的注释如果使用英文表述不够贴切也需要使用中文

## 14. Model层方法命名
  所有的方法必须在文件中有序的组织起来，相似功能的函数放在一起，比如
```
  getBuildsByProject() { }
  getReleasesByProjects() { }
  ...
  create() { }
  batchCreate() { }
  ...
  start() { }
  finish() { }
```

1) 获取一个对象
    getXXByYY，比如 getBuildsByProject($projectID)
    如果获取本模块的数据，可以缩写为getByYY，比如 getByID($projectID)

2) 返回一个对象数组
```
$this->project->getList();
$this->project->getTeamMembers($projectID);
$this->project->getListByProductAndProgram($productID, $programID);
```
3) 返回一个key/value数组，比如
    getProductPairs();
    本模块缩写为 getPairs();
 4) 批量操作
	batchCreate($projectID, $formData)
5)  更新操作
    update($projectID, $formData)
6) 其他操作
    start($projectID)
    finish($projectID)
