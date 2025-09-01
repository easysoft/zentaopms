# ZenTao UI自动化测试脚本生成指导文档

## 概述

本文档用于指导大模型为ZenTao项目生成高质量的UI自动化测试脚本。ZenTao的UI自动化测试基于WebDriver技术，采用页面对象模式，支持多浏览器测试和丰富的断言机制。

---
**🚨🚨🚨 重要警告 🚨🚨🚨**

**在开始编写任何代码之前，必须先创建测试分支！**
**任何直接在开发分支上编写UI测试的行为都是严重违规！**
**必须严格按照分支→开发→测试→提交→推送的流程执行！**

---

## ⚠️ 重要：分支开发流程（必须执行）

**任何UI测试脚本的开发都必须严格按照以下分支流程进行：**

### 第一步：创建测试分支（必须）
1. **检查当前分支**：确认当前处于开发分支（通常是release分支或master分支）
2. **创建测试分支**：基于当前开发分支创建新的测试分支
3. **分支命名规范**：`uitest/{ModuleName}/{action}/{大模型名称}`
   - 示例：`uitest/user/create/claude`
4. **切换到测试分支**：立即切换到新创建的测试分支进行开发

### 分支操作命令示例：
```bash
# 检查当前分支
git branch

# 创建并切换到UI测试分支
git checkout -b uitest/user/create/claude

# 确认已切换到测试分支
git branch
```

**⚠️ 关键要求：**
- 分支名必须包含模块名、功能、大模型标识
- 例如：`uitest/user/create/claude`
- 如果忘记创建分支，必须立即停止当前操作并创建分支

**🚨 警告：如果不创建测试分支，将违反ZenTao开发规范，必须立即停止操作并创建正确的分支！**

## UI测试框架架构

### 1. 目录结构规范

```
module/{moduleName}/test/
├── lib/                              # UI测试类库
│   └── {action}.ui.class.php        # UI测试类
├── ui/                              # UI测试脚本
│   ├── {action}.php                 # 测试执行脚本
│   └── page/                        # 页面对象
│       └── {action}.php             # 页面元素定义
└── yaml/                            # UI测试数据（继承单元测试YAML）
```

### 2. 核心类继承关系
```
tester (基础测试类)
└── {moduleName}Tester (模块测试类)
    └── createUserTester (具体功能测试类)
```

## 核心文件类型详解

### 1. UI测试执行脚本 ({action}.php)

**文件头格式：**
```php
#!/usr/bin/env php
<?php

/**

title={功能描述}
timeout=0
cid=1

- 测试场景1描述
 - 测试结果 @期望结果1
- 测试场景2描述
 - 测试结果 @期望结果2
- 测试场景3描述
 - 测试结果 @期望结果3

 */
```

**标准代码结构：**
```php
// 1. 切换到脚本目录
chdir(__DIR__);

// 2. 导入UI测试类
include '../lib/{action}.ui.class.php';

// 3. 使用zendata准备测试数据
zendata('{tableName}')->loadYaml('{yamlName}', false, 2)->gen(10);

// 或者直接使用zenData API
$table = zenData('{tableName}');
$table->{field}->range('{range}');
$table->gen({count});

// 4. 创建测试实例并登录
$tester = new {action}Tester();
$tester->login();

// 5. 准备测试数据对象
${objectName} = new stdclass();
${objectName}->field1 = 'value1';
${objectName}->field2 = 'value2';
${objectName}->verifyPassword = $config->uitest->defaultPassword;

// 6. 执行测试用例
r($tester->{testMethod}(${objectName})) && p('message') && e('期望结果1'); // 测试场景1
r($tester->{testMethod}(${objectName})) && p('message') && e('期望结果2'); // 测试场景2

// 7. 清理资源
$tester->closeBrowser();
```

### 2. UI测试类 ({action}.ui.class.php)

