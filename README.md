# 添加阿里云 WAF 回源 IP 段到 Laravel
add Aliyun waf's ip ranges to trust proxy

## 安装
**composer 安装**
```bash
composer require xiaohuilam/laravel-aliyun-waf-trustedproxy
```

## 命令

**更新最新IP段**
```bash
php artisan waf:update-range
```

## 授权

MIT