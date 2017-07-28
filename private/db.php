<?php
class Database
{
	private $conn;

	public function __construct()
	{
		$config = array(
			'username' => 'root',
			'password' => '',
			'database' => 'cv',
			'host_url' => 'localhost'
		);

		try {
			$conn = new \PDO('mysql:host=' . $config['host_url'] . ';dbname=' . $config['database'],
							$config['username'],
							$config['password']);

			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			$this->conn = $conn;
		} catch(Exception $e) {
			die('Problem connecting to the db.');
		}
	}

	public function query($query, $bindings)
	{
		$stmt = $this->conn->prepare($query);
		$stmt->execute($bindings);

		return ($stmt->rowCount() > 0) ? $stmt : false;
	}

	/**
	 * Updates the video file for logged in user
	 *
	 * @param video Reference to the video
	 * @return true if successful, false otherwise
	 */
	public function update_video($video)
	{
		$username = $_SESSION['username'];
		try {
			$result = $this->query(
					"UPDATE USERS SET
						video = :video
					WHERE
						username = :un",
				array(
						'video' => file_get_contents($video),
						'un' => $username)
				);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Get the password hash for a user
	 *
	 * @param username The username of the user
	 * @return The hash if successful, otherwise null
	 */
	public function get_password($username)
	{
		try {
			$query = $this->query(
					'SELECT
						password
					FROM
						USERS
					WHERE
						username = :un
					LIMIT 1',
				array('un' => $username)
			);

			if( $query )
				return $query->fetch()[0];
			else
				return null;
		} catch(Exception $e) {
			return null;
		}
	}

	/**
	 * Adds a new user to the database
	 *
	 * @param ... User info
	 * @return true if new user is added successfully, false otherwise
	 */
	public function add_user($username, $password, $first_name, $last_name, $phone_nbr, $address, $zip_code, $city, $linkedin)
	{
		try {
			$query = $this->query(
					'INSERT INTO
						USERS(
							username,
							password,
							role,
							first_name,
							last_name,
							phone_number,
							address,
							zip_code,
							city,
							linkedin)
						VALUES(
							:un,
							:pw,
							:role,
							:fn,
							:ln,
							:pnbr,
							:address,
							:zc,
							:city,
							:linkedin
				)',
				array(	'un' => $username,
						'pw' => password_hash($password, PASSWORD_BCRYPT),
						'role' => "user",
						'fn' => $first_name,
						'ln' => $last_name,
						'pnbr' => $phone_nbr,
						'address' => $address,
						'zc' => $zip_code,
						'city' => $city,
						'linkedin' => $linkedin )
			);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Updates the info of a user
	 *
	 * @param username The username of the user whose info we want to change
	 * @param ... new user info
	 * @return true if successful, false otherwise
	 */
	public function update_user($username, $first_name, $last_name, $phone_number, $address, $zip_code, $city, $linkedin)
	{
		try {
			$query = $this->query(
					'UPDATE USERS SET
						first_name = :fn,
						last_name = :ln,
						phone_number = :pnbr,
						address = :address,
						zip_code = :zc,
						city = :city,
						linkedin = :linkedin
					WHERE
						username = :un',
				array('un' => $username,
						'fn' => $first_name,
						'ln' => $last_name,
						'pnbr' => $phone_number,
						'address' => $address,
						'zc' => $zip_code,
						'city' => $city,
						'linkedin' => $linkedin )
			);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Gives the role (user/admin) of the user with the given username
	 *
	 * @param username The username of the user
	 * @return The role
	 */
	public function get_user_role($username)
	{
		try {
			$query = $this->query(
					'SELECT
						role
					FROM
						USERS
					WHERE
						username = :un',
				array('un' => $username)
			);
			if( $query )
				return $query->fetch()[0];
			else
				return null;
		} catch(Exception $e) {
			return null;
		}
	}

	/**
	 * @param username Username of the user whose info you want
	 * @return An assoc list with user info if successful, null otherwise
	 */
	public function get_user_info($username)
	{
		try {
			$query = $this->query(
					'SELECT
						username, first_name, last_name, role, id, phone_number, address, zip_code, city, linkedin
					FROM
						USERS
					WHERE
						username = :un',
				array('un' => $username)
			);
			if( $query )
				return $query->fetch();
			else
				return null;
		} catch(Exception $e) {
			return null;
		}
	}

	/**
	 * @param user_id Id of the user
	 * @return List of skills associated with the user
	 */
	public function get_user_skills($user_id)
	{
		try {
			$query = $this->query(
				'SELECT s.name, us.skill_level, s.id
				FROM SKILLS s
					INNER JOIN USERS_SKILLS us ON us.skill_id = s.id
					INNER JOIN USERS u ON us.user_id = u.id
				WHERE
					u.id = :uid
				ORDER BY s.name',
				array('uid' => $user_id)
				);

			if( $query )
				return $query->fetchAll(PDO::FETCH_ASSOC);
			else
				return array();
		} catch(Exception $e) {
			return array();
		}
	}

	/**
	 * @return All skills currently in the database
	 */
	public function get_list_of_skills()
	{
		try {
			$query = $this->query(
					'SELECT
						id, name
					FROM
						SKILLS
					ORDER BY name', array());

			if( $query )
				return $query->fetchAll();
			else
				return array();
		} catch(Exception $e) {
			return array();
		}
	}

	/**
	 * Adds a new skill for a user, or updates the skill level if skill already exists
	 *
	 * @param username Username of user
	 * @param skill Skill id of skill
	 * @param skill_level Skill level
	 * @return True if successful, false otherwise
	 */
	public function add_or_update_skill($username, $skill, $skill_level)
	{
		try {
			$query = $this->query(
					'INSERT INTO
						USERS_SKILLS(
							user_id,
							skill_id,
							skill_level)
						VALUES(
							(SELECT id FROM USERS WHERE username = :un LIMIT 1),
							:sid,
							:sl)
					ON DUPLICATE KEY UPDATE skill_level = :sl',
				array('un'=>$username,
						'sid'=>$skill,
						'sl'=>$skill_level)
				);
			if( $query )
				return true;
			else
				return false;
		} catch(Exception $e) {
			return false;
		}

	}

	/**
	 * Searches the database for users matching the supplied list of skills
	 * and skill levels
	 *
	 * @param skills Array of arrays [ [skill_id, skill_level], ...]
	 * @return List of users matching the skills
	 */
	public function search($skills)
	{
		if( !$skills )
			// No arg supplied
			return array();

		$sql = 'SELECT username FROM USERS WHERE id IN ';
		$sql_template = '(SELECT u.id
				FROM USERS_SKILLS us
					INNER JOIN USERS u ON u.id = us.user_id
					INNER JOIN SKILLS s ON s.id = us.skill_id
				WHERE ';
		$bindings = [];

		// For every skill...
		while($skills) {
			$skill = array_shift($skills);

			$tmp = $sql_template . ' s.id = ? AND us.skill_level >= ? )';
			$bindings[] = $skill[0];
			$bindings[] = $skill[1];

			$sql .= $tmp;
			if($skills)
				$sql .= ' AND id IN ';
		}

		try {
			$query = $this->query( $sql, $bindings);
			if( $query )
				return $query;
			else
				return array();
		} catch(Exception $e) {
			return array();
		}

	}

	/**
	 * @param username The username of the user whose video is wanted
	 * @return The video for supplied user
	 */
	public function get_video($username)
	{
		try {
			$query = $this->query(
				'SELECT
					video
				FROM
					USERS
				WHERE
					username = :un',
				array('un' => $username)
			);
			if( $query )
				return $query->fetch()[0];
			else
				return null;
		} catch(Exception $e) {
			return null;
		}
	}

	/**
	 * Adds a new skill to the database
	 *
	 * @param skill_name The name of the new skill
	 * @return The id of the newly added skill or false
	 */
	public function add_skill($skill_name)
	{
		try {
			$query = $this->query(
				'INSERT INTO SKILLS(name) VALUES(:skill)',
				array('skill' => $skill_name)
			);
			if( $query )
				return $this->conn->lastInsertId();
			else
				return false;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Remove a skill from the database
	 *
	 * @param skill_id
	 * @return true if successful, false otherwise
	 */
	public function remove_skill($skill_id)
	{
		try {
			$query = $this->query(
				'DELETE
				FROM
					SKILLS
				WHERE
					id = :skill',
				array('skill' => $skill_id)
			);
			if( $query )
				return true;
			else
				return false;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Remove a skill for the currently logged in user
	 *
	 * @param skill_id Id of the skill
	 * @return true if successful, false otherwise
	 */
	public function remove_user_skill($skill_id)
	{
		try {
			$query = $this->query(
				'DELETE
				FROM
					USERS_SKILLS
				WHERE
					skill_id = :skill AND
					user_id = (SELECT id FROM USERS WHERE username = :un LIMIT 1)',
				array('skill' => $skill_id,
							'un' => $_SESSION['username'])
			);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Updates the password for a user
	 *
	 * @param username Username of the user
	 * @param new_password The new password
	 * @return true if successful, false otherwise
	 */
	public function update_password( $username, $new_password )
	{
		try {
			$query = $this->query(
				'UPDATE USERS SET
					password = :pw
				WHERE
					username = :un',
				array(	'pw' => password_hash($new_password, PASSWORD_BCRYPT),
						'un'=> $username )
			);
			if( $query )
				return true;
			else
				return false;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Deletes the account of a user
	 *
	 * @param username The username of a user
	 * @return true if successful, false otherwise
	 */
	public function delete_account( $username )
	{
		try {
			$query = $this->query(
				'DELETE
				FROM
					USERS
				WHERE
					username = :un',
				array( 'un' => $username )
			);
			if( $query )
				return true;
			else
				return false;
		} catch(Exception $e) {
			return false;
		}

	}

	/**
	 * Sets a recovery code for a user who has forgotten the password
	 *
	 * @param username The username of a user
	 * @return The code if successful, false if username doesn't exist
	 */
	public function forgot_password( $username )
	{
		$hash = md5($username . time());
		try {
			$query = $this->query(
					"UPDATE USERS SET
						recover_password = :rc
					WHERE
						username = :un",
					array(
						'rc' => $hash,
						'un' => $username)
				);
			if( $query ) {
				return $hash;
			}
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Finds the username of a user that the reset code belongs to
	 *
	 * @param hash A reset code
	 * @return A username if successful, false if none are found
	 */
	public function get_username_from_hash($hash)
	{
		try {
			$query = $this->query(
				"SELECT
					username
				FROM
					USERS
				WHERE
					recover_password = :rc
				LIMIT 1",
				array('rc' => $hash)
				);
			if($query) {
				return $query->fetch()[0];
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}

	}

	/**
	 * Removes the reset code from a user
	 *
	 * @param username Username of the user
	 * @return true if successful, false otherwise
	 */
	public function reset_forgot_password_hash($username)
	{
		try {
			$query = $this->query(
					"UPDATE USERS SET
						recover_password = ''
					WHERE
						username = :un",
					array('un' => $username)
				);
			if( $query ) {
				return true;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Create a new random password for a user
	 *
	 * @param username Username of a user
	 * @return A new password if successful, null otherwise
	 */
	public function create_new_random_password_for( $username )
	{
		$password = substr(md5(time()), 0, 15);
		try {
			$query = $this->query(
					"UPDATE USERS SET
						password = :pw
					WHERE
						username = :un",
					array(	'pw' => password_hash($password, PASSWORD_BCRYPT),
							'un' => $username)
			);
			if($query) {
				return $password;
			} else {
				return null;
			}
		} catch(Exception $e) {
			return null;
		}
	}
}
