type d'event (propri�t� "type")

- warp : pour se TP d'une map � une autre

	l'objet "data" peut contenir :

		- map 		: 	le nom de la map de destination
		- x		:	l'abcisse de la destination
		- y		:	l'ordonn�e de la destination
		- direction 	:	la direction qu'aura le joueur (bas -> 0, gauche -> 1, droite -> 2, haut -> 3)

	
	il est possible de d�finir la propri�t� "direction" pour pr�ciser la direction dans laquelle doit se trouver le
	joueur pour se TP, si rien n'est pr�cis�, la TP se fera sans tenir compte de la direction

- script : un event script� via un fichier javascript

	l'objet "data" peut contenir :

		- file		:	le nom du fichier contenant le script

--------------------------------------------------------------

conditions de d�marrage d'event (propri�t� "trigger")

- player_contact :	l'�v�nement se d�clenche lorsque le joueur marche dessus
- key_press :		l'�v�nement se d�clenche lorsque le joueur appuie sur la touche d'action
- auto :		l'�v�nement se d�clenche automatiquement

---------------------------------------------------------------

conditions d'apparition de l'event (propri�t� "conditions") : cette propri�t� contient un tableau d'objets d�finis de la
mani�re suivante :

	propri�t� "type"

- player_switch	:	une condition portant sur l'�tat d'un interrupteur du joueur

	l'objet "data" peut contenir :
		
		- id		:	l'identifiant de l'interrupteur
		- active	:	true si l'interrupteur doit �tre actif, false sinon


- server_switch	:	une condition portant sur l'�tat d'un interrupteur du serveur

	l'objet "data" peut contenir :
		
		- id		:	l'identifiant de l'interrupteur
		- active	:	true si l'interrupteur doit �tre actif, false sinon


- player_variable :	une condition portant sur la valeur d'une variable du joueur

	l'objet "data" peut contenir :

		- type		:	le type de variable

						- player_x
						- player_y
