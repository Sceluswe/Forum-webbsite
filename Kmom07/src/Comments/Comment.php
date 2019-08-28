<?php
namespace Anax\Comments;

/**
 * Model for Users.
 *
 * Contains interactions with the database.
 */
class Comment extends \Anax\MVC\CDatabaseModel
{
	/**
	* Set table to store comment in.
	*
	* @param string, table to use in the database.
	*
	* @return string.
	*/
	public function setSource($key)
	{
		$this->session->set('comment_db', strtolower($key));
	}



	/**
	* Get table to store comment in from session.
	*
	* @return string.
	*/
	public function getSource()
	{
		return $this->session->get('comment_db');
	}



	/**
	* Set route to redirect to in session.
	*
	* @param string, route to redirect to.
	*
	* @return string.
	*/
	public function setRedirect($key)
	{
		$this->session->set('redirect', $key);
	}



	/**
	* Get route to redirect to from session.
	*
	* @return string.
	*/
	public function getRedirect()
	{
		return $this->session->get('redirect');
	}



	/**
	* Test function to initialize a database table.
	*
	* @return boolean.
	*/
	public function initializeTable()
	{
		$boolean = false;

		$table = $this->getSource();

		if(!empty($table))
		{
			$this->db->dropTableIfExists($table)->execute();

			$result = $this->db->createTable($table, [
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'name'		=> ['varchar(40)'],
				'email' 	=> ['varchar(80)'],
				'web'		=> ['varchar(80)'],
				'content' 	=> ['varchar(255)'],
				'timestamp' => ['datetime'],
				'ip'		=> ['integer']
			])->execute();

			// Make sure database was successfully created.
			if(isset($result))
			{
				// Build insert statement.
				$this->db->insert(
					$table,
					['name', 'email', 'web', 'content', 'timestamp', 'ip']
				);

				// Get current time.
				$now = gmdate('Y-m-d H:i:s');

				// Insert user 'admin' with the following values:
				$insert1 = $this->db->execute([
					'admin',
					'admin@dbwebb.se',
					'www.google.se',
					'Jag heter Emil Mattsson och drömmen är att bli en spelprogrammerare. Förra året arbetade jag på ett eget spel tillsammans med två vänner. Nu pluggar jag webbprogrammering för att vidga min kunskap.',
					$now,
					$this->request->getServer('REMOTE_ADDR')
				]);

				// Insert user 'doe' with the following values:
				$insert2 = $this->db->execute([
					'doe',
					'doe@dbwebb.se',
					'www.google.se',
					'This is a short test comment.',
					$now,
					$this->request->getServer('REMOTE_ADDR')
				]);

				if(isset($insert1) && isset($insert2))
				{
					$boolean = true;
				}
			}
		}
		return $boolean;
	}



    /**
    * Create the comment table.
    *
    * @return void.
    */
	public function createCommentTable()
	{
		$table = $this->getSource();

		if(!empty($table))
		{
			$this->db->dropTableIfExists($table)->execute();

			$result = $this->db->createTable($table, [
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'name'		=> ['varchar(40)'],
				'email' 	=> ['varchar(80)'],
				'web'		=> ['varchar(80)'],
				'content' 	=> ['varchar(255)'],
				'timestamp' => ['datetime'],
				'ip'		=> ['integer']
			])->execute();
		}
	}
}