**类结构模板：**
```php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class {action}Tester extends tester
{
    /**
     * {功能描述}
     *
     * @param  object    ${objectName}
     * @access public
     * @return object
     */
    public function {methodName}(${objectName})
    {
        // 1. 初始化表单
        $form = $this->initForm('{moduleName}', '{action}', array(), 'appIframe-admin');

        // 2. 填写表单字段
        $form->dom->{fieldName}->setValue(${objectName}->{fieldName});
        $form->dom->{fieldName2}->setValue(${objectName}->{fieldName2});

        // 3. 提交表单
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        // 4. 验证结果
        if($this->response('method') != '{expectedMethod}')
        {
            if($this->checkFormTips('{moduleName}')) return $this->success('{successMessage}');
            return $this->failed('{failMessage}');
        }

        return $this->success('{operationSuccessMessage}');
    }
}
```

### 3. 页面对象文件 (page/{action}.php)

**页面元素定义：**
```php
<?php
class {action}Page
{
    // 页面元素定位器
    public static $account = "#account";
    public static $password = "#password";
    public static $realname = "#realname";
    public static $submit = "input[type='submit']";

    // 页面URL配置
    public static $url = "/{moduleName}-{action}";

    // 页面标题
    public static $title = "{页面标题}";
}
```

## 核心API详解

### 1. 表单操作API

**initForm() - 初始化表单**
```php
$form = $this->initForm($moduleName, $action, $params, $frameId);
```
- `$moduleName`: 模块名称
- `$action`: 操作名称
- `$params`: URL参数数组
- `$frameId`: iframe ID（如'appIframe-admin'）

**表单字段操作**
```php
$form->dom->{fieldName}->setValue($value);     // 设置字段值
$form->dom->{fieldName}->getText();            // 获取字段文本
$form->dom->{fieldName}->click();              // 点击元素
$form->dom->{fieldName}->clear();              // 清空字段
```

**按钮操作**
```php
$form->dom->btn($this->lang->save)->click();   // 点击保存按钮
$form->dom->btn('提交')->click();              // 点击指定文本按钮
```

### 2. 页面跳转验证

**response() - 获取响应信息**
```php
$this->response('method');                     // 获取当前方法
$this->response('module');                     // 获取当前模块
$this->response('url');                        // 获取当前URL
```

**页面跳转验证模式**
```php
// 模式1：验证跳转到指定页面
if($this->response('method') != 'browse')
{
    // 未跳转，检查错误提示
    if($this->checkFormTips('{moduleName}')) return $this->success('错误提示正确');
    return $this->failed('错误提示不正确');
}
return $this->success('操作成功');

// 模式2：直接验证操作结果
if($this->checkFormTips('{moduleName}')) return $this->success('验证成功');
return $this->failed('验证失败');
```

### 3. 等待和同步机制

**等待方法**
```php
$form->wait(1);                               // 等待1秒
$this->waitFor('#elementId');                 // 等待元素出现
$this->waitForText('期望文本');                // 等待文本出现
```

### 4. 断言和验证

**checkFormTips() - 检查表单提示**
```php
if($this->checkFormTips('{moduleName}'))
{
    // 有错误提示
    return $this->success('错误提示正确');
}
```

**返回结果方法**
```php
return $this->success($message);              // 测试成功
return $this->failed($message);               // 测试失败
```

## 测试场景设计原则

### 1. 正常业务流程测试
- 用户正常操作流程
- 表单正确填写和提交
- 页面正常跳转验证

### 2. 表单验证测试
- 必填字段为空
- 数据格式错误
- 业务规则违反（如重复数据）
- 权限验证

### 3. 用户交互测试
- 按钮点击响应
- 下拉框选择
- 复选框/单选框操作
- 文件上传功能

### 4. 页面状态测试
- 页面加载完成
- 数据显示正确
- 错误信息显示
- 成功提示显示

## 常见UI测试模式

### 1. 创建操作测试
```php
public function createNormalItem($item)
{
    $form = $this->initForm('product', 'create', array(), 'appIframe-admin');
    $form->dom->name->setValue($item->name);
    $form->dom->code->setValue($item->code);
    $form->dom->desc->setValue($item->desc);
    $form->dom->verifyPassword->setValue($item->verifyPassword);
    $form->dom->btn($this->lang->save)->click();
    $form->wait(1);

    if($this->response('method') != 'browse')
    {
        if($this->checkFormTips('product')) return $this->success('创建失败提示正确');
        return $this->failed('创建失败提示错误');
    }

    return $this->success('创建成功');
}
```

