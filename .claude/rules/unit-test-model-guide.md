# ZenTao单元测试脚本生成指导文档

## 概述

本文档专门用于指导AI大模型为ZenTao项目生成高质量的单元测试脚本。ZenTao采用自研测试框架，支持数据驱动测试、分层测试架构和多种断言方式。

### 🎯 大模型使用须知
- **严格按照模板格式**：所有代码必须遵循本文档提供的模板
- **完整执行流程**：按顺序执行所有步骤，不可跳过任何环节
- **精确理解术语**：区分测试用例（.php文件）和测试步骤（r()...e()语句）
- **强制性要求**：每个测试用例必须包含至少5个测试步骤

### 📝 统一占位符说明
**AI大模型必须严格按照以下占位符规范，保持命名一致性：**
- `{moduleName}` - 模块名（小写，如：user、task、project）
- `{layerName}` - 业务分层（小写，如：control、model、tao、zen、ui）
- `{className}` - 类名（驼峰命名，如：userModel、taskTao、projectZen）
- `{methodName}` - 方法名（驼峰命名，如：getById、createUser）
- `{tableName}` - 数据表名（小写，如：user、task、project）
- `{fieldName}` - 字段名（小写，如：id、name、status）
- `{paramName}` - 参数名（驼峰命名，如：userId、taskId）

**🚨 注意：不要使用其他变体形式，如 {ModuleName}、{methodname}、{table} 等**

### 🔧 代码格式要求
**AI大模型必须严格遵守代码格式规范，违反将导致提交失败：**
- **🚨 严禁行尾空格**：所有代码行末尾不得包含任何空格字符
- **文件末尾换行**：每个文件必须以一个换行符结尾，不能有多余空行
- **缩进统一**：使用4个空格进行缩进，不使用制表符
- **字符编码**：使用UTF-8编码，不包含非ASCII特殊字符

---
**🚨🚨🚨 AI大模型必读警告 🚨🚨🚨**

**第一步：每个测试用例必须包含至少5个测试步骤（r()...e()语句）！**
**第二步：r()...e()语句必须写在同一行内，禁止换行！**
**第三步：r()...e()语句必须从行首开始，行内不能有其他代码！
**第四步：必须按照指定格式提交代码！必须使用 test/runtime/ztf 运行测试验证！**
**第五步：必须先使用 php 命令验证脚本没有错误再使用 test/runtime/ztf 验证测试是否通过**

**⛔ 绝对禁止的行为：**
- 测试步骤少于5个
- r()...e()语句换行
- r()...e()语句没有从行首开始
- 不遵循提交信息格式
- 代码包含行尾空格（会导致提交失败）
- 使用 `test/spider.php` 运行测试（必须使用 `test/runtime/ztf`）

---

## 🔍 核心概念说明

### 🔑 关键概念：测试用例 vs 测试步骤
**大模型必须精确理解以下概念，避免混淆：**

#### 测试用例（Test Case）
- **定义**：每个`.php`测试脚本文件 = 一个测试用例
- **作用**：专门测试一个具体方法（如：getUserById.php测试getUserById方法）
- **文件命名**：使用小写方法名 + .php后缀（如：getUserById方法 → getuserbyid.php）

#### 测试步骤（Test Step）
- **定义**：测试用例内的每个`r()...e()`语句 = 一个测试步骤
- **作用**：验证方法在不同输入下的表现
- **强制要求**：每个测试用例必须包含≥5个测试步骤
- **覆盖场景**：正常情况、边界值、异常输入、权限验证、业务规则

**示例说明：**
```php
// 这是一个测试用例：getUserById.php
r($userTest->getByIdTest(1)) && p('account') && e('admin');    // 测试步骤1：正常用户查询
r($userTest->getByIdTest(999)) && p() && e(false);             // 测试步骤2：不存在用户查询
r($userTest->getByIdTest(0)) && p() && e(false);               // 测试步骤3：无效ID查询
r($userTest->getByIdTest(-1)) && p() && e(false);              // 测试步骤4：负数ID查询
r($userTest->getByIdTest('abc')) && p() && e(false);           // 测试步骤5：非数字ID查询
```

