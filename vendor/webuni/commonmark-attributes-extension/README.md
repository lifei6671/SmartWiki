CommonMark Attributes Extension
===============================

[![Packagist](https://img.shields.io/packagist/v/webuni/commonmark-attributes-extension.svg?style=flat-square)](https://packagist.org/packages/webuni/commonmark-attributes-extension)
[![Build Status](https://travis-ci.org/webuni/commonmark-attributes-extension.svg?branch=master)](https://travis-ci.org/webuni/commonmark-attributes-extension)
[![StyleCI](https://styleci.io/repos/38884603/shield)](https://styleci.io/repos/38884603)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webuni/commonmark-attributes-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webuni/commonmark-attributes-extension/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/webuni/commonmark-attributes-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/webuni/commonmark-attributes-extension/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8fbbdeb9-d7ba-4c5c-8d88-db950a668265.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8fbbdeb9-d7ba-4c5c-8d88-db950a668265)

The Attributes extension adds a syntax to define attributes on the various HTML elements in markdown’s output.

Installation
------------

This project can be installed via Composer:

    composer require webuni/commonmark-attributes-extension
    
Usage
-----

```php
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Webuni\CommonMark\AttributesExtension\AttributesExtension;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new AttributesExtension());

$converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

echo $converter->convertToHtml('# Hello World!');
```

Syntax
------

The basic syntax was inspired by [Kramdown](http://kramdown.gettalong.org/syntax.html#attribute-list-definitions)‘s Attribute Lists feature.

You can assign any attribute to a block-level element. Just directly prepend or follow the block with a block inline attribute list.
That consists of a left curly brace, optionally followed by a colon, the attribute definitions and a right curly brace:

```markdown
> A nice blockquote
{: title="Blockquote title"}

{#id .class}
## Header
```

As with a block-level element you can assign any attribute to a span-level elements using a span inline attribute list,
that has the same syntax and must immediately follow the span-level element:

```markdown
This is *red*{style="color: red"}.
```
