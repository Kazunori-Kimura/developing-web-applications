# Introduction to Developing Web Applications

## Webの仕組み

* Webブラウザ(クライアント)はWebサーバーに対して`リクエスト`を送ります
* Webサーバーはリクエストに対応したリソース(画像やテキストなど)を含む`レスポンス`を返します

このときのクライアントとサーバーの通信の約束事が`HTTP`(HyperText Transfer Protocol)です。

また、クライアントがリクエストするファイルの場所を示す文字列を`URL`あるいは`URI`といいます。

<br>

### HTTP

> *Hypertext Transfer Protocol*（ハイパーテキスト・トランスファー・プロトコル、略称 HTTP）とは、WebブラウザとWebサーバの間でHTMLなどのコンテンツの送受信に用いられる通信プロトコルである。RFC 2616で規定されている。  
> [Hypertext Transfer Protocol | Wikipedia](http://ja.wikipedia.org/wiki/Hypertext_Transfer_Protocol)

HTTPでは8つのメソッドが定義されている。

* GET  
指定されたURIのリソースを取り出す。HTTPの最も基本的な動作。
* POST  
クライアントがサーバにデータを送信する。Webフォームや電子掲示板への投稿などで使用される。GETの場合と同じく、サーバはクライアントにデータを返すことができる。
* PUT  
指定したURIにリソースを保存する。
* HEAD  
サーバはHTTPヘッダのみ返す。クライアントはWebページを取得せずともそのWebページが存在するかどうかを知ることができる。
* DELETE  
指定したURIのリソースを削除する。
* OPTION  
サーバを調査する。
* TRACE  
サーバまでのネットワーク経路をチェックする。
* CONNECT  
暗号化したメッセージをプロキシで転送する際に用いる。


### URL

> Uniform Resource Locator（ユニフォームリソースロケータ、URL）または統一資源位置指定子（とういつしげんいちしていし）は、インターネット上のリソース（資源）を特定するための形式的な記号の並び。WWWをはじめとするインターネットアプリケーションにおいて提供されるリソースを、主にその所在を表記することで特定する。なお、ここでいうリソースとは（主にインターネット上の）データやサービスを指し、例えばウェブページや電子メールの宛先といったものがそうである。  
> [Uniform Resource Locator | Wikipedia](http://ja.wikipedia.org/wiki/Uniform_Resource_Locator)

### URI

> Uniform Resource Identifier（ユニフォーム リソース アイデンティファイア、URI）または統一資源識別子（とういつしげんしきべつし）は、一定の書式によってリソース（資源）を指し示す識別子。1998年8月に RFC 2396 として規定され、2005年1月に RFC 3986 として改定された。URI はUniform Resource Locator (URL) の考え方を拡張したものである。
> [Uniform Resource Identifier](http://ja.wikipedia.org/wiki/Uniform_Resource_Identifier)

> 2001年、W3CはRFC 3305内で、... ここで示されたW3Cの新たな考え方により、従来のURLとURNとはすべてURIと呼ばれることになった。URLやURNといった語はW3Cによって非公式な表現とされた。


<br>

### Webブラウザ

* Internet Explorer (Trident)
* Mozilla Firefox (Gecko)
* Google Chrome (Blink)
* Safari (WebKit)


### Webクライアント

GUIを伴わない、コマンドラインアプリケーションもWebクライアントです。

* wget
* curl

ファイルのダウンロードやWebサーバーの動作確認に使用します。

## 通信の中身

telnetコマンドを使用して、
`http://example.com`にアクセスした場合の
HTTPのやりとりを確認します。

### リクエスト

example.comの80番ポートに接続します。

    example.comというサーバーは、このようなテストを行う目的で実際に存在します。

```sh
$ telnet example.com 80
```

つづけて、GETメソッドを実行します。
スペースも含めて、以下の通りタイプしてください。

```sh
GET / HTTP/1.1
Host: example.com

```

1行目は`GET`メソッドでPATH`/`に`HTTP 1.1`でリクエストを送ることを意味しています。

2行目はヘッダーです。リクエストに含めるパラメータなどをセットします。
Webブラウザからリクエストを送信した場合は`User-Agent`などの情報が入ります。

最後に`Enter`キーを押して空行を入れることで、リクエストが送信されます。

<br>

### レスポンス

リクエストを送信すると、Webサーバーからレスポンスが返ってきます。

```
HTTP/1.1 200 OK
Accept-Ranges: bytes
Cache-Control: max-age=604800
Content-Type: text/html
Date: Tue, 01 Apr 2014 14:13:01 GMT
Etag: "359670651"
Expires: Tue, 08 Apr 2014 14:13:01 GMT
Last-Modified: Fri, 09 Aug 2013 23:54:35 GMT
Server: ECS (sea/55ED)
X-Cache: HIT
x-ec-custom-error: 1
Content-Length: 1270

<!doctype html>
<html>
<head>
    <title>Example Domain</title>
:
```

1行目は HTTPのバージョン, ステータスコード, メッセージになります。

2行目以降から空行までがヘッダーです。

空行以降がボディです。
HTMLなどのリクエストに応じたデータが返ってきます。

<br>

## Webページを構成する要素

* HTML  
`HyperText Markup Language` テキストでドキュメントの構造を定義する
* CSS  
`Cascading Style Sheets` HTMLに対して、フォントや色などの装飾を施す
* JavaScript  
`JavaScript` ブラウザ上で実行され、Webページに動きを与える


<br>

## HTTPS

> TLSで暗号化され、セキュリティを確保したHTTPは、HTTPSと呼ばれる（httpsは実際にはURIスキームの1つであり、実際のプロトコルにはHTTP over SSL/TLSが用いられる）。

<br>

# Webアプリケーションとは？

今回の勉強会では、以下のように定義します。

    クライアントからのリクエストごとに、サーバー側の処理で
    状況に合わせたページが生成されることにより、何かしらの機能が提供されるWebサイト


このようなWebサイトを*Webサービス*と呼ぶ場合もあります。
*Webアプリケーション*と*Webサービス*を区別する明確な定義はない...と思います。


<br>

------

## プログラミング言語

Webアプリケーションにおいてよく使用されるプログラミング言語

### サーバー側で実行される

* Java
* ASP.NET
  - C#
  - VB.NET

* PHP
* Ruby
* Python
* Perl
* ASP
  - VBScript
  - JScript

### クライアント側(ブラウザ)で表示/実行される

* HTML
* CSS
* JavaScript

<br>

## Webサーバー

* Apache (あぱっち)
* nginx (えんじんえっくす)
* IIS (Internet Information Services)


### アプリケーション・サーバー

* Tomcat (Java)
* IIS (ASP.NET/ASP)


## データベースシステム

### RDBMS

データを表の形式で永続化し、かつデータ間の関連もまた表の形式で永続化できるDBMS

- Oracle Database
- PostgreSQL
- MySQL
- MariaDB
- SQL Server
- SQLite
- Access

* SQL

リレーショナルデータベース管理システム (RDBMS) において、データの操作や定義を行うためのデータベース言語（問い合わせ言語）


------

#### CRUD (くらっど)

Create / Read / Update / Delete


#### 文字コード

* Shift_JIS / CP932
* UTF-8
  - BOM
* EUC



#### 改行コード

VimのFileFormatを元に説明

* DOS (0x13+0x10)
* Unix (0x13)
* Mac (0x10)


------

# PHPによるWebアプリケーション開発

## 開発環境の構築

1. 開発環境の構築
  * VirtualBox
  * Vagrant
  * CentOS6
  * ssh
  * yum
    - apache
    - php
    - MariaDB
    - git
2. apacheの設定
  * httpd.conf
    - DocumentRoot
    - phpの実行設定
3. phpの設定
  * php.ini
4. Vimの設定


## TODO管理アプリの開発

1. アプリの概要
2. プロジェクトフォルダの作成
3. MariaDBの設定
  * ユーザーの作成
  * 接続確認
  * テーブル作成