## 测试框架架构

### 1. 目录结构规范

```
module/{moduleName}/test/
├── lib                           # 模块测试类库目录
│  ├── model.class.php            # 测试类文件
├── model                         # 测试目录
│  ├── yaml                       # 测试数据目录
│  │   ├── {methodName}           # 被测方法专用测试数据目录
│  │   │    ├── {tableName}.yaml  # 被测方法专用测试数据定义文件
│  ├── {methodName}.php           # 被测方法专用测试脚本文件
├── yaml                          # 模块通用测试数据目录
│  ├── {tableName}.yaml           # 模块通用测试数据定义文件
```

## 核心文件类型详解

### 1. 测试执行脚本 ({methodName}.php)

**文件头格式：**
```php
#!/usr/bin/env php
<?php

/**

title=测试 {className}::{methodName}();
cid=0

- 测试步骤1描述 @期望结果1
- 测试步骤2描述 @期望结果2
- 测试步骤3描述 @期望结果3
- 测试步骤4描述 @期望结果4
- 测试步骤5描述 @期望结果5

*/
```

**🛠️ AI大模型标准代码模板（必须严格遵循）：**
```php
#!/usr/bin/env php
<?php

/**

title=测试 {className}::{methodName}();
timeout=0
cid=0

- 测试步骤1：正常输入情况 >> 期望正常结果
- 测试步骤2：边界值输入 >> 期望边界处理结果
- 测试步骤3：无效输入情况 >> 期望错误处理结果
- 测试步骤4：权限验证情况 >> 期望权限控制结果
- 测试步骤5：业务规则验证 >> 期望业务逻辑结果

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('{tableName}');
$table->{field}->range('{dataRange}');               // 具体数据范围
$table->{field2}->range('{dataRange2}');             // 其他字段数据
$table->gen({count});                                // 生成数据数量

// 3. 用户登录（选择合适角色）
su('admin');  // 或 su('user'); 根据测试需要

// 4. 创建测试实例（变量名与模块名一致）
${moduleName}Test = new {moduleName}ModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(${moduleName}Test->{methodName}Test({param1})) && p('{checkProperty}') && e('{expectedValue}'); // 步骤1：正常情况
r(${moduleName}Test->{methodName}Test({param2})) && p('{checkProperty}') && e('{expectedValue}'); // 步骤2：边界值
r(${moduleName}Test->{methodName}Test({param3})) && p('{checkProperty}') && e('{expectedValue}'); // 步骤3：异常输入
r(${moduleName}Test->{methodName}Test({param4})) && p('{checkProperty}') && e('{expectedValue}'); // 步骤4：权限验证
r(${moduleName}Test->{methodName}Test({param5})) && p('{checkProperty}') && e('{expectedValue}'); // 步骤5：业务规则
```

**关键API说明：**
- `zenData('{tableName}')`: 创建测试数据表对象
- `zendata('{tableName}')->loadYaml('{yamlFile}', false, 2)->gen({count})`: 从YAML文件加载配置并生成数据
- `$table->{field}->range('{range}')`: 设置字段的数据范围
- `$table->gen({count})`: 生成指定数量的测试数据
- `su('{username}')`: 模拟用户登录
- `r()`: 执行测试方法
- `p('{property}')`: 指定检查的属性
- `e('{expected}')`: 期望值断言

### 2. 单元测试类

**类文件：**
model.class.php

**类结构模板：**
```php
<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class {moduleName}ModelTest extends baseTest
{
    protected $moduleName = '{moduleName}';
    protected $className  = 'model';

    /**
     * Test {methodName} method.
     *
     * @param  mixed $param 参数描述
     * @access public
     * @return mixed
     */
    public function {methodName}Test($param = null)
    {
        $result = $this->invokeArgs('{moduleName}', [$param]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
```

### 3. zendata YAML测试数据文件

ZenTao使用zendata工具根据YAML配置文件生成测试数据。zendata是一个通用的测试数据生成工具，支持灵活的数据定义语法。

