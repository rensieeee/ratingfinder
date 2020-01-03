# KNSB-Ratingfinder
KNSB Rating finder

Een script om KNSB rating te vinden op basis van bondsnummer en (optioneel) lijstnummer. Het script is gratis 24/7 te gebruiken via ratingfinder.nl.

Resultaten zijn een JSON-object, met de strings "rating" en "error". Error is leeg als alles goed is, rating is leeg als er iets fout is.

Gebruik: ratingfinder.nl?knsb=[KNSB-nummer]
Optioneel: ratingfinder.nl?knsb=[KNSB-nummer]&list=[Lijstnummer]

Let op: Het script maakt gebruik van de KNSB ratingviewer, omdat de KNSB zelf geen beter systeem heeft. Het haalt de juiste data uit de verkregen html code. Lijstnummer is het "value" attribuut van de <option> tags in de html-pagina. Voorbeeld: Lijst 2019-11 heeft lijstnummer 142.

Stuur me voor PR's een berichtje, vind ik altijd wel goed.
