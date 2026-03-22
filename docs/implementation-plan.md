# Implementation Plan

## Úvod

Tento dokument popisuje odporúčaný postup implementácie projektu Shooting Competition na základe doménového modelu definovaného v docs/domain-model.md.

Cieľom dokumentu nie je znovu popisovať doménu, ale určiť praktické poradie implementácie, hlavné technické kroky a hranice MVP. Dokument má slúžiť ako pracovný plán pre vývoj a ako podklad pre AI asistenta, ktorý bude projekt implementovať.

## 1. Implementačné princípy

Implementácia musí rešpektovať doménový model a zachovať oddelenie medzi scoring logikou, organizačnou logikou súťaže a prezentačnou vrstvou.

Prioritou prvej verzie je vytvoriť funkčné MVP pre súťaže typu M400. Všetky rozhodnutia v implementácii majú preto smerovať k čo najjednoduchšiemu riešeniu, ktoré spoľahlivo pokryje M400 a zároveň neuzavrie cestu k neskoršiemu rozšíreniu o M800, G200 a G400.

Pri návrhu persistence a aplikačnej logiky sa majú uprednostniť jednoduché a čitateľné dátové štruktúry. Ak je pre MVP výhodnejšie uložiť niektoré časti konfigurácie ako štruktúrovaný JSON namiesto plne normalizovaného modelu, má sa uprednostniť tento jednoduchší prístup.

Zdrojom pravdy pre scoring zostávajú uložené výsledky na terčoch a snapshot konfigurácie súťaže. Všetky odvodené výstupy, najmä subtotaly, celkové výsledky a poradia, sa majú počítať z týchto dát a podľa potreby ukladať ako optimalizačné snapshoty pre prezentačnú vrstvu.

Pri implementácii sa má dôsledne rozlišovať medzi údajmi, ktoré systém iba eviduje, a údajmi, ktoré musia byť doménovo konzistentné pre oficiálne vyhodnotenie. Uloženie nekompletných alebo nekonzistentných vstupov má byť možné, no tieto dáta nesmú vstupovať do oficiálneho výpočtu výsledkov a nesmú umožniť ukončenie súťaže.

## 2. MVP scope implementácie

Prvá implementačná fáza sa má sústrediť výhradne na funkčné pokrytie súťaže typu M400. Cieľom MVP nie je vyriešiť celý budúci rozsah systému, ale dodať stabilný základ, na ktorom bude možné postaviť ďalšie formáty a organizačné funkcie.

MVP má pokryť najmä správu typu súťaže, vytvorenie konkrétnej súťaže, evidenciu strelcov, prihlásenie strelcov do súťaže, zadávanie výsledkov na jednotlivé terče, výpočet subtotalov, výpočet celkového výsledku a generovanie priebežného aj finálneho poradia.

Súčasťou MVP má byť aj uloženie snapshotu konfigurácie terčov v rámci konkrétnej súťaže, evidencia nekonzistentných výsledkov bez blokovania uloženia formulára a mechanizmus, ktorý zabráni použiť chybné alebo neúplné dáta na oficiálne vyhodnotenie a ukončenie súťaže.

MVP má zároveň pokryť základnú organizačnú vrstvu potrebnú na praktické použitie systému, najmä štartové čísla, stav účasti strelca v súťaži a údaje potrebné pre zohľadnenie zdieľanej zbrane pri rozlosovaní alebo organizácii priebehu súťaže.

Tímové výsledky môžu byť v MVP implementované ako jednoduchý odvodený výsledkový pohľad nad individuálnymi dátami, bez potreby budovať zložitý samostatný modul. Naopak, mimo rozsahu MVP zatiaľ zostávajú brokové disciplíny, rozšírené štatistiky, komplexné workflow rozstrelu, online registrácia, platby a pokročilé prezentačné alebo exportné funkcie.

## 3. Odporúčané poradie implementácie

Implementácia má postupovať od stabilných referenčných dát a konfigurácie k výsledkom, výpočtom a prezentačným výstupom.

V prvej fáze sa má pripraviť základný model pre definície terčov a typy súťaží, vrátane konfigurácie terčov v type súťaže. Cieľom tejto fázy je mať možnosť korektne zadefinovať M400 ako konfigurovateľný formát bez väzby na konkrétne podujatie.

V druhej fáze sa má implementovať model konkrétnej súťaže vrátane snapshotu konfigurácie terčov a základných organizačných nastavení. Po dokončení tejto časti musí byť možné založiť novú súťaž typu M400 so všetkými údajmi potrebnými pre scoring a priebeh podujatia.