**YAML文件基本结构：**
```yaml
---
title: 数据定义标题
desc: 数据描述（可选）
author: 作者（可选）
version: 版本号（可选）

fields:
- field: id
  note: ID说明
  range: 1-10000
  prefix: ""
  postfix: ""
  loop: 0
  format: ""
- field: {fieldName}
  note: {字段说明}
  range: {数据范围}
  format: {格式化}
```

**zendata字段定义属性：**

| 属性名 | 说明 | 示例 |
|-------|------|------|
| `field` | 字段名，仅支持英文、数字、下划线和点 | `field: username` |
| `range` | 列表范围，最重要的定义 | `range: 1-100, admin{2}` |
| `loop` | 循环次数 | `loop: 3` |
| `loopfix` | 每次循环的连接符 | `loopfix: _` |
| `format` | 格式化输出 | `format: "passwd%02d"` |
| `prefix` | 字段前缀 | `prefix: "user_"` |
| `postfix` | 字段后缀 | `postfix: "@test.com"` |
| `length` | 字段长度（字节） | `length: 10` |
| `leftpad` | 左填充字符 | `leftpad: 0` |
| `rightpad` | 右填充字符 | `rightpad: " "` |
| `note` | 字段说明 | `note: "用户ID"` |

**zendata range语法规则：**

| 语法类型 | 示例 | 说明 |
|---------|------|------|
| **基本范围** | `1-10` | 生成1到10的连续数字 |
| **多个范围** | `1-10, 20-25, 27, 29` | 多个区间用逗号分隔 |
| **指定步长** | `1-10:2` | 步长为2：1,3,5,7,9 |
| **小数步长** | `1-10:0.1` | 支持小数步长 |
| **负步长** | `100-1:-1` | 倒序生成：100,99,98... |
| **随机生成** | `1-10:R` | 从1-10中随机选择 |
| **元素重复** | `admin{100}, user{50}` | admin重复100次，user重复50次 |
| **组合重复** | `[user1,user2]{10}` | 组合重复，用[]括起来 |
| **空值** | `[]{4}` | 生成4个空值 |
| **文件引用** | `users.txt:R` | 从文件随机读取 |
| **字符范围** | `a-z, A-Z` | 字符范围生成 |

**时间和日期支持：**
```yaml
- field: birthday
  range: (-30Y)-(-20Y):60D    # 30年前到20年前，步长60天
  type: timestamp
  format: YYYY-MM-DD

- field: join_date
  range: (M)-(w)              # 本月到本周
  format: YY/MM/DD hh:mm:ss
```

**格式化功能：**
```yaml
- field: password
  range: 1-1000
  format: "passwd%04d"        # 生成passwd0001, passwd0002...

- field: account
  range: admin,user{99}
  format: md5                 # MD5加密

- field: phone
  fields:
    - field: area
      range: 130-199:R
    - field: number
      range: 0000-9999:R
```

**复合字段定义：**
```yaml
- field: email
  fields:
  - field: username
    range: user{100}, admin{10}
  - field: domain
    range: '[@qq.com,@163.com,@gmail.com]'

- field: full_name
  fields:
  - field: first_name
    range: 张,王,李,刘
  - field: last_name
    range: 三,四,五,六
    prefix: ""
    postfix: ""
```

**引用其他配置文件：**
```yaml
# 引用整个配置文件
- field: user_info
  config: user_basic.yaml

# 引用内置定义
- field: numbers
  from: zentao.number.v1.yaml
  use: medium

# 从Excel数据源取数据
- field: address
  from: address.cn.v1.china
  select: city
  where: state like '%山东%'
  rand: true
```

**循环和嵌套：**
```yaml
- field: tags
  range: tag1,tag2,tag3
  loop: 3
  loopfix: "|"              # 输出：tag1|tag2|tag3

- field: nested_data
  fields:
    - field: level1
      range: a-z
      fields:
        - field: level2
          range: 1-10
```

**zendata数据生成最佳实践：**

