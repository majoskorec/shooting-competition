# Domain Model

## Úvod

Tento dokument popisuje doménový model projektu Shooting Competition so zameraním na scoring a výsledky súťaží typu M400, s architektúrou pripravenou aj pre M800, G200 a G400.

Produktový kontext, cieľ projektu a MVP scope sú popísané v dokumente README.md. Tento dokument sa sústreďuje výhradne na doménové entity, ich vzťahy a pravidlá výpočtu výsledkov.

## 1. Návrhové princípy

Doménový model sa riadi týmito zásadami:
- Scoring logika musí byť oddelená od UI.
- Výpočet výsledkov, subtotalov a poradia nesmie byť závislý od formulárov, tabuliek ani prezentačnej vrstvy.
- Výsledné poradie je odvodené z dát a pravidiel.
- Zdrojom pravdy sú zadané výsledky a konfigurácia súťaže. Poradie sa má vždy dopočítať, nie ručne ukladať ako autoritatívna hodnota.
- Konfigurácia súťaže musí byť snapshotovaná.
- Pri vytvorení konkrétnej súťaže sa jej pravidlá a konfigurácia terčov skopírujú zo šablóny tak, aby neskoršie zmeny šablón neovplyvnili historické výsledky.
- Bodovacia schéma terča je samostatná doménová informácia.
- Rôzne terče môžu mať rôzne bodové hodnoty zásahov, preto scoring nesmie byť hardcoded iba podľa názvu terča.
- Maximálne skóre je odvodená hodnota.
- Maximálny počet bodov sa určuje z počtu rán a bodovacej schémy terča, nie ako samostatný primárny vstup.
- Model nesmie byť natvrdo previazaný len na M400.
- Hoci M400 je prvý podporovaný formát, návrh musí bez zásadnej prestavby umožniť doplniť aj M800, G200 a G400.

## 2. Doménový kontext

Primárnym cieľom MVP je pokryť súťaž typu M400.

Súťaž typu M400 je v tomto projekte modelovaná ako súťaž zložená zo štyroch terčov:
- líška
- srnec
- kamzík
- diviak

Výsledok súťažiaceho nevzniká zadaním jedného finálneho čísla za terč, ale zo zápisu zásahov podľa bodových hodnôt. Z týchto údajov sa následne dopočíta výsledok za konkrétny terč a potom aj celkový výsledok súťažiaceho.

Jednotlivé terče môžu mať odlišné bodovacie schémy. Doménový model preto musí umožniť, aby mal každý terč vlastnú sadu povolených bodových hodnôt zásahov.

Celkové poradie sa určuje na základe súčtu výsledkov zo všetkých povinných terčov. Pri rovnosti bodov rozhoduje vopred definované poradie terčov. Pre M400 je aktuálne poradie tie-breaku definované takto:
- diviak
- kamzík
- srnec
- líška

Tieto pravidlá musia byť reprezentované dátovým modelom a nesmú byť hardcoded v prezentačnej vrstve.

Model musí zároveň zostať všeobecný. M400 je prvý podporovaný formát, ale rovnaký návrhový princíp sa má dať použiť aj pre M800, G200 a G400. Z tohto dôvodu sa doména nenavrhuje ako jednorazový model pre jednu konkrétnu súťaž, ale ako konfigurovateľný scoring model pre viac typov súťaží.

## 3. Prehľad entít

Doménový model je postavený na niekoľkých základných entitách, ktoré oddeľujú definíciu pravidiel súťaže od konkrétnych výsledkov.

Základom modelu je definícia terča, ktorá reprezentuje všeobecný typ terča, napríklad líška, srnec, kamzík alebo diviak. Na definíciu terča nadväzuje bodovacia schéma terča, ktorá určuje, aké bodové hodnoty zásahov sú pre daný terč povolené.

Štruktúru konkrétneho formátu súťaže reprezentuje `CompetitionType`, napríklad M400, M800, G200 alebo G400. Tento typ určuje, ktoré terče sa v súťaži používajú, koľko rán sa na ne strieľa a aké pravidlá platia pre tie-break.

Konkrétne podujatie reprezentuje súťaž, ktorá vzniká z vybraného typu súťaže. Pri jej vytvorení sa konfiguračné pravidlá prenesú do vlastného snapshotu, aby neskoršie zmeny šablón neovplyvnili historické výsledky.

Účastníka reprezentuje strelec. Jeho účasť v konkrétnej súťaži reprezentuje samostatná entita `Competitor`, pretože ten istý strelec sa môže zúčastniť viacerých súťaží a v každej z nich môže mať inú kategóriu, štartové číslo alebo stav.

