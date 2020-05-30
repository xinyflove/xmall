# XMall电商系统

> XMall电商系统，提供数据接口

## 软件架构

- Laravel 5.8.38
- PHP
- MySQL

## 安装教程

### 环境要求

- Nginx/Apache/IIS
- MySQL 5.5+
- PHP >= 7.2.9
- OpenSSL PHP 拓展
- PDO PHP 拓展
- Mbstring PHP 拓展
- Tokenizer PHP 拓展
- XML PHP 拓展
- Ctype PHP 拓展
- JSON PHP 拓展
- BCMath PHP 拓展

建议使用环境：Linux + Nginx + PHP >=7.2.9 + MySQL 5.7

### 安装部署

1. 下载源码

从 [https://github.com/xinyflove/x](https://github.com/xinyflove/xmall) 下载代码到本地

2. 执行 composer 命令

```bash
composer install
composer dump-autoload
```

3. 创建 `.env` 环境配置文件

```
cp .env.dev .env
```

4. 生成 key 值

```
php artisan key:generate
```

5. 修改 `.env` 环境配置文件

  - 数据库配置
  - `APP_URL` 配置

6. 测试数据库配置是否正确

```bash
php artisan migrate:install
```

如果出现`Migration table created successfully.`则配置正确。

7. 执行 `migrate`命令安装表

```bash
php artisan migrate
```

8. 确认文件权限

`storage` 和 `bootstrap/cache` 目录应该允许你的 Web 服务器写入，否则 Laravel 将无法写入。

9. 文件储存配置

`.env` 文件添加 `FILESYSTEM_DRIVER=public` 配置

执行命令 `php artisan storage:link`，`./public/storage/` 目录 链接到 `./storage/app/public/` 目录

10. 启动项目

本地开发

```bash
php artisan serve
```

Web 服务器配置 [传送门](https://learnku.com/docs/laravel/5.8/installation/3879#web-server-configuration)

## 原始数据

### 管理员

- 超管
  - username:admin
  - password:******

### 用户

- 测试用户
  - username:test
  - password:******

## 测试数据

### 商品测试数据

```bash
php artisan db:seed --class=ProductsTableSeeder
```

## 功能介绍

## 开发说明

### 生成 controller

```bash
php artisan make:controller Web/TestController
php artisan make:controller Admin/TestController
php artisan make:controller Api/V1/TestController
php artisan make:controller AdminApi/V1/TestController
```

### 创建 Model

```bash
php artisan make:model Models/User
```

### 数据迁移

```bash
php artisan make:migration create_user_table
```
