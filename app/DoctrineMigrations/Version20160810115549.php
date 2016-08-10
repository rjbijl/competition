<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160810115549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rnd (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round_players (round_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_F9EA1B5CA6005CA0 (round_id), INDEX IDX_F9EA1B5C99E6F5DF (player_id), PRIMARY KEY(round_id, player_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE round_players ADD CONSTRAINT FK_F9EA1B5CA6005CA0 FOREIGN KEY (round_id) REFERENCES rnd (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE round_players ADD CONSTRAINT FK_F9EA1B5C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mtch ADD round_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mtch ADD CONSTRAINT FK_B649E140A6005CA0 FOREIGN KEY (round_id) REFERENCES rnd (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B649E140A6005CA0 ON mtch (round_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mtch DROP FOREIGN KEY FK_B649E140A6005CA0');
        $this->addSql('ALTER TABLE round_players DROP FOREIGN KEY FK_F9EA1B5CA6005CA0');
        $this->addSql('DROP TABLE rnd');
        $this->addSql('DROP TABLE round_players');
        $this->addSql('DROP INDEX IDX_B649E140A6005CA0 ON mtch');
        $this->addSql('ALTER TABLE mtch DROP round_id');
    }
}
