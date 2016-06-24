<?php
namespace Anax\Users;

/**
 * Model for Users.
 * 
 * Contains interactions with the database.
 */
class User extends \Anax\MVC\CDatabaseModel
{
	/*
	* Function that checks if a user is an admin based on the provided $condition.
	*
	* @param $user, string, A string with the current users name.
	* @param $condition, array, an array of strings containing the allowed users.
	*
	* @return boolean, depending on result.
	*/
	public function isUserAdmin($currentUser, $condition)
	{
		if(is_array($condition))
		{
			$result = false;
			if($this->isUserLoggedIn())
			{
				if(in_array($currentUser, $condition))
				{
					$result = true;
				}
			}
		}
		else
		{
			die("Error: $condition needs to be array in UsersController::isUserAdmin()");
		}
		
		return $result;
	}
	
	/*
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
			{	// Set return variable $valid to true if that is the case.
				$valid = true;
			}
		}
		
		return $valid;
	}
	
	/*
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
			die("Error: User was not set in User::loginUser()");
		}
	}
	
	/*
	* Function that unsets the logged in user in session.
	*
	*/
	public function logoutUser()
	{
		if(!empty($_SESSION['currentUser']))
		{
			unset($_SESSION['currentUser']);
		}
		else
		{
			die("Error: User was not set in session and could not be logged out.");
		}
	}
	
	/*
	* Returns the current user from session, if one exists.
	*
	* @return string, returns the currently logged in user.
	*/
	public function currentUser()
	{
		$result = "";
		if(!empty($_SESSION['currentUser']))
		{
			$result = $_SESSION['currentUser'];
		}
		
		return $result;
	}
	
	/*
	* Checks if a user is logged in or not.
	*
	* @return boolean, returns if the user is logged in or not.
	*/
	public function isUserLoggedIn()
	{
		$result = false;
		// Check if a user is logged in.
		if(!empty($_SESSION['currentUser']))
		{
			$result = true;
		}
		
		return $result;
	}
	
	
	/*
	* Test function to initialize a database table.
	*
	* @return boolean.
	*/
	public function initializeTable($table)
	{
		$boolean = false;
		
		if(is_string($table))
		{
			$this->db->dropTableIfExists($table)->execute();
		
			$result = $this->db->createTable($table, 
			[
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'acronym' 	=> ['varchar(20)', 'unique', 'not null'],
				'email' 	=> ['varchar(80)'],
				'name' 		=> ['varchar(80)'],
				'password' 	=> ['varchar(255)'],
				'created' 	=> ['datetime'],
				'updated' 	=> ['datetime'],
				'deleted' 	=> ['datetime'],
				'active' 	=> ['datetime'],
			])->execute();
			
			// Make sure database was successfully created.
			if(isset($result))
			{
				// Build insert statement.
				$this->db->insert(
					$table,
					['acronym', 'email', 'name', 'password', 'created', 'active', 'answers', 'questions', 'comments']
				);
				// Get current time.
				$now = gmdate('Y-m-d H:i:s');
				// Insert user 'admin' with the following values:
				$insert1 = $this->db->execute([
					'admin',
					'admin@dbwebb.se',
					'Administrator',
					md5('admin'),
					$now,
					$now,
				]);
				// Insert user 'doe' with the following values:
				$insert2 = $this->db->execute([
					'doe',
					'doe@dbwebb.se',
					'John/Jane Doe',
					md5('doe'),
					$now,
					$now,
					0,
					0,
					0
				]);
				
				if(isset($insert1) && isset($insert2))
				{
					$boolean = true;
				}
			}
		}
		return $boolean;
	}
}