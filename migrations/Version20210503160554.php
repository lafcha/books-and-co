<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503160554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C03E0F8B622');
        $this->addSql('DROP INDEX UNIQ_74AB8C03E0F8B622 ON lending');
        $this->addSql('ALTER TABLE lending CHANGE users_book_is_lent_id users_book_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C03E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74AB8C03E6A2C74B ON lending (users_book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C03E6A2C74B');
        $this->addSql('DROP INDEX UNIQ_74AB8C03E6A2C74B ON lending');
        $this->addSql('ALTER TABLE lending CHANGE users_book_id users_book_is_lent_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C03E0F8B622 FOREIGN KEY (users_book_is_lent_id) REFERENCES users_book (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74AB8C03E0F8B622 ON lending (users_book_is_lent_id)');
    }
}
