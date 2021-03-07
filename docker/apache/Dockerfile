FROM httpd:2-alpine

RUN apk update; \
    apk upgrade;

RUN sed -i '/LoadModule rewrite_module/s/^#//g' /usr/local/apache2/conf/httpd.conf
RUN sed -i '/LoadModule proxy_module/s/^#//g' /usr/local/apache2/conf/httpd.conf
RUN sed -i '/LoadModule proxy_fcgi_module/s/^#//g' /usr/local/apache2/conf/httpd.conf

COPY apache.conf /usr/local/apache2/conf/herisson.conf
RUN echo "Include /usr/local/apache2/conf/herisson.conf" \
    >> /usr/local/apache2/conf/httpd.conf
