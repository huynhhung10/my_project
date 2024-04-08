<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407100447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, img LONGTEXT NOT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED30C2428192');
        $this->addSql('DROP INDEX IDX_C61EED30C2428192 ON movies');
        $this->addSql('ALTER TABLE movies CHANGE genre_id_id genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED304296D31F FOREIGN KEY (genre_id) REFERENCES genres (id)');
        $this->addSql('CREATE INDEX IDX_C61EED304296D31F ON movies (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customers');
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED304296D31F');
        $this->addSql('DROP INDEX IDX_C61EED304296D31F ON movies');
        $this->addSql('ALTER TABLE movies CHANGE genre_id genre_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED30C2428192 FOREIGN KEY (genre_id_id) REFERENCES genres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C61EED30C2428192 ON movies (genre_id_id)');
    }
}
