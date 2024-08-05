<?php

namespace App\Models;

use Database\DatabaseConnection;
use Database\QueryBuilder;

class UserModel
{
    private $db;
    private $dbConnection;
    private $table = 'users';
    private $roleTable = 'user_roles';

    public function __construct()
    {
        $this->dbConnection = DatabaseConnection::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $this->db = new QueryBuilder($this->dbConnection->getConnection());
        $this->createUsersTable();
        $this->createUserRolesTable();
    }

    private function createUsersTable()
    {
        $schema = "
        CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(50),
            last_name VARCHAR(50),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->dbConnection->createTable($this->table, $schema);
    }

    private function createUserRolesTable()
    {
        $schema = "
        CREATE TABLE IF NOT EXISTS {$this->roleTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            role VARCHAR(50) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES {$this->table}(id) ON DELETE CASCADE,
            UNIQUE KEY unique_user_role (user_id, role)
        )";

        $this->dbConnection->createTable($this->roleTable, $schema);
    }

    public function createUser($data, $roles = [])
    {
        try {
            $userId = $this->db->from('users')->insert($data);
            
            foreach ($roles as $role) {
                $this->addUserRole($userId, $role);
            }
            
            return $userId;
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') {  // Integrity constraint violation
                echo "Error: User with username '{$data->username}' already exists.\n";
                return false;
            }
            throw $e;
        }
    }

    public function addUserRole($userId, $role)
    {
        $this->db->from($this->roleTable);
        return $this->db->insert(['user_id' => $userId, 'role' => $role]);
    }

    public function removeUserRole($userId, $role)
    {
        $this->db->from($this->roleTable);
        return $this->db->where('user_id','=', $userId)->where('role','=', $role)->delete();
    }

    public function getUserRoles($userId)
    {
        return $this->db->from('user_roles')
                        ->where('user_id','=', $userId)
                        ->all();
    }

    public function getUsersByRole($role)
    {
        return $this->db->from($this->table)
                        ->join($this->roleTable, "{$this->table}.id = {$this->roleTable}.user_id")
                        ->where("{$this->roleTable}.role", '=', $role)
                        ->all();
    }

    public function hasRole($userId, $role)
    {
        $this->db->from($this->roleTable);
        $result = $this->db->where('user_id','=', $userId)->where('role','=', $role)->get();
        return !empty($result);
    }

    // Existing methods...

    public function getUserWithRoles($id)
    {
        $user = $this->getUserById($id);
        if ($user) {
            $user->roles = $this->getUserRoles($id);
        }
        return $user;
    }

    // ... (other existing methods)

    public function getAllUsers()
    {
        return $this->db->from($this->table)->all();
    }

    public function getUserById($id)
    {

        $result = $this->db->from('users')
                        ->where('id','=', $id)
                        ->get();
                        
        return $result;
    }


    public function updateUser($id, $data)
    {
        return $this->db->from('users')
                        ->where('id','=', $id)
                        ->update($data);
    }

    public function deleteUser($id)
    {
        return $this->db->from($this->table)->where('id', '=', $id)->delete();
    }

    public function getUserByEmail($email)
    {
        return $this->db->from($this->table)->where('email','=', $email)->get();
    }

    public function getUserByUsername($username)
    {
        return $this->db->from($this->table)->where('username','=', $username)->get();
    }

    public function getUsersWithPagination($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        return $this->db->from($this->table)
                        ->orderBy('id', 'DESC')
                        ->limit($perPage)
                        ->offset($offset)
                        ->all();
    }

    public function countUsers()
    {
        return count($this->db->from($this->table)->all());
    }


    public function searchUsers($term)
    {
        return $this->db->reset()
            ->from($this->table)
            ->where('username', 'LIKE', "%$term%")
            ->orWhere('email', 'LIKE', "%$term%")
            ->orWhere('first_name', 'LIKE', "%$term%")
            ->orWhere('last_name', 'LIKE', "%$term%")
            ->all();
    }



    public function activateUser($id)
    {
        return $this->updateUser($id, ['is_active' => true]);
    }

    public function deactivateUser($id)
    {
        return $this->updateUser($id, ['is_active' => 0]);
    }

    public function getActiveUsers()
    {
        return $this->db->from($this->table)->where('is_active', '=', true)->all();
    }
}