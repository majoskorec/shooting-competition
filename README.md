# Shooting Competition

Jednoduchý a spoľahlivý webový systém na správu streleckých súťaží, evidenciu výsledkov a publikovanie priebežného aj finálneho poradia.

Primárny scope MVP je **M400**, ale doménový model a architektúra sú navrhnuté tak, aby bolo možné bez zásadnej prestavby doplniť aj **M800**, **G200** a **G400**.

## Cieľ projektu

Cieľom projektu je vytvoriť systém, ktorý umožní:

- založiť súťaž podľa vybraného **typu súťaže**
- definovať alebo prevziať sadu terčov a disciplín
- evidovať strelcov a ich zaradenie do kategórií
- zadávať výsledky po jednotlivých terčoch
- automaticky vypočítať celkové skóre a poradie
- publikovať priebežné a finálne výsledky na webe

Projekt nie je postavený ako jednorazové riešenie pre jednu konkrétnu disciplínu. Jadrom je generický scoring model, v ktorom sa pravidlá súťaže konfigurujú dátami.

## MVP scope

Prvá verzia systému pokrýva najmä:

- **typ súťaže M400**
- definíciu terčov použitých v súťaži
- konfiguráciu počtu rán a bodovacej schémy terča
- konfiguráciu priority terčov pri rovnosti bodov
- evidenciu strelcov
- prihlasovanie strelcov do konkrétnej súťaže
- zadávanie výsledkov po jednotlivých terčoch
- automatický výpočet:
    - subtotalu za terč
    - celkového bodového súčtu
    - priebežného poradia
    - finálneho poradia
- verejný prehľad výsledkov

## Budúci rozsah

Architektúra má byť pripravená aj na:

- M800
- G200
- G400
- viac kategórií súťažiacich
- export výsledkov
- verejnú výsledkovú tabuľu optimalizovanú pre mobil aj veľkú obrazovku

## Základný doménový model

Projekt rozlišuje medzi definíciou pravidiel súťaže, konkrétnym podujatím a výsledkami.

### Definícia terča

**Definícia terča** je katalógový pojem, napríklad:

- diviak
- líška
- srnec
- kamzík

Obsahuje najmä:

- názov a interný kód
- typ disciplíny
- bodovaciu schému
- ďalšie metadata potrebné pre scoring

### Typ súťaže

**Typ súťaže** reprezentuje reusable formát ako napríklad **M400**, **M800**, **G200** alebo **G400**.

Určuje:

- ktoré terče patria do súťaže
- v akom poradí sa zobrazujú
- koľko rán sa na ne strieľa
- ktoré terče rozhodujú pri rovnosti bodov a v akom poradí

### Súťaž

**Súťaž** je konkrétne podujatie vytvorené z vybraného typu súťaže.

Obsahuje napríklad:

- názov
- dátum
- miesto
- organizátora
- stav súťaže
- zoznam prihlásených strelcov
- výsledky

Pri vytvorení súťaže sa konfigurácia typu súťaže prenáša do vlastného **snapshotu**, aby neskoršie zmeny typu súťaže nemenili historické výsledky.

### Strelec a prihlásenie do súťaže

- **Strelec** reprezentuje osobu.
- **Prihlásenie do súťaže** reprezentuje účasť konkrétneho strelca v konkrétnej súťaži.

Toto oddelenie je dôležité, pretože ten istý strelec sa môže zúčastniť viacerých súťaží a v každej z nich môže mať inú kategóriu, štartové číslo alebo stav účasti.

### Výsledok na terči

**Výsledok na terči** patrí jednému prihláseniu do súťaže a jednému terču v rámci konkrétnej súťaže.

Nevzniká iba zadaním jedného subtotalu. Zdrojom pravdy je **rozpis zásahov** podľa bodových hodnôt. Z neho sa následne dopočíta subtotal za terč a potom aj celkový výsledok súťažiaceho.

### Poradie a ranking snapshot

Poradie je **odvodené** z výsledkov na jednotlivých terčoch a z pravidiel súťaže. Nie je to primárny vstup.

