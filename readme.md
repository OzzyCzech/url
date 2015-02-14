# Sphido / URL

[![Build Status](https://travis-ci.org/sphido/url.svg?branch=master)](https://travis-ci.org/sphido/url) [![Latest Stable Version](https://poser.pugx.org/sphido/url/v/stable.svg)](https://packagist.org/packages/sphido/url) [![Total Downloads](https://poser.pugx.org/sphido/url/downloads.svg)](https://packagist.org/packages/sphido/url) [![Latest Unstable Version](https://poser.pugx.org/sphido/url/v/unstable.svg)](https://packagist.org/packages/sphido/url) [![License](https://poser.pugx.org/sphido/url/license.svg)](https://packagist.org/packages/sphido/url)

## Work with URL object

Return current URL

```php
Url::current();
```

Create URL instance from URL instance:

```php
$url = new Url('http://www.sphido.org/')
$url2 = new Url($url);
```

Fluent interface

```php
echo Url::current()->path('/a/b/c')->host('sphido.org')->port(123456)->scheme('ftp');
```

## URL function

Function `url()` it's fastest way how to generate in application URL:

```php
echp url('/path')
echo url('css/main.css');
echo url('css/main.css', ['param' => 'value']);
```
