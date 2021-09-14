#!/bin/bash

env | php generate-config.php > /etc/proxysql.cnf

echo "Using the following config generated from PS_ env variables:"
cat /etc/proxysql.cnf
echo "======================="

exec proxysql -f -D /var/lib/proxysql