1. **合理的数据量**：通常生成10-100条测试数据
2. **业务合理性**：数据要符合业务逻辑
3. **边界值覆盖**：包含空值、边界值、特殊字符
4. **关联关系**：注意外键约束和数据完整性
5. **性能考虑**：避免生成过大的测试数据集

## 测试用例设计原则

### 1. 边界值测试
- 测试空值、null、0等边界情况
- 测试数据类型边界（最大值、最小值）
- 测试字符串长度边界

### 2. 等价类划分
- 有效等价类：正常业务逻辑路径
- 无效等价类：异常输入处理
- 特殊等价类：业务规则特殊情况

### 3. 状态转换测试
- 测试业务对象的状态变迁
- 验证状态转换的合法性
- 检查状态转换后的副作用

## 单元测试开发完整流程（必须按顺序执行）

### 步骤1：分析待测方法
1. 确定方法所属模块和业务分层（model/tao/zen/control/ui）
2. 分析方法参数类型和返回值
3. 理解业务逻辑和数据依赖关系
4. 识别可能的异常情况

### 步骤2：设计测试用例和测试步骤
1. **设计测试用例**：每个测试脚本对应一个测试用例，用于测试单个方法
2. **设计测试步骤**：在测试用例中设计多个测试步骤来验证不同场景
   - 正常流程测试步骤
   - 边界值测试步骤
   - 异常输入测试步骤
3. 考虑数据库状态对测试的影响
4. 确定每个步骤的断言点和期望结果
5. **🔴 强制要求：每个测试用例必须包含至少5个测试步骤**

### 步骤3：创建YAML数据文件（如需要）
1. 分析方法依赖的数据表
2. 设计符合业务逻辑的测试数据
3. 使用适当的数据生成规则
4. 考虑数据之间的关联关系

### 步骤4：编写测试脚本
1. 创建测试执行脚本，包含完整的测试用例描述
2. 测试脚本使用小写的方法名称作为文件名，后缀统一为.php
3. **🔴 强制要求：每个测试用例必须包含至少5个测试步骤（r()...e()语句）**
4. 实现单元测试类中的测试方法
5. 配置数据准备和环境设置
6. 为每个测试步骤编写准确的断言验证

### 步骤5：验证测试脚本（AI大模型必须执行验证）

**🔴 强制要求：必须先使用 php 命令运行脚本没有错误再使用 ztf 运行器验证**

#### 5.1 执行测试命令
```bash

# 使用 php 命令运行测试脚本，检查是否有错误
php module/{moduleName}/test/model/{methodName}.php

# 使用 ztf 运行测试脚本
test/runtime/ztf module/{moduleName}/test/model/{methodName}.php
```

#### 5.2 验证测试结果
**✅ 测试通过标准：**
- 通过数（PASS）：1
- 失败数（FAIL）：0
- 忽略数（SKIP）：0

**❌ 如果测试失败：**
1. 查看错误输出信息
2. 检查测试数据是否正确
3. 验证断言是否匹配实际结果
4. 修改测试脚本后重新运行

#### 5.3 AI执行检查点
- [ ] 已使用 php 命令运行测试脚本，确认无错误
- [ ] 已使用 test/runtime/ztf 命令运行测试
- [ ] 测试结果显示 PASS=1, FAIL=0, SKIP=0
- [ ] 如有失败，已根据错误信息修正代码

### 🔴 步骤6：提交代码（强制要求）
**测试验证通过后，必须按以下步骤提交代码：**

1. **添加测试文件到git（仅添加测试相关文件）**：

   ```bash
   git add module/{moduleName}/test/lib/model.class.php
   git add module/{moduleName}/test/model/{methodName}.php
   git add module/{moduleName}/test/model/yaml/{methodName}/{tableName}.yaml
   ```

2. **提交代码**：
   ```bash
   git commit -m "+ [misc] Add unit tests for {className}::{methodName}() method

   🤖 Generated with [Claude Code](https://claude.ai/code)

   Co-Authored-By: Claude <noreply@anthropic.com>"
   ```

