<?php
/** @author Roman Ozana <ozana@omdesign.cz> */

/**
 * @method Url scheme(string $scheme)
 * @method Url host(string $host)
 * @method Url port(int $port)
 * @method Url user(string $user)
 * @method Url pass(string $pass)
 * @method Url query(array $query)
 * @method Url fragment(string $fragment)
 */
class Url {

	public $scheme = '';
	public $host = '';
	public $port = '';
	public $user = '';
	public $pass = '';
	public $path = '';
	public $query = [];
	public $fragment = '';
	public static $ports = [
		'http' => 80,
		'https' => 443,
		'ftp' => 21,
		'news' => 119,
		'nntp' => 119,
	];

	public function __construct($url = null) {
		if ($u = parse_url($url)) {
			$this->scheme = isset($u['scheme']) ? $u['scheme'] : '';
			$this->port = isset($u['port']) ? $u['port'] : (isset(self::$ports[$this->scheme]) ? self::$ports[$this->scheme] : null);
			$this->host = isset($u['host']) ? rawurldecode($u['host']) : '';
			$this->user = isset($u['user']) ? rawurldecode($u['user']) : '';
			$this->pass = isset($u['pass']) ? rawurldecode($u['pass']) : '';
			$this->fragment = isset($u['fragment']) ? rawurldecode($u['fragment']) : '';
			$this->path(isset($u['path']) ? $u['path'] : '');
			isset($u['query']) ? parse_str($u['query'], $this->query) : null;
		} elseif ($url instanceof self) {
			foreach ($url as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}

	public function path($path) {
		$this->path = ($this->scheme === 'https' || $this->scheme === 'http' ? '/' . ltrim($path, '/') : $path);
		return $this;
	}

	/** @return self */
	public static function current($uri = null) {
		$url = new Url($uri === null && isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $uri);
		$url->scheme = (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off') ? 'https' : 'http');
		$url->host = preg_replace('#:\d+$#', '', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')));
		$url->port = isset($_SERVER['SERVER_PORT']) ? intval($_SERVER['SERVER_PORT']) : null;
		return $url;
	}

	/** @return self */
	public function __call($name, $arguments) {
		$this->{$name} = reset($arguments);
		return $this;
	}

	function __toString() {
		return
			($this->scheme ? $this->scheme . ':' : '') . '//' .
			($this->user !== '' ? rawurlencode($this->user) . ($this->pass === '' ? '' : ':' . rawurlencode(
						$this->pass
					)) . '@' : '') .
			($this->host) .
			($this->port && (!isset(self::$ports[$this->scheme]) || $this->port !== self::$ports[$this->scheme]) ? ':' . $this->port : '') .
			($this->path) .
			(($q = http_build_query($this->query, '', '&', PHP_QUERY_RFC3986)) ? '?' . $q : '') .
			($this->fragment === '' ? '' : '#' . $this->fragment);
	}
}

/**
 * Return current URL with get query.
 *
 * @param  null|string $slug
 * @param array $query
 * @return string
 */
function url($slug = null, array $query = []) {
	static $url = null;
	if ($url === null) $url = strval(Url::current('/'));
	return $url . ltrim($slug, '/') . (($q = http_build_query($query, '', '&', PHP_QUERY_RFC3986)) ? '?' . $q : '');
}
