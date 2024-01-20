-- Tout le sql à ajouter qui n'est pas intégré au code SQL fourni par Looping
ALTER TABLE `PERFORMANCE` ADD UNIQUE(`date_perf`, `id_EXERCICE`, `id_UTILISATEUR`);
ALTER TABLE `PERFORMANCE` CHANGE `date_perf` `date_perf` DATE NULL DEFAULT CURRENT_TIMESTAMP;
