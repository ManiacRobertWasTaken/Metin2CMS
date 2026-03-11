<?php

class Cache
{
	private static $instance = null;
	private $redis = null;
	private $connected = false;

	private function __construct()
	{
		try {
			$host = getenv('REDIS_HOST') ?: 'redis';
			$this->redis = new Redis();
			$this->connected = $this->redis->connect($host, 6379, 1);
		} catch (Exception $e) {
			$this->connected = false;
		}
	}

	public static function getInstance()
	{
		if (self::$instance === null)
			self::$instance = new self();
		return self::$instance;
	}

	public function get($key)
	{
		if (!$this->connected) return false;
		try {
			$val = $this->redis->get($key);
			return $val !== false ? unserialize($val) : false;
		} catch (Exception $e) {
			return false;
		}
	}

	public function set($key, $val, $ttl = 60)
	{
		if (!$this->connected) return false;
		try {
			return $this->redis->setex($key, $ttl, serialize($val));
		} catch (Exception $e) {
			return false;
		}
	}

	public function del($key)
	{
		if (!$this->connected) return false;
		try {
			return $this->redis->del($key);
		} catch (Exception $e) {
			return false;
		}
	}

	public function flush($pattern)
	{
		if (!$this->connected) return false;
		try {
			$keys = $this->redis->keys($pattern);
			if (!empty($keys))
				return $this->redis->del($keys);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
