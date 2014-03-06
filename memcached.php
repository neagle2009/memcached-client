<?php
/**
 * PHP memcached client class
 *
 * For build develop environment in windows using memcached.
 *
 * @package     memcached-client
 * @copyright   Copyright 2013-2014, Fwolf
 * @author      Fwolf <fwolf.aide+memcached-client@gmail.com>
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL v3
 * @since       2013-01-18
 * @version     1.0.0
 */
class Memcached {
    // Predefined Constants
    // See: http://php.net/manual/en/memcached.constants.php

    // Defined in php_memcached.c
    const OPT_COMPRESSION = -1001;
    const OPT_SERIALIZER = -1003;

    // enum memcached_serializer in php_memcached
    const SERIALIZER_PHP = 1;
    const SERIALIZER_IGBINARY = 2;
    const SERIALIZER_JSON = 3;

    // Defined in php_memcached.c
    const OPT_PREFIX_KEY = -1002;

    // enum memcached_behavior_t in libmemcached
    const OPT_HASH = 2;     //MEMCACHED_BEHAVIOR_HASH

    // enum memcached_hash_t in libmemcached
    const HASH_DEFAULT = 0;
    const HASH_MD5 = 1;
    const HASH_CRC = 2;
    const HASH_FNV1_64 = 3;
    const HASH_FNV1A_64 = 4;
    const HASH_FNV1_32 = 5;
    const HASH_FNV1A_32 = 6;
    const HASH_HSIEH = 7;
    const HASH_MURMUR = 8;

    // enum memcached_behavior_t in libmemcached
    const OPT_DISTRIBUTION = 9;     // MEMCACHED_BEHAVIOR_DISTRIBUTION

    // enum memcached_server_distribution_t in libmemcached
    const DISTRIBUTION_MODULA = 0;
    const DISTRIBUTION_CONSISTENT = 1;

    // enum memcached_behavior_t in libmemcached
    const OPT_LIBKETAMA_COMPATIBLE = 16;    // MEMCACHED_BEHAVIOR_KETAMA_WEIGHTED
    const OPT_BUFFER_WRITES = 10;           // MEMCACHED_BEHAVIOR_BUFFER_REQUESTS
    const OPT_BINARY_PROTOCOL = 18;         // MEMCACHED_BEHAVIOR_BINARY_PROTOCOL
    const OPT_NO_BLOCK = 0;                 // MEMCACHED_BEHAVIOR_NO_BLOCK
    const OPT_TCP_NODELAY = 1;              // MEMCACHED_BEHAVIOR_TCP_NODELAY
    const OPT_SOCKET_SEND_SIZE = 4;         // MEMCACHED_BEHAVIOR_SOCKET_SEND_SIZE
    const OPT_SOCKET_RECV_SIZE = 5;         // MEMCACHED_BEHAVIOR_SOCKET_RECV_SIZE
    const OPT_CONNECT_TIMEOUT = 14;         // MEMCACHED_BEHAVIOR_CONNECT_TIMEOUT
    const OPT_RETRY_TIMEOUT = 15;           // MEMCACHED_BEHAVIOR_RETRY_TIMEOUT
    const OPT_SEND_TIMEOUT = 19;            // MEMCACHED_BEHAVIOR_SND_TIMEOUT
    const OPT_RECV_TIMEOUT = 20;            // MEMCACHED_BEHAVIOR_RCV_TIMEOUT
    const OPT_POLL_TIMEOUT = 8;             // MEMCACHED_BEHAVIOR_POLL_TIMEOUT
    const OPT_CACHE_LOOKUPS = 6;            // MEMCACHED_BEHAVIOR_CACHE_LOOKUPS
    const OPT_SERVER_FAILURE_LIMIT = 21;    // MEMCACHED_BEHAVIOR_SERVER_FAILURE_LIMIT

    // In php_memcached config, define HAVE_MEMCACHED_IGBINARY default 1,
    // then use ifdef define HAVE_IGBINARY to 1.
    const HAVE_IGBINARY = 1;
    // In php_memcached config, define HAVE_JSON_API default 1,
    // then use ifdef define HAVE_JSON to 1.
    const HAVE_JSON = 1;

    // Defined in php_memcached.c, (1<<0)
    const GET_PRESERVE_ORDER = 1;

