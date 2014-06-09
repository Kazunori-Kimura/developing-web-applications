Simple ToDo
===========================


手順
---------

```
$ su -
# yum install yum-fastestmirror
# yum update -y
# yum install -y vim
# yum install -y git-core
```

epel リポジトリを追加します(remi-release-6 が依存している)。

* 64bit
```
# rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm
```

* 32bit
```
# rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/6/i386/epel-release-6-8.noarch.rpm
```

remi リポジトリを追加します。

```
# rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
```

remi Repositoryからphpをアップデート

```
# yum --enablerepo=remi update php -y
```

PHPの設定

```
# vim /etc/php.ini
```

```
date.timezone = Asia/Tokyo
mbstring.internal_encoding = UTF-8
```

sqlite入ってる？

```
# sqlite3 --version
3.6.20
# exit
$ which vim
/usr/bin/vim
```
