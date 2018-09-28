./configure --prefix=$BASE/server/apache/php --with-config-file-path=$BASE/server/apache/php --with-apxs2=$BASE/server/apache/bin/apxs --with-curl --with-gd --with-gettext --with-kerberos --with-openssl --with-mhash --with-libdir=lib64 --with-mysql --with-mysqli --with-pcre-regex --with-pear --with-xsl --with-zlib --with-iconv --enable-bcmath --enable-calendar --enable-exif --enable-ftp --enable-gd-native-ttf --enable-soap --enable-sockets --enable-mbstring --enable-zip --enable-wddx --with-pdo-mysql 
make 
make install 
