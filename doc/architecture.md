# Architecture

## Úvod

Tento dokument popisuje technickú architektúru projektu Shooting Competition. Vychádza z dokumentov README.md, docs/domain-model.md a docs/implementation-plan.md a rozpracúva ich do technického návrhu systému.

Cieľom dokumentu nie je znovu definovať doménu ani implementačné poradie, ale určiť, ako má byť systém technicky rozdelený, kde majú byť jednotlivé zodpovednosti a akým spôsobom majú spolupracovať perzistencia, aplikačná logika a prezentačná vrstva.

## 1. Architektonické ciele

Architektúra systému musí podporovať spoľahlivé spracovanie výsledkov súťaže typu M400 a zároveň zostať pripravená na neskoršie rozšírenie o M800, G200 a G400 bez potreby meniť základné princípy návrhu.

Základným cieľom architektúry je oddeliť scoring jadro od administrátorského rozhrania, verejnej prezentačnej vrstvy a organizačných workflow súťaže. Výpočet výsledkov a poradia preto nesmie byť závislý od konkrétnych formulárov, šablón alebo UI komponentov.

Architektúra musí rozlišovať medzi zdrojom pravdy a odvodenými výstupmi. Zdrojom pravdy sú uložené výsledky na terčoch a snapshot konfigurácie konkrétnej súťaže. Subtotaly, celkové výsledky, poradia a `RankingSnapshot`y sú odvodené dáta, ktoré je možné ukladať pre optimalizáciu, ale ich význam musí vždy vychádzať z primárnych vstupných údajov.

Systém musí umožniť uložiť aj nekompletné alebo nekonzistentné vstupy, ak je to potrebné pre praktické spracovanie podkladov. Zároveň však musí zabezpečiť, aby takéto dáta nevstupovali do oficiálneho vyhodnotenia a neumožnili ukončenie súťaže bez odstránenia problémov.

Technická architektúra má uprednostniť jednoduchosť, čitateľnosť a udržateľnosť pred predčasnou generalizáciou. V prvej verzii sa preto majú používať také technické riešenia, ktoré spoľahlivo pokryjú M400 a zachovajú čisté rozhrania pre budúce rozšírenia, aj keď nie všetky časti systému budú od začiatku modelované maximálne abstraktne.

## 2. Vrstvy systému

Systém má byť rozdelený do niekoľkých jasne oddelených vrstiev, z ktorých každá nesie inú zodpovednosť. Cieľom tohto rozdelenia je zabrániť miešaniu doménovej logiky, organizačných workflow, perzistencie a prezentácie výsledkov.

Základ tvorí doménová vrstva, ktorá reprezentuje pojmy a pravidlá systému, najmä súťaž, `CompetitionType`, strelec, `CompetitionEntry`, `TargetResult`, rozpis zásahov a pravidlá výpočtu poradia. Táto vrstva nesmie byť závislá od konkrétneho používateľského rozhrania ani od spôsobu prezentácie výsledkov.

Nad ňou sa nachádza aplikačná vrstva, ktorá orchestruje jednotlivé use-casy systému. Sem patrí vytvorenie súťaže z typu súťaže, uloženie výsledku na terči, kontrola konzistencie vstupov, prepočet subtotalov, výpočet celkových výsledkov, generovanie poradia a vytváranie výsledkových snapshotov.

Samostatnú zodpovednosť má perzistenčná vrstva, ktorá zabezpečuje uloženie a načítanie doménových dát. Táto vrstva má pracovať s relačnými entitami a JSON snapshotmi podľa potrieb jednotlivých častí modelu, ale nemá v sebe niesť scoring logiku ani rozhodovať o pravidlách vyhodnotenia.

Na vrchole stoja dve prezentačné vrstvy. Administrátorská vrstva slúži na správu súťaží, strelcov, prihlásení a výsledkov. Verejná prezentačná vrstva slúži na zobrazovanie priebežných alebo finálnych výsledkov. Ani jedna z týchto vrstiev nemá obsahovať scoring pravidlá; majú iba pracovať s dátami a výsledkovými snapshotmi pripravenými aplikačnou logikou.

