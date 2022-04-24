<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423173528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trt_profilcandidat ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trt_profilcandidat ADD CONSTRAINT FK_8E47E91C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES trt_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E47E91C79F37AE5 ON trt_profilcandidat (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trt_profilcandidat DROP FOREIGN KEY FK_8E47E91C79F37AE5');
        $this->addSql('DROP INDEX UNIQ_8E47E91C79F37AE5 ON trt_profilcandidat');
        $this->addSql('ALTER TABLE trt_profilcandidat DROP id_user_id');
    }
}
