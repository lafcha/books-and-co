<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503144937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_book DROP FOREIGN KEY FK_46CED3FE0F8B622');
        $this->addSql('DROP INDEX UNIQ_46CED3FE0F8B622 ON users_book');
        $this->addSql('ALTER TABLE users_book DROP users_book_is_lent_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_book ADD users_book_is_lent_id INT NOT NULL');
        $this->addSql('ALTER TABLE users_book ADD CONSTRAINT FK_46CED3FE0F8B622 FOREIGN KEY (users_book_is_lent_id) REFERENCES lending (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46CED3FE0F8B622 ON users_book (users_book_is_lent_id)');
    }
}