Výsledok strelca na konkrétnom terči reprezentuje `TargetResult`. Tento výsledok sa skladá z rozpisu zásahov podľa bodových hodnôt, z ktorého sa dopočítava subtotal za daný terč.

Na úrovni výsledkov celej súťaže sa následne vytvára poradie, ktoré je odvodené z výsledkov na jednotlivých terčoch a z pravidiel typu súťaže. Poradie preto nie je samostatný zdroj pravdy, ale výpočtový výstup nad uloženými dátami.

## 4.1 Definícia terča

Definícia terča reprezentuje všeobecný typ terča používaného v súťaži. Ide o katalógovú entitu, ktorá popisuje samotný terč bez väzby na konkrétnu súťaž alebo konkrétny výsledok.

Príkladmi definície terča sú najmä:
- líška
- srnec
- kamzík
- diviak

Definícia terča má obsahovať stabilné identifikačné a popisné údaje, najmä interný kód, názov a prípadné doplňujúce metadata potrebné pre ďalšie spracovanie.

Definícia terča nesmie obsahovať údaje, ktoré závisia od konkrétneho typu súťaže alebo konkrétneho podujatia. Nepatrí sem teda najmä počet rán, poradie zobrazenia v súťaži ani priorita pri rovnosti bodov. Tieto údaje sa definujú až v kontexte typu súťaže alebo konkrétnej súťaže.

Na definíciu terča nadväzuje bodovacia schéma, ktorá určuje, aké bodové hodnoty zásahov sú pre daný terč povolené. Samotná definícia terča však nemá obsahovať rozpis výsledkov ani scoring výpočty.

Úlohou tejto entity je vytvoriť stabilný referenčný základ, na ktorý sa môžu odkazovať typy súťaží, konkrétne súťaže aj výsledky. Vďaka tomu je možné rovnaký terč použiť vo viacerých formátoch súťaží bez duplicity základných údajov.

## 4.2 Bodovacia schéma terča

Bodovacia schéma terča určuje, aké bodové hodnoty zásahov sú pre daný terč povolené. Rôzne terče môžu používať odlišné sady hodnôt, preto scoring logika nesmie byť odvodená len od názvu terča.

Bodovacia schéma je súčasťou konfigurácie terča a predstavuje štruktúrovaný zoznam povolených bodových hodnôt. Pri zadávaní výsledku sa pre každú povolenú hodnotu eviduje počet zásahov a výsledné skóre terča sa z týchto údajov dopočítava.

V implementácii môže byť bodovacia schéma uložená ako jednoduchá dátová štruktúra, napríklad JSON pole povolených hodnôt. Nie je potrebné modelovať každú bodovú hodnotu ako samostatnú entitu, pokiaľ to nevyžadujú budúce rozšírenia systému.

Tento prístup zachováva flexibilitu modelu a zároveň drží implementáciu primerane jednoduchú pre potreby MVP.

## 4.3 `CompetitionType`

Typ súťaže reprezentuje šablónu formátu, podľa ktorého sa súťaž organizuje a vyhodnocuje. Ide o definíciu pravidiel spoločných pre všetky súťaže rovnakého typu, nie o konkrétne podujatie.

Príkladmi typu súťaže sú najmä:
- M400
- M800
- G200
- G400

Typ súťaže určuje, ktoré terče patria do daného formátu, v akom poradí sa používajú, koľko rán sa na ne strieľa a aké pravidlá sa použijú pri určovaní poradia.

Typ súťaže nesmie obsahovať údaje viazané na konkrétnu akciu, ako sú dátum, miesto, účastníci alebo výsledky. Jeho úlohou je definovať opakovane použiteľný rámec, z ktorého je možné vytvárať konkrétne súťaže.

Z pohľadu domény je `CompetitionType` nadradený konkrétnemu podujatiu. Konkrétna súťaž vzniká ako inštancia zvoleného typu súťaže a preberá z neho základnú konfiguráciu, ktorú si následne uchováva vo vlastnom snapshote.

Tento prístup umožňuje, aby boli pravidlá M400, M800, G200 a G400 definované konzistentne na jednom mieste a zároveň aby historické výsledky zostali stabilné aj v prípade neskorších úprav šablón.

## 4.4 Konfigurácia terča v type súťaže

