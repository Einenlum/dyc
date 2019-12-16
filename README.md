# Dyc

A very simple DIC, supporting autowiring. For fun only.

## Use

```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

$dic = new \Dyc\Dic();
$dic->set(\Foo\Bar::class, function(\Dyc\Dic $dic) {
    return new \Foo\Bar($dic->get(\Bar\Baz::class));
});

$bar = $dic->get(\Foo\Bar::class);
```

## Autowiring

We recommend using [haydenpierce/class-finder](https://packagist.org/packages/haydenpierce/class-finder), to get a list of all FQCN in your project.

```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

$dic = new \Dyc\Dic();
$classes = \HaydenPierce\ClassFinder\ClassFinder::getClassesInNamespace('Foo');
$dic->autowire($classes);

$bar = $dic->get(\Foo\Bar::class);
```

If one service requires an interface or a scalar, you will need to rewrite the whole definition for this one:

```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

$dic = new \Dyc\Dic();
$classes = \HaydenPierce\ClassFinder\ClassFinder::getClassesInNamespace('Foo');
$dic->autowire($classes);
$dic->set(\Some\Scalar\Dependent\Service::class, function(\Dyc\Dic $dic) {
    return new \Some\Scalar\Dependent\Service($dic->get(\Foo\Bar::class), 'some api key');
});

$service = $dic->get(\Some\Scalar\Dependent\Service::class);
```
