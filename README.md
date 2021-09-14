
## ProxySQL Docker image configured with ENV variables

This is a Docker image derived from proxysql/proxysql with a script
  added that reads all your `PS_*` env variables and turns them into
  a config file.
  
### Example

With the following env variables:
```
PS_DATADIR=/var/lib/proxysql
PS_ADMIN_VARIABLES__ADMIN_CREDENTIALS='admin:admin;radmin:radmin'
PS_ADMIN_VARIABLES__MYSQL_IFACES='0.0.0.0:6032'
PS_MYSQL_VARIABLES__THREADS=4
```

We will produce the following config:
```
datadir="/var/lib/proxysql"

admin_variables=
{
    mysql_ifaces="0.0.0.0:6032"
    admin_credentials="admin:admin;radmin:radmin"
}

mysql_variables=
{
    threads=4
}
```

#### Test this
```
docker run \
  -e PS_DATADIR=/var/lib/proxysql \
  -e PS_ADMIN_VARIABLES__ADMIN_CREDENTIALS='admin:admin;radmin:radmin' \
  -e PS_ADMIN_VARIABLES__MYSQL_IFACES='0.0.0.0:6032' \
  -e PS_MYSQL_VARIABLES__THREADS=4 \
  quay.io/nikita2206/proxysql
```
