<?php

class LoginModel {

    /** ID number of the user (userid)
     * @var int $UserId
     */
    public $UserId = -1;

    /** The screen name for the user (username)
     * @var string $Username
     */
    public $Username = "";

    /** The salted and hashed password for the user (password)
     * @var string $Password
     */
    public $Password = "";

    /** The session key to validate a logged in user (sessionstr)
     * @var string $SessionString
     */
    public $SessionString = NULL;
}
