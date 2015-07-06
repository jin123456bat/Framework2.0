<?php
class config implements ArrayAccess
{
	/**
	 * @param $dirname 配置保存位置
	 * 永久保存当前配置
	 */
	function save($dirname)
	{
		$classname = get_class($this);
		$data = "<?php\n";
		$data .= "class ".$classname." extends config\n";
		$data .= "{\n";
		foreach ($this as $key=>$value)
		{
			$data .= "\tpublic $".$key." = '".$value."';\n";
		}
		$data .= "}\n";
		$data .= "?>";
		file_put_contents(rtrim($dirname,'/').'/'.$classname.'.php', $data,0);
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