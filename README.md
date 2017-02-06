## 简介
SmartWiki是一款针对IT团队开发的简单好用的文档管理系统。
可以用来储存日常接口文档，数据库字典，手册说明等文档。内置项目管理，用户管理，权限管理等功能，能够满足大部分中小团队的文档管理需求。

## 在线演示

演示地址： [https://www.iminho.me](https://www.iminho.me)
账号密码： test123@test123
该演示账号移除了项目创建功能。
QQ交流群： [190317359](//shang.qq.com/wpa/qunwpa?idkey=9a04393e101664709ed559e890b08fbfee5cac6979b027fe25fb44088bf52f12)

## 安装与部署

安装教程请参见使用手册：[https://wiki.iminho.me/docs/show/1](https://wiki.iminho.me/docs/show/1)

## SmartWiki迁移

如果已存在完整的SmartWiki的数据，可以手动修改.env文件，设置新的数据库，也可以执行一下命令迁移到新数据库：

```
 php artisan smartwiki:migrate --dbHost=数据库地址 --dbName=数据库名称 --dbPort=数据库端口号 --dbUser=数据库账号 --dbPassword=数据库密码
```

## 使用Docker部署

Dockerfile 文件请参考 [https://github.com/lifei6671/docker-smartwiki](https://github.com/lifei6671/docker-smartwiki)


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

1. 项目导出
2. 角色细分
3. 实现系统日志

## 参与开发

我们欢迎您在 SmartWiki 项目的 GitHub 上报告 issue 或者 pull request。

如果您还不熟悉GitHub的Fork and Pull开发模式，您可以阅读GitHub的文档（[https://help.github.com/articles/using-pull-requests](https://help.github.com/articles/using-pull-requests)） 获得更多的信息。

## 作者

一个纯粹的PHPer。[SmartWiki 演示文档](https://wiki.iminho.me)