Samotný `CompetitionType` nestačí na určenie scoring pravidiel, pretože musí byť zrejmé aj to, ako sa v rámci daného typu používa konkrétny terč. Z tohto dôvodu musí model obsahovať samostatnú konfiguráciu väzby medzi typom súťaže a terčom.

Táto konfigurácia určuje, že určitý terč patrí do konkrétneho typu súťaže, a zároveň definuje jeho správanie v rámci daného formátu. Práve sem patria údaje, ktoré nie sú vlastnosťou samotného terča, ale až jeho použitia v konkrétnom type súťaže.

Do tejto konfigurácie patrí najmä:
- počet rán na daný terč
- poradie zobrazenia terča v rámci súťaže

priorita terča pri rovnosti bodov.

Táto vrstva je dôležitá preto, že rovnaký terč môže byť použitý vo viacerých typoch súťaží, ale s odlišným počtom rán alebo inými pravidlami vyhodnotenia. Konfigurácia preto nesmie byť uložená priamo v definícii terča.

Z pohľadu domény ide o prepojenie medzi katalógovou definíciou terča a šablónou typu súťaže. Vďaka tomu je možné modelovať M400, M800, G200 a G400 jednotným spôsobom bez duplicity dát a bez potreby hardcoded logiky pre konkrétne formáty.

## 4.5 `Competition`

Súťaž reprezentuje konkrétne podujatie organizované podľa vybraného typu súťaže. Na rozdiel od typu súťaže nejde o všeobecnú šablónu pravidiel, ale o reálnu inštanciu s vlastným názvom, dátumom, miestom a účastníkmi.

Súťaž vzniká na základe typu súťaže a preberá z neho základnú konfiguráciu. Z pohľadu domény však nejde len o referenciu na `CompetitionType`. Pri vytvorení súťaže sa musí konfigurácia relevantná pre scoring preniesť do vlastného snapshotu, aby neskoršie zmeny typu súťaže neovplyvnili už existujúce výsledky.

Súťaž preto predstavuje hranicu medzi opakovane použiteľnou šablónou a historickými dátami. Typ súťaže určuje všeobecné pravidlá, zatiaľ čo súťaž uchováva konkrétny stav použitý pri vyhodnotení daného podujatia.

Do súťaže patria najmä údaje ako názov, dátum, miesto, organizátor, stav súťaže a organizačné nastavenia konkrétneho podujatia, napríklad kapacita jednej rundy alebo počet súťažiacich, ktorí môžu strieľať súčasne.

Nepatria sem však samotné výsledky na terčoch ani detailná konfigurácia terčov prevzatá zo šablóny, pretože tie majú byť reprezentované samostatnými entitami naviazanými na konkrétnu súťaž.

Takto navrhnutá súťaž umožňuje, aby bolo možné opakovane organizovať viac podujatí rovnakého typu a zároveň zachovať konzistentné historické výsledky aj v prípade neskorších zmien pravidiel alebo konfigurácie šablón.

## 4.6 Snapshot konfigurácie terčov v súťaži

Pri vytvorení súťaže sa konfigurácia terčov nepreberá len dynamicky z typu súťaže, ale uloží sa ako snapshot priamo ku konkrétnej súťaži. Tento snapshot obsahuje všetky údaje potrebné na scoring a vyhodnotenie daného podujatia.

Dôvodom je, že historické výsledky musia zostať stabilné aj v prípade, že sa neskôr zmení šablóna typu súťaže. Súťaž preto nesmie byť pri scoringu závislá od aktuálneho stavu typu súťaže, ale od vlastnej uloženej konfigurácie.

Snapshot konfigurácie terčov obsahuje najmä zoznam použitých terčov a pri každom z nich údaje potrebné pre vyhodnotenie, najmä bodovaciu schému, počet rán, poradie zobrazenia a prioritu pri rovnosti bodov.

V implementácii môže byť tento snapshot uložený ako štruktúrovaný JSON priamo v entite súťaže. Takýto prístup zjednodušuje model a zároveň zachováva všetky údaje potrebné na korektné historické vyhodnotenie výsledkov.

Takto navrhnutý snapshot tvorí hranicu medzi opakovane použiteľnou šablónou typu súťaže a konkrétnym historickým podujatím.

## 4.7 `Shooter`

Strelec reprezentuje osobu, ktorá sa môže zúčastniť jednej alebo viacerých súťaží. Ide o samostatnú doménovú entitu, pretože identita strelca musí byť oddelená od jeho účasti v konkrétnom podujatí a od konkrétnych výsledkov.

