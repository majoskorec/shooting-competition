# Tech Stack

## Účel dokumentu

Tento dokument definuje odporúčaný technický stack pre MVP projektu **Shooting Competition**.

Jeho cieľom je odstrániť zbytočné otvorené rozhodnutia pri implementácii, aby vývojár aj AI agent vedeli, v akom technologickom rámci sa má systém stavať. Tento dokument nadväzuje na `README.md`, `AGENTS.md`, `docs/domain-model.md`, `docs/implementation-plan.md` a `docs/architecture.md`.

Technologické rozhodnutia v tomto dokumente musia podporovať tieto princípy:

- jednoduchá a čitateľná implementácia MVP pre `M400`
- oddelenie scoring logiky od UI a prezentačnej vrstvy
- hybridná perzistencia: relačné entity + štruktúrované JSON snapshoty
- dobrá testovateľnosť scoring jadra
- pripravenosť na neskoršie rozšírenie bez redizajnu základov

---

## Hlavné technologické rozhodnutie

Pre MVP sa odporúča tento stack:

- **Backend framework:** Symfony 8.0
- **Jazyk:** PHP 8.5
- **Databáza:** MySQL 8.0
- **Frontend pre admin aj public vrstvu:** Symfony UX balíčky
- **Styling:** Bootstrap 5.3
- **Build nástroje:** AssetMapper alebo Vite podľa potreby konkrétnych UX balíkov, s preferenciou jednoduchšieho setupu
- **Autentifikácia:** Symfony Security
- **Autorizácia / roly:** Symfony Security Voters + jednoduchá role vrstva v aplikácii
- **Testovanie:** PHPUnit + Pest
- **Formátovanie a kvalita kódu:** PHP CS Fixer + PHPStan
- **Cache:** Symfony Cache, file cache alebo MySQL-backed cache podľa potreby MVP
- **Deployment runtime:** Nginx + PHP-FPM
- **Kontajnerizácia pre lokálny vývoj:** Docker Compose

Tento stack je preferovaný preto, že:

- dobre sedí na PHP projekt a Symfony ekosystém
- umožňuje rýchlu implementáciu admin workflow bez zbytočne ťažkého SPA frontendu
- podporuje čisté oddelenie aplikačných služieb od UI
- znižuje množstvo infra rozhodnutí potrebných v MVP
- umožňuje neskôr doplniť API alebo samostatný frontend bez rozbitia jadra

---

## Prečo práve tento stack

Architektúra projektu vyžaduje jasné oddelenie domény, aplikačnej logiky, perzistencie a prezentácie. Zároveň má zostať MVP jednoduché a nemá predčasne zavádzať komplexné technológie len kvôli hypotetickým budúcim scenárom.

Implementation plan zároveň ráta s hybridnou perzistenciou, oddelenými aplikačnými službami a postupným budovaním jadra od konfigurácie cez výsledky až po ranking snapshoty. Preto je vhodný framework, ktorý dobre podporuje relačné entity, JSON polia, service layer a rýchlu tvorbu admin workflow bez toho, aby tlačil projekt do zložitého frontendového alebo distribuovaného modelu.

---

## Backend

### Symfony 8.0

Symfony je pre tento projekt odporúčaný ako hlavný backend framework.

Dôvody:

- poskytuje stabilný základ pre modulárnu MVC aplikáciu
- veľmi dobre podporuje service-oriented architektúru
- umožňuje čitateľne organizovať aplikačné služby, command handlery, eventy a security vrstvu
- nevyžaduje skoré rozhodnutie pre samostatné API-first riešenie
- dobre sa kombinuje so Symfony UX balíkmi pre interné admin rozhranie

Symfony sa v tomto projekte nemá používať ako miesto, kde sa „rozleje" biznis logika do controllerov alebo Doctrine entít. Doménové a scoring rozhodnutia majú zostať v samostatných službách a calculation classes.

### PHP 8.5

Projekt má byť implementovaný na PHP 8.5.

Dôvody:

- moderný jazykový základ pre typed codebase
- lepšia čitateľnosť a robustnosť doménových DTO a value objektov
- vhodné pre dlhodobo udržiavateľný Symfony projekt

Pri implementácii sa majú používať:

- typed properties
- enumy tam, kde zvyšujú čitateľnosť stavov
- readonly objekty alebo immutable DTO tam, kde dávajú zmysel
- explicitné návratové typy

---

## Databáza

### MySQL 8.0

Ako primárna databáza sa odporúča MySQL 8.0.

Dôvody:

- stabilný a rozšírený relačný základ pre PHP aplikácie
- dostatočná podpora JSON polí pre hybridný persistence model
- vhodná kombinácia pre MVP, kde hlavné entity zostávajú relačné a snapshoty alebo hit breakdown ostávajú v štruktúrovanom JSON
- jednoduchý deployment a široká dostupnosť hostingu aj tooling-u
- dobrá podpora indexov, foreign keys a transakčného správania

