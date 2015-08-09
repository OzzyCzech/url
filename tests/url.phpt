<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

{ // simple URL function
	$_SERVER['REQUEST_URI'] = '/PATH/index.php?param=1#fragment';
	$_SERVER['HTTP_HOST'] = 'SERVER';
	$_SERVER['HTTPS'] = 'on';
	$_SERVER['SERVER_PORT'] = 443;

	Assert::same(url('/some/path'), 'https://SERVER/some/path');
	Assert::same(url('/other/path'), 'https://SERVER/other/path');
	Assert::same(url('/other/path', ['param' => 123456]), 'https://SERVER/other/path?param=123456');
}

{ // query in path
	$url = new Url('http://www.sphido.org/?param=value');
	Assert::same($url->query, ['param' => 'value']);
}

{ // append param to query
	$url = new Url('http://www.sphido.org/?param=value');
	$url->query['param2'] = 'value';
	Assert::same('http://www.sphido.org/?param=value&param2=value', strval($url));
}

{ // fragment in path
	$url = new Url('http://www.sphido.org/#home');
	Assert::same($url->fragment, 'home');
}

{ // username and password
	$url = new Url('http://username:password@sphido.org/');
	Assert::same($url->host, 'sphido.org');
	Assert::same($url->user, 'username');
	Assert::same($url->pass, 'password');
	Assert::same('http://username:password@sphido.org/', strval($url));
}

{ // ftp, news, https, http
	$url = new Url('ftp://username:password@sphido.org/');
	Assert::same($url->scheme, 'ftp');
	Assert::same($url->host, 'sphido.org');
	Assert::same($url->user, 'username');
	Assert::same($url->pass, 'password');
}

{ // port in HTTP_HOST
	$_SERVER['REQUEST_URI'] = '/path/to';
	$_SERVER['HTTP_HOST'] = 'SERVER:80808080880';
	$_SERVER['HTTPS'] = 'on';
	Assert::same('https://SERVER/path/to', strval(Url::current()));
}

{ // default ports
	$url = new Url('ftp://sphido.org:21');
	Assert::same($url->port, 21);
	Assert::same('ftp://sphido.org', strval($url));

	$url = new Url('http://sphido.org:80');
	Assert::same($url->port, 80);
	Assert::same('http://sphido.org/', strval($url));

	$url = new Url('https://sphido.org:443');
	Assert::same($url->port, 443);
	Assert::same('https://sphido.org/', strval($url));

	$url = new Url('news://sphido.org:119');
	Assert::same($url->port, 119);
	Assert::same('news://sphido.org', strval($url));

	$url = new Url('nntp://sphido.org:119');
	Assert::same($url->port, 119);
	Assert::same('nntp://sphido.org', strval($url));
}