Úlohou tejto entity je uchovávať stabilné identifikačné údaje, ktoré sa nemenia podľa konkrétnej súťaže. Patrí sem najmä meno a ďalšie základné údaje potrebné na evidenciu a zobrazenie výsledkov.

Strelec sám o sebe nereprezentuje účasť v súťaži. Ten istý strelec sa môže zúčastniť viacerých súťaží a v každej z nich môže mať inú kategóriu, štartové číslo alebo organizačný status. Z tohto dôvodu sa údaje viazané na konkrétne podujatie nemajú ukladať priamo na entitu strelca.

Definícia strelca preto tvorí stabilný základ pre evidenciu účastníkov naprieč viacerými súťažami. Všetky údaje, ktoré závisia od konkrétnej účasti na podujatí, majú byť modelované samostatne v kontexte prihlásenia do súťaže.

## 4.8 `Competitor`

Prihlásenie strelca do súťaže reprezentuje účasť konkrétneho strelca v konkrétnom podujatí. Ide o samostatnú doménovú vrstvu medzi strelcom a súťažou, pretože ten istý strelec sa môže zúčastniť viacerých súťaží a v každej z nich môže mať odlišné vlastnosti relevantné len pre dané podujatie.

Do prihlásenia patria všetky údaje, ktoré nevystihujú samotnú identitu strelca, ale jeho konkrétnu účasť v súťaži. Typicky sem patrí štartové číslo, kategória, stav účasti a ďalšie informácie potrebné pre prezentáciu, losovanie alebo výsledkové listiny.

Do tejto vrstvy patrí aj informácia o zdieľanej zbrani, ak viacerí strelci v rámci jednej súťaže používajú tú istú zbraň. Tento údaj je viazaný na konkrétne podujatie, a preto nemá byť uložený priamo na entite strelca.

Táto vrstva je dôležitá aj preto, že výsledky sa nemajú viazať priamo na entitu strelca, ale na jeho konkrétnu účasť v súťaži. Výsledok preto vždy patrí k prihláseniu do súťaže, nie k osobe ako takej.

Takto navrhnutý model umožňuje, aby bol ten istý strelec evidovaný len raz, no zároveň aby sa jeho účasť v jednotlivých súťažiach správala samostatne a historicky konzistentne.

## 4.9 `TargetResult`

Výsledok strelca na terči reprezentuje výkon jedného strelca na jednom konkrétnom terči v rámci jednej konkrétnej súťaže. Ide o základnú výsledkovú jednotku, z ktorej sa následne odvádza subtotal za terč aj celkové `RankingSnapshot`.

Tento výsledok musí byť viazaný na `Competitor`, nie priamo na entitu strelca. Zároveň musí byť jednoznačne určené, ku ktorému terču v rámci konkrétnej súťaže patrí. Výsledok teda vždy vzniká v kontexte konkrétneho podujatia a jeho snapshotu konfigurácie.

Samotný `TargetResult` nereprezentuje iba jedno finálne číslo zadané používateľom. Jeho význam spočíva v tom, že agreguje rozpis zásahov podľa bodových hodnôt a predstavuje výsledný subtotal za daný terč.

Do tejto vrstvy patria aj pomocné stavové informácie potrebné pre spracovanie výsledkov, napríklad či je výsledok kompletný, neplatný alebo ešte čaká na doplnenie. Nejde však o miesto, kde sa definujú scoring pravidlá. Tie sú určené konfiguráciou súťaže a bodovacou schémou terča.

Takto navrhnutý `TargetResult` tvorí spojenie medzi konfiguráciou súťaže a konkrétnym výkonom súťažiaceho. Je základom pre všetky ďalšie výpočty, najmä subtotal za terč, celkový súčet bodov a poradie.

## 4.10 Rozpis zásahov na terči

Výsledok strelca na terči je postavený na rozpise zásahov podľa povolených bodových hodnôt. Tento rozpis predstavuje zdrojové dáta, z ktorých sa dopočítava subtotal za daný terč.

Pre každú bodovú hodnotu povolenú bodovacou schémou sa eviduje počet zásahov, ktoré strelec na danom terči dosiahol. Ak má teda terč povolené napríklad hodnoty 0, 1, 3, 8, 9 a 10, výsledok sa neukladá len ako jedno číslo, ale ako súbor počtov zásahov pre tieto hodnoty.

Rozpis zásahov nemá byť modelovaný ako voľný text ani ako neštruktúrovaný údaj. Musí ísť o dátovú štruktúru, ktorá jednoznačne mapuje bodové hodnoty na počet zásahov. V implementácii môže byť tento rozpis uložený ako JSON priamo pri výsledku strelca na terči.

