Kmom04: Databasdrivna modeller
------------------------------------
#### Vad tycker du om formulärhantering som visas i kursmomentet?
Den gillar jag. Det är steget vidare på den funktion jag själv skapade i Oophp vilket jag fann både roligt och intressant.

#### Vad tycker du om databashanteringen som visas, föredrar du kanske traditionell SQL?
Jag har ingen favorit. Båda delarna har sin charm. Databashanteringen i allmänhet var mycket väl utförd men den var seg att sätta sig in i då det var en hel del nytt i funktionerna för modellen och hur de fungerar.

#### Gjorde du några vägval, eller extra saker, när du utvecklade basklassen för modeller?
Basklassen utvecklade byggde jag inte vidare på. Däremot använde jag mig av special implementationer i subklasserna när saker blev annorlunda och specifika till just den subklassen.
Ett val jag gjorde var att installera CForm och CDatabase med hjälp av Composer.

#### Beskriv vilka vägval du gjorde och hur du valde att implementera kommentarer i databasen.
Jag valde att använda mig av”CDatabaseModel som en bas för mina kommentarsmodeller utan att göra några förändringar förutom lite extra bugg tester. Modellernas namn blev namnet på den tabell som användes i databasen på grund av det. Därmed kunde jag skapa en ny modell för varje kommentarsfält och på så sätt förminska den kod som behövde skrivas. Detta ledde även till att jag gjorde om CommentController så att den enkelt kan hantera flera olika modeller med hjälp av en fabriksmetod. Slutligen planerade jag även att lägga fabriksmetoden i en basklass för Controller-klasser men skippade detta på grund av tiden.

#### Gjorde du extrauppgiften? Beskriv i så fall hur du tänkte och vilket resultat du fick.
Jag beslöt mig för att inte göra extrauppgiften. Den sparar jag till ett senare tillfälle när jag vill utveckla mig på egen tid.

#### Svårigheter/problem
Det svåra var att hinna med tiden. Det dök till en början upp många buggar och kampen för att hinna med drog ut långt över den tänkta studietiden för detta kursmoment. En av dessa buggar var när Composer autoloadern inte laddade de filer jag skapade.

Momentet var svårt eftersom det krävde att man hade ordentlig koll på ramverket man arbetade med. När jag inte hade det började jag hitta på ”halvlösningar”, funktioner och kodrader som enkelt kunnat ersättas med vad som redan fanns i ramverket.

Det svåraste var när jag skulle skapa två olika kommentarstabeller men till slut fann jag den lösning som jag nämnde tidigare.

#### Lösningar
Det blev ibland en hel del repeterande kod. Detta var något jag löste genom att skapa nya funktioner. Till exempel initialize() och redirects(). Den jag känner blev mest lyckad kallas getFormHTML(). Den använder sig av CForm och returnerar en form specifik för den Controller som använder den.

Jag har nu till fullo förstått vikten av att dokumentera koden man arbetar med i ett UML-diagram. Diagrammet hjälpte mig att lösa uppgiften och gav mig en helhetsbild över ramverket vilket ledde till att jag inte behövde gå tillbaka och leta i en massa filer för att hitta rätt.

#### Resultat
Resultatet känner jag mig riktigt nöjd med. Den struktur jag valde kommer att växa horisontellt i ett UML-diagram, detta känns bra för min del. Momentet utmanade med andra ord min förmåga att planera innan jag kodar, och som följd av detta har jag börjat tänka långt i förväg och satsa allt mer på planeringen än tidigare. När jag gör det har även observerat att arbetstiden reduceras enormt. Denna nya läxa i planering blir det viktigaste verktyget jag tar med mig ifrån kmom04. Att ha läst kurslitteraturen kändes extra nödvändigt i detta kursmoment och jag fick en kraftig upplevelse av att bitarna föll på plats när jag utförde det. Allt som tagits upp i litteraturen fick jag användning av.

Med problemen i åtanke var momentet ett av de bästa och mest utmanade som jag gått igenom. Det utmanade inte bara mig utan min programmeringsförmåga.