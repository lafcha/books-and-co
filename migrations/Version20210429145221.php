<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429145221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(32) NOT NULL, author VARCHAR(64) NOT NULL, cover VARCHAR(128) DEFAULT NULL, editor VARCHAR(64) NOT NULL, year INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(32) NOT NULL, isbn BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lending (id INT AUTO_INCREMENT NOT NULL, borrower_id INT NOT NULL, status SMALLINT NOT NULL, INDEX IDX_74AB8C0311CE312B (borrower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, lending_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FB235D63A (lending_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(64) NOT NULL, avatar VARCHAR(128) NOT NULL, county INT NOT NULL, city VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C0311CE312B FOREIGN KEY (borrower_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB235D63A FOREIGN KEY (lending_id) REFERENCES lending (id)');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78CE6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_book ADD CONSTRAINT FK_46CED3FE0F8B622 FOREIGN KEY (users_book_is_lent_id) REFERENCES lending (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78C16A2B381');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB235D63A');
        $this->addSql('ALTER TABLE users_book DROP FOREIGN KEY FK_46CED3FE0F8B622');
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C0311CE312B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE user_users_book DROP FOREIGN KEY FK_A30B2BD3A76ED395');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE lending');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78CE6A2C74B');
        $this->addSql('ALTER TABLE user_users_book DROP FOREIGN KEY FK_A30B2BD3E6A2C74B');
    }
}
