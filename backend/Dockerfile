FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y \
        mariadb-server \
        nano \
        curl \
        wget \
        gnupg \
        ca-certificates \
        tzdata && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN useradd -m -s /bin/bash user_vuln && echo "user_vuln:securepassword_imt" | chpasswd

RUN ln -fs /usr/share/zoneinfo/Europe/Riga /etc/localtime && \
    dpkg-reconfigure --frontend noninteractive tzdata

COPY bookshop.sql /tmp/bookshop.sql

# Ensure MySQL has correct permissions
RUN mkdir -p /var/run/mysqld && chown mysql:mysql /var/run/mysqld

RUN sed -i "s/^bind-address\s*=.*$/bind-address = 0.0.0.0/" /etc/mysql/mariadb.conf.d/50-server.cnf && \
    service mariadb start && \
    mysql -e "CREATE DATABASE IF NOT EXISTS bookshop;" && \
    mysql -e "CREATE USER IF NOT EXISTS 'bookstore_user'@'%' IDENTIFIED BY 'password123_imt';" && \
    mysql -e "GRANT SELECT, INSERT, UPDATE, DELETE ON bookshop.* TO 'bookstore_user'@'%';" && \
    mysql -e "FLUSH PRIVILEGES;" && \
    mysql bookshop < /tmp/bookshop.sql && \
    service mariadb stop

EXPOSE 3306

CMD ["/bin/bash", "-c", "service mariadb start && tail -f /dev/null"]
