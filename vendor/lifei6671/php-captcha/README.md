# php-captcha
一个PHP实现的验证码库

## 图片实例

![1](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/1.png)
![2](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/2.png)
![3](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/3.png)
![4](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/4.png)
![5](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/5.png)
![6](https://raw.githubusercontent.com/lifei6671/php-captcha/master/examples/image/6.png)


## 安装

使用 Composer

```json
{
    "require": {
            "lifei6671/php-captcha": "0.*"
    }
}
```

## 用法

```php
<?php
use Minho\Captcha\CaptchaBuilder;

$captch = new CaptchaBuilder();

$captch->initialize([
    'width' => 150,     // 宽度
    'height' => 50,     // 高度
    'line' => false,    // 直线
    'curve' => true,    // 曲线
    'noise' => 1,       // 噪点背景
    'fonts' => []       // 字体
]);

$captch->create();
```

直接输出图片：

```php
<?php
$captch->output(1);
```

保存图片到硬盘：

```php
<?php

$captch->save('1.png',1);
```

获取验证码文字：

```php
<?php

$_SESSION['captch'] = $captch->getText();
```