Takto navrhnuté vrstvy umožňujú, aby sa scoring jadro rozvíjalo nezávisle od používateľského rozhrania a aby sa verejná prezentácia výsledkov mohla meniť bez zásahu do doménovej a výpočtovej logiky.

## 3. Hlavné technické moduly

Architektúra systému má byť rozdelená aj na úrovni hlavných technických modulov, ktoré zodpovedajú najdôležitejším oblastiam aplikácie. Tieto moduly nemajú predstavovať izolované samostatné aplikácie, ale logicky oddelené časti jedného systému s jasne určenými zodpovednosťami.

Prvým modulom je modul konfigurácie súťaží, ktorý spravuje definície terčov, typy súťaží a ich konfiguráciu. Jeho úlohou je umožniť vytvoriť a udržiavať formáty ako M400, M800, G200 alebo G400 bez väzby na konkrétne podujatie.

Druhým modulom je modul správy súťaží, ktorý pracuje s konkrétnymi podujatiami. Zodpovedá za založenie súťaže, vytvorenie snapshotu konfigurácie, uloženie organizačných nastavení a správu stavov súťaže počas jej životného cyklu.

Tretím modulom je modul účastníkov, ktorý pokrýva evidenciu strelcov a ich prihlásení do súťaže. Sem patrí aj práca so štartovými číslami, kategóriami, stavmi účasti a údajmi potrebnými pre organizačné procesy, napríklad zdieľaná zbraň.

Štvrtým modulom je modul scoringu, ktorý predstavuje jadro systému. Je zodpovedný za spracovanie výsledkov na terčoch, kontrolu konzistencie, výpočet subtotalov, celkových výsledkov a pravidiel pre rovnosť bodov.

Piatym modulom je modul výsledkov a publikovania, ktorý vytvára a spravuje `RankingSnapshot`y určené pre administrátorské prehľady aj verejnú prezentáciu výsledkov. Tento modul pracuje nad výstupmi scoring vrstvy a zabezpečuje ich stabilné a rýchle zobrazovanie.

Takto rozdelené technické moduly uľahčujú orientáciu v kóde, pomáhajú oddeliť zodpovednosti a vytvárajú dobrý základ pre neskoršie rozšírenie systému bez narušenia scoring jadra.

## 4. Stratégia perzistencie

Perzistencia má kombinovať relačný model pre stabilné doménové entity a JSON štruktúry pre snapshoty a prirodzene vnorené konfiguračné alebo výsledkové dáta.

Relačne majú byť ukladané najmä entity, ktoré majú vlastnú identitu a opakovane sa používajú naprieč systémom, najmä definície terčov, typy súťaží, konkrétne súťaže, strelci, prihlásenia do súťaže a výsledky na terčoch.

Naopak, tie časti modelu, ktoré reprezentujú snapshot alebo majú prirodzene štruktúrovaný, ale obmedzený vnútorný tvar, majú byť v MVP ukladané ako JSON. Ide najmä o bodovaciu schému terča, snapshot konfigurácie terčov uložený pri konkrétnej súťaži a rozpis zásahov uložený pri výsledku na terči.

Tento prístup znižuje zložitosť databázového modelu a umožňuje rýchlejšiu implementáciu bez straty doménovej presnosti. Zároveň ponecháva otvorenú možnosť, aby boli tieto časti v budúcnosti refaktorované do detailnejšie normalizovanej podoby, ak si to vyžiada rast systému.

Pri návrhu perzistencie sa musí dôsledne rozlišovať medzi primárnymi a odvodenými dátami. Primárne dáta predstavujú uložené vstupy a snapshot konfigurácie súťaže. Odvodené dáta, ako subtotaly, celkové výsledky alebo snapshoty poradia, môžu byť uložené pre výkon a prezentáciu, ale ich význam musí byť vždy odvoditeľný z primárnych údajov.

