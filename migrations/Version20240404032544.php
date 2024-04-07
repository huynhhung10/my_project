<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404032544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies (id INT AUTO_INCREMENT NOT NULL, genre_id_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_C61EED30C2428192 (genre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, review_text VARCHAR(255) NOT NULL, rating INT NOT NULL, INDEX IDX_6970EB0F8F93B6FC (movie_id), INDEX IDX_6970EB0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED30C2428192 FOREIGN KEY (genre_id_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F8F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED30C2428192');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F8F93B6FC');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE reviews');
    }
}
