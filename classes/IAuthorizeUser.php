<?php
// classes/IAuthorizeUser.php
interface IAuthorizeUser {
    public function auth($login, $password);
}
