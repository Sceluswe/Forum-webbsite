Kmom03: Bygg ett eget tema
------------------------------------
#### Vad tycker du om CSS-ramverk i allmänhet och vilka tidigare erfarenheter har du av dem?
Jag har inga tidigare erfarenheter av CSS ramverk annat än att jag läst om dem i tidigare kurser. Det har varit intressant läsning och även om jag tror att CSS i sig har rum för många förbättringar så gör ramverken det bästa av situationen och erbjuder struktur samt användarvänlighet (av de jag stött på). De är effektiva och utmärkta att använda.

#### Vad tycker du om LESS, lessphp och Semantic.gs?
Jag har länge undrat hur jag kan göra en webbsida helt responsive och nu har jag upptäckt flera olika platser där jag kan studera detta och lära mig mera. Semantic.gs var en bra början och det är ett ramverk jag gillar skarpt just för att det är semantiskt.

När jag gick den första kursen letade jag efter ett sätt att skriva bättre CSS kod och kom då på att variabler vore en riktigt bra grej. Däremot hittade jag inte LESS vid den här tiden men har alltid haft i baktanken att det borde finnas ett bättre sätt att skriva CSS kod. Nu har jag hittat ett. LESS är genialt.

Lessphp var en intressant lösning som jag gärna skulle studera närmare och även (i lärosyfte) använda för att skriva min egen kompilator.

#### Vad tycker du om gridbaserad layout, vertikalt och horisontellt?
Jag har alltid tyckt att grids är väldigt överflödiga men efter att ha använt dem kan jag inte argumentera emot att de har en användning. De ger en mer strukturerad grund att utgå ifrån. Jag kommer använda mig av det i fortsättningen tills jag kan hitta ett enklare sätt att göra det på. Grids i allmänhet anser jag är tradiga att arbeta med.

#### Har du några kommentarer om Font Awesome, Bootstrap, Normalize?
Font Awesome är nog det mest användbara jag stött på. Det tillåter vem som helst att använda snygga ikoner och det kan jag inte annat än uppskatta.

Normalize är en snygg lösning till ett problem jag tidigare försökt lösa, nämligen att bli av med de förutbestämda värdena. Det var skönt att få tag i en grund som tillåter en att börja ifrån noll utan att behöva ändra saker överallt i sin kod. Det tar helt enkelt bort en del av röran som blir av förutbestämda värden.

Bootstrap är ett bra ramverk och det är troligen därför det blivit så populärt. Det är inte helt olikt det som jag själv gjorde i momentet även om det är mer omfattande. Det känns bra att vara på en ny nivå i sitt kodande.

#### Beskriv ditt tema, hur tänkte du när du gjorde det, gjorde du några utsvävningar?
Jag har gjort ett tema som är responsivt och enkelt att styla. Några utsvävningar blev det inte i just det konceptet. Däremot använde jag mig endast av 12 regioner. Jag tog bort regionen ”sidebar” eftersom jag ansåg att den bröt av innehållet i ”main” för tvärt. Annat än det ser mitt ut på ett liknande sätt. På webbsidan använder jag en font ifrån Google men den är inte med i temat jag laddade upp på GitHub.

En princip jag har är att introducera sidan på 5 sekunder till en läsare, då det enligt forskning tar ungefär så lång tid innan de flesta bestämt sig för om de vill kolla vidare eller inte. På grund av detta har mitt tema en stor bild i ”headern” med tydlig text som introducerar sidan.

Min tanke angående stilen i sig var att den skulle ha kalla färger och ett mörkt utseende, med knappar inspirerade av Facebook (fast i mörkare färg).

#### Antog du utmaningen som extra uppgift?
Jag antog utmaningen och bröt ur mitt tema ur ramverket. Det var inga problem. Jag gjorde även en testfil för temat utifall att någon är intresserad av att ladda ned det ifrån GitHub och snabbt komma igång. Länk finns i slutet. Slutligen skapade jag en komplett readme-fil med licenser och lånade filer med mera.

Dessutom använde jag mig av LESS för att göra det enklare att styla om temat. Alla nödvändiga variabler lades in i ”variables.less”. Man kan ändra det mesta ifrån den filen och mycket mer behövs inte om man redan nöjt sig med temat.

#### Problem/Lösningar
Mitt största problem var LESS. Ibland när man gjorde fel så fungerade det helt enkelt inte och det fanns inga felmeddelanden. Detta problem dök upp flera gånger och tog ibland lång tid att handskas med innan jag hittade ett strukturerat sätt att fixa det på. 

Ett annat problem var med länkarna i navbaren. När jag förminskade sidan höll musen över ett av alternativen bytte navbaren storlek. Jag listade ut att höger och vänster paddingen på ett vanligt <li> elementen ska vara exakt lika stort som när man drar musen över den. Top och botten paddingen måste vara 1 pixel mindre än original paddingen så att <li> elementen inte knuffar varandra åt sidan horisontellt när ett markerat element ligger raden över eller raden under det element man för musen över. 

#### Resultat
Jag är nöjd över resultatet och det kändes bra att anta och klara utmaningen. Jag fick verkligen lära mig hur man kan skriva CSS kod på ett effektivare sätt (med hjälp av LESS). 

Under kursens (och momentets) gång har jag utvecklat processer för att lösa problem på ett smidigare sätt och de består av att jag skriver ned problemet på ett papper, går igenom det steg för steg, skriver ned tänkbara lösningar och resonerar mig fram till den som passar bäst. Detta är något jag kommer ta med mig i framtiden. Dessutom sparar jag de lösningar jag tror kommer att koma upp igen i framtiden vilket ger mig en databas av erfarenheter att vända mig till. Mest av allt har jag lärt mig uppskatta sekvensdiagram desto mer avancerad programmeringen blir.