## 5. Tok dát pri spracovaní výsledkov

Tok dát pri spracovaní výsledkov má byť navrhnutý tak, aby bolo vždy zrejmé, ktoré údaje sú vstupné, ktoré sú odvodené a ktoré slúžia len ako prezentačný snapshot.

Proces začína uložením alebo úpravou výsledku strelca na konkrétnom terči. Pri tomto kroku sa ukladá rozpis zásahov a podľa potreby aj pomocné stavové informácie o konzistencii alebo úplnosti výsledku.

Po uložení výsledku má aplikačná logika vykonať kontrolu konzistencie rozpisu zásahov voči snapshotu konfigurácie súťaže a vypočítať subtotal za daný terč. Ak je to potrebné, má sa označiť, že výsledok nie je spôsobilý vstúpiť do oficiálneho vyhodnotenia.

Následne má byť možné spustiť alebo obnoviť výpočet celkového výsledku súťažiaceho, pravidiel pre rovnosť bodov a poradia v rámci celej súťaže, kategórií alebo tímov. Výstupom tejto vrstvy má byť `RankingSnapshot` pripravený pre administrátorské aj verejné zobrazenie.

Takto navrhnutý tok dát zabezpečuje, že scoring logika je centralizovaná, opakovateľná a nezávislá od konkrétneho formulára alebo obrazovky, z ktorej bol vstup do systému zapísaný.

## 6. Výsledkové snapshoty

Architektúra má explicitne počítať s výsledkovými snapshotmi ako s osobitnou vrstvou medzi scoring jadrom a prezentačnými obrazovkami. Ich úlohou je poskytovať stabilný a rýchlo dostupný výsledkový výstup bez potreby opakovať kompletnú výpočtovú logiku pri každom zobrazení.

Výsledkový snapshot nepredstavuje zdroj pravdy pre scoring. Zdrojom pravdy zostávajú uložené výsledky na terčoch a snapshot konfigurácie konkrétnej súťaže. Snapshot výsledkov je odvodený technický artefakt, ktorý sumarizuje stav vyhodnotenia v podobe vhodnej pre administrátorské prehľady aj verejnú prezentáciu.

Snapshot musí vedieť reprezentovať individuálne poradie, poradie v kategóriách aj tímové výsledky. Zároveň má byť schopný zachytiť rozdiel medzi priebežným a finálnym stavom výsledkov a podľa potreby evidovať aj dodatočné rozhodovacie zásahy, napríklad rozstrel alebo rozhodnutie rozhodcov.

Takto navrhnutá vrstva snapshotov umožní oddeliť výpočtovú zložitosť scoringu od požiadaviek na rýchle a stabilné zobrazovanie výsledkov. Zároveň vytvára priestor na neskoršie rozšírenie verejnej prezentácie bez zásahu do scoring jadra.

## 7. Administrátorská vrstva

Administrátorská vrstva má poskytovať rozhranie pre správu všetkých údajov potrebných na prípravu, priebeh a vyhodnotenie súťaže. Jej úlohou nie je niesť scoring logiku, ale umožniť bezpečne a prehľadne pracovať s dátami, nad ktorými scoring jadro vykonáva výpočty.

Administrátorské rozhranie má pokrývať najmä správu typov súťaží, zakladanie konkrétnych súťaží, evidenciu strelcov, prihlásenia do súťaže, priraďovanie štartových čísel a zadávanie výsledkov na jednotlivé terče.

Pri práci s výsledkami má administrátorská vrstva zobrazovať aj informácie o konzistencii vstupov, chybách v rozpise zásahov a stave pripravenosti súťaže na oficiálne vyhodnotenie. Upozornenia a validačné hlásenia majú pomáhať používateľovi identifikovať problémy, nesmú však nahrádzať samotnú scoring logiku ani byť jej zdrojom.

Administrátorské rozhranie má byť navrhnuté tak, aby dokázalo pracovať s rozpracovanými dátami, nekonzistentnými podkladmi aj priebežnými výsledkami bez toho, aby sa scoring pravidlá presúvali do formulárov alebo UI komponentov.

