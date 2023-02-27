-- RECUPERER LES EXERCICES DE L UTILISATEUR ET DU PROGRAMME SELECTIONNE
SELECT E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                FROM EXERCICE E 
                LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                WHERE EPU.id_UTILISATEUR = 1 AND 
                        EPU.id_PROGRAMME = 2
                GROUP BY E.titre;