### Perzistenčný štýl

V MVP sa má používať:

**Relačný model** pre:

- `target_definitions`
- `competition_types`
- `competitions`
- `shooters`
- `competitors`
- `target_results`
- podľa potreby `ranking_snapshots`

**JSON polia** pre:

- scoring schema na definícii terča
- competition target configuration snapshot na súťaži
- hit breakdown na `TargetResult`
- prípadné publikované ranking payloady

Zásada je jednoduchá: zdroj pravdy zostáva v uložených výsledkoch a snapshot konfigurácii súťaže, odvodené údaje môžu byť uložené len ako reprodukovateľné výstupy.

---

## Frontend

### Admin rozhranie: Symfony UX balíčky

Pre MVP sa odporúča administrátorské rozhranie postaviť na Symfony UX balíčkoch.

Preferovaný základ:

- Twig pre layouty a server-side rendering
- Symfony UX Turbo pre rýchle a responzívne workflow interakcie
- Symfony UX Stimulus pre menšie interaktívne správanie na fronte
- podľa potreby doplnkové UX balíčky tam, kde prinášajú jasnú hodnotu

Dôvody:

- zodpovedá potrebe rýchlo dodať praktický admin systém
- minimalizuje overhead oproti plnému SPA
- drží frontend tenký a blízko backend use-case vrstve
- dobre sa hodí pre formuláre, tabuľky, filtre a workflow obrazovky
- znižuje počet technológií, ktoré musí AI alebo vývojár koordinovať

Admin vrstva má vedieť pracovať s rozpracovanými a nekonzistentnými dátami, ale nesmie obsahovať scoring logiku. Tento stack je na to vhodný, lebo interaktivitu rieši bez toho, aby vynucoval presun doménových rozhodnutí do JavaScriptu.

### Verejná vrstva: Twig + publikované snapshoty

Aj verejná prezentačná vrstva má byť v MVP renderovaná primárne cez Twig.

Dôvody:

- verejná vrstva má byť čo najtenšia
- má zobrazovať publikované výsledky, nie robiť scoring
- SSR je dostatočný pre výsledkové tabuľky a detail súťaže
- zjednodušuje nasadenie aj údržbu

Neskôr môže byť doplnené samostatné API alebo modernejší frontend, ale MVP ho nepotrebuje.

### Styling: Bootstrap 5.3

Bootstrap 5.3 sa odporúča pre admin aj public UI.

Dôvody:

- rýchla implementácia konzistentného rozhrania
- vhodný základ pre formuláre, tabuľky, modaly a administračné obrazovky
- znižuje potrebu budovať vlastný design system v MVP
- dobre sedí na Symfony + Twig + UX stack

### Build a asset pipeline

Na asset pipeline sa má preferovať čo najjednoduchší setup.

Odporúčaný prístup:

- primárne použiť **AssetMapper**, ak pokrýva potreby zvolených UX balíkov
- pre prípady, kde je potrebný komplikovanejší JS build alebo third-party frontend tooling, použiť **Vite**

Cieľom nie je maximalizovať frontend tooling, ale minimalizovať zložitosť pri zachovaní rozumného DX.

---

## Autentifikácia a autorizácia

### Autentifikácia: Symfony Security

Pre MVP sa odporúča Symfony Security.

Dôvody:

- natívne riešenie v Symfony ekosystéme
- dostatočne flexibilné pre interný admin systém
- nepreťažuje projekt zbytočnými features

### Autorizácia: Voters + jednoduché roly

MVP nepotrebuje plnohodnotný enterprise RBAC framework.

Odporúčaný prístup:

- použiť Symfony Voters pre ochranu operácií
- mať jednoduchú role vrstvu na používateľovi, napríklad:
  - `ROLE_ADMIN`
  - `ROLE_ORGANIZER`
  - neskôr prípadne `ROLE_VIEWER`

Ak sa neskôr ukáže potreba detailnejších práv, môže sa doplniť robustnejší permission model. Pre prvé MVP to nie je povinné.

---

## Aplikačná architektúra v kóde

Odporúčaná štruktúra aplikácie:

```text
assets/
  controllers/
  styles/
src/
  Command/
  Controller/
    Admin/
    Public/
  Doctrine/
    Mapping/
  Entity/
  Presentation/
  Ranking/
  Repository/
  Form/
    Dto/
    Type/
  Twig/
    Components/
```

### Kde má byť scoring logika

Scoring pravidlá majú žiť v samostatných triedach a službách, napríklad:

- `TargetResultConsistencyChecker`
- `TargetSubtotalCalculator`
- `CompetitionTotalCalculator`
- `TieBreakResolver`
- `RankingGenerator`
- `RankingSnapshotPublisher`

### Kde scoring logika nemá byť

Scoring logika nesmie byť v:

