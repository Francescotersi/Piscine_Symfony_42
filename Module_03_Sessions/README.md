# Piscine Symfony 42 - Module 03: Sessions

## Comandi Utili

- php bin/console doctrine:schema:update --force => Forza l`update dei database senza uso di file di migrazione

## Rotte del Progetto

### Esercizio 01

- `/e01/homepage` - Pagina principale con lo stato dell'utente e i pulsanti per login, registrazione o logout.
- `/e01/register` - Pagina per creare un nuovo account utente.
- `/e01/login` - Pagina per accedere al proprio account.
- `/e01/logout` - Disconnette l'utente attualmente collegato.

### Esercizio 02

- `/e02/admin/register` - Pagina per creare un nuovo account amministratore.
- `/e02/admin` - Pannello admin con la lista di tutti gli utenti/admin e i tasti per eliminarli.
- `/e02/admin/delete/user/{id}` - Elimina l'utente selezionato.
- `/e02/admin/delete/admin/{id}` - Elimina l'amministratore selezionato (non permette di auto-eliminarsi).
