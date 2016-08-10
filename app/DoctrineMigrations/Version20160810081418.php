<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160810081418 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mtch (id INT AUTO_INCREMENT NOT NULL, home_player_id INT DEFAULT NULL, away_player_id INT DEFAULT NULL, date DATE NOT NULL, home_score INT NOT NULL, away_score INT NOT NULL, INDEX IDX_B649E140E7328C9B (home_player_id), INDEX IDX_B649E1406861DE1 (away_player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mtch ADD CONSTRAINT FK_B649E140E7328C9B FOREIGN KEY (home_player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mtch ADD CONSTRAINT FK_B649E1406861DE1 FOREIGN KEY (away_player_id) REFERENCES player (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mtch DROP FOREIGN KEY FK_B649E140E7328C9B');
        $this->addSql('ALTER TABLE mtch DROP FOREIGN KEY FK_B649E1406861DE1');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE mtch');
    }
}
