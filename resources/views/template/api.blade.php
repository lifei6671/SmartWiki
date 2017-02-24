### 简要描述：

- 用户登录接口

### 请求域名:

- http://xx.com

### 请求URL:

GET:/api/login

POST:/api/login

PUT:/api/login

DELETE:/api/login

TRACE:/api/login


### 参数:

|参数名|是否必须|类型|说明|
|:----    |:---|:----- |-----   |
|username |是  |string |用户名   |
|password |是  |string | 密码    |

### 返回示例:

**正确时返回:**

```
{
    "errcode": 0,
    "data": {
    "uid": "1",
    "account": "admin",
    "nickname": "Minho",
    "group_level": 0 ,
    "create_time": "1436864169",
    "last_login_time": "0",
}
}
```

**错误时返回:**


```
{
    "errcode": 500,
    "errmsg": "invalid appid"
}
```

### 返回参数说明:

|参数名|类型|说明|
|:-----  |:-----|-----                           |
|group_level |int   |用户组id，1：超级管理员；2：普通用户  |

### 备注:

- 更多返回错误代码请看首页的错误代码描述