Subtotal za terč sa z rozpisu zásahov dopočítava ako súčet súčinov jednotlivých bodových hodnôt a ich počtu zásahov. Výsledok na terči je preto odvodený z rozpisu zásahov, nie naopak.

Tento model umožňuje presnú validáciu výsledku. Systém vie overiť, či rozpis obsahuje len povolené bodové hodnoty, či počet zásahov zodpovedá počtu rán definovanému pre daný terč a či subtotal sedí s uloženými vstupnými údajmi.

Takto navrhnutý rozpis zásahov zachováva vernosť reálnemu spôsobu zapisovania výsledkov a zároveň poskytuje dostatočne presný základ pre scoring, kontrolu správnosti aj neskoršie rozšírenia systému.

## 4.11 `RankingSnapshot`

Poradie v súťaži nepredstavuje samostatný zdroj pravdy, ale odvodený výpočtový výstup nad uloženými výsledkami. Vychádza z výsledkov strelcov na jednotlivých terčoch a z pravidiel definovaných pre konkrétny `CompetitionType` a jeho snapshot v rámci konkrétneho podujatia.

Základom poradia je celkové skóre súťažiaceho, ktoré vzniká ako súčet subtotalov zo všetkých terčov zaradených do súťaže. Poradie sa určuje zostupne podľa tohto celkového výsledku.

Ak dvaja alebo viacerí strelci dosiahnu rovnaký celkový počet bodov, použije sa pravidlo pre rovnosť bodov definované v konfigurácii súťaže. Pri M400 sa porovnávajú výsledky na jednotlivých terčoch podľa vopred určeného poradia priorít.

Poradie môže byť vyhodnocované nad všetkými účastníkmi súťaže alebo nad ich podmnožinou, najmä v rámci konkrétnej kategórie. Rovnaké scoring a tie-break pravidlá sa pritom aplikujú nad zvolenou množinou súťažiacich, pričom výsledkom môže byť absolútne poradie aj samostatné poradie pre jednotlivé kategórie.

Poradie sa určuje na základe výsledkov a pravidiel súťaže, no pre potreby prezentácie a publikovania výsledkov môže byť ukladané aj ako snapshot. Takýto snapshot nepredstavuje zdroj pravdy pre scoring, ale optimalizovaný a stabilizovaný výstup určený pre zobrazovanie priebežných alebo finálnych výsledkov.

Táto vrstva je dôležitá najmä pri výsledkoch, ktoré vznikajú agregáciou nad viacerými súťažiacimi, napríklad pri tímovej kategórii. V takom prípade snapshot sumarizuje výsledky členov tímu, počíta tímové subtotaly a celkové skóre a uchováva výsledné poradie tímov bez potreby opakovať celú výpočtovú logiku pri každom zobrazení.

Pri určovaní konečného poradia musí model umožniť zachytiť aj situácie, keď samotný automatický výpočet nestačí na definitívne rozhodnutie. Typicky ide o prípady rozstrelu alebo rozhodnutia rozhodcov, ktoré vstupujú do výsledného poradia ako samostatný organizačný a rozhodovací zásah nad rámec bežného scoring výpočtu.

Tieto zásahy nemajú meniť zdrojové výsledky na jednotlivých terčoch, ale majú byť evidované na úrovni výsledného poradia alebo rozhodnutia o poradí. Systém tak musí odlišovať medzi automaticky vypočítaným poradím a finálnym poradím potvrdeným podľa pravidiel súťaže.

## 4.12 `CompetitionTeam`

Tím v súťaži reprezentuje skupinu strelcov, ktorých výsledky sa v rámci konkrétneho podujatia vyhodnocujú spoločne ako tímový výsledok. Nejde o všeobecnú entitu platnú naprieč všetkými súťažami, ale o organizačnú a výsledkovú vrstvu viazanú na konkrétnu súťaž.

Tím vzniká v kontexte konkrétneho podujatia a združuje prihlásenia strelcov do súťaže, ktoré do neho patria. Ten istý strelec tak môže byť v jednej súťaži zaradený do tímu a v inej súťaži nemusí byť členom žiadneho tímu. Z tohto dôvodu sa tím nemá modelovať ako trvalá vlastnosť strelca, ale ako väzba v rámci konkrétnej súťaže.

