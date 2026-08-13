# APIv2 同进程调用库

`apiV2Invoker` 用于在已经初始化的禅道 PHP 请求中，直接调用 APIv2 路由，不发起 HTTP 请求，也不依赖 PHP CLI 子进程。

## 适用场景

- 在普通浏览器请求、控制器、模型中模拟一次 APIv2 调用。
- 以指定账号身份测试权限和业务逻辑。
- 在单元测试、调试脚本中直接复用 APIv2 接口。

## 快速开始

```php
include '/path/to/zentaopms/lib/apiv2invoker/apiv2invoker.class.php';

$result = apiV2_request(array(
    'method'  => 'GET',
    'path'    => '/products',
    'account' => 'admin',
));
```

等价类方法调用：

```php
$result = apiV2Invoker::request(array(
    'method'  => 'GET',
    'path'    => '/products',
    'account' => 'admin',
));
```

## 请求参数

| 参数 | 必填 | 说明 |
|---|---|---|
| `method` | 否 | 默认 `GET`，支持 `GET`、`POST`、`PUT`、`DELETE`、`OPTIONS` |
| `path` | 是 | APIv2 路径，例如 `/products`、`/products/1` |
| `account` | 是 | 以哪个用户身份执行 |
| `query` | 否 | 查询参数，数组或 query string |
| `body` | 否 | 请求体，数组、对象或 JSON 字符串 |
| `headers` | 否 | 可选 HTTP header |
| `files` | 否 | 可选模拟上传文件 |
| `jsonDecode` | 否 | 是否自动 JSON 解码，默认 `true` |

当前同进程模式使用已经初始化的 `$app`，因此不接收 `appRoot` 参数。

## 返回值

默认自动解码 JSON：

- JSON 响应返回数组；
- 非 JSON 响应返回原始字符串；
- 空响应返回 `null`。

如需原始响应：

```php
$result = apiV2_request(array(
    'method'     => 'GET',
    'path'       => '/products',
    'account'    => 'admin',
    'jsonDecode' => false,
));
```

获取状态码和原始响应：

```php
apiV2Invoker::lastStatusCode();
apiV2Invoker::lastRawBody();
```

## 快捷方法

```php
apiV2Invoker::get('/products', array(), 'admin');
apiV2Invoker::post('/products', array('name' => '测试产品'), 'admin');
apiV2Invoker::put('/products/1', array('name' => '新名称'), 'admin');
apiV2Invoker::delete('/products/1', array(), 'admin');
```

## 身份模拟

`account` 为必填字段。

执行器会：

1. 查询目标用户；
2. 计算其 `rights`、`groups`、`view`、`admin`；
3. 临时写入 session；
4. 执行 API；
5. 恢复原 session。

不会记录登录日志，也不会发放登录积分。

## 状态管理

`apiV2StateManager` 会在执行前后保存并恢复：

- 全局变量：
  - `$app`
  - `$config`
  - `$lang`
  - `$common`
  - `$dbh`
  - `$dao`
  - `$routes`
  - `$filter`
  - `$loadedModels`
- 超全局变量：
  - `$_GET`
  - `$_POST`
  - `$_FILES`
  - `$_COOKIE`
  - `$_SERVER`
  - `$_SESSION`
- 类静态属性。
- output buffer 层级。

## 使用边界

同进程执行无法保证所有业务代码中的方法内 static 局部变量完全恢复。

因此建议：

- 适合终点调用、调试、测试和短生命周期请求。
- 调用后不应依赖未受状态管理覆盖的方法级 static 缓存。
- 如要求绝对无副作用，应使用独立进程方案。

## 会话处理

同进程执行不会重启 session。

执行前后会深拷贝保存并恢复 `$_SESSION`、`session_id()`、`session_name()`、`session_status()`。

内部 API 执行过程中不应调用：

```php
session_regenerate_id()
session_destroy()
session_write_close()
session_start()
```

如果 session 生命周期被改变，当前实现无法完全透明恢复。

## 文件结构

```text
lib/apiv2invoker/
├── apiv2invoker.class.php
└── apiv2state.class.php
```

- `apiv2invoker.class.php`：对外入口和同进程执行。
- `apiv2state.class.php`：状态保存与恢复。