V tretej fáze sa má implementovať evidencia strelcov a ich prihlásení do súťaže. Táto vrstva má pokryť štartové čísla, kategórie, stav účasti a údaje potrebné pre organizáciu súťaže, vrátane informácie o zdieľanej zbrani.

V štvrtej fáze sa má implementovať zadávanie výsledkov na jednotlivé terče vrátane rozpisu zásahov, kontroly konzistencie vstupu a výpočtu subtotalov.

V piatej fáze sa má implementovať výpočet celkového výsledku, pravidlá pre rovnosť bodov a generovanie poradia vrátane snapshotov pre prezentačnú vrstvu.

V šiestej fáze sa má doplniť základná podpora tímových výsledkov, stavov súťaže a podmienok potrebných na oficiálne ukončenie podujatia.

Takto zvolený postup minimalizuje riziko, že sa prezentačné alebo organizačné časti začnú implementovať skôr, než bude stabilné scoring jadro.

## 4. Databázová a perzistenčná vrstva

Perzistenčná vrstva má v MVP kombinovať relačný model pre hlavné doménové entity a štruktúrované JSON polia pre časti konfigurácie, ktoré sú prirodzene snapshotované alebo ktoré by boli v plne normalizovanej podobe zbytočne komplikované.

Relačne majú byť modelované najmä stabilné entity, ktoré majú vlastnú identitu a opakované väzby naprieč systémom, najmä definícia terča, `CompetitionType`, súťaž, strelec, `CompetitionEntry` a `TargetResult`.

Naopak, časti modelu, ktoré predstavujú snapshot konfigurácie alebo štruktúrovaný rozpis s pevne obmedzeným rozsahom, môžu byť v MVP uložené ako JSON. To sa týka najmä bodovacej schémy terča, snapshotu konfigurácie terčov uloženého pri súťaži a rozpisu zásahov uloženého pri výsledku na terči.

Tento prístup znižuje počet tabuliek a zjednodušuje implementáciu bez toho, aby sa narušila logika domény. Zároveň ponecháva otvorenú možnosť, aby boli tieto časti v budúcnosti refaktorované do detailnejšie normalizovaného modelu, ak si to vyžiada ďalší rozvoj systému.

Pri návrhu databázy sa má dôsledne rozlišovať medzi zdrojom pravdy a optimalizačnými údajmi. Subtotal za terč, celkový výsledok súťažiaceho alebo výsledkové poradie môžu byť uložené ako cache alebo snapshot, ale ich význam musí byť vždy odvodený z primárnych vstupných dát a z konfigurácie konkrétnej súťaže.

## 5. Aplikačné služby a výpočtová logika

Implementácia má oddeliť perzistenciu dát od doménovej logiky, ktorá zabezpečuje scoring, validáciu a tvorbu výsledkových snapshotov. Výpočtové pravidlá sa nemajú rozptyľovať medzi formuláre, controllery a prezentačnú vrstvu, ale majú byť sústredené do samostatných aplikačných služieb.

Samostatná služba má byť zodpovedná za vytvorenie súťaže z typu súťaže a za vytvorenie snapshotu konfigurácie terčov. Ďalšia služba má spracovávať uloženie výsledku na terči, kontrolu konzistencie rozpisu zásahov a výpočet subtotalu.

Nad týmito vstupmi má existovať samostatná logika pre výpočet celkového výsledku súťažiaceho, aplikáciu pravidiel pre rovnosť bodov a generovanie poradia. Výsledkom tejto vrstvy má byť nielen výpočtový výsledok, ale aj snapshot pripravený pre prezentačnú vrstvu.

Osobitná služba alebo samostatná časť výpočtovej logiky má zabezpečovať tímové výsledky, pretože tie vznikajú agregáciou nad individuálnymi dátami. Rovnako má byť oddelená aj logika organizačných procesov, napríklad prideľovanie štartových čísel alebo rešpektovanie obmedzení vyplývajúcich zo zdieľanej zbrane.

Takéto rozdelenie umožní, aby bola implementácia čitateľná, testovateľná a pripravená na neskoršie rozšírenie bez zásahu do základného scoring jadra.

## 6. Administrátorské workflow MVP

MVP má podporovať jednoduchý a priamočiary administrátorský workflow, ktorý zodpovedá reálnemu priebehu spracovania súťaže od založenia podujatia až po publikovanie výsledkov.

Prvým krokom je vytvorenie alebo výber typu súťaže a následné založenie konkrétnej súťaže. Pri tomto kroku sa musí vytvoriť aj snapshot konfigurácie terčov a uložiť organizačné nastavenia potrebné pre priebeh podujatia.

Následne musí byť možné evidovať strelcov a prihlasovať ich do konkrétnej súťaže. Pri prihlásení sa zadávajú alebo generujú štartové čísla, kategórie, stav účasti a prípadné informácie o zdieľanej zbrani.

