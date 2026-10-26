<?php
/**
 * Privacy and cookies policy template, as block markup. Company data comes from
 * [r24_firma] (settings), so the document follows any change there. Phrases in
 * span.ph-var are for the client to replace in the editor before publishing.
 */

defined( 'ABSPATH' ) || exit;

$h  = fn( string $id, string $text ) => "<!-- wp:heading {\"anchor\":\"$id\"} -->\n<h2 class=\"wp-block-heading\" id=\"$id\">$text</h2>\n<!-- /wp:heading -->\n\n";
$p  = fn( string $html, string $class = '' ) => $class
	? "<!-- wp:paragraph {\"className\":\"$class\"} -->\n<p class=\"$class\">$html</p>\n<!-- /wp:paragraph -->\n\n"
	: "<!-- wp:paragraph -->\n<p>$html</p>\n<!-- /wp:paragraph -->\n\n";
$ul = function ( array $items ): string {
	$li = implode( "\n", array_map( fn( $i ) => "<!-- wp:list-item -->\n<li>$i</li>\n<!-- /wp:list-item -->", $items ) );
	return "<!-- wp:list -->\n<ul class=\"wp-block-list\">$li</ul>\n<!-- /wp:list -->\n\n";
};
$ph = fn( string $text ) => '<span class="ph-var">[' . $text . ']</span>';

return $p( 'Szanujemy prywatność osób, które odwiedzają naszą stronę i kontaktują się z nami. Poniżej wyjaśniamy prostym językiem, jakie dane zbieramy, po co i jakie prawa Ci przysługują.', 'lead' )
	. $h( 'administrator', 'Administrator danych' )
	. $p( 'Administratorem Twoich danych osobowych jest [r24_firma pole="nazwa"] z siedzibą pod adresem [r24_firma pole="adres"], NIP [r24_firma pole="nip"], REGON [r24_firma pole="regon"], zwana dalej „[r24_firma pole="marka"]”.' )
	. $p( 'W sprawach dotyczących danych osobowych możesz pisać na adres [r24_firma pole="email"] lub zadzwonić pod numer [r24_firma pole="telefon"].' )
	. $h( 'dane', 'Jakie dane przetwarzamy' )
	. $ul( [
		'dane podane w formularzu kontaktowym: imię, numer telefonu lub adres e-mail oraz treść wiadomości,',
		'dane przekazane w rozmowie telefonicznej lub korespondencji e-mail, w tym informacje o mieszkaniu,',
		'adres e-mail podany przy zapisie do newslettera,',
		'dane techniczne zbierane automatycznie przez pliki cookies, takie jak adres IP, typ przeglądarki i odwiedzone podstrony.',
	] )
	. $h( 'cele', 'Cele i podstawy prawne' )
	. $ul( [
		'odpowiedź na wiadomość i przygotowanie oferty — art. 6 ust. 1 lit. b RODO (działania przed zawarciem umowy),',
		'realizacja umowy na wykończenie mieszkania — art. 6 ust. 1 lit. b RODO,',
		'wypełnienie obowiązków księgowych i podatkowych — art. 6 ust. 1 lit. c RODO,',
		'wysyłka newslettera — art. 6 ust. 1 lit. a RODO (Twoja zgoda),',
		'statystyki odwiedzin i bezpieczeństwo strony — art. 6 ust. 1 lit. f RODO (nasz prawnie uzasadniony interes) lub zgoda wyrażona w banerze cookies,',
		'ustalenie, dochodzenie lub obrona roszczeń — art. 6 ust. 1 lit. f RODO.',
	] )
	. $h( 'odbiorcy', 'Odbiorcy danych' )
	. $p( 'Twoje dane mogą trafić wyłącznie do podmiotów, które pomagają nam prowadzić działalność: firmy hostingowej (LH.pl sp. z o.o.), dostawcy poczty e-mail, dostawcy usługi newslettera (' . $ph( 'nazwa dostawcy' ) . '), biura rachunkowego oraz — w zakresie niezbędnym do realizacji prac — wykonawców i dostawców, z którymi współpracujemy przy danym mieszkaniu.' )
	. $p( 'Nie sprzedajemy danych i nie przekazujemy ich do celów marketingowych innych firm. ' . $ph( 'Jeśli dane trafiają poza Europejski Obszar Gospodarczy, np. przy narzędziach analitycznych, opisz to tutaj albo usuń to zdanie.' ) )
	. $h( 'okres', 'Jak długo przechowujemy dane' )
	. $ul( [
		'korespondencję bez zawarcia umowy — do 12 miesięcy od ostatniego kontaktu,',
		'dane związane z umową — przez czas jej realizacji, a następnie do upływu terminów przedawnienia roszczeń i obowiązków księgowych,',
		'adres e-mail w newsletterze — do wycofania zgody,',
		'dane z plików cookies — zgodnie z okresem opisanym w ustawieniach przeglądarki i narzędzia.',
	] )
	. $h( 'prawa', 'Twoje prawa' )
	. $p( 'Masz prawo dostępu do swoich danych, ich sprostowania, usunięcia lub ograniczenia przetwarzania, przeniesienia danych oraz wniesienia sprzeciwu. Jeśli przetwarzanie opiera się na zgodzie, możesz ją wycofać w dowolnym momencie, bez wpływu na zgodność z prawem wcześniejszego przetwarzania.' )
	. $p( 'Przysługuje Ci także prawo wniesienia skargi do Prezesa Urzędu Ochrony Danych Osobowych (ul. Stawki 2, 00-193 Warszawa).' )
	. $p( 'Podanie danych jest dobrowolne, ale bez nich nie będziemy mogli odpowiedzieć na wiadomość ani przygotować oferty.' )
	. $h( 'newsletter', 'Newsletter' )
	. $p( 'Do newslettera zapisujesz się, podając adres e-mail i zaznaczając zgodę. Z newslettera możesz zrezygnować w każdej chwili, klikając link w stopce wiadomości lub pisząc na adres [r24_firma pole="email"].' )
	. $h( 'cookies', 'Pliki cookies' )
	. $p( 'Strona używa plików cookies, czyli niewielkich plików zapisywanych na Twoim urządzeniu. Dzielimy je na trzy grupy:' )
	. $ul( [
		'niezbędne — zapewniają prawidłowe działanie strony i zapamiętują Twój wybór w banerze cookies; nie wymagają zgody,',
		'analityczne — pomagają nam zrozumieć, jak korzystasz ze strony (' . $ph( 'np. Google Analytics' ) . '); używamy ich tylko za Twoją zgodą,',
		'marketingowe — ' . $ph( 'np. piksel Meta; usuń ten punkt, jeśli nie jest używany' ) . '; używamy ich tylko za Twoją zgodą.',
	] )
	. $p( 'Zgodę możesz w każdej chwili zmienić w <a href="#cookie-settings">ustawieniach cookies</a> (link „Cookies” jest też w stopce strony) albo zablokować pliki cookies w ustawieniach przeglądarki.' )
	. $h( 'zmiany', 'Zmiany polityki' )
	. $p( 'Politykę możemy aktualizować, np. gdy zmienią się przepisy lub narzędzia, z których korzystamy. Aktualna wersja jest zawsze dostępna na tej stronie. Ostatnia zmiana: [r24_data].' );
