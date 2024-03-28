<?php

class User
{
    private $conn;
    private $table_name = "users";
    public $id;
    public $email;
    public $password;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {

        // Запрос для добавления нового пользователя в БД
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    email = :email,
                    password = :password";

        // Подготовка запроса
        $stmt = $this->conn->prepare($query);

        // Инъекция
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Привязывание значения
        $stmt->bindParam(":email", $this->email);

        // Хеширование пароля перед сохранением в базу данных
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Проверка, существует ли электронная почта в нашей базе данных
    function emailExists() {

        // Запрос, чтобы проверить, существует ли электронная почта
        $query = "SELECT id, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // Подготовка запроса
        $stmt = $this->conn->prepare($query);

        // Инъекция
        $this->email=htmlspecialchars(strip_tags($this->email));

        // Привязка значения e-mail
        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        // Если электронная почта существует,
        // Присвоение значения свойствам объекта для доступа и использования для php сессий
        if ($num > 0) {

            // Получение значения
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row["id"];
            $this->password = $row["password"];

            return true;
        }

        return false;
    }
}