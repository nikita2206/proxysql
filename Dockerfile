FROM proxysql/proxysql

RUN apt update -y && apt install -y php-cli

ADD ./generate-config-and-run.sh .
ADD  ./generate-config.php .

ENTRYPOINT [ "./generate-config-and-run.sh" ]
CMD []
