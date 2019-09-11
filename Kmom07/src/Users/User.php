<?php
namespace Anax\Users;

/**
* Model for Users.
*
* Contains interactions with the database.
*/
class User extends \Anax\MVC\CDatabaseModel
{



	/**
	* Function that checks if a user is an admin based on the provided $condition.
	*
	* @param $user, string, A string with the current users name.
	* @param $condition, array, an array of strings containing the allowed users.
	*
	* @return boolean, depending on result.
	*/
	public function isUserAdmin($currentUser, array $condition)
	{
        return ($this->isUserLoggedIn()) ? in_array($currentUser, $condition) : false;
	}



	/**
	* Function that validates a user based on acronym and password.
	*
	* $return boolean, false or true depending on result.
	*/
	public function validateUser($acronym, $password)
	{
		$valid = false;

        if(!empty($acronym) && !empty($password))
		{
			// Prepare SQL statement.
			$this->query('acronym')
				->where("acronym= ?")
				->andWhere("password= ?");

			// Execute SQL.
			$result = $this->execute([$acronym, $password]);

			// Check if a user matching the provided data does exist.
			if(!empty($result))
                $valid = true;
		}

		return $valid;
	}



	/**
	* Function that saves the logged in user in session.
	*
	* @param $user, the user to be logged in.
	*/
	public function loginUser($user)
	{
        if(!empty($user))
        {
            $_SESSION['currentUser'] = $user;
        }
        else
        {
            die("User.loginUser() error: User was not set.");
        }
	}



	/**
	* Function that unsets the logged in user in session.
	*
	*/
	public function logoutUser()
	{
        unset($_SESSION['currentUser']);
	}



	/**
	* Returns the current user from session, if one exists.
	*
	* @return string, returns the currently logged in user.
	*/
	public function currentUser()
	{
        return (!empty($_SESSION['currentUser'])) ? $_SESSION['currentUser'] : "";
	}



	/**
	* Checks if a user is logged in or not.
	*
	* @return boolean, returns if the user is logged in or not.
	*/
	public function isUserLoggedIn()
	{
        return (!empty($_SESSION['currentUser'])) ? true : false;
	}



    public function getTopRatedUsers()
    {
        // Get the highest rated users.
        return $this->query()->orderBy('score DESC LIMIT 6')->execute();
    }



	/**
	* Test function to initialize a database table.
	*
	* @return boolean.
	*/
	public function initializeTable(string $table)
	{
		$boolean = false;

        if(!empty($table))
        {
    		$this->db->dropTableIfExists($table)->execute();

    		$result = $this->db->createTable($table, [
    			'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
    			'acronym' 	=> ['varchar(20)', 'unique', 'not null'],
    			'email' 	=> ['varchar(80)'],
    			'name' 		=> ['varchar(80)'],
    			'password' 	=> ['varchar(255)', 'not null'],
    			'created' 	=> ['datetime'],
    			'updated' 	=> ['datetime'],
    			'deleted' 	=> ['datetime'],
    			'active' 	=> ['datetime'],
                'score'     => ['integer default 0', 'not null']
    		])->execute();

    		// Make sure database was successfully created.
    		if(isset($result))
    		{
    			// Build insert statement.
    			$this->db->insert($table, [
                    'acronym',
                    'email',
                    'name',
                    'password',
                    'created',
                    'active'
                ]);

    			// Get current time.
    			$now = gmdate('Y-m-d H:i:s');

    			// Insert user 'admin' with the following values:
    			$insert1 = $this->db->execute([
    				'admin',
    				'admin@dbwebb.se',
    				'Administrator',
    				md5('admin'),
    				$now,
    				$now
    			]);

    			// Insert user 'doe' with the following values:
    			$insert2 = $this->db->execute([
    				'doe',
    				'doe@dbwebb.se',
    				'John/Jane Doe',
    				md5('doe'),
    				$now,
    				$now
    			]);

    			if(isset($insert1) && isset($insert2))
    				$boolean = true;
    		}
        }

        return $boolean;
	}
}
