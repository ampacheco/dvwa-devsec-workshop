# Damn Vulnerable API

Some general notes.

## Config

You need to set this in the Apache config, otherwise the `Authorization` header will not get passed through to PHP.

```
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```
