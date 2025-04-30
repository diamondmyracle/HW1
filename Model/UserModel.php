<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT * FROM users ORDER BY username ASC LIMIT ?", ["i", $limit]);
    }

    public function usernameInDatabase($username)
    {
        return $this->select("SELECT username FROM users WHERE username = ?", ["s", $username]) ;
    }

    public function insertUser($username, $hashedPassword)
    {
        return $this->insert("INSERT INTO users (username, password) VALUES (?, ?)", ["ss", $username, $hashedPassword]) ;
    }

    public function getUserInfo($username)
    {
        return $this->select("SELECT * FROM users WHERE username = ?", ["s", $username]) ;
    }

    public function updateUser($data)
    {
    return $this->update(
        "UPDATE users SET user_descript = ? WHERE username = ?",
        ["ss", $data['user_descript'], $data['user']]
    );
    }

    public function getUserDescription($username) {
        return $this->select("SELECT user_descript FROM users WHERE username = ?", ["s", $username])[0];
    }
}
?>
