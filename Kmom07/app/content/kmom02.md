Kmom02: Kontroller och Modeller
------------------------------------
#### Hur känns det att jobba med Composer?
Det kändes bra att arbeta med Composer. Det blev någonting nytt att lära sig och jag kunde direkt förstå hur användbart det kan vara.

#### Vad tror du om de paket som finns i Packagist, är det något du kan tänka dig använda och hittade du något spännande att inkludera i ditt ramverk?
Jag hittade ingenting spännande just nu som jag kan använda i mitt ramverk, men det gör jag troligtvis framöver. Jag tror att de paket som finns och Packagist i sin helhet är ett bra koncept samt att det finns mycket användbara idéer samlade på denna webbsida. En framtida användning av denna tjänst blir det definitivt.

#### Hur var begreppen att förstå med klasser som kontroller som tjänster som dispatchas, fick du ihop allt?
Jag gick igenom guiderna och tutorialsen till Phalcon noggrant och på grund av detta blev momentet lättförståeligt för min del. Den kraft jag ägnade åt förberedelse leddet till ett resultat som jag verkligen blev nöjd med. Med andra ord: Jag fick ihop allting.

#### Hittade du svagheter i koden som följde med phpmvc/comment? Kunde du förbättra något?
Ja, jag hittade en del saker som jag kunde lägga till och förbättra. Först och främst skapade jag en funktion som tillät editering av existerande kommentarer. Därefter skapade jag funktionen för att ta bort enskilda kommentarer. Vidare utvecklade jag vyn comments.tpl på ett sätt som tillät snygg editering och borttagning utifrån användarens perspektiv.

Fortsättningsvis såg jag till så att man enkelt kan bestämma var arrrayen för den nuvarande sidans kommentarer ska sparas. Genom att skicka in en parameter till viewControllern bestämmer man namnet på platsen där arrayen sparas i $_SESSION.
På grund av detta behöver man endast bestämma sparningsplatsen en ända gång och sedan sköts resten automatiskt. Dessutom sparade jag namnet på den sparningsplats som används i nuläget i en annan del av $_SESSION. Följden av detta blev att man enkelt kan skapa ett kommentarsystem på ett obegränsat antal separata sidor och fortfarande ha samtliga system sparade i sessionen.
Avslutningsvis skapade jag även en funktion som omvandlar unix tidsstämplar till en lättläslig sträng för att användare ska kunna see när en kommentar skapades.

#### Svårigheter/problem
Det kändes segt i början men genom att arbeta igenom Phalcons tutorials (inte endast läsa) så släppte allting rätt så fort. Det svåraste var när jag skulle bestämma mig för hur skapandet av flera ”containrar” i session skulle se ut. Jag skrev ned en lista över de alternativ jag hade och resonerade mig fram till det som jag ansåg var lätt att använda och lätt att implementera.

#### Lösningar
Många lösningar är redan nämnda men en väljer jag att föra fram här. ”Post a comment”-knappen. Denna extra uppgift (nummer 6) löste jag igenom att lägga till en form och en knapp i själva form.tpl. Sedan avslutades det hela med session→getPost() för att kolla om knappen blivit tryckt eller inte. Mer komplicerat än så blev det inte. Detta är den lösning som jag fann enklast.

#### Resultat
Jag är mycket nöjd över resultatet och har lärt mig en ny teknik för att handskas med problem. Skriv ned dem rad för rad, gör en punktlista över valmöjligheterna och resonera fram vilken som passar in bäst. Med min nuvarande kunskap kunde det inte blivit bättre. Jag har även under kursernas gång skappat en slags rutin för hur jag går tillväga inför varje problem och denna utvecklades nu på tidigare nämnt sätt. 

Samtliga uppgifter och extra uppgifter är gjorda. Kursmomentet i sig var lärorikt och ett av de bästa hittills.
