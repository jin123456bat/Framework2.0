<?php
class config implements ArrayAccess
{

	/**
	 * 永久保存当前配置
	 */
	function save()
	{
	}

	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}

	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}

	public function offsetGet($offset)
	{
		return isset($this->$offset) ? $this->$offset : null;
	}
}
?>