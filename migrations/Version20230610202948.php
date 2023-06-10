<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610202948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE visitor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE visitor (id INT NOT NULL, route VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, params JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE printed_project ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE web_project ALTER created_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE visitor_id_seq CASCADE');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('ALTER TABLE web_project ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE printed_project ALTER created_at SET DEFAULT \'now()\'');
    }
}