Úlohou tímu nie je nahrádzať individuálne výsledky, ale vytvárať nad nimi agregovanú vrstvu. Tímový výsledok preto vzniká zo sumarizácie výsledkov jeho členov podľa pravidiel definovaných pre danú tímovú kategóriu alebo výsledkový pohľad.

Do tejto vrstvy patria najmä identita tímu, zoznam jeho členov a pravidlá potrebné na určenie tímového výsledku v rámci konkrétnej súťaže. Samotné individuálne výsledky zostávajú uložené oddelene na úrovni jednotlivých strelcov a tím nad nimi vytvára len agregačný výsledkový pohľad.

Takto navrhnutý model umožňuje, aby systém pracoval súčasne s individuálnymi aj tímovými výsledkami bez duplicity vstupných dát a bez potreby meniť základný scoring model strelca.

## 5.1 Výpočet výsledku na terči

Výsledok strelca na konkrétnom terči sa určuje z rozpisu zásahov podľa bodových hodnôt povolených bodovacou schémou daného terča.

Pre každú povolenú bodovú hodnotu sa eviduje počet zásahov. Subtotal za terč sa následne vypočíta ako súčet súčinov jednotlivých bodových hodnôt a počtu zásahov, ktoré boli pri danej hodnote zaznamenané.

Zdrojom pravdy pre výpočet výsledku na terči je rozpis zásahov. Samotný subtotal je odvodená hodnota, ktorá sa z tohto rozpisu vypočíta a môže byť uložená ako optimalizačný údaj pre ďalšie spracovanie a zobrazovanie.

Výpočet výsledku na terči musí rešpektovať bodovaciu schému a počet rán definované v kontexte konkrétnej súťaže. Nie je preto možné vyhodnocovať výsledok len podľa všeobecnej definície terča bez väzby na snapshot konfigurácie konkrétneho podujatia.

## 5.2 Kontrola konzistencie rozpisu zásahov

Rozpis zásahov na terči sa po uložení kontroluje voči konfigurácii konkrétneho terča v rámci konkrétnej súťaže.

Systém musí vedieť overiť, či rozpis obsahuje iba bodové hodnoty povolené bodovacou schémou daného terča a či súčet všetkých zaznamenaných zásahov zodpovedá počtu rán definovanému v snapshote konfigurácie súťaže. Zároveň musí byť možné overiť, či subtotal vypočítaný z rozpisu zásahov zodpovedá údajom uloženým pri výsledku na terči, ak sa tento subtotal ukladá aj ako optimalizačný údaj.

Táto kontrola však nemá sama osebe brániť uloženiu formulára. Jej úlohou je identifikovať nekonzistentné alebo nekompletné výsledky a označiť ich tak, aby sa k nim bolo možné neskôr vrátiť a opraviť ich.

Nekonzistentné údaje preto môžu byť v systéme dočasne uložené, nesmú však byť použité ako podklad pre finálne vyhodnotenie súťaže, výpočet oficiálneho poradia alebo ukončenie súťaže. Systém musí vedieť odlíšiť medzi uloženým výsledkom a validným výsledkom použiteľným pre oficiálne spracovanie.

Cieľom tejto kontroly je zachovať praktickú použiteľnosť pri zadávaní dát a zároveň chrániť doménovú konzistenciu pri vyhodnotení výsledkov.

## 5.3 Výpočet celkového výsledku súťažiaceho

Celkový výsledok súťažiaceho v súťaži vzniká ako súčet subtotalov zo všetkých terčov zaradených do konfigurácie konkrétnej súťaže.

Pri výpočte celkového výsledku sa musia použiť iba také výsledky na terčoch, ktoré sú dostatočne konzistentné na to, aby mohli vstúpiť do oficiálneho vyhodnotenia. Ak je niektorý z povinných výsledkov neúplný alebo nekonzistentný, súťažiaceho nie je možné považovať za kompletne vyhodnoteného pre finálne poradie.

Celkový výsledok je odvodená hodnota. Systém ho môže ukladať ako optimalizačný údaj pre rýchlejšie zobrazovanie a spracovanie výsledkov, no jeho význam musí vždy vychádzať z individuálnych výsledkov na jednotlivých terčoch.

Výpočet celkového výsledku musí byť viazaný na snapshot konfigurácie konkrétnej súťaže. Nie je teda možné predpokladať, že rovnaký názov formátu vždy znamená identické scoring podmienky, ak konkrétne podujatie používa vlastnú uloženú konfiguráciu.

## 5.4 Určenie poradia pri rovnosti bodov