    // enum memcached_return_t in libmemcached
    const RES_SUCCESS = 0;                  // MEMCACHED_SUCCESS
    const RES_FAILURE = 1;                  // MEMCACHED_FAILURE
    const RES_HOST_LOOKUP_FAILURE = 2;      // MEMCACHED_HOST_LOOKUP_FAILURE
    const RES_UNKNOWN_READ_FAILURE = 7;     // MEMCACHED_UNKNOWN_READ_FAILURE
    const RES_PROTOCOL_ERROR = 8;           // MEMCACHED_PROTOCOL_ERROR
    const RES_CLIENT_ERROR = 9;             // MEMCACHED_CLIENT_ERROR
    const RES_SERVER_ERROR = 10;            // MEMCACHED_SERVER_ERROR
    const RES_WRITE_FAILURE = 5;            // MEMCACHED_WRITE_FAILURE
    const RES_DATA_EXISTS = 12;             // MEMCACHED_DATA_EXISTS
    const RES_NOTSTORED = 14;               // MEMCACHED_NOTSTORED
    const RES_NOTFOUND = 16;                // MEMCACHED_NOTFOUND
    const RES_PARTIAL_READ = 18;            // MEMCACHED_PARTIAL_READ
    const RES_SOME_ERRORS = 19;             // MEMCACHED_SOME_ERRORS
    const RES_NO_SERVERS = 20;              // MEMCACHED_NO_SERVERS
    const RES_END = 21;                     // MEMCACHED_END
    const RES_ERRNO = 26;                   // MEMCACHED_ERRNO
    const RES_BUFFERED = 32;                // MEMCACHED_BUFFERED
    const RES_TIMEOUT = 31;                 // MEMCACHED_TIMEOUT
    const RES_BAD_KEY_PROVIDED = 33;        // MEMCACHED_BAD_KEY_PROVIDED
    const RES_CONNECTION_SOCKET_CREATE_FAILURE = 11;    // MEMCACHED_CONNECTION_SOCKET_CREATE_FAILURE

    // Defined in php_memcached.c
    const RES_PAYLOAD_FAILURE = -1001;


    /**
     * Dummy option array
     *
     * @var array
     */
    protected $aOption = array(
        Memcached::OPT_COMPRESSION  => true,
        Memcached::OPT_SERIALIZER   => Memcached::SERIALIZER_PHP,
        Memcached::OPT_PREFIX_KEY   => '',
        Memcached::OPT_HASH         => Memcached::HASH_DEFAULT,
        Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_MODULA,
        Memcached::OPT_LIBKETAMA_COMPATIBLE => false,
        Memcached::OPT_BUFFER_WRITES    => false,
        Memcached::OPT_BINARY_PROTOCOL  => false,
        Memcached::OPT_NO_BLOCK     => false,
        Memcached::OPT_TCP_NODELAY  => false,

        // This two is a value by guess
        Memcached::OPT_SOCKET_SEND_SIZE => 32767,
        Memcached::OPT_SOCKET_RECV_SIZE => 65535,

        Memcached::OPT_CONNECT_TIMEOUT  => 1000,
        Memcached::OPT_RETRY_TIMEOUT    => 0,
        Memcached::OPT_SEND_TIMEOUT     => 0,
        Memcached::OPT_RECV_TIMEOUT     => 0,
        Memcached::OPT_POLL_TIMEOUT     => 1000,
        Memcached::OPT_CACHE_LOOKUPS    => false,
        Memcached::OPT_SERVER_FAILURE_LIMIT => 0,
    );


    /**
     * Last result code
     *
     * @var int
     */
    protected $iResultCode = 0;


    /**
     * Last result message
     *
     * @var string
     */
    protected $sResultMessage = '';


    /**
     * Server list array/pool
     *
     * I added array index.
     *
     * array (
     *  host:port:weight => array(
     *      host,
     *      port,
     *      weight,
     *  )
     * )
     *
     * @var array
     */
    protected $aServer = array();


    /**
     * Socket connect handle
     *
     * Point to last successful connect, ignore others
     * @var resource
     */
    protected $rSocket = null;


    /**
     * Add a serer to the server pool
     *
     * @param   string  $host
     * @param   int     $port
     * @param   int     $weight
     * @return  boolean
     */
    public function addServer ($host, $port = 11211, $weight = 0) {
        $key = $host . ':' . strval($port) . ':' . strval($weight);
        if (isset($this->aServer[$key])) {
            // Dup
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'Server duplicate.';
            return false;
        }
        else {
            $this->aServer[] = array(
                'host'  => $host,
                'port'  => $port,
                'weight'    => $weight,
            );

            $this->Connect();
            return true;
        }
    } // end of fund addServer


    /**
     * Add multiple servers to the server pool
     *
     * @param   array   $servers
     * @return  boolean
     */
    public function addServers ($servers) {
        foreach ((array)$servers as $svr) {
            $host = array_shift($svr);
            $port = array_shift($svr);
            if (false === $port)
                $port = 11211;
            $weight = array_shift($svr);
            if (false === $weight)
                $weight = 0;

            $this->addServer($host, $port, $weight);
        }

        return true;
    } // end of func addServers


