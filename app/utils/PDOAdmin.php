<?php
namespace Strong\Provider;

/**
 * Redefinition of PDO Provider for admin role in user details
 * @see Strong\Provider\PDO
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class PDOAdmin extends \Strong\Provider
{
    /**
     * @var array
     */
    protected $settings = array(
        'dsn' => '',
        'dbuser' => null,
        'dbpass' => null,
    );

    /**
     * Initialize the PDO connection and merge user
     * config with defaults.
     *
     * > Changes for depenencies injection! (PDO Connection)
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
        $this->config = array_merge($this->settings, $this->config);

        if (!isset($this->config['pdo']) || !($this->config['pdo'] instanceof \PDO))
            throw new \InvalidArgumentException('You must add valid pdo connection object');

        $this->pdo = $this->config['pdo'];
    }

    /**
     * User login check based on provider
     *
     * @return boolean
     */
    public function loggedIn()
    {
        return (isset($_SESSION['auth_user']) && !empty($_SESSION['auth_user']));
    }

    /**
     * To authenticate user based on username or email and password
     *
     * @param string $usernameOrEmail
     * @param string $password
     * @param bool   $remember  For compatibility (no-op).
     * @return boolean
     */
    public function login($usernameOrEmail, $password, $remember = false)
    {
        if(! is_object($usernameOrEmail)) {
            $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':username', $usernameOrEmail);
            $stmt->bindParam(':email', $usernameOrEmail);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_OBJ);
        }

        if(is_object($user) && ($user->email === $usernameOrEmail || $user->username === $usernameOrEmail) && $user->password === $password) {
            return $this->completeLogin($user);
        }

        return false;
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    /**
     * Login and store user details in Session
     * Added 'isadmin' to the userinfo structure. Relies on 'isadmin' being present and set
     * in the user table
     *
     * @param object $user
     * @return boolean
     */
    protected function completeLogin($user)
    {
        $userInfo = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        	'isadmin' => $user->isadmin,
        	'isgroup' => $user->isgroup,
        	'isdemo' => $user->isdemo,
        	'active' => $user->active,
            'logged_in' => true
        );

        return parent::completeLogin($userInfo);
    }
}
