<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429133059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD id INT AUTO_INCREMENT NOT NULL, CHANGE isbn isbn BIGINT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_users_book ADD CONSTRAINT FK_9BC1A78CE6A2C74B FOREIGN KEY (users_book_id) REFERENCES users_book (id) ON DELETE CASCADE');
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
        $this->addSql('ALTER TABLE book MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE book DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE book DROP id, CHANGE isbn isbn BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE book ADD PRIMARY KEY (isbn)');
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78C16A2B381');
        $this->addSql('ALTER TABLE book_users_book DROP FOREIGN KEY FK_9BC1A78CE6A2C74B');
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
