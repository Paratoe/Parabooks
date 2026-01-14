Boekenreview Webapplicatie

Dit is mijn eerste backendproject, gebouwd in PHP met PDO en MySQL.  
De applicatie is bedoeld om boeken toe te voegen, te bekijken, te beoordelen en reviews te plaatsen.  
De website werkt volledig dynamisch met een MySQL-database en is opgebouwd volgens de backend-eisen van het project.

Functionaliteiten

1. Boekenbeheer
- Boeken toevoegen
- Boeken bekijken (detailpagina)
- Boeken bewerken
- Boeken verwijderen
- Zoeken op titel of auteur

2. Reviewsysteem
- Reviews plaatsen met tekst + rating (1 t/m 5)
- Reviews worden direct onder het boek getoond
- Een bezoeker kan **slechts één review per boek plaatsen (m.b.v. cookies maar dit kan pas als het online is gezet)

Likes & Dislikes
- Elk boek kan geliked of disliked worden
- Per bezoeker maximaal één stem per boek (via cookies maar dit kan pas als het online is gezet)

Extra
- Bootstrap styling
- Volledig responsive layout
- Netjes gestructureerde projectmappen





 Installatie-instructies:

Volg deze stappen om het project lokaal te draaien:

1. Download of clone de repository
Plaats de map in:
2. Start XAMPP
Start:

- Apache
- MySQL

3. Importeer de database
Ga naar:


- Klik op **Importeren**
- Selecteer het bestand: **init.sql**  
  (dit bestand staat in de repository)
- Klik op **Start**

Er wordt een database met deze tabellen aangemaakt:

- `books`
- `reviews`

4. Controleer `db.php`
De database-connectie is al correct ingesteld voor XAMPP:

```php
$host = '127.0.0.1';
$db   = 'boekenreview';
$user = 'root';
$pass = '';

5.de website zou nu lokaal kunnen draaien. 
http://localhost/backen-website%20kopie/index.php

