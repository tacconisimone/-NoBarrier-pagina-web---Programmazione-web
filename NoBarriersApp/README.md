#Gruppo 4, membri:
-Simone Tacconi
-Cristina Giannone
-Valeria Ioannucci



# Definizione del Problema
Il progetto ha come scopo la creazione di un’applicazione web che offra un servizio a tutti coloro che sono alla ricerca di posti senza barriere 
architettoniche in Italia e a tutti gli imprenditori che vogliono pubblicizzare la propria attività.



# Utenti del Sistema
- Utente non registrato
- Utente loggato come Visitatore
- Utente loggato come Proprietario
- Amministratore

# Elenco delle funzionalità

Un utente non loggato può:
-registrarsi come visitatore o proprietario
-navigare l' applicazione vedendo i posti nella categoria e città selezionata e i commenti relativi

Un utente loggato come Visitatore può;
-navigare l' applicazione 
-cambiare le informazioni del proprio profilo(username,password...)
-modificare la foto profilo
-valutare le attività (dare voto e commento)

Un utente loggato come proprietario può:
-navigare l' applicazione
-cambiare le informazioni del proprio profilo(username,password,PIVA,...)
-modificare la foto profilo
-pubblicare un' attività (prima deve essere validata dall' amministratore)
-accedere dal proprio profilo alla lista delle attività da lui pubblicate, da cui può modificare foto e descrizione di ciascuna attività(è necessaria sempre la validazione dell' amministratore)

L' amministratore può:
-Aggiungere nuove città
-Aggiungere nuove categorie 
-decidere se pubblicare o meno un' attività dopo averne visto un' anteprima
-decidere se approvare o menno le modifiche fatte ad un' attività 
(username= amministratore , password= nobarriersappweb)


# Implementazione funzionalità
Le funzionalità del precedente paragrafo sono state implementate seguendo uno sviluppo dell'applicazione basato su quattro strati:
- View
- Controller
- Entity
- Foundation

# Installazione
L'applicazione è stata svilupatta in PHP e provata su XAMPP, una piattaforma software che contiene strumenti quali:
- Server Apache
- Il DBMS MySQL
- PHP (Versione 7.2.1)

Pertanto l'applicazione dovrà richiedere un'installazione di XAMPP, in particolare la cartella contenente l'applicativo dovrà essere posizionata nella cartella _htdocs_ . Raggiungendo l'applicazione attraverso l'URL
``` localhost/NoBarriersApp/ ```
verrà richiesta inizialmente una configurazione per creare e popolare il database. Al primo avvio infatti verrà sottoposta una form, in cui verranno richiesti:
- nome utente del DBMS
- password del DBMS
- nome che si vuole dare al database

