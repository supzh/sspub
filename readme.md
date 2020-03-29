# SSPUB

sspub是基于[ss-panel](https://github.com/orvice/ss-panel) 提供的muAPI 开发的多用户Web端，借鉴了ss-panel的一些管理功能，sspub包含用户订阅支付(测试)功能并提供了新的界面；

访问demo : http://kexueshangwang.pub/ 目前可以注册免费使用

### 项目部署

#### 项目安装

环境需求

|  环境 | 说明     |
| :----:      | :---:        |
| 数据库    | 需要安装好mysql服务器并在项目config目录的database.php配置文件配置好对应的连接方式     |
| 应用  |  php版本需要7.0以上版本并且安装好composer以及npm；web端访问需要安装好nginx |


``` bash
# 安装 composer 依赖包
composer install

# 安装 npm 依赖包
cd web/view
npm install

# 前端构建 dist/build.js 文件
npm run build
```

#### 修改文件存储目录权限

``` bash
chmod -R 777 storage
```

#### 导入SQL文件到数据库

``` bash
mysql -umysqluser -pmysqlpassword
>source /projectpath/create_database.sql
```
默认Web端管理员帐号密码

登录邮箱：admin@kexueshangwang.pub
密码：123456

#### 将以下内容配置到Nginx的配置文件中

```
server
{
    listen 80;
    server_name  localhost;
    root  /projectpath/web;
    location / {
        try_files $uri /index.php$is_args$args;
    }
    location ~ ^/(index)\.php(/|$) {
        fastcgi_pass unix:/dev/shm/php-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param  HTTPS              off;
     }
}
```

#### 配置config目录下的应用配置文件

数据库配置文件 config/database.php

应用配置文件 config/app.php

前端配置服务器API: web/view/js/gdata.js 修改serverhost值为服务端api地址

Mu接口配置文件 config/mu.php

shadowsocks-go mu配置文件 mu/config.conf

#### 启动shadowsocks-go mu

```bash
cd mu
./mu
```

#### TODO
```
用户流量邮件提醒
礼品订阅支付需要做真实接入
站点配置项应用到页面显示
忘记密码邮件发送到Gmail无法收到
服务器监控相关
```
