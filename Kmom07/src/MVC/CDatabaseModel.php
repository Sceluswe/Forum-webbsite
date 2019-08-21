<?php
namespace Anax\MVC;

/**
 * Model for Users.
 *
 */
class CDatabaseModel implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;

	/**
	* Save current object/row.
	*
	* @param array $values key/values to save or empty to use object properties.
	*
	* @return boolean true of false if saving went okay.
	*/
	public function save($values = [])
	{
		// If there are any incoming values, fill the object with them.
		$this->setProperties($values);
		$values = $this->getProperties();

		// Check if the object exists already or not.
		if(isset($values['id']))
		{
			return $this->update($values);
		}
		else
		{
			return $this->create($values);
		}
	}



	/**
	* Update.
	*
	* @param array $values to update.
	*
	* @return boolean true or false.
	*/
	public function update($values)
	{
		$keys = array_keys($values);
		$values = array_values($values);

		//remove id from $keys and use it in your WHERE-clause.
		unset($keys['id']);
		$values[] = $this->id;

		$this->db->update(
			$this->getSource(), // Database table.
			$keys, // Table Keys.
			"id = ?" // Where id = ?
		);

		return $this->db->execute($values);
	}



	/**
	* Returns the name of the Class which is also the name of the database table.
    *
    * @return string
	*/
	public function getSource()
	{
		return strtolower(implode('', array_slice(explode('\\', get_class($this)), -1)));
	}



	/**
	* Find and return all.
    *
	* @return array.
	*/
	public function findAll()
	{
		$this->db->select()->from($this->getSource());
		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		return $this->db->fetchAll();
	}



	/**
	* Find single User and return.
    *
	* @return array.
	*/
	public function find($id)
	{
		$this->db->select()
					->from($this->getSource())
					->where("id = ?");

		$this->db->execute([$id]);
		return $this->db->fetchInto($this);
	}



	/**
	* Get object properties.
    *
	* @return array with object properties.
	*/
	public function getProperties()
	{
		$properties = get_object_vars($this);
		unset($properties['di']);
		unset($properties['db']);

		return $properties;
	}



	/**
	* Set object properties.
	*
	* @param array $properties with properties to set.
	*
	* @return void
	*/
	public function setProperties($properties)
	{
		// Update object with incoming values, if any.
		if(!empty($properties))
		{
			foreach($properties as $key => $val)
			{
				$this->$key = $val;
			}
		}
	}



	/**
	* Create User.
	*
	* @param array values with which to initiate User.
	*
	* @return boolean true or false.
	*/
	public function create($values)
	{
		// Turn incoming values into arrays.
		$keys = array_keys($values);
		$values = array_values($values);

		$this->db->insert(
			$this->getSource(),
			$keys
		);

		$res = $this->db->execute($values);

		$this->id = $this->db->lastInsertId();

		return $res;
	}



	/**
	* Delete User.
	*
	* @param integer, id of the User to delete.
	*
	* @return void.
	*/
	public function delete($id = null)
	{
		if(!isset($id))
		{
			$id = $this->id;
		}

		$this->db->delete(
			$this->getSource(), // Database table.
			'id = ?' // Where-clause.
		);

		return $this->db->execute([$id]);
	}



	/**
	* Build a select-query.
	*
	* @param string $columns which columns to select.
	*
	* @return $this
	*/
	public function query($columns = '*')
	{
		$this->db->select($columns)
			->from($this->getSource());

		return $this;
	}



	/**
	* Build the where part.
	*
	* @param string $condition for building the where part of the query.
	*
	* @return $this
	*/
	public function where($condition)
	{
		$this->db->where($condition);

		return $this;
	}



	/**
	* Build the where part.
	*
	* @param string $condition for building the where part of the query.
	*
	* @return $this
	*/
	public function andWhere($condition)
	{
		$this->db->andWhere($condition);

		return $this;
	}



	/**
	* Execute the query built.
	*
	* @param string $query custom query.
	*
	* @return $this
	*/
	public function execute($params = [])
	{
		$this->db->execute($this->db->getSQL(), $params);
		$this->db->setFetchModeClass(__CLASS__);

		return $this->db->fetchAll();
	}



	/**
	* Find row by column name and return.
    *
	* @return array.
	*/
	public function findByColumn($column, $id)
	{
		$this->db->select()
			->from($this->getSource())
			->where("{$column} = ?");

		$this->db->execute([$id]);
		return $this->db->fetchAll();
	}



    /**
    * Build the order by part.
    *
    * @param string $condition for building the where part of the query.
    *
    * @return $this
    */
	public function orderBy($order)
	{
		$this->db->orderBy($order);

		return $this;
	}



    /**
    * Build the group by part.
    *
    * @param string $condition for building the group by part of the query.
    *
    * @return $this
    */
	public function groupBy($condition)
    {
        $this->db->groupBy($condition);

        return $this;
    }



    /**
     * Execute a select-query with arguments and return all resultset.
     *
     * @param string  $query      the SQL query with ?.
     * @param array   $params     array which contains the argument to replace ?.
     *
     * @return array with resultset.
     */
	public function executeFetchAll($query, $params)
	{
        return $this->db->executeFetchAll($query, $params);;
    }
}
