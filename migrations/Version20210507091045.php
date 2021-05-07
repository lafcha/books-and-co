<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507091045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP INDEX UNIQ_74AB8C03E6A2C74B, ADD INDEX IDX_74AB8C03E6A2C74B (users_book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lending DROP INDEX IDX_74AB8C03E6A2C74B, ADD UNIQUE INDEX UNIQ_74AB8C03E6A2C74B (users_book_id)');
    }
}
