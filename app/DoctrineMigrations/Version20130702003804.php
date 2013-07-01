<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130702003804 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, liked_at DATETIME NOT NULL, INDEX IDX_49CA4E7DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE likings (id INT AUTO_INCREMENT NOT NULL, like_id INT DEFAULT NULL, resource_id VARCHAR(50) NOT NULL, resource_type VARCHAR(50) NOT NULL, INDEX IDX_F7B46264859BFA32 (like_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE likings ADD CONSTRAINT FK_F7B46264859BFA32 FOREIGN KEY (like_id) REFERENCES likes (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE likings DROP FOREIGN KEY FK_F7B46264859BFA32");
        $this->addSql("DROP TABLE likes");
        $this->addSql("DROP TABLE likings");
    }
}
