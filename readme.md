RestApiPackage
===============

Balíček pro vytváření JSON REST API v aplikacích založených na *Skeleton21*.

Balíček slouží k verzování a distribuci části funkčnosti webové aplikace včetně presenterů, šablon, rout, migrací atp.

Od běžného composerového balíčku se liší v tom, že jednotlivé části jsou při instalaci rozkopírovány na příslušná místa
skeletonové aplikace.

####obsahuje:
- rozšíření uživatele o autentizaci klíčem API (entita `User`)
- logování API požadavků do databáze (entita `ApiRequest`)
- podporu pro API: `ApiPresenter`, `ApiResponse`, `RestRoute`, validaci JSON schémat (balíček *RestApiBase*)

####todo:
- presenter pro debugování požadavků na API (přenést z Lamaicy a dodělat)


Instalace balíčku:
------------------
Balíčky se instalují *Composerem* pomocí *SkeletonPackageInstalleru*. Ten se stará o zkopírování všech souborů na správná místa.

Balíček přidejte do závislostí v souboru `composer.json` v projektu (do sekce require).

Do sekce repositories přidejte další dvě adresy: tento repozitář a RestApiBase.

Spusťte `composer update --prefer-dist`.

V projektu balíček aktivujete zavoláním registrátoru v metodě `onInitModules()` v `Configuratoru`:

	public function onInitModules()
	{
		MyPackageNamespace\Package::register($this);
	}


Struktura balíčku:
------------------

	- app 					(stejné jako je běžná struktura skeletonní aplikace)
		- components
		- presenters
		- templates
		- *SomeModule*		(modul)
			- components
        	- presenters
        	- templates
	- migrations
		- data   			(obsahuje testovací data pro integrační a seleniové testy)
		- struct 			(obsahuje struktury a *provozní data*)
	- tests
		- cases
			- Selenium
			- Unit
	- www					(assets)


Rozšiřitelnost:
---------------
- Routy:
	- routy balíčku se registrují až po routách projektu
	- to zajistí, že je lze v projektu přetížit
	- (canonicalizace přetížených rout zatím není vyřešena)
- Presentery:
	- `PresenterFactory` hledá třídu presenteru nejdríve ve jmenném prostoru aplikace, až poté ve jmenném prostoru
		balíčku (ten se v `PresenterFactory` musí registrovat)
	- presentery v balíčku musí dědit od třídy `Clevis\Skeleton\BasePresenter`
	- presentery by neměly rozšiřovat presenter z jiného balíčku (raději použijte kompozici nebo traity)
- Šablony: `TemplateFactory` hledá šablony nejdříve v adresáři aplikace, pak teprve v adresáři balíčku
	- aplikační šablona tedy může rozšiřovat šablonu z balíčku, aniž by bylo třeba cokoliv nastavovat
	- pro zjednodušení jsou šablony z balíčku zkopírovány do adresáře `/app[/Module]/templates/{Presenter}/package/`
- Služby a komponenty:
	- služby se definují v konfiguračním souboru `config.neon`
	- konfiguráky balíčku se načítají před konfiguráky aplikace, proto může aplikace výchozí nastavení upravit
- Helpery:
	- (zatím není nijak vyřešeno)


Testy:
------
- Testy v balíčku by měly dědit od `Tests\Unit\TestCase` nebo `Test\Selenium\SeleniumTestCase` (nejspíše se změní)
- Prozatím je počítáno pouze se spouštěním testů z prostředí *Skeletonu*


Migrace:
--------
- **Obecné pravidlo migrací je, že migrace se nesmí nikdy editovat. Každá úprava databáze musí být v novém souboru.**
- Migrace z balíčku je ale možné editovat právě jednou po připojení balíčku k projektu, aby se zabránilo případným kolizím
	s migracemi z jiných balíčků či z projektu. Instalátor balíčku totiž při jeho
	aktualizaci **již jednou nainstalované migrace nepřepisuje**
- **Soubor migrace nelze z aplikace odstranit** - instalátor by ho totiž při updatu opětovně přidal. Pokud je třeba
	migraci vyřadit, je možné ji přepsat prázdným příkazem (např. `SELECT 1;`)
- **Při odinstalování balíčku zůstávají jeho migrace na místě.** Migrace jsou nevratné a odebrat je nelze. Pokud je to nutné,
	je třeba stav databáze změnit reverzní migrací
- **Balíček nesmí měnit nebo odebírat sloupce/tabulky/indexy atd., které sám nepřidal**
