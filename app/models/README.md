**/models/** *(Datenmodelle und Datenbankinteraktion)*
*   Enthält Klassen, die die Datenstrukturen der Anwendung repräsentieren (z.B. Benutzer, Produkte, Bestellungen).
*   Die Models sind für die Interaktion mit der Datenbank zuständig (z.B. Abfragen, Einfügen, Aktualisieren, Löschen).
*   Oftmals wird ein ORM (Object-Relational Mapper) wie Doctrine oder Eloquent verwendet, um die Datenbankinteraktion zu vereinfachen.
*   Beispiel: `User.php`, `Product.php`, `Order.php`