### 2. 编辑操作测试
```php
public function editItem($item)
{
    $form = $this->initForm('product', 'edit', array('productID' => 1));
    $form->dom->name->clear();
    $form->dom->name->setValue($item->name);
    $form->dom->verifyPassword->setValue($item->verifyPassword);
    $form->dom->btn($this->lang->save)->click();
    $form->wait(1);

    return $this->success('编辑成功');
}
```

### 3. 批量操作测试
```php
public function batchCreateItems($items)
{
    $form = $this->initForm('user', 'batchCreate', array());

    foreach($items as $index => $item)
    {
        $form->dom->{"account[$index]"}->setValue($item->account);
        $form->dom->{"realname[$index]"}->setValue($item->realname);
    }

    $form->dom->verifyPassword->setValue($items[0]->verifyPassword);
    $form->dom->btn($this->lang->save)->click();
    $form->wait(2);

    return $this->success('批量创建成功');
}
```

### 4. 列表页面测试
```php
public function browsePage()
{
    $this->openUrl('product', 'browse');

    // 验证列表数据显示
    if($this->checkElementExists('.table tbody tr'))
    {
        return $this->success('列表显示正常');
    }

    return $this->failed('列表显示异常');
}
```

## 高级功能使用

### 1. iframe操作
```php
// 切换到iframe
$this->switchToFrame('appIframe-admin');

// 在iframe中操作
$form = $this->initForm('user', 'create', array(), 'appIframe-admin');

// 切换回主框架
$this->switchToDefaultFrame();
```

### 2. 弹窗处理
```php
// 处理确认弹窗
$this->acceptAlert();

// 处理取消弹窗
$this->dismissAlert();

// 获取弹窗文本
$alertText = $this->getAlertText();
```

### 3. 多选框和下拉框
```php
// 下拉框选择
$form->dom->select->selectByText('选项文本');
$form->dom->select->selectByValue('optionValue');

// 多选框操作
$form->dom->checkbox->check();        // 选中
$form->dom->checkbox->uncheck();      // 取消选中
```

### 4. 文件上传
```php
// 文件上传
$form->dom->fileInput->sendKeys('/path/to/file');
```

## zendata测试数据管理

ZenTao的UI测试同样使用zendata工具进行测试数据管理，支持灵活的数据生成和配置。

### 1. zendata数据准备方式

**方式一：直接使用zenData API**
```php
// 基础数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user{99},test{50}');
$userTable->password->range('123456{100},Test123{50}');
$userTable->realname->range('管理员,用户{99},测试{50}');
$userTable->role->range('admin{1},qa{20},dev{50},pm{10}');
$userTable->gen(10);

// 多表关联数据
$productTable = zenData('product');
$productTable->name->range('产品{10}');
$productTable->code->range('PROD{10}');
$productTable->gen(5);
```

**方式二：加载YAML配置文件**
```php
// 从YAML文件加载配置
zendata('user')->loadYaml('user', false, 2)->gen(10);

// 加载后覆盖特定字段
$table = zenData('story');
$table->loadYaml('story_create', false, 1);
$table->title->range('需求{100}');
$table->status->range('draft{30},active{50},closed{20}');
$table->gen(20);
```

**方式三：混合使用**
```php
// 先加载基础数据，再添加特殊测试数据
zendata('user')->loadYaml('user', false, 2)->gen(8);

// 添加特殊测试用户
$specialUser = zenData('user');
$specialUser->id->range('999');
$specialUser->account->range('specialtest');
$specialUser->password->range('Special@123');
$specialUser->gen(1);
```

### 2. UI测试中的数据对象创建