- controlleroch
- Twig templatoch
- UX controlleroch na fronte
- Doctrine repository query hackoch
- JavaScripte na fronte

---

## Cache

Cache má byť v MVP skôr pomocná než centrálny prvok architektúry.

Použitie:

- cache publikovaných prehľadov
- cache read-heavy verejných ranking view
- nie ako náhrada source-of-truth modelu

Pre MVP je postačujúca file cache alebo jednoduchý cache backend bez povinnej Redis infraštruktúry.

---

## Testovanie

### Testovací stack

Odporúčaný stack:

- **PHPUnit** ako základ
- **Pest** pre čitateľné testy biznis správania

### Testovacie priority

Najvyššiu prioritu majú testy pre:

- kontrolu konzistencie `hit breakdown`
- výpočet subtotalu na terči
- výpočet total score súťažiaceho
- tie-break pravidlá
- ranking generation
- vylúčenie nekonzistentných výsledkov z oficiálneho poradia
- closure conditions súťaže
- team aggregation

### Typy testov

V projekte majú byť minimálne tieto vrstvy testov:

- **Unit testy** pre scoring a ranking služby
- **Feature testy** pre admin workflow a základné HTTP use-casy
- **Database testy** pre migrácie, constraints a snapshot persistenciu

Nepreferuje sa ťažké E2E testovanie v prvej fáze, pokiaľ neprináša priamu hodnotu.

---

## Kvalita kódu

### Povinné nástroje

- **PHP CS Fixer** pre formátovanie
- **PHPStan** minimálne na rozumnej strictness úrovni
- **EditorConfig**
- **Rector** môže byť doplnkový nástroj, ale nie je povinný pre prvé MVP

### Praktické pravidlá

- explicitné typy
- malé služby s úzkou zodpovednosťou
- žiadne god services
- repository triedy bez scoring rozhodnutí
- DTO alebo command input objekty pre komplexnejšie use-casy
- enumy pre stavové hodnoty, kde to zvyšuje čitateľnosť

---

## Lokálny vývoj

### Odporúčaný setup

Pre lokálny vývoj sa odporúča jednoduchý **Docker Compose** setup.

Minimálne služby:

- symfony server
- mysql
- voliteľne mailpit

Redis nie je povinný pre prvé MVP.

### `.env` smerovanie

Projekt má mať pripravené prostredia aspoň pre:

- local
- testing
- production

---

## Produkčné nasadenie

### Odporúčaný runtime

- **Apache**
- **PHP-FPM**
- **MySQL 8.0**
- cron pre scheduler, ak bude použitý

### Deployment prístup

Pre MVP je vhodné držať deployment čo najjednoduchší:

- jedna Symfony aplikácia
- jedna databáza
- bez mikroservisov
- bez samostatného frontend deploy pipeline

Tento projekt zatiaľ nepotrebuje distribuovanú architektúru. Logické moduly majú byť oddelené v kóde, nie nutne v samostatných deployable jednotkách.

---

## Čo zámerne nie je súčasťou MVP stacku

Tieto technológie sa v MVP neodporúčajú zavádzať bez explicitného dôvodu:

- React / Vue SPA ako hlavný frontend
- samostatné public API ako povinný základ
- mikroservisy
- event-driven distribuovaná architektúra
- Redis-only infra ako povinnosť
- Elasticsearch / OpenSearch
- websocket realtime infra
- komplexný RBAC balík
- CQRS / event sourcing

Dôvod je jednoduchý: MVP má dodať funkčné scoring jadro a praktický admin workflow, nie riešiť infra zložitosť, ktorú ešte systém nepotvrdil ako potrebnú.

---

## Budúce rozšírenia, s ktorými stack počíta

Tento stack má zostať kompatibilný s neskorším rozšírením o:

- ďalšie `CompetitionType` konfigurácie ako `M800`, `G200`, `G400`
- detailnejšie team rules
- exporty a reporty
- public JSON API
- oddelený frontend pre verejnú prezentáciu
- robustnejší permission model
- výkonnejší queue/cache backend
- background jobs pre náročnejšie prepočty

Dôležité je, že tieto rozšírenia nemajú vyžadovať zmenu základného technologického smeru, len doplnenie ďalších vrstiev alebo modulov.

---

## Finálne rozhodnutie pre MVP

Ak nie je explicitne určené inak, projekt sa má implementovať v tomto stacku:

- Symfony 8.0
- PHP 8.5
- MySQL 8.0
- Twig
- Symfony UX Turbo
- Symfony UX Stimulus
- Bootstrap 5.3
- AssetMapper alebo Vite podľa potreby
- Symfony Security
- Symfony Voters + jednoduché roly
- PHPUnit + Pest
- PHP CS Fixer + PHPStan
- Nginx + PHP-FPM
- Docker Compose pre local dev

Toto je defaultný technologický základ pre celý projekt a AI agent ho nemá svojvoľne meniť bez explicitného zadania.