Ak dvaja alebo viacerí súťažiaci dosiahnu rovnaký celkový výsledok, poradie sa neurčuje náhodne ani ručne, ale podľa vopred definovaného poradia terčov určeného pre danú súťaž.

Pravidlo pre rovnosť bodov je súčasťou konfigurácie súťaže a vychádza zo snapshotu terčov použitého pri danom podujatí. Pri porovnaní dvoch alebo viacerých súťažiacich sa preto postupuje podľa priority terčov definovanej v tejto konfigurácii.

Pri M400 je aktuálne poradie tie-breaku definované takto:
- diviak
- kamzík

- srnec
- líška

To znamená, že pri rovnosti celkového výsledku sa najprv porovná subtotal na terči s najvyššou prioritou. Ak ani ten nerozhodne, pokračuje sa ďalším terčom v poradí, až kým nevznikne jednoznačné poradie alebo sa nevyčerpajú všetky definované priority.

Ak ani po aplikovaní všetkých priorít nevznikne jednoznačné automatické poradie, výsledok zostáva nerozhodný na úrovni automatického výpočtu a musí byť dořešený ďalším mechanizmom, napríklad rozstrelom alebo rozhodnutím rozhodcov.

Takto definované pravidlo zabezpečuje, že aj pri rovnosti bodov je poradie deterministickým dôsledkom konfigurácie súťaže, nie neformálneho zásahu pri spracovaní výsledkov.

## 5.5 Výpočet poradia v súťaži

Poradie v súťaži sa určuje nad množinou výsledkov, ktoré sú spôsobilé vstúpiť do oficiálneho vyhodnotenia. Do výpočtu preto môžu vstúpiť iba tí súťažiaci, ktorí majú dostatočne kompletné a konzistentné výsledky podľa pravidiel konkrétnej súťaže.

Základným kritériom poradia je celkový výsledok súťažiaceho. Súťažiaci sa zoradia zostupne podľa celkového počtu bodov a pri rovnosti sa použije pravidlo priority terčov definované v konfigurácii súťaže.

Rovnaký mechanizmus výpočtu sa používa pri absolútnom poradí aj pri poradí v rámci kategórie. Rozdiel je len v množine súťažiacich, nad ktorou sa poradie počíta. Systém preto nemá mať oddelenú scoring logiku pre jednotlivé výsledkové pohľady, ale jeden výpočtový mechanizmus aplikovaný nad rôznymi filtrami.

Výsledkom výpočtu je poradie, ktoré môže byť následne uložené ako snapshot pre prezentačnú vrstvu. Tento snapshot môže reprezentovať priebežné aj finálne poradie a môže byť vytváraný samostatne pre celú súťaž, jednotlivé kategórie aj tímové výsledky.

## 5.6 Zásahy do finálneho poradia

Automaticky vypočítané poradie nemusí byť vždy zároveň finálnym oficiálnym poradím. Doménový model preto musí umožniť zachytiť aj situácie, keď do výsledného poradia vstupuje dodatočný rozhodovací mechanizmus nad rámec bežného scoring výpočtu.

Typicky ide o prípady, keď automatický výpočet nedokáže jednoznačne rozhodnúť poradie ani po aplikovaní všetkých pravidiel pre rovnosť bodov, alebo keď pravidlá súťaže vyžadujú rozstrel či rozhodnutie rozhodcov. V takom prípade sa zdrojové výsledky na jednotlivých terčoch nemenia, mení sa iba spôsob, akým sa určí alebo potvrdí finálne poradie.

Systém preto musí vedieť odlíšiť medzi automaticky vypočítaným poradím a finálnym poradím potvrdeným podľa pravidiel súťaže. Tieto dodatočné zásahy majú byť evidované tak, aby bolo zrejmé, na základe čoho bolo konečné poradie určené.

Takto navrhnutý model zachováva konzistenciu scoring dát a zároveň umožňuje korektne reprezentovať reálne rozhodnutia, ktoré vznikajú pri spracovaní výsledkov súťaže.

## 5.7 Tímové výsledky a tímové poradie

Tímové výsledky nevznikajú samostatným zadávaním dát, ale agregáciou individuálnych výsledkov členov tímu v rámci konkrétnej súťaže.

Zdrojom pravdy pre tímové vyhodnotenie zostávajú individuálne výsledky jednotlivých strelcov na terčoch. Tímový výsledok sa z nich odvádza podľa pravidiel platných pre daný tímový výsledkový pohľad.