**标准数据对象创建：**
```php
// 正常测试数据
$user = new stdclass();
$user->account = 'testuser' . time();          // 避免重复
$user->password = 'Test123456!';
$user->confirmPassword = 'Test123456!';
$user->realname = '测试用户';
$user->email = 'test@example.com';
$user->verifyPassword = $config->uitest->defaultPassword;

// 边界值测试数据
$emptyUser = new stdclass();
$emptyUser->account = '';
$emptyUser->password = '';
$emptyUser->realname = '';
$emptyUser->verifyPassword = $config->uitest->defaultPassword;

// 重复数据测试
$duplicateUser = new stdclass();
$duplicateUser->account = 'admin';             // 已存在的用户名
$duplicateUser->password = 'Admin123';
$duplicateUser->verifyPassword = $config->uitest->defaultPassword;
```

**动态数据生成：**
```php
// 使用时间戳确保唯一性
$timestamp = time();
$product = new stdclass();
$product->name = "测试产品_{$timestamp}";
$product->code = "PROD_{$timestamp}";
$product->desc = "这是一个测试产品的描述信息";

// 使用随机数生成测试数据
$randomId = mt_rand(10000, 99999);
$project = new stdclass();
$project->name = "项目_" . $randomId;
$project->code = "PRJ_" . $randomId;
```

### 3. zendata在不同测试场景中的应用

**创建功能测试：**
```php
// 准备干净的测试环境
zendata('user')->gen(0);                       // 清空用户表

// 生成基础测试数据
$baseUsers = zenData('user');
$baseUsers->account->range('admin,manager');
$baseUsers->password->range('123456{2}');
$baseUsers->gen(2);

// 测试对象
$newUser = new stdclass();
$newUser->account = 'newuser001';
$newUser->password = 'NewUser123!';
```

**批量操作测试：**
```php
// 生成批量测试数据
$batchTable = zenData('user');
$batchTable->account->range('[]{5}');          // 5个空账号用于批量创建
$batchTable->gen(5);

// 批量创建的数据数组
$batchUsers = [];
for($i = 0; $i < 5; $i++) {
    $user = new stdclass();
    $user->account = "batch_user_$i";
    $user->realname = "批量用户$i";
    $user->password = "Batch123!";
    $batchUsers[] = $user;
}
```

**权限测试：**
```php
// 准备不同角色的用户
$roleTable = zenData('user');
$roleTable->account->range('admin,manager,developer,tester,guest');
$roleTable->role->range('admin,pm,dev,qa,limited');
$roleTable->gen(5);

// 准备权限组数据
$groupTable = zenData('usergroup');
$groupTable->account->range('admin,manager,developer,tester,guest');
$groupTable->group->range('1,2,3,4,5');
$groupTable->gen(5);
```

### 4. zendata数据清理和重置

**测试前数据准备：**
```php
// 清空相关表数据
zendata('user')->gen(0);
zendata('product')->gen(0);
zendata('project')->gen(0);

// 重新生成基础数据
zendata('user')->loadYaml('user_basic', false, 1)->gen(5);
zendata('product')->loadYaml('product_basic', false, 1)->gen(3);
```

**测试间数据隔离：**
```php
class createProductTester extends tester
{
    public function setUp()
    {
        // 每个测试方法前重置数据
        zendata('product')->gen(0);
        zendata('user')->loadYaml('user', false, 1)->gen(5);
    }

    public function tearDown()
    {
        // 测试后清理（可选）
        // zendata('product')->gen(0);
    }
}
```

### 5. zendata配置文件在UI测试中的最佳实践

**模块化YAML配置：**
```
test/yaml/
├── user_basic.yaml          # 基础用户数据
├── user_roles.yaml          # 角色权限数据
├── product_basic.yaml       # 基础产品数据
├── project_basic.yaml       # 基础项目数据
└── story_workflow.yaml      # 需求工作流数据
```

**测试特定的数据配置：**
```yaml
# test/yaml/user_ui_test.yaml
---
title: UI测试用户数据配置
fields:
- field: account
  range: uitest{1},testuser{5},[]{3}      # UI测试专用账号+空值测试
- field: password
  range: UITest123!{6},[]{3}             # 统一密码+空密码测试
- field: realname
  range: UI测试用户{6},[]{3}              # 中文名称+空名称测试
- field: email
  range: uitest@test.com{6},[]{3}        # 测试邮箱+空邮箱测试
```

