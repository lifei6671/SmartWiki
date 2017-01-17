## 简介
SmartWiki是一款针对IT团队开发的简单好用的文档管理系统。
可以用来储存日常接口文档，数据库字典，手册说明等文档。内置项目管理，用户管理，权限管理等功能，能够满足大部分中小团队的文档管理需求。

## 在线演示

演示地址： [https://www.iminho.me](https://www.iminho.me)
账号密码： test123@test123
该演示账号移除了项目创建功能。
QQ交流群： [190317359](//shang.qq.com/wpa/qunwpa?idkey=9a04393e101664709ed559e890b08fbfee5cac6979b027fe25fb44088bf52f12)

## 使用

SmartWiki 需要运行在PHP5.6以上版本，且必须开启gd扩展。如果不需要使用Memcached做缓存的话，请删除config/cache.php中memcached相关配置。

1.配置PHP环境，以apache+php5为例

第一步 安装Apache2

```
sudo apt-get install apache2
sudo a2enmod rewrite
sudo gedit /etc/apache2/apache2.conf&
```
添加：AddType application/x-httpd-php .php .htm .html

第二步 安装PHP模块
```
sudo apt-get install php5
```
 
第三步 安装Mysql

```
sudo apt-get install mysql-server
sudo apt-get install mysql-client
```
 
第四步 其他模块安装
```
sudo apt-get install libapache2-mod-php5
sudo apt-get install libapache2-mod-auth-mysql
sudo apt-get install php5-mysql
sudo apt-get install php5-gd
```

第五步 测试Apache是否正常工作

打开浏览器，输入localhost，看看是否有It Works!网页展示。目录为/var/www
（默认目录是www/html，自己改配置文件）

2.下载源码
```
git clone https://github.com/lifei6671/SmartWiki.git
```
3.安装composer

```
sudo curl -sS https://getcomposer.org/installer | sudo php
sudo mv composer.phar /usr/local/bin/composer
```
或者

```
php -r "readfile('https://getcomposer.org/installer');" | php
mv composer.phar /usr/local/bin/composer
```
具体可参考 [http://docs.phpcomposer.com/00-intro.html](http://docs.phpcomposer.com/00-intro.html)

4.设置目录权限

```
sudo chmod -R 0777 storage

```

5.恢复laravel的依赖

```
composer install

```

如果不是root权限，可能会出现没有写权限的错误。解决方法是手动创建目录，或者是切换到root权限执行。

6.添加apache需要的.htaccess文件

```
Options +FollowSymLinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

7.配置apache的虚拟目录并指向 SmartWiki/public 目录

```
<VirtualHost 127.0.0.1:80>  
    #你的网站目录  
    DocumentRoot "/var/www/SmartWiki/public"  
    #你网站的域名  
    ServerName wiki.iminho.me  
    ErrorLog "logs/dummy-host2.example.com-error.log"  
    CustomLog "logs/dummy-host2.example.com-access.log" common  
    #权限设置  
    Order allow,deny  
    Allow from all  
</VirtualHost>  
```
8.然后访问 http://wiki.iminho.me 会自动跳转到安装页面。

## 命令安装

在SmartWiki根目录依次执行：

```
#恢复依赖库
composer install

# 缓存配置【Windows平台请勿执行该命令】
php artisan config:cache

# 缓存路由【Windows平台请勿执行该命令】
php artisan route:cache

#清除缓存
php artisan clear-compiled

#优化加载类
php artisan optimize

#安装SmartWiki
php artisan smartwiki:install --dbHost=数据库地址 --dbName=数据库名称 --dbPort=数据库端口号 --dbUser=数据库账号 --dbPassword=数据库密码 --account=管理员账号 --password=管理员密码 --email=管理员邮箱

#设置加密密钥
php artisan key:generate
```

## SmartWiki迁移

如果已存在完整的SmartWiki的数据，可以手动修改.env文件，设置新的数据库，也可以执行一下命令迁移到新数据库：

```
 php artisan smartwiki:migrate --dbHost=数据库地址 --dbName=数据库名称 --dbPort=数据库端口号 --dbUser=数据库账号 --dbPassword=数据库密码
```

## 使用手册

更多使用与配置可以访问 [https://wiki.iminho.me/show/1](https://wiki.iminho.me/show/1)

## 部分截图

**个人资料**

![个人资料](https://raw.githubusercontent.com/lifei6671/SmartWiki/master/storage/app/images/20161124082553.png)

**我的项目**

![我的项目](https://raw.githubusercontent.com/lifei6671/SmartWiki/master/storage/app/images/20161124082647.png)

**项目参与用户**

![项目参与用户](https://raw.githubusercontent.com/lifei6671/SmartWiki/master/storage/app/images/20161124082703.png)

**文档编辑**

![文档编辑](https://raw.githubusercontent.com/lifei6671/SmartWiki/master/storage/app/images/20161124082810.png)

**文档模板**

![文档模板](https://raw.githubusercontent.com/lifei6671/SmartWiki/master/storage/app/images/20161124082844.png)


## 使用的技术
- laravel 5.2
- mysql 5.6
- editor.md
- bootstrap 3.2
- jquery 库
- layer 弹出层框架
- webuploader 文件上传框架
- Nprogress 库
- jstree 
- font awesome 字体库
- cropper 图片剪裁库

## 功能
1. 项目管理，可以对项目进行编辑更改，成员添加等。
2. 文档管理，添加和删除文档，文档历史恢复等。
3. 用户管理，添加和禁用用户，个人资料更改等。
4. 用户权限管理 ， 实现用户角色的变更。
5. 项目加密，可以设置项目公开状态为私密、半公开、全公开。
6. 站点配置，二次开发时可以添加自定义配置项。

## 待实现

1. 项目转让
2. 项目导出
3. 角色细分
4. 项目文档树生成
5. 忘记密码
6. 实现系统日志

## 参与开发

我们欢迎您在 SmartWiki 项目的 GitHub 上报告 issue 或者 pull request。

如果您还不熟悉GitHub的Fork and Pull开发模式，您可以阅读GitHub的文档（[https://help.github.com/articles/using-pull-requests](https://help.github.com/articles/using-pull-requests)） 获得更多的信息。

## 作者

一个纯粹的PHPer。[SmartWiki 演示文档](https://wiki.iminho.me/docs/show/1)









