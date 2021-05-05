<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503145127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending ADD users_book_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C03E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id)');
        $this->addSql('CREATE INDEX IDX_74AB8C03E6A2C74B ON lending (users_book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C03E6A2C74B');
        $this->addSql('DROP INDEX IDX_74AB8C03E6A2C74B ON lending');
        $this->addSql('ALTER TABLE lending DROP users_book_id');
    }
}