Pri výpočte tímového výsledku systém sumarizuje výsledky všetkých členov tímu, ktorí do daného tímu patria v rámci konkrétneho podujatia. Z takto agregovaných dát sa následne určujú tímové subtotaly, celkový tímový výsledok a prípadné pomocné hodnoty potrebné pre určenie poradia.

Rovnako ako pri individuálnom poradí, aj tímové poradie má byť deterministickým dôsledkom vstupných dát a pravidiel súťaže. Ak to pravidlá vyžadujú, musia sa na tímové výsledky aplikovať aj pravidlá pre rovnosť bodov alebo ďalšie rozhodovacie mechanizmy.

Pre potreby prezentácie a publikovania výsledkov môže byť tímové poradie ukladané ako samostatný snapshot. Takýto snapshot predstavuje odvodený a optimalizovaný výstup nad individuálnymi dátami, nie samostatný zdroj pravdy.

## 5.8 Stav súťaže a podmienky ukončenia

Súťaž počas svojho životného cyklu prechádza jednotlivými stavmi, ktoré odrážajú mieru pripravenosti dát na oficiálne vyhodnotenie a publikovanie výsledkov.

Z pohľadu domény je dôležité rozlišovať medzi súťažou, v ktorej sa ešte zhromažďujú alebo opravujú vstupné údaje, a súťažou, ktorá už môže byť považovaná za oficiálne vyhodnotenú. Uloženie výsledkov preto samo osebe nesmie automaticky znamenať, že súťaž je pripravená na ukončenie.

Na zmenu súťaže do ukončeného alebo oficiálne publikovaného stavu musia byť splnené podmienky vyplývajúce z konzistencie výsledkov. Všetky výsledky, ktoré majú vstupovať do oficiálneho poradia, musia byť dostatočne kompletné a konzistentné. Zároveň musia byť uzavreté alebo vyriešené aj situácie, ktoré bránia určeniu finálneho poradia, napríklad nerozhodný automatický výsledok vyžadujúci rozstrel alebo rozhodnutie rozhodcov.

Systém preto musí vedieť odlíšiť medzi priebežným stavom súťaže, v ktorom je možné výsledky ďalej dopĺňať a opravovať, a stavom, v ktorom sú výsledky považované za oficiálne uzatvorené. Tento prechod má byť riadený pravidlami domény, nie len technickým faktom, že v databáze existujú nejaké uložené dáta.

Takto navrhnutý model chráni konzistenciu oficiálnych výsledkov a zároveň ponecháva dostatočnú flexibilitu pri reálnom zadávaní a opravovaní podkladov počas priebehu súťaže.

## 5.9 Prideľovanie štartových čísel

Štartové číslo je vlastnosť konkrétneho prihlásenia strelca do súťaže. Nejde o trvalý údaj strelca, ale o organizačný identifikátor používaný v rámci konkrétneho podujatia.

Pridelenie štartového čísla môže prebiehať ručne alebo automatizovane podľa pravidiel organizácie súťaže. Spôsob prideľovania nie je súčasťou scoring logiky, ale organizačnej logiky súťaže.

Pri automatickom prideľovaní musí systém umožniť zohľadniť obmedzenia vyplývajúce z priebehu súťaže, napríklad kapacitu jednej rundy alebo skutočnosť, že viacerí strelci zdieľajú tú istú zbraň a nemajú byť zaradení do konfliktnej pozície v rozlosovaní.

Takto navrhnutý model oddeľuje výsledkové pravidlá od organizačných procesov a zároveň ponecháva priestor na neskoršie doplnenie logiky losovania bez zásahu do scoring jadra.

## 6. Zhrnutie modelu

Doménový model projektu oddeľuje definíciu pravidiel súťaže od konkrétnych historických výsledkov. Typ súťaže určuje štruktúru formátu, zatiaľ čo konkrétna súťaž si uchováva vlastný snapshot konfigurácie použitý pri vyhodnotení podujatia.

Výsledky sa evidujú na úrovni jednotlivých strelcov a jednotlivých terčov. Zdrojom pravdy pre scoring je rozpis zásahov podľa bodových hodnôt, z ktorého sa odvádzajú subtotaly, celkový výsledok a poradie.

Poradie v súťaži je odvodené z výsledkov a pravidiel súťaže. Pre potreby prezentácie a publikovania môže byť ukladané aj ako snapshot, a to na úrovni absolútneho poradia, kategórií aj tímových výsledkov.

Model je navrhnutý tak, aby spoľahlivo pokryl M400 a zároveň umožnil bez zásadnej zmeny architektúry doplniť aj M800, G200 a G400.
