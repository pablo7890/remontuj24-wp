# Wdrożenie na lh.pl

Strona to dwa pakiety: wtyczka **Remontuj24 — bloki i ustawienia** (`remontuj24-core.zip`: sekcje, ustawienia, formularz, newsletter, cookies, SEO) i motyw **Remontuj24** (`remontuj24.zip`: wygląd). Paczki buduje `tools/build_zips.sh` (trafiają do `dist/`).

Wymagania: WordPress 6.5 lub nowszy, PHP 8.1+ (na lh.pl ustaw PHP 8.5 w panelu), biblioteka GD lub Imagick z obsługą WebP (na lh.pl jest).

## Najpierw na kopii testowej

Motyw nie był jeszcze uruchomiony na prawdziwym WordPressie (sprawdzony składniowo i wyrenderowany w środowisku testowym). Pierwsze uruchomienie zrób na subdomenie testowej, np. `nowa.remontuj24.pl`, i dopiero po przejrzeniu przenieś na główny adres.

1. Panel lh.pl → Domeny → dodaj subdomenę `nowa.remontuj24.pl` (z certyfikatem SSL).
2. Autoinstalator → WordPress na tej subdomenie, język polski.
3. Ustawienia → Czytanie → zaznacz „Proś wyszukiwarki o nieindeksowanie” (na czas testów).

## Instalacja

1. **Wtyczki → Dodaj nową → Wyślij wtyczkę na serwer** → `remontuj24-core.zip` → Włącz.
2. **Wygląd → Motywy → Dodaj nowy → Wyślij motyw** → `remontuj24.zip` → Włącz.
3. **Remontuj24 → Treści startowe** → zostaw wszystko zaznaczone → *Zaimportuj treści startowe*. Powstaną: 8 stron z tekstami klienta, 3 artykuły Poradnika, polityka prywatności, menu, zdjęcia w bibliotece (WebP) i przykładowa realizacja jako szkic. Import można powtórzyć, istniejące strony zostają bez zmian.
4. **Remontuj24 → Ustawienia**, zakładka po zakładce:
   - *Firma i kontakt*: pełna nazwa, adres, NIP, REGON (trafiają do polityki prywatności), telefon, e-mail.
   - *Nagłówek i social media*: linki Instagram, Facebook, TikTok (puste = ikona się nie pokaże).
   - *Formularz kontaktowy*: adres, na który mają przychodzić wiadomości.
   - *Newsletter*: włącz, wybierz dostawcę (MailerLite, Brevo, Mailchimp albo „tylko lista w WordPressie”) i wklej klucz API oraz ID listy.
   - *Cookies i analityka*: ID Google Analytics 4 i piksel Meta, jeśli są używane. Ładują się dopiero po zgodzie.
5. **Strony → Polityka prywatności** → uzupełnij fragmenty wyróżnione na beżowo (dostawca newslettera, narzędzia analityczne). Najlepiej, żeby całość przejrzała osoba od RODO.

## Poczta z formularza (ważne)

Żeby wiadomości z formularza nie trafiały do spamu, wysyłaj je przez skrzynkę w domenie:

1. Panel lh.pl → Poczta → utwórz `formularz@remontuj24.pl` (albo użyj `biuro@`).
2. Zainstaluj wtyczkę **WP Mail SMTP** → Inny SMTP: serwer z panelu lh.pl, port 465, SSL, login i hasło tej skrzynki.
3. Wyślij próbną wiadomość z formularza na stronie Kontakt. Każda wiadomość jest też zapisana w **Remontuj24 → Wiadomości**, więc nic nie zginie.

## Realizacje

**Realizacje → Dodaj realizację**: tytuł, w panelu po prawej „Dane realizacji” (miasto, metraż, zakres, czas, zdjęcie przed i po z tego samego miejsca, galeria, opinia) i krótki opis w treści. Pozycja „Realizacje” pojawi się w menu sama, gdy opublikujesz pierwszą realizację. Przykładowy szkic z importu usuń, gdy dodasz prawdziwe.

## Poradnik

Nowy artykuł to zwykły **Wpis**: obrazek wyróżniający, kategoria, zajawka. Nagłówki zaczynające się od numeru („1. Planowanie…”) dostają złote numery i trafiają do spisu treści obok tekstu. Pierwszy akapit z klasą `lead` (Blok → Zaawansowane → Dodatkowe klasy CSS) jest większy.

## Przeniesienie na remontuj24.pl

1. Kopia obecnej strony (panel lh.pl → Kopie zapasowe).
2. Przeniesienie wtyczką **All-in-One WP Migration** albo **Duplicator** z subdomeny na domenę główną.
3. Ustawienia → Czytanie → odznacz „Proś wyszukiwarki o nieindeksowanie”.
4. Stare adresy (np. wpisy z obecnej strony) przekieruj wtyczką **Redirection**, jeśli zmieniły się ich adresy.
5. Wtyczka cache: **LiteSpeed Cache** (serwery lh.pl to LiteSpeed). Formularze działają z cache, bo nie używają jednorazowych kodów.

## Edycja treści

Każda strona to sekcje (bloki z grupy „Remontuj24 · sekcje”). Kliknij sekcję, a pola pojawią się w panelu po prawej; podgląd na środku jest prawdziwym wyglądem strony. Zdjęcia możesz wgrywać w JPG: WordPress sam zrobi lżejsze wersje WebP w kilku rozmiarach dla telefonu i komputera.
