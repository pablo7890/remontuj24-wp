<?php
/**
 * Starter pages built from the client's texts. Each page is a list of section blocks
 * with their fields; "img:<file>" points to a photo in demo/img, swapped for the
 * media library ID during import. Used by Remontuj24 → Treści startowe.
 */

defined( 'ABSPATH' ) || exit;

$cta     = [ 'btn_text' => 'Porozmawiajmy o mieszkaniu', 'btn_url' => '/kontakt/' ];
$process = [
	[ 'title' => 'Rozmowa i zakres', 'image' => 'img:proces-1-1600' ],
	[ 'title' => 'Plan i przygotowanie', 'image' => 'img:proces-2-1600' ],
	[ 'title' => 'Materiały i zamówienia', 'image' => 'img:proces-3-1600' ],
	[ 'title' => 'Koordynacja prac', 'image' => 'img:proces-4-1600' ],
	[ 'title' => 'Wyposażenie', 'image' => 'img:proces-5-1600' ],
	[ 'title' => 'Gotowe mieszkanie', 'image' => 'img:proces-6-1600' ],
];
$process_short = [
	'Poznanie potrzeb, założeń oraz oczekiwanego standardu wykończenia.',
	'Ustalenie zakresu prac, kolejności działań oraz harmonogramu.',
	'Koordynacja wyboru, zamówień i dostaw materiałów potrzebnych do realizacji.',
	'Organizacja kolejnych etapów i współpracy wykonawców.',
	'Kompletowanie mebli, zabudów, oświetlenia, AGD i pozostałych elementów wnętrza.',
	'Kontrola zakończonych prac i przygotowanie mieszkania do przekazania.',
];
$process_long = [
	[ 'Pierwszym krokiem jest poznanie potrzeb, przeznaczenia mieszkania i oczekiwanego standardu. Na tej podstawie określamy zakres i porządkujemy dalsze działania.', '' ],
	[ 'Ustalamy kolejność prac, terminy i najważniejsze zależności, aby cały proces od początku miał jasno określony kierunek.', '' ],
	[ 'Pomagamy uporządkować wybory oraz organizujemy zamówienia i dostawy zgodnie z przebiegiem prac.', '' ],
	[ 'Koordynujemy kolejne etapy, wykonawców i bieżące kwestie pojawiające się podczas realizacji.', 'Jeden proces zamiast wielu osobnych ustaleń.' ],
	[ 'Kompletujemy meble, zabudowy, oświetlenie, AGD i pozostałe elementy potrzebne do przygotowania gotowego wnętrza.', '' ],
	[ 'Kontrolujemy zakończone prace i przygotowujemy mieszkanie do przekazania.', 'Proces kończy się tam, gdzie powinien — gotowym wnętrzem.' ],
];
$steps_short = array_map( fn( $s, $t ) => $s + [ 'text' => $t ], $process, $process_short );
$steps_long  = array_map( fn( $s, $t ) => $s + [ 'text' => $t[0], 'note' => $t[1] ], $process, $process_long );

