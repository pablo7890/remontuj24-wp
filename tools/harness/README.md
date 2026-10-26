# Podgląd motywu bez WordPressa

`stubs.php` udaje tylko te funkcje WordPressa, których używają motyw i wtyczka, a `render.php`
składa z nich statyczne strony z treściami startowymi. Służy do porównania z prototypem
i szybkiego wyłapania błędów (np. w polskich znakach). Nie zastępuje testu na prawdziwym WordPressie.

```
R24H_HOME=http://127.0.0.1:8765 php tools/harness/render.php /tmp/r24-site
ln -s "$PWD/wp-content" /tmp/r24-site/wp-content
ln -s "$PWD/wp-content/plugins/remontuj24-core/demo/img" /tmp/r24-site/media
cd /tmp/r24-site && python3 -m http.server 8765
```
