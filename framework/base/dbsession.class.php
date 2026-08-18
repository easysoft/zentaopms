<?php
declare(strict_types=1);
/**
 * 数据库会话存储驱动。 The database session storage driver.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * 数据库会话存储处理器。 The database session handler.
 *
 * 仅当 $config->sessionDriver 为 'db' 时启用；默认为 'file'，所以现有部署完全不受影响。
 * Only used when $config->sessionDriver is 'db'. The default is 'file', so existing
 * deployments are completely unaffected.
 *
 * 为什么需要它：文件会话要求每台应用服务器共享同一个文件系统。在网络文件系统上，
 * 一次会话读写往往比一次数据库查询慢一到两个数量级，而应用本来就连着数据库。
 * 在一个这样的部署上实测：会话存储占了请求总时间的约 97%，把会话搬到数据库之后，
 * 单次请求从约 800 毫秒降到约 26 毫秒。
 * Why this exists: file sessions require every application server to share a
 * filesystem. On a network filesystem one session read/write is often one to two
 * orders of magnitude slower than a database query, while the application is already
 * connected to the database anyway. Measured on one such deployment, session storage
 * accounted for roughly 97% of total request time; moving it to the database took a
 * warm request from about 800ms to about 26ms.
 *
 * 所需数据表（前缀取自 $config->db->prefix）:
 * Required table (the prefix comes from $config->db->prefix):
 *
 *   CREATE TABLE IF NOT EXISTS `zt_session` (
 *     `id`      varchar(128) NOT NULL,
 *     `data`    mediumblob   NOT NULL,
 *     `expires` int unsigned NOT NULL,
 *     PRIMARY KEY (`id`),
 *     KEY `expires` (`expires`)
 *   ) ENGINE=InnoDB;
 *
 * data 用 mediumblob 而不是 text：序列化后的会话是二进制安全的字节串，不应经过字符集转换。
 * `data` is a mediumblob rather than text because serialized session data is a
 * binary-safe byte string and must not go through charset conversion.
 */
class ztDBSessionHandler implements SessionHandlerInterface
{
    /**
     * 数据库连接。 The database handler.
     *
     * @var object
     */
    public $dbh;

    /**
     * 会话表名。 The session table name.
     *
     * @var string
     */
    public $table;

    /**
     * 会话有效期（秒）。 The session lifetime in seconds.
     *
     * @var int
     */
    public $lifetime;

    /**
     * 当前会话ID。 The current session id.
     *
     * @var string
     */
    public $sessionID;

    /**
     * 会话ID允许的字符，与文件驱动保持一致。
     * Allowed characters in a session id, matching the file driver.
     */
    const ID_PATTERN = '/^\w+$/';

    /**
     * 按配置决定是否启用数据库会话，不启用时返回 null。
     * Decide whether to use database sessions, returning null when not enabled.
     *
     * 把这个判断放在这里而不是放在 startSession() 里，是为了让它可以被单独测试。
     * This decision lives here rather than in startSession() so that it can be tested
     * on its own.
     *
     * @param  object      $config
     * @param  object|null $dbh
     * @param  bool        $apiMode  API 会话始终使用自己的目录。 API sessions always use their own directory.
     * @static
     * @access public
     * @return ztDBSessionHandler|null
     */
    public static function createIfEnabled($config, $dbh, bool $apiMode = false)
    {
        if($apiMode) return null;

        $driver = isset($config->sessionDriver) ? $config->sessionDriver : 'file';
        if($driver !== 'db') return null;

        /* 没有可用连接时退回文件驱动，避免因为数据库不可用而完全无法响应。 */
        /* Fall back to the file driver when there is no usable handle, so that an
         * unavailable database does not stop the app from responding at all. */
        if(empty($dbh)) return null;

        return new ztDBSessionHandler($dbh, $config);
    }

    /**
     * Construct.
     *
     * @param  object $dbh
     * @param  object $config
     * @access public
     * @return void
     */
    public function __construct($dbh, $config)
    {
        $this->dbh   = $dbh;
        $this->table = $config->db->prefix . 'session';

        $lifetime = (int)ini_get('session.gc_maxlifetime');
        $this->lifetime = $lifetime > 0 ? $lifetime : 1440;

        /* 与文件驱动一致：请求结束时写回会话。 Mirror the file driver: write the session back at request end. */
        register_shutdown_function('session_write_close');
    }

    /**
     * 获取当前会话ID。 Get the current session id.
     *
     * @access public
     * @return string
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    /**
     * Creates a new session, or reinitializes an existing one.
     *
     * @param  string $savePath
     * @param  string $sessionName
     * @access public
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    /**
     * Closes the current session.
     *
     * @access public
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function close(): bool
    {
        return true;
    }

    /**
     * Reads the session data and returns it.
     *
     * @param  string       $id
     * @access public
     * @return string|false
     */
    #[\ReturnTypeWillChange]
    public function read($id): string|false
    {
        if(!preg_match(self::ID_PATTERN, (string)$id)) return false;
        $this->sessionID = $id;

        $stmt = $this->dbh->prepare("SELECT `data` FROM `{$this->table}` WHERE `id` = ? AND `expires` > ?");
        if(!$stmt) return '';

        $stmt->execute(array($id, time()));
        $data = $stmt->fetchColumn();

        return $data === false ? '' : (string)$data;
    }

    /**
     * Writes the session data.
     *
     * 单条 upsert，写入本身是原子的，等价于文件驱动的 LOCK_EX。
     * A single upsert, so the write itself is atomic -- the equivalent of the file
     * driver's LOCK_EX.
     *
     * @param  string $id
     * @param  string $sessData
     * @access public
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function write($id, $sessData): bool
    {
        if(!preg_match(self::ID_PATTERN, (string)$id)) return true;

        $stmt = $this->dbh->prepare("INSERT INTO `{$this->table}` (`id`, `data`, `expires`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `data` = VALUES(`data`), `expires` = VALUES(`expires`)");
        if(!$stmt) return true;

        $stmt->execute(array($id, $sessData, time() + $this->lifetime));

        return true;
    }

    /**
     * Destroys a session.
     *
     * @param  string $id
     * @access public
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function destroy($id): bool
    {
        if(!preg_match(self::ID_PATTERN, (string)$id)) return true;

        $stmt = $this->dbh->prepare("DELETE FROM `{$this->table}` WHERE `id` = ?");
        if($stmt) $stmt->execute(array($id));

        return true;
    }

    /**
     * Cleans up expired sessions.
     *
     * @param  int       $maxlifeTime
     * @access public
     * @return int|false
     */
    #[\ReturnTypeWillChange]
    public function gc($maxlifeTime): int|false
    {
        /* API session never expires -- 与文件驱动一致。 Same rule as the file driver. */
        global $config;
        if(defined('RUN_MODE') && RUN_MODE == 'api') return 0;
        if(isset($config->sessionVar) && isset($_GET[$config->sessionVar])) return 0;

        $stmt = $this->dbh->prepare("DELETE FROM `{$this->table}` WHERE `expires` < ?");
        if(!$stmt) return 0;

        $stmt->execute(array(time()));

        return $stmt->rowCount();
    }
}
