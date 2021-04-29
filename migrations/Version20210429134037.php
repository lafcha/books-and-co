<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429134037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_users_book (book_id INT NOT NULL, users_book_id INT NOT NULL, INDEX IDX_9BC1A78C16A2B381 (book_id), INDEX IDX_9BC1A78CE6A2C74B (users_book_id), PRIMARY KEY(book_id, users_book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_users_book (user_id INT NOT NULL, users_book_id INT NOT NULL, INDEX IDX_A30B2BD3A76ED395 (user_id), INDEX IDX_A30B2BD3E6A2C74B (users_book_id), PRIMARY KEY(user_id, users_book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_book (id INT AUTO_INCREMENT NOT NULL, users_book_is_lent_id INT NOT NULL, is_available TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_46CED3FE0F8B622 (users_book_is_lent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78CE6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_users_book ADD CONSTRAINT FK_A30B2BD3E6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_book ADD CONSTRAINT FK_46CED3FE0F8B622 FOREIGN KEY (users_book_is_lent_id) REFERENCES lending (id)');
        $this->addSql('ALTER TABLE lending ADD borrower_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C0311CE312B FOREIGN KEY (borrower_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_74AB8C0311CE312B ON lending (borrower_id)');
        $this->addSql('ALTER TABLE message ADD sender_id INT DEFAULT NULL, ADD lending_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB235D63A FOREIGN KEY (lending_id) REFERENCES lending (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB235D63A ON message (lending_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78CE6A2C74B');
        $this->addSql('ALTER TABLE user_users_book DROP FOREIGN KEY FK_A30B2BD3E6A2C74B');
        $this->addSql('DROP TABLE book_users_book');
        $this->addSql('DROP TABLE user_users_book');
        $this->addSql('DROP TABLE users_book');
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C0311CE312B');
        $this->addSql('DROP INDEX IDX_74AB8C0311CE312B ON lending');
        $this->addSql('ALTER TABLE lending DROP borrower_id');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB235D63A');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FB235D63A ON message');
        $this->addSql('ALTER TABLE message DROP sender_id, DROP lending_id');
    }
}