**⚠️ AI大模型提交信息模板（必须严格遵循）：**

```bash
git commit -m "+ [misc] Add unit tests for {className}::{methodName}() method

🤖 Generated with [Claude Code](https://claude.ai/code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

**📋 提交信息格式说明：**
- **符号**：`+` (新增测试) / `*` (修改测试) / `-` (删除测试)
- **分类**：`[misc]` (测试相关固定使用misc)
- **描述**：`Add unit tests for {className}::{methodName}() method`
- **标识**：必须包含Claude Code和Co-Authored-By标记

## 常见测试模式

### 1. CRUD操作测试
```php
// Create测试
r($userTest->createTest($validUser)) && p('result') && e(1);        // 正常创建
r($userTest->createTest($invalidUser)) && p('errors') && e('用户名不能为空'); // 异常创建

// Read测试
r($userTest->getByIdTest(1)) && p('account') && e('admin');         // 存在用户
r($userTest->getByIdTest(999)) && p() && e(false);                 // 不存在用户

// Update测试
r($userTest->updateTest($user)) && p('result') && e(1);            // 正常更新

// Delete测试
r($userTest->deleteTest(1)) && p('result') && e(1);                // 正常删除
```

### 2. 列表查询测试
```php
// 分页测试
r($userTest->getListTest('', 0, 10)) && p() && e(10);              // 分页数量
r($userTest->getListTest('', 10, 10)) && p() && e(5);              // 最后一页

// 条件过滤测试
r($userTest->getListTest('deleted')) && p() && e(0);               // 已删除用户
r($userTest->getListTest('active')) && p() && e(5);                // 活跃用户

// 排序测试
r($userTest->getListTest('', 0, 0, 'id_desc')) && p('0,id') && e(10); // 倒序
```

### 3. 权限验证测试
```php
// 不同角色权限测试
su('admin');
r($userTest->deleteTest(1)) && p('result') && e(1);                // 管理员可删除

su('user');
r($userTest->deleteTest(1)) && p('errors') && e('权限不足');        // 普通用户无权限
```

## 高级技巧

### 1. zendata复杂数据关系处理
```php
// 多表关联数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user{99},test{100}');
$userTable->password->range('123456{100}');
$userTable->role->range('qa{50},dev{200},pm{10}');
$userTable->gen(10);

$groupTable = zenData('usergroup');
$groupTable->account->range('admin,user1,user2,user3');
$groupTable->group->range('1-3');
$groupTable->gen(6);

// 使用YAML文件加载数据
zendata('product')->loadYaml('product_create', false, 2)->gen(5);

// 从现有YAML配置生成数据
$taskTable = zenData('task');
$taskTable->loadYaml('task', false, 1);  // 加载task.yaml配置
$taskTable->status->range('wait{3},doing{5},done{2}');  // 覆盖特定字段
$taskTable->gen(10);
```

### 2. 时间相关测试
```php
// 时间范围测试
$now = time();
$table->last->range($now - 3600, $now + 3600); // 前后1小时范围

// 测试时间格式化
r($userTest->formatTimeTest($now)) && p() && e(date('Y-m-d H:i:s', $now));
```

### 3. 状态机测试
```php
// 测试状态转换
r($bugTest->resolveTest($bugId, 'fixed')) && p('status') && e('resolved');
r($bugTest->activateTest($bugId)) && p('status') && e('active');
r($bugTest->closeTest($bugId)) && p('status') && e('closed');
```

### 4. 异常处理测试
```php
// 测试业务异常
r($userTest->createTest($duplicateUser)) && p('errors,account') && e('用户名已存在');

// 测试数据验证异常
r($userTest->updatePasswordTest($weakPassword)) && p('errors,password') && e('密码强度不够');
```

## 断言方法详解

### 1. 基础断言
- `p()` - 检查整个返回结果
- `p('field')` - 检查指定字段
- `p('field1,field2')` - 检查多个字段
- `p('0:field')` - 检查数组第一个元素的字段

### 2. 期望值类型
- `e('string')` - 字符串期望
- `e(123)` - 数值期望
- `e(1/0)` - 布尔期望
- `e('0')` - 字符串0（表示false或empty）
- `e('~~')` - 空

### 3. 复杂断言示例
```php
// 检查数组长度
r($userTest->getListTest()) && p() && e(10);

