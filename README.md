Pplog-php
========

[http://www.pplog.net/](http://www.pplog.net/)よりポエムを取得して表示するだけです。<br />
zshログイン時や処理待ち時間などのお供に。<br />


### Installation

```
$ git clone https://github.com/catls/pplog-php.git
```
クラスだけ

```
$ wget -q https://raw.githubusercontent.com/catls/pplog-php/master/Pplog.php
```

### Usage

* ポエムを表示する

```
./pplog
```
実行例

```
ねむい
---------------------------------------------
ぽよぽよ( ˘ω˘)

@user_name (2014/04/08 09:31:11)
```


### Pplog Class Usage

* ポエムを表示する

```php
$Pplog = new Pplog();
$Pplog->zapping();
echo $Pplog->getPoem();

// ワンライナー
echo (new Pplog())->zapping()->getPoem();
```

* 特定ユーザのポエムを表示する

```php
$Pplog = new Pplog();
echo $Pplog->getUserPoem('user_name')->getPoem();

// ユーザ名はクラス作成時にも指定出来ます
echo (new Pplog('user_name'))->getPoem();
```

* 内容を個別に取得する

```php
$Pblog->zapping();

echo $Pblog->user_name;  // ユーザ名
echo $Pblog->title;      // タイトル
echo $Pblog->content;    // ポエム内容
echo $Pblog->created_at; // 投稿日時
```

