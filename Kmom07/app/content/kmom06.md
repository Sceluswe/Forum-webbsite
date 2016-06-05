Kmom06: Verktyg och CI
------------------------------------
 
#### Var du bekant med några av dessa tekniker innan du började med kursmomentet?
Den ända tekniken jag arbetat med förut är förstås GitHub. Annars har jag endast hört PHPUnit tidigare och då endast kortfattat. Scrutinizer och Travis var helt nytt.

#### Hur gick det att göra testfall med PHPUnit?
Det var lite klurigt till en början eftersom jag alltid börjar med att försöka klura ut allting själv. Det funkade bra men med Mos exempel blev det ännu bättre. Det klurigaste var att ta reda på hur man gör testfall för Exceptions. Mer om det senare.

#### Hur gick det att integrera med Travis?
Travis var svårt att förstå sig på i början då jag som vanligt försökte på egen hand. Men efter att ha hittat en länk till deras Lint verktyg i dokumentationen så kunde jag enkelt hitta rätt syntax och fullfölja integrationen.

#### Hur gick det att integrera med Scrutinizer?
Integreringen med Scruitinizer gick ännu smidigare än Travis. Det funkade så fort jag länkat ihop Scrutinizer med mitt Github och gett Scrutinizer rättigheter att skicka pull requests till min Github repository. Även delen med code coveragesom visade sig lite krångligare lyckades jag även lösa med övningarnas hjälp.

#### Hur känns det att jobba med dessa verktyg, krångligt, bekvämt, tryggt? 
Det känns riktigt bekvämt. Jag har tidigare arbetat med att skriva tester på nästan all min kod i de fritidsprojekt jag åtagit mig och därför var detta redan en vana. Men att ta hjälp av automatisering förenklade det hela. Som mos sa i slutet på en av övningar ”du kommer vilja skriva tester” och det har han fullkomligt rätt i, det ger en extra trygghet i att ens kod kan fungera precis som man tänkt sig.

Lite krångligt var det att komma in i de olika dokumentationerna men övning ger färdighet och även det släpper snart. 

#### Kan du tänka dig att fortsätta använda dem?
Ja! Man känner sig säkrare på sin kod och baserat på min egen erfarenhet kan jag även säga att det minskar utvecklingstiden när man har någonting som konstant kollar att ens kod fungerar. Min egna rutin för det hela kan sammanfattas så här: Planera, koda, testa, bygg/kompilera, dokumentera. Sedan repeterar jag det för nästan all kod jag skriver. Undantag görs endast när det är något så simpelt som en get-funktion, då kan jag skriva flera samtidigt innan jag testar om de fungerar eller inte, men ett test görs alltid i slutändan.

#### Gjorde du extrauppgiften? Beskriv i så fall hur du tänkte och vilket resultat du fick.
Jag skippade extrauppgiften i mån av tid. Det skulle varit intressant och utmanade att göra, men jag tar beslutet att få mer övning inom detta medans jag fortsätter framåt och lär mig nya saker. Kanske kommer jag gå tillbaka till denna under sommaren för att få mera träning. 

#### Svårigheter/Problem/Lösningar
Det var inga större svårigheter under detta kursmoment. Det ända som kändes svårt var, som tidigare nämnt, att sätta sig in i de olika dokumentationerna och att förstå grunden i hur ”.travis.yml” fungerar.

Ett problem jag hade var när jag skulle skriva ett test för att se om min exception kastades. Jag studerade manualen och blev inte klokare på det, men upptäckte sedan I mos genomgång att det var ”@expectedException Exception” som saknades för att PHPUnit skulle skapa en ”wrapper” runt min funktion.

#### Resultat
Som vanligt är jag nöjd över resultatet. Detta var ett av de bästa och mest lärorika kursmomenten som jag haft hittills. Det fanns gott om utrymme för eget initiativ och dessutom fick vi en introduktion till personer som är och varit aktiva i kod-världen under längre tid. Bäst av allt fick vi lära oss om CI och TDD vilket man direkt förstår vikten av, speciellt efter att ha läst Martin Fowlers reflektioner om det hela. Bland de saker han nämnde beskrev han en historia där ett projekt planerade att genomgå integrering under 7 månader och hur detta visar sig helt onödigt med CI. Den lärdomen kommer jag bära med mig länge. 