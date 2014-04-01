# Introduction to Developing Web Applications

## オペレーティングシステム

* Windows
* Linux
* Unix
* Mac OS X

### 文字コード

* Shift_JIS / CP932
* UTF-8
* EUC

### 改行コード

VimのFileFormatを元に説明

* DOS (0x13+0x10)
* Unix (0x13)
* Mac (0x10)

```
vim -b hoge.txt
```


### Webブラウザ

* Internet Explorer (Trident)
* Mozilla Firefox (Gecko)
* Google Chrome (Blink)
* Safari (WebKit)


## ネットワークとインターネット

* プロトコル
  - TCP/IP
  - HTTP
  - URL, URI

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

![URLとURI](http://ja.wikipedia.org/wiki/%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB:URI_Venn_Diagram.svg)

> 2001年、W3CはRFC 3305内で、... ここで示されたW3Cの新たな考え方により、従来のURLとURNとはすべてURIと呼ばれることになった。URLやURNといった語はW3Cによって非公式な表現とされた。


### Webクライアント

GUIを伴わない、コマンドラインアプリケーションもWebクライアントになりうる。

* wget
* curl


### HTTPS

> TLSで暗号化され、セキュリティを確保したHTTPは、HTTPSと呼ばれる（httpsは実際にはURIスキームの1つであり、実際のプロトコルにはHTTP over SSL/TLSが用いられる）。



## プログラミング言語

Webアプリケーションにおいてよく使用されるプログラミング言語

* Java
* C#
* VB.NET

* PHP
* Ruby
* Python
* Perl

* HTML
* CSS
* JavaScript


### 標準化団体

* W3C
* ECMA


## Webサーバー

* Apache
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

#### CRUD (くらっど)

Create / Read / Update / Delete



---

この辺は難しいか...

### HTTP cookie

> [HTTP cookie | Wikipedia](http://ja.wikipedia.org/wiki/HTTP_cookie)
> 
> クッキーでは次のようにサーバとクライアント間の状態を管理する。
> 1. ウェブサーバがウェブブラウザにその状態を区別する識別子をHTTPヘッダに含める形で渡す。
> 2. ブラウザは次にそのサーバと通信する際に、与えられた識別子をHTTPヘッダに含めて送信する。
> 3. サーバはその識別子を元にコンテンツの内容をユーザに合わせてカスタマイズし、ブラウザに渡す。必要があれば新たな識別子もHTTPヘッダに含める。
> -----
> 例
> 例えば特定のページの表示回数を、ウェブページ上に表示したいときには、おおむね次のようなやりとりが行われる。
> 1. ブラウザがサーバに閲覧を要求する。ここにはクッキーの情報はない。
> 2. サーバはブラウザに対し「1」回目というクッキー情報と、「1回目」と表示するようなデータを送信する。
> 3. ブラウザがサーバに閲覧を要求する。このときブラウザは、そのサーバから受け取ったクッキーを探して、「1」のクッキー情報をサーバに送信する。
> 4. サーバは「1」というクッキー情報に基づき、ブラウザに対し「2」回目というクッキー情報と、「2回目」と表示するようなデータを送信する。

### セッション / セッション変数


