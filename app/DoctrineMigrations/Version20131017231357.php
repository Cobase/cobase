<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131017231357 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE postevents DROP FOREIGN KEY FK_4EA43739FE54D947");
        $this->addSql("DROP INDEX IDX_4EA43739FE54D947 ON postevents");
        $this->addSql("ALTER TABLE postevents DROP group_id");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE postevents ADD group_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE postevents ADD CONSTRAINT FK_4EA43739FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)");
        $this->addSql("CREATE INDEX IDX_4EA43739FE54D947 ON postevents (group_id)");
    }
}