Po príprave štartového poľa musí administrátor vedieť priebežne zapisovať výsledky na jednotlivé terče. Systém má pri ukladaní upozorňovať na nekonzistentné vstupy, ale nesmie blokovať evidenciu rozpracovaných alebo chybne dodaných podkladov.

Po doplnení výsledkov musí byť možné spustiť alebo obnoviť výpočet subtotalov, celkových výsledkov a poradia. Administrátor musí mať zároveň k dispozícii prehľad nekonzistentných alebo neuzavretých údajov, ktoré bránia oficiálnemu ukončeniu súťaže.

Záverečným krokom workflow je potvrdenie finálneho poradia a prechod súťaže do ukončeného alebo publikovaného stavu. Tento krok musí byť podmienený tým, že všetky relevantné výsledky sú dostatočne konzistentné a že sú vyriešené aj situácie, ktoré si vyžadovali rozstrel alebo rozhodnutie rozhodcov.

## 7. Verejná prezentačná vrstva MVP

MVP má obsahovať aj základnú verejnú prezentačnú vrstvu, ktorá umožní zobrazovať priebežné alebo finálne výsledky bez potreby prístupu do administrácie.

Prezentačná vrstva nemá počítať scoring logiku priamo pri zobrazovaní. Jej úlohou je čítať pripravené `RankingSnapshot`y a zobrazovať ich v podobe, ktorá je zrozumiteľná pre organizátora, súťažiacich aj verejnosť.

Na úrovni MVP má byť možné zobraziť najmä absolútne poradie súťaže, poradie v kategóriách a podľa potreby aj tímové výsledky. Súčasťou prezentácie má byť aj rozpad výsledku súťažiaceho na jednotlivé terče a jeho celkový súčet.

Prezentačná vrstva má jasne rozlišovať medzi priebežnými a finálnymi výsledkami. Ak súťaž ešte nie je ukončená alebo obsahuje nevyriešené nekonzistencie, musí byť zrejmé, že zobrazené poradie má len priebežný charakter.

Takto navrhnutá prezentačná vrstva umožní publikovať výsledky rýchlo a spoľahlivo, bez potreby opakovať zložitú výpočtovú logiku pri každom načítaní stránky.

## 8. Čo neimplementovať v prvej fáze

V prvej implementačnej fáze sa nemajú riešiť funkcie, ktoré nie sú nevyhnutné pre spoľahlivé fungovanie M400 v bežnej prevádzke. Cieľom MVP je stabilný scoring a výsledky, nie úplné pokrytie všetkých budúcich scenárov.

Mimo rozsahu prvej fázy preto zostávajú najmä brokové disciplíny, špecifické workflow pre iné formáty než M400, pokročilé štatistiky, rozsiahle exporty, online registrácia, platby a automatizované generovanie dokumentov.

Rovnako sa v prvej fáze nemá budovať zbytočne zložitý normalizovaný model tam, kde postačuje jednoduchá a čitateľná JSON reprezentácia. Cieľom nie je dosiahnuť maximálnu abstrakciu, ale doručiť implementáciu, ktorá verne pokryje doménu a zostane udržateľná.

Do neskorších fáz je vhodné odložiť aj tie oblasti, ktoré si vyžadujú detailnejšie procesné pravidlá, napríklad plnohodnotný modul rozstrelov, pokročilé losovanie s viacerými obmedzeniami alebo rozšírené workflow tímových súťaží, ak presiahnu jednoduchú agregáciu individuálnych výsledkov.

Takto definované hranice MVP pomáhajú udržať implementáciu sústredenú na scoring jadro a zabraňujú tomu, aby sa prvá verzia zbytočne rozrástla do šírky skôr, než bude spoľahlivo fungovať základ systému.

## 9. Zhrnutie implementačného postupu

Implementácia má najprv vytvoriť stabilný základ pre M400 ako prvý podporovaný formát. Tento základ sa opiera o jasne definovaný doménový model, jednoduchú perzistenciu kombinujúcu relačné entity a JSON snapshoty, a o oddelenú výpočtovú logiku pre scoring, poradie a `RankingSnapshot`y.

Prvá fáza má pokryť celý tok od definície súťaže cez evidenciu strelcov a zadávanie výsledkov až po výpočet poradia a zobrazenie výsledkov. Všetky ďalšie rozšírenia majú byť budované až na tomto funkčnom a overenom jadre.

Ak bude implementácia sledovať poradie a princípy popísané v tomto dokumente, vznikne systém, ktorý bude použiteľný v praxi už pre M400 a zároveň pripravený na ďalší rozvoj bez potreby meniť základnú architektúru projektu.