return [
	[
		'slug'   => 'strona-glowna',
		'title'  => 'Strona główna',
		'front'  => true,
		'seo'    => 'Kompleksowe wykończenie mieszkań pod klucz w Trójmieście. Jeden kontakt, jeden proces, pełna koordynacja — również dla właścicieli spoza regionu.',
		'blocks' => [
			[ 'hero', [
				'eyebrow'       => 'Mieszkania pod klucz · Trójmiasto',
				'title'         => "Od odbioru kluczy\ndo gotowego wnętrza.",
				'lead'          => 'Kompleksowo organizujemy i koordynujemy przygotowanie mieszkań w Trójmieście — od pierwszych ustaleń po gotowe wnętrze.',
				'note'          => 'Również dla właścicieli mieszkających poza regionem.',
				'btn_text'      => 'Porozmawiajmy o mieszkaniu',
				'btn_url'       => '/kontakt/',
				'link_text'     => 'Zobacz, jak działamy',
				'link_url'      => '/jak-dzialamy/',
				'before'        => 'img:hero-przed-1024',
				'after'         => 'img:hero-po-1024',
				'before_m'      => 'img:hero-przed-m-640',
				'after_m'       => 'img:hero-po-m-640',
				'before_label'  => 'Odbiór kluczy',
				'after_label'   => 'Gotowe wnętrze',
				'caption_right' => 'Ten sam salon, przed i po',
			] ],
			[ 'pillars', [ 'items' => [
				[ 'title' => 'Jeden kontakt', 'text' => 'Wszystko w jednym miejscu.' ],
				[ 'title' => 'Jeden proces', 'text' => 'Od pierwszych ustaleń do gotowego wnętrza.' ],
				[ 'title' => 'Pełna koordynacja', 'text' => 'Ekipy, zamówienia, dostawy i kolejne etapy.' ],
			] ] ],
			[ 'process', [
				'anchor'    => 'proces',
				'eyebrow'   => 'Jak wygląda współpraca?',
				'title'     => "Od planu do gotowego wnętrza.\nJeden spójnie prowadzony proces.",
				'lead'      => 'Sześć etapów, jedna osoba kontaktowa. Każdy krok przygotowuje następny, więc decyzje zapadają we właściwym momencie.',
				'steps'     => $steps_short,
				'link_text' => 'Poznaj cały proces',
				'link_url'  => '/jak-dzialamy/',
			] ],
			[ 'styles', [
				'eyebrow'   => 'Kierunki wnętrz',
				'title'     => "Różne style.\nRóżne możliwości.",
				'lead'      => 'Wybrane kierunki stylistyczne, które mogą stanowić punkt wyjścia do stworzenia spójnego wnętrza.',
				'items'     => [
					[ 'name' => 'Japandi', 'text' => 'Naturalne materiały, prostota i spokojne proporcje.', 'image' => 'img:styl-japandi-800', 'url' => '/inspiracje/#japandi' ],
					[ 'name' => 'Modern Classic', 'text' => 'Ponadczasowa elegancja połączona ze współczesnymi rozwiązaniami.', 'image' => 'img:styl-modern-classic-800', 'url' => '/inspiracje/#modern-classic' ],
					[ 'name' => 'Soft Minimal', 'text' => 'Proste formy, światło i subtelna paleta materiałów.', 'image' => 'img:styl-soft-minimal-800', 'url' => '/inspiracje/#soft-minimal' ],
					[ 'name' => 'Elegant Natural', 'text' => 'Naturalne faktury, szlachetne materiały i ciepła estetyka.', 'image' => 'img:styl-elegant-natural-800', 'url' => '/inspiracje/#elegant-natural' ],
				],
				'link_text' => 'Zobacz wszystkie inspiracje',
				'link_url'  => '/inspiracje/',
			] ],
			[ 'coord', [
				'eyebrow'   => 'Mieszkanie w Trójmieście',
				'title'     => 'Pełna koordynacja — niezależnie od tego, gdzie jest właściciel.',
				'lead'      => 'Proces prowadzony jest na miejscu — od przygotowania mieszkania po gotowe wnętrze.',
				'facts'     => "Postępy są regularnie raportowane.\nNajważniejsze decyzje mogą być podejmowane zdalnie.\nBez konieczności regularnej obecności w Trójmieście.",
				'link_text' => 'Dowiedz się więcej',
				'link_url'  => '/o-nas/',
				'image'     => 'img:gdansk-1600',
				'inset'     => 'img:zdalnie-1600',
			] ],
			[ 'guide', [
				'title' => 'Wiedza, która pomaga podejmować dobre decyzje.',
				'lead'  => 'Praktyczne informacje dotyczące przygotowania, wykończenia i wyposażenia mieszkania.',
			] ],
			[ 'band-image', [ 'image' => 'img:panorama-1940' ] ],
			[ 'closing', [
				'eyebrow'  => 'Pierwszy krok',
				'title'    => 'Porozmawiajmy o mieszkaniu.',
				'text'     => 'Każde mieszkanie i zakres prac są inne. Pierwszym krokiem jest krótka rozmowa pozwalająca określić potrzeby, zakres oraz możliwości realizacji.',
				'btn_text' => 'Umów rozmowę',
			] ],
		],
	],
	[
		'slug'   => 'jak-dzialamy',
		'title'  => 'Jak działamy',
		'seo'    => 'Od pierwszych ustaleń do gotowego wnętrza. Sześć etapów, jeden kontakt i pełna koordynacja wykończenia mieszkania w Trójmieście.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'Jak działamy',
				'title'   => "Od pierwszych ustaleń\ndo gotowego wnętrza.",
				'lead'    => 'Przygotowanie mieszkania to wiele decyzji, zamówień, terminów i osób zaangażowanych na różnych etapach.',
				'text'    => 'W Remontuj24 łączymy je w jeden spójnie prowadzony proces. Ustalamy zakres, planujemy kolejne działania i koordynujemy ich realizację — tak, aby wszystko prowadziło do jednego efektu: gotowego wnętrza.',
				'motto'   => 'Jeden kontakt. Jeden proces. Pełna koordynacja.',
				'image'   => 'img:salon-l-1600',
			] ],
			[ 'process', [
				'anchor'  => 'proces',
				'eyebrow' => 'Nasz proces · krok po kroku',
				'title'   => 'Sześć etapów, które prowadzą do jednego efektu.',
				'lead'    => 'Każdy etap przygotowuje kolejny. Dzięki temu decyzje zapadają wtedy, gdy rzeczywiście są potrzebne, a nie w pośpiechu.',
				'steps'   => $steps_long,
			] ],
			[ 'split', [
				'anchor'  => 'koordynacja',
				'eyebrow' => 'Pełna koordynacja',
				'title'   => 'Niezależnie od tego, gdzie jest właściciel.',
				'lead'    => 'Obecność właściciela na każdym etapie nie jest konieczna.',
				'text'    => 'Prace prowadzone są na miejscu, postępy są regularnie raportowane, a najważniejsze decyzje mogą być podejmowane zdalnie. Dzięki temu mieszkanie w Trójmieście może być przygotowywane również dla właściciela mieszkającego w innym mieście lub za granicą.',
				'image'   => 'img:zdalnie-1600',
				'flip'    => true,
				'cream'   => true,
			] ],
			[ 'closing', [
				'eyebrow' => 'Zaczynamy od rozmowy',
				'title'   => 'Wszystko zaczyna się od rozmowy.',
				'sub'     => 'Nie trzeba mieć gotowego projektu, wybranych materiałów ani szczegółowego planu.',
				'text'    => 'Na początku wystarczy określić mieszkanie, jego przeznaczenie i oczekiwany efekt. Dalsze kroki można uporządkować wspólnie.',
			] + $cta ],
		],
	],
	[
		'slug'   => 'zakres-obslugi',
		'title'  => 'Zakres obsługi',
		'seo'    => 'Planowanie, prace wykończeniowe, materiały, meble, oświetlenie i AGD prowadzone jako jeden proces. Zakres dopasowany do konkretnego mieszkania.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'Zakres obsługi',
				'title'   => "Gotowe wnętrze składa się\nz wielu elementów.",
				'tagline' => 'Dobrze, gdy tworzą jeden proces.',
				'lead'    => 'Przygotowanie mieszkania to znacznie więcej niż same prace wykończeniowe. Materiały, zamówienia, dostawy, wykonawcy i wyposażenie muszą pojawić się we właściwej kolejności i we właściwym czasie.',
				'text'    => 'W Remontuj24 łączymy te elementy w jeden spójnie prowadzony zakres obsługi — tak, aby kolejne etapy naturalnie prowadziły do gotowego wnętrza.',
				'image'   => 'img:proces-3-1600',
			] ],
			[ 'areas', [
				'eyebrow' => 'Co obejmujemy',
				'title'   => 'Sześć obszarów, jedna odpowiedzialność.',
				'image'   => 'img:kuchnia-p-720',
				'items'   => [
					[ 'title' => 'Przygotowanie i planowanie', 'text' => 'Na początku porządkujemy zakres, oczekiwany standard i najważniejsze decyzje. Pozwala to określić kierunek oraz dobrze przygotować kolejne etapy.' ],
					[ 'title' => 'Prace wykończeniowe', 'text' => 'Organizujemy prace potrzebne do przygotowania wnętrza — od podstawowych etapów wykończenia po elementy wymagające współpracy różnych wykonawców.' ],
					[ 'title' => 'Materiały i zamówienia', 'text' => 'Pomagamy uporządkować wybór materiałów oraz organizujemy zamówienia i dostawy tak, aby były skoordynowane z przebiegiem prac.' ],
					[ 'title' => 'Meble i zabudowy', 'text' => 'Kompletujemy wyposażenie oraz organizujemy zabudowy dopasowane do charakteru, funkcji i założonego standardu mieszkania.' ],
					[ 'title' => 'Oświetlenie i AGD', 'text' => 'Organizujemy elementy potrzebne do funkcjonalnego przygotowania wnętrza — wraz z dostawami i montażem na odpowiednim etapie realizacji.' ],
					[ 'title' => 'Detale, które domykają wnętrze', 'text' => 'Drzwi, lustra, armatura i pozostałe elementy wykończenia są częścią tego samego procesu. To właśnie na tym etapie poszczególne decyzje zaczynają tworzyć spójną całość.' ],
				],
			] ],
			[ 'compare', [
				'eyebrow' => 'Przed i po',
				'title'   => 'Od surowych ścian do gotowego wnętrza.',
				'lead'    => 'Przeciągnij suwak, aby zobaczyć to samo pomieszczenie w dniu odbioru kluczy i po zakończeniu prac.',
				'items'   => [
					[ 'title' => 'Salon z jadalnią', 'caption' => 'Prace wykończeniowe, zabudowy, oświetlenie', 'before' => 'img:hero-przed-1024', 'after' => 'img:hero-po-1024' ],
					[ 'title' => 'Łazienka', 'caption' => 'Instalacje, okładziny, zabudowa, armatura', 'before' => 'img:lazienka-przed-1024', 'after' => 'img:lazienka-po-1024' ],
				],
			] ],
			[ 'factors', [
				'eyebrow' => 'Zakres dopasowany do mieszkania',
				'title'   => 'Każde mieszkanie potrzebuje czegoś innego.',
				'lead'    => 'Nie tworzymy jednego sztywnego schematu dla wszystkich realizacji. Zakres ustalany jest na podstawie stanu mieszkania, jego przeznaczenia, oczekiwanego standardu oraz elementów, które są już przygotowane.',
				'items'   => [
					[ 'icon' => 'plan', 'title' => 'Stan mieszkania', 'text' => 'Inny zakres wymaga mieszkanie deweloperskie, inny częściowo wykończone, a jeszcze inny wymagające zmian układu.' ],
					[ 'icon' => 'person', 'title' => 'Przeznaczenie', 'text' => 'Mieszkanie dla siebie, dla rodziny czy na wynajem — każde z nich wymaga innych rozwiązań i priorytetów.' ],
					[ 'icon' => 'bars', 'title' => 'Oczekiwany standard', 'text' => 'Od funkcjonalnego wykończenia po wyższy standard — zakres dopasowujemy do oczekiwań i budżetu.' ],
					[ 'icon' => 'check', 'title' => 'To, co już jest', 'text' => 'Gotowy projekt, wybrane materiały lub zamówione meble włączamy w proces, zamiast zaczynać od nowa.' ],
				],
				'closing' => 'Proces obejmuje dokładnie to, czego wymaga konkretne mieszkanie.',
			] ],
			[ 'split', [
				'eyebrow'   => 'Jeden proces',
				'title'     => 'Od prac wykończeniowych do ostatnich elementów wnętrza.',
				'lead'      => 'Poszczególne etapy nie muszą oznaczać wielu osobnych ustaleń, terminów i kontaktów.',
				'text'      => 'Łączymy je w jeden proces, którego celem nie jest wykonanie kolejnej usługi, ale przygotowanie <strong>gotowego mieszkania</strong>.',
				'btn_text'  => 'Zobacz, jak działamy',
				'btn_url'   => '/jak-dzialamy/',
				'image'     => 'img:panorama-1940',
				'tight'     => true,
			] ],
			[ 'closing', [
				'eyebrow' => 'Pierwszy krok',
				'title'   => 'Wszystko zaczyna się od określenia zakresu.',
				'text'    => 'Krótka rozmowa pozwala ustalić, czego wymaga mieszkanie i w jaki sposób najlepiej poprowadzić kolejne etapy.',
			] + $cta ],
		],
	],
	[
		'slug'   => 'inspiracje',
		'title'  => 'Inspiracje',
		'seo'    => 'Japandi, Modern Classic, Soft Minimal, Elegant Natural, Contemporary i Soft Glamour. Kierunki wnętrz, od których może zacząć się Twoje mieszkanie.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'Inspiracje',
				'title'   => "Wnętrze zaczyna się\nod kierunku.",
				'lead'    => 'Światło, materiały, proporcje i detale tworzą atmosferę, którą czuje się od pierwszej chwili.',
				'text'    => 'Wybrane kierunki pokazują różne sposoby budowania wnętrza — od spokojnych i naturalnych po bardziej eleganckie i wyraziste. Każdy z nich może stać się punktem wyjścia do stworzenia wnętrza o własnym charakterze.',
				'image'   => 'img:insp-top-1600',
			] ],
			[ 'features', [
				'anchor'  => 'kierunki',
				'eyebrow' => 'Sześć kierunków',
				'title'   => 'Różne style. Różne możliwości.',
				'items'   => [
					[ 'name' => 'Japandi', 'tagline' => 'Naturalny spokój.', 'text' => 'Drewno, miękkie światło i proste formy tworzą harmonijną przestrzeń o ponadczasowym charakterze.', 'image' => 'img:styl-japandi-l-1600', 'colors' => '#D8C3A0, #ECE6DA, #B39B80, #3D3A36', 'colors_label' => 'Dąb · len · glina · grafit' ],
					[ 'name' => 'Modern Classic', 'tagline' => 'Współczesna elegancja.', 'text' => 'Klasyczne proporcje, dopracowane detale i szlachetne materiały tworzą wnętrze eleganckie, ale lekkie w odbiorze.', 'image' => 'img:styl-modern-classic-l-1600', 'colors' => '#F2EDE4, #D9D4CC, #B38A50, #1C1B1A', 'colors_label' => 'Kość · marmur · mosiądz · czerń' ],
					[ 'name' => 'Soft Minimal', 'tagline' => 'Przestrzeń i lekkość.', 'text' => 'Spokojna paleta, miękkie linie i oszczędne formy pozwalają wydobyć światło, proporcje i charakter wnętrza.', 'image' => 'img:styl-soft-minimal-l-1600', 'colors' => '#F6F3EE, #E6DDD0, #CFC3B2, #8E8274', 'colors_label' => 'Biel · piasek · trawertyn · taupe' ],
					[ 'name' => 'Elegant Natural', 'tagline' => 'Naturalność w bardziej wyrafinowanym wydaniu.', 'text' => 'Kamień, drewno, tkaniny i ciepłe światło budują atmosferę subtelnej, niewymuszonej elegancji.', 'image' => 'img:styl-elegant-natural-l-1600', 'colors' => '#D6CDBF, #7A5A40, #EAE3D7, #6E6B55', 'colors_label' => 'Kamień · orzech · wełna · oliwka' ],
					[ 'name' => 'Contemporary', 'tagline' => 'Nowoczesność z charakterem.', 'text' => 'Czyste linie, wyraziste materiały i subtelne kontrasty tworzą współczesne wnętrze o zdecydowanym, ale harmonijnym charakterze.', 'image' => 'img:styl-contemporary-l-1600', 'colors' => '#C9C6C0, #2E2D2B, #6B5442, #A9B0AE', 'colors_label' => 'Beton · grafit · dąb wędzony · szkło' ],
					[ 'name' => 'Soft Glamour', 'tagline' => 'Subtelny luksus.', 'text' => 'Kamień, szkło, miękkie tkaniny i eleganckie detale budują bardziej reprezentacyjne wnętrze z wyważonym poczuciem elegancji.', 'image' => 'img:styl-soft-glamour-l-1600', 'colors' => '#F1ECE6, #E2CFC0, #C9B28A, #5C4A40', 'colors_label' => 'Perła · pudrowy beż · szampan · mocha' ],
				],
			] ],
			[ 'statement', [
				'title' => 'Każde wnętrze ma swój charakter.',
				'lead'  => 'Inspiracje pomagają określić kierunek i uporządkować pierwsze decyzje. Wybrane rozwiązania można następnie dopasować do przestrzeni, jej funkcji oraz oczekiwanego standardu.',
				'quote' => 'Z pierwszej inspiracji stopniowo powstaje spójna koncepcja wnętrza.',
				'cream' => true,
			] ],
			[ 'split', [
				'eyebrow'   => 'Od inspiracji do realizacji',
				'title'     => 'Od pierwszej inspiracji do gotowego mieszkania.',
				'lead'      => 'Czasem wszystko zaczyna się od jednego wnętrza, materiału albo detalu, który po prostu przyciąga uwagę.',
				'text'      => 'Punktem wyjścia może być gotowy projekt, kilka inspiracji lub dopiero pierwsza wizja wnętrza. Dalszy zakres można dopasować do konkretnego mieszkania i potrzeb realizacji.',
				'btn_style' => 'btn',
				'image'     => 'img:proces-2-1600',
			] + $cta ],
		],
	],
	[
		'slug'   => 'realizacje',
		'title'  => 'Realizacje',
		'seo'    => 'Wybrane mieszkania przygotowane przez Remontuj24, od odbioru kluczy do gotowego wnętrza. Gdańsk, Gdynia, Sopot.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'Realizacje',
				'title'   => "Mieszkania, które\nprowadziliśmy do końca.",
				'lead'    => 'Każda realizacja zaczyna się inaczej: od stanu deweloperskiego, gotowego projektu albo kilku inspiracji. Kończy się tak samo — gotowym wnętrzem.',
			] ],
			[ 'realizacje', [] ],
			[ 'closing', [
				'eyebrow' => 'Twoje mieszkanie',
				'title'   => 'Następna realizacja może być Twoja.',
				'sub'     => 'Punktem wyjścia może być gotowy projekt, kilka inspiracji lub dopiero pierwsza wizja wnętrza.',
			] + $cta ],
		],
	],
	[
		'slug'   => 'poradnik',
		'title'  => 'Poradnik',
		'seo'    => 'Praktyczne informacje o wykończeniu mieszkania pod klucz: od odbioru kluczy, przez koszty i planowanie, po błędy, których warto uniknąć.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'Poradnik',
				'title'   => "Dobre decyzje zaczynają się\nod właściwych informacji.",
				'lead'    => 'Przygotowanie mieszkania to wiele decyzji podejmowanych na różnych etapach.',
				'text'    => 'W Poradniku zebraliśmy praktyczne informacje, które pomagają uporządkować najważniejsze kwestie, uniknąć przypadkowych wyborów i spokojnie przejść przez kolejne etapy.',
				'image'   => 'img:martwa-natura-1600',
			] ],
			[ 'poradnik', [] ],
			[ 'cta-band', [
				'eyebrow' => 'Pytanie o Twoje mieszkanie',
				'title'   => 'Nie znalazłeś odpowiedzi?',
				'text'    => 'Każde mieszkanie ma swoją specyfikę. Krótka rozmowa pozwala ustalić, czego wymaga Twoje i od czego najlepiej zacząć.',
			] ],
		],
	],
	[
		'slug'   => 'o-nas',
		'title'  => 'O nas',
		'seo'    => 'Remontuj24 powstało z wieloletniego doświadczenia w przygotowaniu i wykańczaniu mieszkań w Trójmieście. Planujemy, przewidujemy i koordynujemy.',
		'blocks' => [
			[ 'page-hero', [
				'eyebrow' => 'O nas',
				'title'   => "Doświadczenie, które pozwala\npatrzeć kilka kroków naprzód.",
				'lead'    => 'Remontuj24 powstało z wieloletniego doświadczenia w przygotowaniu i wykańczaniu mieszkań w Trójmieście.',
				'motto'   => 'Jeden kontakt. Jeden proces. Pełna koordynacja.',
				'image'   => 'img:gdansk-1600',
			] ],
			[ 'factors', [
				'eyebrow' => 'Jak pracujemy',
				'title'   => 'Znamy ten proces od strony praktycznej.',
				'lead'    => 'Wiemy, które decyzje mają znaczenie na początku, co powinno wydarzyć się później i czego będą wymagały kolejne etapy.',
				'items'   => [
					[ 'icon' => 'house', 'title' => 'Planować', 'text' => 'Porządkujemy zakres, kolejność prac i najważniejsze decyzje, zanim na miejscu pojawi się pierwszy wykonawca.' ],
					[ 'icon' => 'target', 'title' => 'Przewidywać', 'text' => 'Dzięki doświadczeniu widzimy zależności, które pojawiają się po drodze, i przygotowujemy kolejne etapy z wyprzedzeniem.' ],
					[ 'icon' => 'screen', 'title' => 'Koordynować', 'text' => 'Łączymy wykonawców, zamówienia i dostawy w jeden proces prowadzony na miejscu, również gdy właściciel jest daleko.' ],
				],
			] ],
			[ 'split', [
				'eyebrow'  => 'Od czego zaczynamy',
				'title'    => 'Każda inwestycja ma inny punkt wyjścia.',
				'text'     => "Czasem jest nim gotowy projekt, czasem kilka inspiracji, a czasem dopiero pierwsza wizja wnętrza. Na każdym z tych etapów można rozpocząć rozmowę, uporządkować priorytety i określić dalszy kierunek.\nRealizację możemy prowadzić również wtedy, gdy właściciel mieszka poza Trójmiastem. Ustalenia, decyzje i postępy mogą być prowadzone zdalnie, podczas gdy kolejne etapy organizowane są na miejscu.",
				'btn_text' => 'Zobacz, jak działamy',
				'btn_url'  => '/jak-dzialamy/',
				'image'    => 'img:zdalnie-1600',
				'flip'     => true,
				'cream'    => true,
			] ],
			[ 'statement', [
				'eyebrow' => 'Nasza rola',
				'quote'   => 'Naszą rolą jest prowadzić proces od pierwszych ustaleń do gotowego wnętrza — planować, przewidywać i koordynować to, co powinno wydarzyć się po drodze.',
				'motto'   => 'Jeden kontakt. Jeden proces. Pełna koordynacja.',
			] ],
			[ 'closing', [
				'eyebrow'  => 'Zaczynamy od rozmowy',
				'title'    => 'Porozmawiajmy o mieszkaniu.',
				'sub'      => 'Każde mieszkanie i zakres prac są inne. Pierwszym krokiem jest krótka rozmowa pozwalająca określić potrzeby, zakres oraz możliwości realizacji.',
				'btn_text' => 'Umów rozmowę',
			] ],
		],
	],
	[
		'slug'   => 'kontakt',
		'title'  => 'Kontakt',
		'seo'    => 'Pierwszy krok to rozmowa. Zadzwoń pod 512 64 00 44 lub napisz na biuro@remontuj24.pl. Mieszkania pod klucz w Gdańsku, Gdyni i Sopocie.',
		'blocks' => [
			[ 'contact', [
				'title' => "Pierwszy krok\nto rozmowa.",
				'rows'  => [
					[ 'label' => 'Obszar działania', 'value' => 'Gdańsk · Gdynia · Sopot' ],
					[ 'label' => 'Właściciel poza Trójmiastem', 'value' => 'Ustalenia zdalnie' ],
				],
			] ],
			[ 'factors', [
				'eyebrow' => 'Co dalej',
				'title'   => 'Nie trzeba mieć gotowego projektu.',
				'lead'    => 'Na początku wystarczy określić mieszkanie, jego przeznaczenie i oczekiwany efekt. Dalsze kroki można uporządkować wspólnie.',
				'tight'   => true,
				'items'   => [
					[ 'icon' => 'number', 'title' => 'Rozmowa i zakres', 'text' => 'Poznajemy potrzeby, przeznaczenie mieszkania i oczekiwany standard.' ],
					[ 'icon' => 'number', 'title' => 'Plan i przygotowanie', 'text' => 'Ustalamy kolejność prac, terminy i najważniejsze zależności.' ],
					[ 'icon' => 'number', 'title' => 'Realizacja', 'text' => 'Koordynujemy kolejne etapy aż do gotowego wnętrza.' ],
				],
			] ],
			[ 'band-image', [ 'image' => 'img:panorama-1940', 'height' => 'low' ] ],
		],
	],
];