Pre prezentačné účely je možné ukladať aj **ranking snapshot**, ale ten musí byť vždy reprodukovateľný zo zdrojových dát.

## Výpočet poradia

Poradie sa určuje podľa týchto princípov:

1. Spočíta sa celkové skóre ako súčet výsledkov zo všetkých povinných terčov.
2. Súťažiaci sa zoradia podľa celkového skóre zostupne.
3. Pri rovnosti bodov rozhodujú terče podľa vopred definovanej priority tie-breaku.
4. Ak ani po porovnaní priorít nevznikne jednoznačné poradie, výsledok zostáva nerozhodný alebo sa rieši podľa pravidiel konkrétnej súťaže.

Pre M400 je aktuálne poradie tie-breaku:

1. diviak
2. kamzík
3. srnec
4. líška

## Zdroj pravdy vs. odvodené dáta

Zdrojom pravdy pre scoring sú:

- snapshot konfigurácie konkrétnej súťaže
- uložené výsledky na terčoch
- rozpis zásahov pre jednotlivé výsledky na terči

Odvodené dáta sú najmä:

- subtotal za terč
- celkové skóre súťažiaceho
- poradie
- ranking snapshot
- publikované výsledkové pohľady

Odvodené dáta je možné ukladať kvôli výkonu alebo prezentácii, ale musia byť vždy spätne dopočítateľné zo zdrojových dát.

## Kľúčové entity

V aktuálnom názvosloví projektu sa pracuje najmä s týmito pojmami:

- **TargetDefinition** — definícia terča
- **CompetitionType** — typ súťaže
- **Competition** — konkrétna súťaž
- **Shooter** — strelec
- **CompetitionEntry** — prihlásenie strelca do súťaže
- **TargetResult** — výsledok strelca na konkrétnom terči
- **RankingSnapshot** — materializovaný snapshot poradia

Poznámka: v starších návrhoch sa objavovali názvy ako `CompetitionTemplate`, `CompetitionTemplateTarget`, `CompetitionTarget` alebo `Result`. Aktuálne preferované názvoslovie je vyššie uvedené a malo by sa používať konzistentne v kóde aj dokumentácii.

## Používateľské roly

### Organizátor / admin

- zakladá súťaže
- spravuje typy súťaží a definície terčov
- eviduje strelcov a ich prihlásenia
- zadáva alebo opravuje výsledky
- kontroluje konzistenciu dát
- uzatvára súťaž
- publikuje výsledky

### Verejný návštevník

- vidí priebežné alebo finálne výsledky
- môže filtrovať podľa súťaže alebo kategórie
- môže si pozrieť detail strelca a jeho výsledky na jednotlivých terčoch

## Implementačné zásady

Pri implementácii sa majú dodržať tieto zásady:

- scoring logika musí byť oddelená od UI
- poradie sa má počítať deterministicky na základe dát a pravidiel
- typy súťaží majú byť konfigurovateľné
- konkrétna súťaž si má ukladať vlastný snapshot konfigurácie
- zmeny typov súťaží nesmú spätne meniť historické výsledky
- riešenie má byť pripravené na rozšírenie bez refaktoru celej domény
- systém má umožniť uložiť aj nekompletné alebo nekonzistentné vstupy, ale nesmie ich pustiť do oficiálneho vyhodnotenia

## Mimo scope MVP

MVP zatiaľ nezahŕňa:

- brokové disciplíny
- online registráciu súťažiacich
- platby
- automatický export diplomov
- live WebSocket realtime infraštruktúru
- pokročilé štatistiky
- komplexný shoot-off workflow

## Projektové dokumenty

Odporúčané poradie čítania pre človeka aj AI agenta:

1. `README.md`
2. `docs/domain-model.md`
3. `docs/implementation-plan.md`
4. `docs/architecture.md`
5. `AGENTS.md`

## Status

Projekt je aktuálne v štádiu návrhu architektúry, doménového modelu a implementačného plánu pre prvú verziu MVP.
