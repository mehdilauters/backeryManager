<?php
	class Upgrader {
		private $upgPath;
		private $appPath;
		
		public function __construct($_upgPath, $_appPath)
		{
			$this->upgPath = $_upgPath;
			$this->appPath = $_appPath;
		}
	
		public function beforeUpgrade()
		{
			return true;
		}
		
		public function beforeConfigUpgrade()
		{
			return true;
		}

		public function afterConfigUpgrade()
		{
			return true;
		}
		
		
		public function beforeDatabaseUpgrade()
		{
			return true;
		}

		public function afterDatabaseUpgrade()
		{
			return true;
		}

		public function afterUpgrade()
		{
			return true;
		}
		
	}

?>