FROM catmandu-marc

LABEL maintainer="Wolfgang Astleitner <wolfgang.astleitner@jku.at>"

ENV container=docker

COPY ebooks /var/www/apps/ebooks
COPY ebooks-fix /var/www/apps/ebooks-fix
COPY .git/modules/ebooks/refs/heads/master /var/www/apps/ebooks-fix/githash.txt

EXPOSE 80

RUN set -ex \
    && yum makecache -y \
    && yum upgrade -y \
    && yum install httpd php perl-LWP-Protocol-https -y \
    && yum clean all \
    && rm -rf /var/cache/yum \
    && mv /var/www/apps/ebooks-fix/apache.conf /etc/httpd/conf.d/ebook-fix.conf

ENTRYPOINT ["/usr/sbin/httpd", "-D", "FOREGROUND"]
