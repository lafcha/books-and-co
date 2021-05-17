<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503160425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_book (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_46CED3FA76ED395 (user_id), INDEX IDX_46CED3F16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_book ADD CONSTRAINT FK_46CED3FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE users_book ADD CONSTRAINT FK_46CED3F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('ALTER TABLE lending ADD users_book_is_lent_id INT NOT NULL');
        $this->addSql('ALTER TABLE lending ADD CONSTRAINT FK_74AB8C03E0F8B622 FOREIGN KEY (users_book_is_lent_id) REFERENCES users_book (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74AB8C03E0F8B622 ON lending (users_book_is_lent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP FOREIGN KEY FK_74AB8C03E0F8B622');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE users_book');
        $this->addSql('DROP INDEX UNIQ_74AB8C03E0F8B622 ON lending');
        $this->addSql('ALTER TABLE lending DROP users_book_is_lent_id');
    }
}