// 检查对象属性
r($userTest->getByIdTest(1)) && p('account,realname') && e('admin,管理员');

// 检查错误信息
r($userTest->createTest($invalidUser)) && p('errors,account') && e('用户名不能为空');
```

## 最佳实践

### 1. 命名规范
- 测试文件：使用被测方法名
- 测试类：{moduleName}ModelTest
- 测试方法：{methodName}Test
- YAML文件：{tableName}.yaml

### 2. 测试数据管理
- 每个测试脚本独立准备数据
- 使用合理的数据量（通常10-100条）
- 避免测试间的数据污染
- 考虑数据的业务合理性

### 3. 测试用例和测试步骤组织
- **测试用例层面**：每个测试脚本对应一个测试用例，专注于测试单个方法
- **测试步骤层面**：在测试用例中合理组织测试步骤
  - 正常流程步骤优先
  - 边界值测试步骤完整
  - 异常处理步骤覆盖全面
  - 步骤描述清晰准确
- **🔴 每个测试用例必须包含至少5个测试步骤**

### 4. 断言设计
- 断言粒度适中
- 关键业务逻辑必须断言
- 避免过度断言
- 错误信息要有意义

### 5.理解被测方法
- 理解被测方法的业务逻辑
- 分析被测方法涉及的数据库表
- 理解数据库表的结构
- 表结构定义参考 `db/zentao.sql`

## 注意事项

### 1. 数据库相关
- 测试数据自动回滚
- 外键约束处理
- 事务边界考虑
- 数据隔离保证

### 2. 环境依赖
- 配置文件隔离
- 外部服务Mock
- 文件系统清理
- 网络环境模拟

### 3. 性能考虑
- 控制测试数据量
- 避免复杂计算
- 优化数据库查询
- 并发测试支持

## 📋 单元测试开发检查清单

在完成单元测试脚本开发后，请确认以下所有步骤都已正确执行：

### 🔴 AI大模型强制检查清单（每项必须100%完成）

**📋 代码生成检查**
- [ ] 测试文件名使用小写方法名：`{methodName}.php`
- [ ] 单元测试类名格式：`{moduleName}ModelTest`
- [ ] 测试方法名格式：`{methodName}Test`
- [ ] 每个测试用例包含≥5个 `r()...e()` 测试步骤
- [ ] 每个 `r()...e()` 从行首开始
- [ ] 每个 `r()...e()` 没有换行
- [ ] 文件头包含完整的测试步骤描述
- [ ] **🚨 所有代码行尾无空格**：检查每行末尾是否清洁

**📋 测试验证检查**
- [ ] 已使用 `php` 命令验证测试脚本无语法错误
- [ ] 已使用 `test/runtime/ztf` 命令运行测试
- [ ] 测试结果：PASS=1, FAIL=0, SKIP=0
- [ ] 所有断言都与实际输出匹配

**📋 代码提交检查**
- [ ] 已添加测试相关文件：`git add module/{moduleName}/test/...`
- [ ] 提交信息符合格式：`+ [misc] Add unit tests for...`

### 📝 代码质量检查项
- [ ] **测试步骤完整**：测试用例包含正常流程、边界值、异常情况等测试步骤
- [ ] **断言准确**：每个测试步骤的断言都与实际输出匹配
- [ ] **数据准备充分**：zenData配置合理，覆盖测试所需场景
- [ ] **注释清晰**：测试步骤描述明确，期望结果准确

### ⚠️ 常见错误避免
- [ ] **避免不提交代码**：测试通过后必须提交代码
- [ ] **🚨 避免行尾空格**：所有生成的代码必须移除行尾空格，否则提交会被拒绝

---

**🎯 最终目标：通过遵循本指导文档，生成高质量、可维护的ZenTao单元测试脚本，确保代码质量和系统稳定性。**