    /**
     * Connect to memcached server
     *
     * @return  boolean
     */
    protected function Connect () {
        $rs = false;

        foreach ((array)$this->aServer as $svr) {
            $error = 0;
            $errstr = '';
            $rs = @fsockopen($svr['host'], $svr['port'], $error, $errstr);
            if ($rs)
                $this->rSocket = $rs;
            else {
                $s = 'Connect to ' . $svr['host'] . ':' . $svr['port']
                    . " error:\n\t[" . $error . '] ' . $errstr;
                error_log($s);
            }
        }

        if (is_null($this->rSocket)) {
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'No server avaliable.';
            return false;
        }
        else {
            $this->iResultCode = Memcached::RES_SUCCESS;
            $this->sResultMessage = '';
            return true;
        }
    } // end of func Connect


    /**
     * Delete an item
     *
     * @param   string  $key
     * @param   int     $time       Ignored
     * @return  boolean
     */
    public function delete ($key, $time = 0) {
        $this->SocketWrite('delete ' . addslashes($key));

        $s = $this->SocketRead();
        if ('DELETED' == $s) {
            $this->iResultCode = Memcached::RES_SUCCESS;
            $this->sResultMessage = '';
            return true;
        }
        else {
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'Delete fail.';
            return false;
        }
    } // end of func delete


    /**
     * Retrieve an item
     *
     * @param   string  $key
     * @param   callable    $cache_cb       Ignored
     * @param   float   $cas_token          Ignored
     * @return  mixed
     */
    public function get ($key, $cache_cb = null, $cas_token = null) {
        $this->SocketWrite('get ' . addslashes($key));

        $s_result = '';
        $s = '';
        $s = $this->SocketRead();

        if ('VALUE' != substr($s, 0, 5)) {
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'Get fail.';
            return false;
        }
        else {
            $s = $this->SocketRead();
            while ('END' != $s) {
                $s_result .= $s;
                $s = $this->SocketRead();
            }
            $this->iResultCode = Memcached::RES_SUCCESS;
            $this->sResultMessage = '';
        }

        return $s_result;
    } // end of func get


    /**
     * Get a memcached option value
     *
     * @param   int     $option
     * @return  mixed
     */
    public function getOption ($option) {
        if (isset($this->aOption[$option])) {
            $this->iResultCode = Memcached::RES_SUCCESS;
            $this->sResultMessage = '';
            return $this->aOption[$option];
        }
        else {
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'Option not seted.';
            return false;
        }
    } // end of func getOption


    /**
     * Return the result code of the last operation
     *
     * @return  int
     */
    public function getResultCode () {
        return $this->iResultCode;
    } // end of func getResultCode


    /**
     * Return the message describing the result of the last opteration
     *
     * @return  string
     */
    public function getResultMessage () {
        return $this->sResultMessage;
    } // end of func getResultMessage


    /**
     * Get list array of servers
     *
     * @see     $aServer
     * @return  array
     */
    public function getServerList () {
        return $this->aServer;
    } // end of func getServerList


    /**
     * Got item key
     *
     * @param   string  $key
     * @return  string
     */
    public function Key ($key) {
        return addslashes($this->aOption[Memcached::OPT_PREFIX_KEY]) . $key;
    } // end of func Key


    /**
     * Store an item
     *
     * @param   string  $key
     * @param   mixed   $val
     * @param   int     $expt
     * @return  boolean
     */
    public function set ($key, $val, $expt = 0) {
        $this->SocketWrite('set ' . addslashes($key) . ' 0 '
            . $expt . ' ' . strlen($val));
        $this->SocketWrite($val);

        $s = $this->SocketRead();
        if ('STORED' == $s) {
            $this->iResultCode = Memcached::RES_SUCCESS;
            $this->sResultMessage = '';
            return true;
        }
        else {
            $this->iResultCode = Memcached::RES_FAILURE;
            $this->sResultMessage = 'Set fail.';
            return false;
        }
    } // end of func set


    /**
     * Set a memcached option
     *
     * @param   int     $option
     * @param   mixed   $value
     * @return  boolean
     */
    public function setOption ($option, $value) {
        $this->aOption[$option] = $value;
        return true;
    } // end of func setOption


    /**
     * Set memcached options
     *
     * @param   array   $options
     * @return  bollean
     */
    public function setOptions ($options) {
        $this->aOption = array_merge($this->aOption, $options);
        return true;
    } // end of func setOptions


    /**
     * Read from socket
     *
     * @return  string
     */
    protected function SocketRead () {
        if (is_null($this->rSocket))
            return '';

        return trim(fgets($this->rSocket));
    } // end of func SocketRead


    /**
     * Write data to socket
     *
     * @param   string  $cmd
     * @param   boolean $result     Need result/response
     * @return  mixed
     */
    protected function SocketWrite ($cmd, $result = false) {
        if (is_null($this->rSocket))
            return false;

        fwrite($this->rSocket, $cmd . "\r\n");

        if (true == $result)
            return $this->SocketRead();

        return true;
    } // end of func SocketWrite


} // end of class Memcached
?>
