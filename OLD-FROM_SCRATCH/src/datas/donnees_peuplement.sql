-- BYTE = INT
-- COUNTER = INT AUTO_INCREMENT
-- LOGICAL = BOOLEAN

INSERT INTO `EXERCICE`(`titre`, `titre_affiche`, `auPoidsDuCorps`) VALUES
("overhead_press", "Soulevé militaire", false),
("bench_press", "Développé couché", false),
("declined_bench_press", "Développé couché décliné", false),
("pushups", "Pompes", true),
("supine_pull-ups", "Tractions supination", true),
("pronation_pull-ups", "Tractions pronation", true),
("deadlift", "Soulevé de terre", false),
('user6_1','Astication de poulie',false,6);

INSERT INTO `UTILISATEUR`(`mail`, `prenom`, `mot_de_passe`) VALUES 
("root@root.root", "root", "root"),
("ralonzo@iutbayonne.univ-pau.fr", "Robin", "bibite"),
("mathis.lague@gmail.com", "Mathis", "mathis"),
("nico601.delahaie@gmail.com", "Nico", "batata"),
('alexandre.pascal.ep@gmail.com','Alesssssxxandre','MediuMOOOK_');

INSERT INTO `PROGRAMME`(`nom`, `id_UTILISATEUR`) VALUES 
("PUSH",NULL),("PULL",NULL),("LEGS",NULL),
("Lundi",1), ("Mercredi",1), ("Week-end",1), ("Musclage du nez", 4),("Echauffement du pouce", 4);

INSERT INTO `CATEGORIE`(`nom`) VALUES 
("pectoraux"),("triceps"),("épaules"),("grand_dorsal"),("trapezes"),("biceps"), ("brachial"), ("ischios-jambiers"), ("lombaires");

INSERT INTO `EXERCICE_CATEGORIE`(`id_EXERCICE`, `id_CATEGORIE`, `priorite_numerateur`, `priorite_denominateur`) VALUES 
(1,5,2), (1,6,1),
(2,4,1),(2,5,1),(2,6,3),
(3,4,1),(3,5,2),(3,6,3),
(4,4,1),(4,5,1),
(5,7,1),(5,8,2),(5,9,1),
(6,7,1),(6,8,2),(6,9,3),(6,10,2),
(7,11,2),(7,12,1);

INSERT INTO `EXERCICE_PROGRAMME_UTILISATEUR`(`id_EXERCICE`, `id_UTILISATEUR`, `id_PROGRAMME`) VALUES
(1,1,1),(2,1,1),(3,1,1),(4,1,1),(5,1,2),(6,1,2),(7,1,3),
(2,4,7),(4,4,7),(5,4,8),(6,4,7),(7,4,8);

INSERT INTO `PERFORMANCE`(`id_EXERCICE`, `id_UTILISATEUR`, `repetitions`, `kilos`) VALUES 
(1,1,5,60),(2,1,4,90),(3,1,4,95),(4,1,40,0),(7,1,8,150),(2,4,10,25),(4,4,10,0),(5,4,2,0),(6,4,1,0),(7,4,5,70);
INSERT INTO `PERFORMANCE`(`id_EXERCICE`, `id_UTILISATEUR`, `repetitions`, `kilos`, `date_perf`) VALUES 
(2,1,3,80,"2023-01-15");







