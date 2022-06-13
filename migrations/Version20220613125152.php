<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613125152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495554177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('CREATE INDEX IDX_42C8495554177093 ON reservation (room_id)');
        $this->addSql('ALTER TABLE room ADD apartment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartment (id)');
        $this->addSql('CREATE INDEX IDX_729F519B176DFE85 ON room (apartment_id)');
        $this->addSql('ALTER TABLE user ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B83297E7 ON user (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495554177093');
        $this->addSql('DROP INDEX IDX_42C8495554177093 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP room_id');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B176DFE85');
        $this->addSql('DROP INDEX IDX_729F519B176DFE85 ON room');
        $this->addSql('ALTER TABLE room DROP apartment_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B83297E7');
        $this->addSql('DROP INDEX UNIQ_8D93D649B83297E7 ON user');
        $this->addSql('ALTER TABLE user DROP reservation_id');
    }
}
