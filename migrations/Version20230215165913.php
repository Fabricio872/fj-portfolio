<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215165913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE printed_project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE web_project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE printed_project (id INT NOT NULL, title_id INT NOT NULL, description_id INT NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E81114A9F87BD ON printed_project (title_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E81114D9F966B ON printed_project (description_id)');
        $this->addSql('CREATE TABLE translation (id INT NOT NULL, sk TEXT NOT NULL, en TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE web_project (id INT NOT NULL, title_id INT NOT NULL, description_id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C71C7E70A9F87BD ON web_project (title_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C71C7E70D9F966B ON web_project (description_id)');
        $this->addSql('ALTER TABLE printed_project ADD CONSTRAINT FK_D4E81114A9F87BD FOREIGN KEY (title_id) REFERENCES translation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE printed_project ADD CONSTRAINT FK_D4E81114D9F966B FOREIGN KEY (description_id) REFERENCES translation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE web_project ADD CONSTRAINT FK_C71C7E70A9F87BD FOREIGN KEY (title_id) REFERENCES translation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE web_project ADD CONSTRAINT FK_C71C7E70D9F966B FOREIGN KEY (description_id) REFERENCES translation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE printed_project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE translation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE web_project_id_seq CASCADE');
        $this->addSql('ALTER TABLE printed_project DROP CONSTRAINT FK_D4E81114A9F87BD');
        $this->addSql('ALTER TABLE printed_project DROP CONSTRAINT FK_D4E81114D9F966B');
        $this->addSql('ALTER TABLE web_project DROP CONSTRAINT FK_C71C7E70A9F87BD');
        $this->addSql('ALTER TABLE web_project DROP CONSTRAINT FK_C71C7E70D9F966B');
        $this->addSql('DROP TABLE printed_project');
        $this->addSql('DROP TABLE translation');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE web_project');
    }
}
