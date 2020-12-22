<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201222125831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_recipe (ingredient_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_36F27176933FE08C (ingredient_id), INDEX IDX_36F2717659D8A214 (recipe_id), PRIMARY KEY(ingredient_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, recipe_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, symbol VARCHAR(255) NOT NULL, INDEX IDX_9FF31636933FE08C (ingredient_id), INDEX IDX_9FF3163659D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, image_file VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, creted_at DATETIME NOT NULL, steps LONGTEXT NOT NULL, updated_at DATETIME DEFAULT NULL, prep_time INT NOT NULL, cook_time INT NOT NULL, servings INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F27176933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F2717659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF3163659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F27176933FE08C');
        $this->addSql('ALTER TABLE quantity DROP FOREIGN KEY FK_9FF31636933FE08C');
        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F2717659D8A214');
        $this->addSql('ALTER TABLE quantity DROP FOREIGN KEY FK_9FF3163659D8A214');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_recipe');
        $this->addSql('DROP TABLE quantity');
        $this->addSql('DROP TABLE recipe');
    }
}
