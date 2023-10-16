<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009134501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, niveau_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, last_name_user VARCHAR(255) NOT NULL, first_name_user VARCHAR(255) NOT NULL, age INT NOT NULL, email VARCHAR(255) NOT NULL, n_mdp INT DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649A4AEAFEA (entreprise_id), INDEX IDX_8D93D649B3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, role_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_1D1C63B388987678 (role_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vocabulaire (id INT AUTO_INCREMENT NOT NULL, theme_id INT NOT NULL, categorie_id INT NOT NULL, libelle_fr VARCHAR(255) NOT NULL, libelle_en VARCHAR(255) NOT NULL, INDEX IDX_DB1ADE7D59027487 (theme_id), INDEX IDX_DB1ADE7DBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau_user (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B388987678 FOREIGN KEY (role_id_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE vocabulaire ADD CONSTRAINT FK_DB1ADE7D59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE vocabulaire ADD CONSTRAINT FK_DB1ADE7DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE effectuer ADD CONSTRAINT FK_985281501E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE effectuer ADD CONSTRAINT FK_98528150B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE effectuer ADD CONSTRAINT FK_98528150A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payer ADD CONSTRAINT FK_41CB5B99F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE payer ADD CONSTRAINT FK_41CB5B99FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE effectuer DROP FOREIGN KEY FK_98528150A76ED395');
        $this->addSql('ALTER TABLE payer DROP FOREIGN KEY FK_41CB5B99FB88E14F');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE vocabulaire');
        $this->addSql('ALTER TABLE effectuer DROP FOREIGN KEY FK_985281501E5D0459');
        $this->addSql('ALTER TABLE effectuer DROP FOREIGN KEY FK_98528150B897366B');
        $this->addSql('ALTER TABLE payer DROP FOREIGN KEY FK_41CB5B99F1D74413');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C59027487');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CB3E9C81');
    }
}
