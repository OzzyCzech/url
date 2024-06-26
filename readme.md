# Sphido / URL

Simple and fast PHP library to parse and manipulate with URLs.

## Work with URL object

Return current URL

```php
$url = Url::current();
$url->path('whatever');
echo $url; // will made string URL

// or 

echo Url::current('/some/path'); // change current URL with path 
```
Create URL instance from URL instance:

```php
$url = new Url('http://www.sphido.org/'); // crate instance from string
$url2 = new Url($url); // crate instance from URL instance
```

Fluent interface for simplify URL changes:

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