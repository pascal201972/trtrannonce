<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426145104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trt_annonce (id INT AUTO_INCREMENT NOT NULL, profession_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, contrat_id INT DEFAULT NULL, recruteur_id INT DEFAULT NULL, etat_id INT DEFAULT NULL, description LONGTEXT NOT NULL, horaire VARCHAR(50) NOT NULL, salaire_annuel INT NOT NULL, valider TINYINT(1) NOT NULL, complet TINYINT(1) NOT NULL, INDEX IDX_9DB0FA10FDEF8996 (profession_id), INDEX IDX_9DB0FA1046E90E27 (experience_id), INDEX IDX_9DB0FA101823061F (contrat_id), INDEX IDX_9DB0FA10BB0859F1 (recruteur_id), INDEX IDX_9DB0FA10D5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_candidature (id INT AUTO_INCREMENT NOT NULL, annonce INT NOT NULL, profil INT NOT NULL, valider TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_contrat (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_etat_annonce (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_experiences (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_initpassword (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expire INT NOT NULL, UNIQUE INDEX UNIQ_F30BCE94A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_professions (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_profilcandidat (id INT AUTO_INCREMENT NOT NULL, profession_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, INDEX IDX_8E47E91CFDEF8996 (profession_id), INDEX IDX_8E47E91C46E90E27 (experience_id), UNIQUE INDEX UNIQ_8E47E91C79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_profilrecruteur (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(8) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, etablissement VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_1834883479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, valider TINYINT(1) NOT NULL, profil TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B0E89F99E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10FDEF8996 FOREIGN KEY (profession_id) REFERENCES trt_professions (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA1046E90E27 FOREIGN KEY (experience_id) REFERENCES trt_experiences (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA101823061F FOREIGN KEY (contrat_id) REFERENCES trt_contrat (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES trt_profilrecruteur (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10D5E86FF FOREIGN KEY (etat_id) REFERENCES trt_etat_annonce (id)');
        $this->addSql('ALTER TABLE trt_initpassword ADD CONSTRAINT FK_F30BCE94A76ED395 FOREIGN KEY (user_id) REFERENCES trt_user (id)');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91CFDEF8996 FOREIGN KEY (profession_id) REFERENCES trt_professions (id)');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91C46E90E27 FOREIGN KEY (experience_id) REFERENCES trt_experiences (id)');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES trt_user (id)');
        $this->addSql('ALTER TABLE trt_profilrecruteur ADD CONSTRAINT FK_1834883479F37AE5 FOREIGN KEY (id_user_id) REFERENCES trt_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA101823061F');
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA10D5E86FF');
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA1046E90E27');
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91C46E90E27');
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA10FDEF8996');
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91CFDEF8996');
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA10BB0859F1');
        $this->addSql('ALTER TABLE trt_initpassword DROP FOREIGN KEY FK_F30BCE94A76ED395');
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91C79F37AE5');
        $this->addSql('ALTER TABLE trt_profilrecruteur DROP FOREIGN KEY FK_1834883479F37AE5');
        $this->addSql('DROP TABLE trt_annonce');
        $this->addSql('DROP TABLE trt_candidature');
        $this->addSql('DROP TABLE trt_contrat');
        $this->addSql('DROP TABLE trt_etat_annonce');
        $this->addSql('DROP TABLE trt_experiences');
        $this->addSql('DROP TABLE trt_initpassword');
        $this->addSql('DROP TABLE trt_professions');
        $this->addSql('DROP TABLE trt_profilcandidat');
        $this->addSql('DROP TABLE trt_profilrecruteur');
        $this->addSql('DROP TABLE trt_user');
    }
}