**使用示例：**
```php
// 加载UI测试专用配置
zendata('user')->loadYaml('user_ui_test', false, 2)->gen(10);

// 验证管理员账号存在
$adminTable = zenData('user');
$adminTable->id->range('1');
$adminTable->account->range('admin');
$adminTable->password->range('123456');
$adminTable->role->range('admin');
$adminTable->gen(1);
```

通过合理使用zendata，可以确保UI测试数据的一致性、可重复性和完整性，提高测试的稳定性和可维护性。

## 错误处理和调试

### 1. 错误捕获
```php
try {
    $form->dom->element->click();
} catch (Exception $e) {
    return $this->failed('元素点击失败: ' . $e->getMessage());
}
```

### 2. 截图保存
```php
// 失败时自动截图
if($testFailed) {
    $this->screenshot('test_failure_' . date('Y-m-d_H-i-s'));
}
```

### 3. 调试信息输出
```php
// 输出调试信息
echo "Current URL: " . $this->response('url') . "\n";
echo "Page Title: " . $this->getTitle() . "\n";
```

## 最佳实践

### 1. 测试脚本组织
- 一个功能一个测试类
- 相关的测试方法放在同一个类中
- 测试方法命名要描述清楚测试场景

### 2. 页面元素定位
- 优先使用ID选择器
- 避免使用易变的class名称
- 使用稳定的属性进行定位
- 建立页面对象模式

### 3. 等待策略
- 显式等待优于隐式等待
- 等待时间要合理，不要过长
- 关键操作后要有适当等待
- 页面跳转后要等待加载完成

### 4. 测试数据管理
- 每个测试用例独立准备数据
- 避免测试间的数据依赖
- 使用有意义的测试数据
- 考虑数据的业务合理性

### 5. 断言设计
- 验证关键的业务结果
- 检查页面跳转的正确性
- 验证错误提示的准确性
- 确认数据保存的成功性

## UI测试开发完整流程（必须按顺序执行）

### 🔴 步骤1：创建测试分支（强制要求，不可跳过）
**在开始任何代码编写之前，必须先执行分支操作：**

1. **检查当前分支状态**：
   ```bash
   git branch
   git status
   ```

2. **创建并切换到测试分支**：
   ```bash
   git checkout -b uitest/{ModuleName}/{action}/{大模型名称}
   ```

3. **验证分支创建成功**：
   ```bash
   git branch  # 确认带*的是新创建的测试分支
   ```

**⚠️ 关键要求：**
- 分支名必须包含模块名、功能、大模型标识
- 例如：`uitest/user/create/claude`
- 如果忘记创建分支，必须立即停止当前操作并创建分支

### 步骤2：分析待测功能
1. 确定测试的页面和功能模块
2. 分析用户操作流程和交互元素
3. 理解业务逻辑和验证规则
4. 识别可能的异常情况和边界条件

### 步骤3：设计测试场景
1. 设计正常业务流程测试用例
2. 设计表单验证和错误处理用例
3. 设计用户交互和页面状态用例
4. 确定断言点和期望结果

### 步骤4：编写UI测试脚本
1. 创建UI测试执行脚本，包含完整的测试场景描述
2. 实现UI测试类中的测试方法
3. 创建页面对象文件定义页面元素
4. 编写全面的断言验证

### 步骤5：验证测试脚本
1. **使用ZenTao UI测试框架运行测试脚本**
2. 验证所有测试场景都能正确执行
3. 确认页面操作和断言都符合预期
4. 如果测试失败，根据结果修改测试脚本
5. 重复验证直到所有测试通过

### 🔴 步骤6：提交和推送代码（强制要求）
**测试验证通过后，必须按以下步骤提交代码：**

1. **添加UI测试文件到git（仅添加测试相关文件）**：
   ```bash
   git add module/{module}/test/lib/{action}.ui.class.php
   git add module/{module}/test/ui/{action}.php
   git add module/{module}/test/ui/page/{action}.php
   # 注意：不要添加规范文档文件到测试分支！规范文档应保留在开发分支上
   ```

