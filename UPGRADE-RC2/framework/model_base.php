<?php

/**
 * @package WMBIND 
 */


/**
 * Model_Base 
 * 
 * @abstract
 * @package WMBIND
 * @author Espen Volden <voldern@hoeggen.net> 
 */
abstract class Model_Base
{
	/**
	 * $registry 
	 * 
	 * Store the registry object
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $registry;

	/**
	 * $table 
	 *
	 * Name of the database table
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $table;

	/**
	 * __construct 
	 * 
	 * @param mixed $registry 
	 * @access protected
	 * @return void
	 */
	function __construct($registry)
	{
		$this->registry = $registry;
	}

	function query($query, $variables = array(), $return = false)
	{
		// TODO
		// Check if the user is doing a select and return data 
        // depending on that instead of using $return
		$sth = $this->registry->db->prepare($query);
		
		if (!$sth->execute($variables))
			return false;

		if ($return)
			return $sth->fetchAll(PDO::FETCH_ASSOC);

		return true;
	}

	function find($query, $variables = array(), $fields = array())
	{
		$select = NULL;
		if (count($fields) > 0) {
			foreach ($fields as $field)
				$select .= $field . ", ";

			$select = substr_replace($select, "", -2);
		} else {
			$select = '*';
        }
		
		if (is_numeric($query))
			$query = "SELECT {$select} FROM {$this->table} WHERE id = '$query'";
		else
			$query = "SELECT {$select} FROM {$this->table} WHERE $query";
        
		$sth = $this->registry->db->prepare($query);

		$sth->execute($variables);

		return $sth->fetch(PDO::FETCH_ASSOC);
	}


	function findValue($query, $variables = array(), $field)
	{
		if (!is_string($field))
			throw new Exception('Field must be of type String');
	
		if (is_numeric($query))
			$query = "SELECT {$field} FROM {$this->table} WHERE id = '$query'";
		else
			$query = "SELECT {$field} FROM {$this->table} WHERE $query";

		$sth = $this->registry->db->prepare($query);

		$sth->execute($variables);

		$result = $sth->fetch(PDO::FETCH_ASSOC);
		
		if (!$result)
			return false;
		else
			return $result[$field];
	}

	function findAll($query, $variables = array(), $fields = array(), $sort = '1')
	{
		$select = null;

		if (count($fields) > 0) {
			foreach ($fields as $field)
				$select .= $field . ", ";

			$select = substr_replace($select, "", -2);
		} else {
			$select = '*';
        }

		if (is_numeric($query))
			$query = "SELECT {$select} FROM {$this->table} ".
                "WHERE id = '$query' ORDER BY $sort";
		else
			$query = "SELECT {$select} FROM {$this->table} WHERE $query ORDER BY $sort";

		$sth = $this->registry->db->prepare($query);

		$sth->execute($variables);

		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	function delete($query, $variables = array(), $table = NULL)
	{
		if ($table == NULL)
			$table = $this->table;

		if (is_numeric($query))
			$query = "DELETE FROM {$table} WHERE id = '$query'";
		else
			$query = "DELETE FROM {$table} WHERE $query";

		$sth = $this->registry->db->prepare($query);

		if (!$sth->execute($variables))
			return false;
		else
			return true;
	}

	function save($data, $table = NULL)
	{
		$fields = $values = '';

		if ($table == NULL)
			$table = $this->table;

		foreach ($data as $key => $value)
		{
			if ($key != 'id') {
                // Are we updating a row?
                if (!empty($data['id']) && is_numeric($data['id'])) {
                    $fields .= "{$key} = :{$key}, ";
                } else {
                    $fields .= "{$key}, ";
                    $values .= ":{$key}, ";
                }
            }
		}

		$fields = substr_replace($fields, "", -2);
        if ($values != '')
            $values = substr_replace($values, "", -2);

		if ($fields == NULL)
			return false;

		if (!empty($data['id']) && is_numeric($data['id']))
			$query = "UPDATE {$table} SET {$fields} WHERE id = :id"; 
		else	
			$query = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
	
		$sth = $this->registry->db->prepare($query);
		
		if (!$sth->execute($data))
			return false;
        
		return true;
	}

	function describe()
	{
		$query = "DESCRIBE {$this->table}";
		
		$sth = $this->registry->db->prepare($query);
		$sth->execute();

		return $sth->fetchAll();
	}

	function unique($query, $variables = array())
	{
		if (is_int($query))
			$query = "SELECT count(*) FROM {$this->table} WHERE id = '$query'";
		else
			$query = "SELECT count(*) FROM {$this->table} WHERE $query";

		$sth = $this->registry->db->prepare($query);

		if (count($variables) > 0)
			$sth->execute($variables);
		else
			$sth->execute();

		if ($sth->fetchColumn() == 0)
			return true;
		else
			return false;
	}

}

?>
