#!/bin/bash

# FILE=test.sh

# Définition des couleurs
green='\e[1;32m'
cyan='\e[1;36m'
red='\e[0;31m'
classic='\e[0;m'

# Création de fichiers nécessaires au bon fonctionnement des sites (BDD, Crontab)
sudo mysqldump -u login4017 -pbtsinfo --all-databases > /var/www/html/symfony4-4017/archives/tables.sql
crontab -l > /var/www/html/symfony4-4017/archives/crontabs.txt

echo -e "${cyan}Création de l'archive...${classic}"
sudo nohup tar czvf /var/www/html/symfony4-4017/archives/archive.tar.gz /var/www/html/symfony4-4017/recette /var/www/html/symfony4-4017/public /var/www/html/symfony4-4017/archives/tables.sql /var/www/html/symfony4-4017/archives/crontabs.txt /var/www/html/symfony4-4017/scripts/*
echo -e "${green}Archive créée${classic}"

echo -e "${cyan}Envoi de l'archive${classic}"
mega-put /var/www/html/symfony4-4017/archives/archive.tar.gz /backups/
echo -e "${green}Archive reçue par Méga${classic}"

echo -e "${cyan}Envoi du lien par mail...${classic}"
echo "Lien d'accès du fichier: https://mega.nz/folder/zABn2JYa#oiLrOoSCJgxPgD90OYZFyA" | mail -s "Backup du `date +%d-%m-%Y` à `date +%H | bc -l`h" marceau0707@gmail.com
echo -e "${green}Mail reçu${classic}"

echo -e "${red}Suppression des éléments crées${classic}"
sudo rm -r /var/www/html/symfony4-4017/archives/tables.sql
sudo rm -r /var/www/html/symfony4-4017/scripts/crontabs.txt
sudo rm -r /var/www/html/symfony4-4017/archives/crontabs.txt
sudo rm -r ~/nohup.out
sudo rm -r /var/www/html/symfony4-4017/archives/archive.tar.gz

echo -e "${green}Terminé${classic}"
