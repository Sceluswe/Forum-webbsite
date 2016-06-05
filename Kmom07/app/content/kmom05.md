Kmom05: Bygg ut ramverket
------------------------------------
#### Var hittade du inspiration till ditt val av modul och var hittade du kodbasen som du använde?
Inspirationen fick jag direkt ifrån Mos. Jag har alltid varit intresserad av säkerhet  och säkerhetslösningar, därför var det naturligt för mig att välja att göra en egen Escaper-klass. 
Min inspiration kom direkt ifrån Phalcon och själva RFCn när jag valde mina funktionsnamn. Klassnamnet blev CEscaper så att det matchar övriga klasser I Anax. 

#### Hur gick det att utveckla modulen och integrera i ditt ramverk?
Det var en utmaning att utveckla modulen. I början behövde jag läsa en hel del och resonera mig fram till lösningarna. Men när jag väl kom in I tänket och lärde mig använda de kod exempel som fanns länkade på XSS Cheat Sheet hemsidan så flöt allting på. Att integrera var värre men jag studerade Mos lösningar och projekt och det gjorde allting mycket enklare.

#### Hur gick det att publicera paketet på Packagist?
Publiceringen fungerade utmärkt och jag hade stor hjälp av kurslitteraturen när jag använde mig av Git_Shell. Problemet uppstod när jag skulle få Composer att ta ned paketet och sedan integrera det I Anax. Min lösning blev att använda Mos .json filer som mall och även strukturen I Mos egna GitHub projekt. 

Jag upptäckte även att om man inte taggar sitt projekt på GitHub så hittar inte Composer en stabil version att ladda ned. Kravet på att lägga till en tag står som Krav nummer 6, det känns som att den skulle passa bättre som krav nummer 2 för att alla krav ska hamna i “rätt” ordning. 

#### Hur gick det att skriva dokumentationen och testa att modulen fungerade tillsammans med Anax MVC?
Det gick alldeles utmärkt! Dokumentationen gick fort att skriva ned och jag skapade även en frontcontroller samt en controller för att kunna visa upp den på hemsidan. Efter att jag löst problemet med att autoloadern inte ville hitta mitt paket i vendor så var testandet inget problem. Klassen fungerade felfritt.

#### Gjorde du extrauppgiften? Beskriv i så fall hur du tänkte och vilket resultat du fick.
Jag hoppade extrauppgiften på grund av tidsschemat. 

#### Svårigheter/Problem
Jag hade svårt för att komma igång eftersom jag inte riktigt förstod hur allting skulle fungera; det kändes som en svår uppgift. Men efter att ha grävt mig in i de texter och länkar som Mos erbjöd började det släppa och jag kunde snart planera och implementera min lösning.

Ett annat problem jag hade var läsningen. Det var många sidor och artiklar att ta sig igenom, men i slutändan tjänade jag på det. Lärdomarna som jag kunde hämta ifrån Kapitel 17 hjälpte mig att komma igång direkt med Git och Packagist.

#### Lösningar
Som tidigare nämnt hade jag problem med att integrera mitt projekt. Först och främst öppnade jag notepad och skrev ned tänkbara lösningar där. Därefter plockade jag den mest trovärdiga och det visade sig att jag valde rätt. Lösningen blev att studera Mos paket tills jag förstod vad det var som saknades för att mitt paket skulle fungera ihop med Anax autoloader.

Jag tog beslutet att lägga in CEscaper som en tjänst i CDIFactoryDefault.php eftersom jag ville att den skulle finnas tillgänglig överallt när man arbetar. Min motivering var att CEscaper kan vara bra att ha när som helst. Genom att lägga in den på detta sätt behövde jag inte heller binda den till just Anax för att den ska fungera. Inte heller behövde jag krångla med att kopiera filer. CEscaper har inte någon dependency vilket gör den ännu smidigare.

#### Resultat
Jag är nöjd över resultatet. CEscaper blev fullt fungerande och det blev enkelt att lägga in den som en användbar tjänst i Anax. Jag är dessutom glad över att jag avslutade projektet lång i förväg och kunde spendera de 3 sista dagarna på att läsa mera samt skriva min redovisning. Kursens bok börjar tilltala mig allt mer och mer. 

Min största lärdom ifrån detta är att läsning kan hjälpa en långt på traven, men att studera någon som redan kan gör allt ännu enklare. Det viktigaste är att man själv förstår sig på problemet och löser det på sitt eget vis.

Momentet i sig tycket jag var mycket bra. Det var en hel del tänka själv och i det här stadiet känner jag att det är precis vad som behövdes för att man själv skulle känna att man kommit till nästa steg inom programmering.
