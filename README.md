# Miniprojet-PHP
PHP project following the PHP course

Les fonctionnalités demandées sont les suivantes :  
• visualiser tous les enregistrements existants en base  
• pour un enregistrement, accéder au détail avec :  
  ◦ affichage de tous les paramètres  
  ◦ affichage des graphiques associés (cambrure et rigidité/solidité)  
  ◦ lien pour réaliser un export de tous les points enregistrés au format csv  
• créer une page permettant de créer un nouvel enregistrement (formulaire de saisie des paramètres)  
• sur chaque page, utilisation d’un en-tête et d’un pied de page commun à toutes les pages  
• pour chaque enregistrement, prévoir :  
  ◦ un lien permettant de gérer la modification des paramètres et relancer la génération des points  
  ◦ un lien permettant la suppression des données associées à cet enregistrement avec un message de confirmation avant la suppression  
• sauvegarder les fichiers générés (image et csv) et les rattacher à l’enregistrement réalisé  


LOGICIELS NECESSAIRES :  
  ◦ MySQL  
  ◦ Serveur web pouvant exécuter du PHP et jpgraph  

INSTRUCTIONS D'UTLISATION :  
• Ajouter le dossier du projet dans les fichiers du serveur web  
• Créer dans la base de données un utilisateur 'user6' dont le mot de passe est 'user6'  
  ◦ Créer avec lui une base de données nommée 'user6', sur laquelle il possède les droits  
  ◦ Exécuter sql/user6.sql dans MySQL  
