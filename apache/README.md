```bash
docker-compose up --build
docker-compose up -d # detached
```

If `mod_rewrite` is needed, the container has to be configured to load the module.

un-comment the line
```bash
# LoadModule rewrite_module modules/mod_rewrite.so
```
in `/usr/local/apache2/conf/httpd.conf`

TODO: do it automatically in Dockerfile