Takto navrhnutá administrátorská vrstva umožní pohodlnú správu súťaže a zároveň zachová čistú hranicu medzi používateľským rozhraním a doménovou logikou systému.

## 8. Verejná prezentačná vrstva

Verejná prezentačná vrstva má slúžiť výhradne na zobrazovanie výsledkov a základných informácií o súťaži. Nemá vykonávať scoring výpočty ani rozhodovať o stave výsledkov, ale iba pracovať s pripravenými výsledkovými snapshotmi a údajmi publikovanými systémom.

Táto vrstva má byť optimalizovaná na jednoduché a rýchle zobrazovanie priebežných alebo finálnych výsledkov. Na úrovni MVP má podporovať najmä absolútne poradie, poradie v kategóriách a podľa potreby aj tímové výsledky.

Prezentácia musí jasne rozlišovať medzi priebežným a oficiálnym stavom výsledkov. Používateľ musí vedieť rozpoznať, či ide o priebežné poradie, finálne potvrdené výsledky alebo výsledky, ktoré ešte môžu byť ovplyvnené opravou vstupných údajov, rozstrelom alebo rozhodnutím rozhodcov.

Verejná vrstva má byť z technického pohľadu čo najtenšia. Jej úlohou je interpretovať a zobraziť publikované dáta, nie znovu implementovať doménové pravidlá. Takýto prístup zjednodušuje frontend, znižuje riziko nekonzistentného správania a umožňuje neskôr meniť spôsob prezentácie bez zásahu do scoring jadra.

## 9. Rozšíriteľnosť architektúry

Architektúra má byť od začiatku navrhnutá tak, aby prvá implementácia pre M400 neblokovala budúce rozšírenie o ďalšie formáty a organizačné scenáre.

Rozšíriteľnosť systému nemá byť dosahovaná predčasne zložitou abstrakciou, ale jasným oddelením zodpovedností a dôsledným používaním konfigurovateľných dát namiesto hardcoded pravidiel. To znamená najmä oddelenie definície terča od typu súťaže, uloženie snapshotu konfigurácie pri konkrétnej súťaži a reprezentáciu scoring pravidiel cez dáta, nie cez vetvenie UI alebo controllerov.

Vďaka tomuto prístupu má byť možné doplniť M800, G200 a G400 bez zásahu do základnej štruktúry systému. Rozšírenie sa má prejaviť najmä doplnením nových konfigurácií typov súťaží, nie prepisovaním scoring jadra.

Rovnaký princíp sa má použiť aj pri neskorších organizačných rozšíreniach, napríklad pri pokročilejšom losovaní, zložitejších tímových pravidlách alebo doplnení ďalších prezentačných a exportných vrstiev. Architektúra má zostať otvorená pre rast systému, ale zároveň má ostať čitateľná a prakticky implementovateľná v rámci MVP.

## 10. Zhrnutie architektúry

Architektúra systému je postavená na oddelení doménového modelu, aplikačnej logiky, perzistencie a prezentačných vrstiev. Zdrojom pravdy zostávajú výsledky na terčoch a snapshot konfigurácie konkrétnej súťaže, zatiaľ čo subtotaly, celkové výsledky a poradia sú odvodené dáta spracovávané scoring jadrom.

Technický návrh uprednostňuje jednoduché a čitateľné riešenie vhodné pre MVP, pričom zachováva priestor na budúce rozšírenie o ďalšie formáty súťaží a zložitejšie organizačné scenáre. Kľúčovým prvkom architektúry je oddelenie scoring logiky od administrátorského rozhrania a verejnej prezentácie výsledkov.

Ak bude implementácia rešpektovať princípy popísané v tomto dokumente, vznikne systém, ktorý bude spoľahlivo pokrývať M400 v prvej fáze a zároveň bude pripravený na ďalší rozvoj bez potreby meniť základné architektonické rozhodnutia.
