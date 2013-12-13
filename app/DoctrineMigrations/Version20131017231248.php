<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131017231248 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE postevents ADD post_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE postevents ADD CONSTRAINT FK_4EA437394B89032C FOREIGN KEY (post_id) REFERENCES posts (id)");
        $this->addSql("CREATE INDEX IDX_4EA437394B89032C ON postevents (post_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE postevents DROP FOREIGN KEY FK_4EA437394B89032C");
        $this->addSql("DROP INDEX IDX_4EA437394B89032C ON postevents");
        $this->addSql("ALTER TABLE postevents DROP post_id");
    }
}
