<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503153042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78CE6A2C74B');
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C03E6A2C74B');
        $this->addSql('ALTER TABLE user_users_book DROP FOREIGN KEY FK_A30B2BD3E6A2C74B');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE book_users_book');
        $this->addSql('DROP TABLE user_users_book');
        $this->addSql('DROP TABLE users_book');
        $this->addSql('DROP INDEX IDX_74AB8C03E6A2C74B ON lending');
        $this->addSql('ALTER TABLE lending DROP users_book_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_users_book (book_id INT NOT NULL, users_book_id INT NOT NULL, INDEX IDX_9BC1A78C16A2B381 (book_id), INDEX IDX_9BC1A78CE6A2C74B (users_book_id), PRIMARY KEY(book_id, users_book_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_users_book (user_id INT NOT NULL, users_book_id INT NOT NULL, INDEX IDX_A30B2BD3A76ED395 (user_id), INDEX IDX_A30B2BD3E6A2C74B (users_book_id), PRIMARY KEY(user_id, users_book_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_book (id INT AUTO_INCREMENT NOT NULL, is_available TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78CE6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('ALTER TABLE lending ADD users_book_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C03E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id)');
        $this->addSql('CREATE INDEX IDX_74AB8C03E6A2C74B ON lending (users_book_id)');
    }
}
