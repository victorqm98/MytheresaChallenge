<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateProductTable extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE product (
                id VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                sku VARCHAR(255) NOT NULL,
                category_id VARCHAR(255) NOT NULL,
                price_id VARCHAR(255) NOT NULL,
                PRIMARY KEY(id),
                CONSTRAINT FK_category FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
                CONSTRAINT FK_price FOREIGN KEY (price_id) REFERENCES price(id) ON DELETE CASCADE
            )
        ');
        $this->addSql('CREATE INDEX category_sku_index ON product (category_id, sku)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
