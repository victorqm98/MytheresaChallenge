<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateDiscountTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE discount (
                id VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                applies_to VARCHAR(255) NOT NULL,
                percentage INT NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE discount');
    }
}
