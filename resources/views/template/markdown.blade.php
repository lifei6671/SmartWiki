### 简要描述：

- {{$api_name or '无'}}

### 请求域名:

- {{$request_host or '无'}}

### 请求URL:

{{$method or 'GET'}}:{{$request_path or '/'}}

### 请求头：

<?php
if(empty($headers) === false){
    $html = "|参数名|是否必须|类型|说明|\r\n|:----    |:---|:----- |-----   |\r\n" ;
    foreach ($headers as $item){

        $isMust = strcasecmp($item['enabled'],'true') === 0 ? '是' : '否';
        $key = isset($item['key']) ? $item['key'] : '';
        if(empty($key) === false){
            $html .= "|{$key} |  {$isMust} |string  | 无    |\r\n";
        }
    }
    echo $html;
}
?>

### 参数:

<?php
if(strcasecmp($enctype,'raw') === 0){
    echo '```',"\r\n",(isset($raw_data) ? $raw_data:''),"\r\n",'```';
}elseif(empty($body) === false){
    $html = "|参数名|是否必须|类型|说明|\r\n|:----    |:---|:----- |-----   |\r\n" ;
    foreach ($body as $item){
        $isMust = strcasecmp($item['enabled'],'true') === 0 ? '是' : '否';
        $key = isset($item['key']) ? $item['key'] : '';
        if(empty($key) === false){
            $html .= "|{$key} |  {$isMust} |string  | 无    |\r\n";
        }
    }
    echo $html;
}
?>


### 返回示例:

**正确时返回:**

```
{!! $response or '' !!}
```

**错误时返回:**

```
{!! $response_error or '' !!}
```

### 返回参数说明:

|参数名|类型|说明|
|:-----  |:-----|-----|
| 无 |int   |  无 |

### 备注:

- 更多返回错误代码请看首页的错误代码描述