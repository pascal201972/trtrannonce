<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423142337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trt_experiences (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_professions (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_profilcandidat (id INT AUTO_INCREMENT NOT NULL, profession_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, INDEX IDX_8E47E91CFDEF8996 (profession_id), INDEX IDX_8E47E91C46E90E27 (experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_profilrecruteur (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(8) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, etablissement VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_1834883479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91CFDEF8996 FOREIGN KEY (profession_id) REFERENCES trt_professions (id)');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91C46E90E27 FOREIGN KEY (experience_id) REFERENCES trt_experiences (id)');
        $this->addSql('ALTER TABLE trt_profilrecruteur ADD CONSTRAINT FK_1834883479F37AE5 FOREIGN KEY (id_user_id) REFERENCES trt_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91C46E90E27');
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91CFDEF8996');
        $this->addSql('DROP TABLE trt_experiences');
        $this->addSql('DROP TABLE trt_professions');
        $this->addSql('DROP TABLE trt_profilcandidat');
        $this->addSql('DROP TABLE trt_profilrecruteur');
    }
}