2. **提交代码**：
   ```bash
   git commit -m "+ [misc] Add UI tests for {ModuleName} {action} functionality

   🤖 Generated with [Claude Code](https://claude.ai/code)

   Co-Authored-By: Claude <noreply@anthropic.com>"
   ```

3. **推送到远程仓库**：
   ```bash
   git push
   ```

4. **切换回开发分支**：
   ```bash
   git checkout {原开发分支名}  # 如 release/21.7.5 或 master
   ```

**⚠️ 提交信息规范：**
- 必须以符号开头：`+`（新增）、`-`（删除）、`*`（修改）
- 格式：`{symbol} [misc] {描述}`
- 必须包含Claude Code标识
- 必须包含Co-Authored-By信息

## 环境配置要求

### 1. WebDriver配置
- 浏览器驱动版本匹配
- 浏览器启动参数配置
- 超时时间设置合理
- 窗口大小适配测试需要

### 2. 测试环境
- 测试数据库独立
- 配置文件环境隔离
- 文件上传路径可写
- 网络访问正常

### 3. 依赖组件
- PHP WebDriver扩展
- 浏览器驱动程序
- 测试框架依赖库
- 图片处理扩展（如需截图）

## 注意事项

### 1. 浏览器兼容性
- 不同浏览器的行为差异
- 元素定位方式的兼容性
- JavaScript执行的差异
- 页面渲染时间差异

### 2. 性能考虑
- 避免不必要的等待时间
- 合理控制测试数据量
- 并发测试的资源竞争
- 浏览器资源占用

### 3. 稳定性保证
- 网络超时处理
- 页面加载异常处理
- 元素查找失败处理
- 浏览器崩溃恢复

## 📋 UI测试开发检查清单

在完成UI测试脚本开发后，请确认以下所有步骤都已正确执行：

### 🔴 强制检查项（必须100%完成）
- [ ] **已创建测试分支**：分支名格式为 `uitest/{模块名}/{功能}/{大模型名}`
- [ ] **当前在测试分支上工作**：通过 `git branch` 确认当前分支正确
- [ ] **测试脚本验证通过**：所有测试场景都能正确执行
- [ ] **已提交代码到本地**：使用正确格式的提交信息
- [ ] **已推送到远程仓库**：执行 `git push` 推送测试分支
- [ ] **已切换回开发分支**：完成后切换回原始开发分支

### 📝 代码质量检查项
- [ ] **测试场景完整**：包含正常流程、表单验证、异常情况测试
- [ ] **页面元素定位准确**：使用稳定的选择器定位页面元素
- [ ] **等待机制合理**：适当的等待时间和等待条件
- [ ] **断言验证全面**：验证关键业务结果和页面状态
- [ ] **页面对象模式**：正确使用页面对象组织代码结构

### 🌐 UI测试特有检查项
- [ ] **浏览器兼容性考虑**：考虑不同浏览器的行为差异
- [ ] **元素等待策略**：使用显式等待而非固定延时
- [ ] **错误处理机制**：处理页面加载失败和元素查找异常
- [ ] **测试数据独立**：每个测试用例独立准备和清理数据

### ⚠️ 常见错误避免
- [ ] **避免直接在开发分支开发**：必须在测试分支上进行开发
- [ ] **避免使用不稳定的选择器**：优先使用ID和稳定属性
- [ ] **避免硬编码等待时间**：使用条件等待替代固定延时
- [ ] **避免不提交代码**：测试通过后必须提交并推送代码
- [ ] **避免忘记切换回开发分支**：完成后必须切换回原分支
- [ ] **避免将规范文档提交到测试分支**：规范文档应该保留在开发分支供后续参照使用

---

**🎯 最终目标：通过遵循本指导文档，生成高质量、稳定可靠的ZenTao UI自动化测试脚本，有效提升系统界面功能的测试覆盖率和测试效率。**

**🚨 重要提醒：任何跳过分支管理步骤的行为都违反了ZenTao开发规范，必须严格按照流程执行！**