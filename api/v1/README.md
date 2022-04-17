# Damn Vulnerable API

Some general notes.

## Config

You need to set this in the Apache config, otherwise the `Authorization` header will not get passed through to PHP.

```
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

## Database

```sql
CREATE TABLE IF NOT EXISTS sessions (session_id INT UNSIGNED NOT NULL AUTO_INCREMENT, data TEXT NOT NULL, PRIMARY KEY (session_id));
TRUNCATE sessions;
INSERT INTO sessions VALUES (null, '{"Name": "pippa", "Balance": 500}');
INSERT INTO sessions values (null, '{"Name": "robin", "Balance": 100}');
```
