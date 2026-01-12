<?php

namespace App\Migrations;

use App\config\Database;
use Faker\Factory as FakerFactory;
use mysqli;

class MigrationsTest
{
    private mysqli $db;

    public function __construct()
    {
        // Instantiate Database inside the class
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function runMigrationAndSeed(int $userCount = 10): bool
    {
        echo "--- Starting migration and seeding ---\n";

        if (!$this->createUsersTable()) {
            echo "Migration failed. ❌\n";
            return false;
        }

        if (!$this->seedUsers($userCount)) {
            echo "Seeding failed. ❌\n";
            return false;
        }

        echo "--- Migration and seeding completed successfully. ✅ ---\n";
        return true;
    }

    private function createUsersTable(): bool
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'users' created or already exists. ✅\n";
            return true;
        } else {
            echo "Error creating 'users' table: " . $this->db->error . " ❌\n";
            return false;
        }
    }

    private function seedUsers(int $count): bool
    {
        $faker = FakerFactory::create();

        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo "Prepare failed: " . $this->db->error . " ❌\n";
            return false;
        }

        for ($i = 0; $i < $count; $i++) {
            $name = $faker->name();
            $email = $faker->unique()->safeEmail();
            $password = password_hash('password123', PASSWORD_DEFAULT);

            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                echo "Inserted user: $name ($email) ✅\n";
            } else {
                echo "Error inserting user $email: " . $stmt->error . " ❌\n";
            }
        }

        $stmt->close();
        echo "\nSuccessfully seeded $count users. ✨\n";
        return true;
    }

    private function dropUsersTable(): bool
    {
        $sql = "DROP TABLE IF EXISTS users;";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'users' dropped. ✅\n";
            return true;
        } else {
            echo "Error dropping 'users' table: " . $this->db->error . " ❌\n";
            return false;
        }
    }
}
