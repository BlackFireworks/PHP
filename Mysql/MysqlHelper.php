<!DOCTYPE html>
<html>

<body>
<h1>mysql</h1>

<?php
	//
	//						-------------------------------------------------
	//						|					Mysql Class					|
	//						|												|
	//						|					2016-10-18					|
	//						-------------------------------------------------
	
	class mysql_helper{
		var $servername = "localhost", 	//服务器地址
			$username = "root",			//数据库帐号
			$password = "ging9597";		//数据库密码
		
		var $sql,						//sql语句
			$conn;						//连接数据库后返回的对象
		
		//连接到数据库
		function sql_connect (){
			// 连接到数据库
			$this->conn = new mysqli($this->servername, $this->username, $this->password);
			// 检测连接
			if ($this->conn -> connect_error) {
				die("连接失败: " . $this->conn -> connect_error);
			}
			echo 'mysql connect successfuily';
		}
		
		//创建数据库
		function sql_createDatabases($databasesName){
			if(!empty($databasesName)){
				$this->sql = 'create database ' . $databasesName;
				$this->sql_query('createDatabases');
			}
		}
		
		//创建数据表
		function sql_createTable($databasesName,$dataTable,$tableData){
			if(!empty($databasesName) && !empty($dataTable) && !empty($tableData)){
				$this->conn = new mysqli($this->servername, $this->username, $this->password, $databasesName);
				// 检测连接
				if ($this->conn -> connect_error) {
					die("连接失败: " . $this->conn -> connect_error);
				}
				$this->sql = 'create table ' . $dataTable . ' (' . $tableData . ')';
				$this->sql_query('createTable');
			}
		}
		
		//选择数据表中的数据
		function sql_selectData($dataTable,$selectData,$whereData){
			if(!empty($dataTable) && !empty($selectData)){
				$this->sql = 'select ' . $selectData . ' from ' . $dataTable;
				//如果参数whereData不为空，则在选择语句后添加选择条件
				if(!empty($whereData)){
					
					$this->sql .= ' where ' . $whereData;
				}
				
				return $this->sql_query('selectData',$selectDataArray);
			}
			
			
		}
		
		//按条件删除数据表中的数据
		function sql_deleteData($dataTable,$whereData){
			if(!empty($dataTable) && !empty($whereData)){
				$this->sql = 'delete from ' . $dataTable . ' where ' . $whereData;
				$this->sql_query('deleteData');
			}
			
		}
		
		//删除数据库
		function sql_dropDatabases($databasesName){
			if(!empty($databasesName)){
				$this->sql = 'drop database ' . $databasesName;
				$this->sql_query('dropDatabases');
			}
		}
		
		//在数据表中插入数据
		function sql_insertData($dataTable,$column,$columnValue){
			if(!empty($dataTable) && !empty($column) && !empty($columnValue)){
				
				//												 各个列名					各个列值
				$this->sql = 'insert into ' . $dataTable . '(' . $column . ') values (' . $columnValue . ')';
				$this->sql_query('insert');
			}
		}
		
		//关闭数据库
		function sql_close(){
			$this->conn -> close();
		}
		
		//数据表查询语句
		function sql_query($queryName){
			$resultData = $this->conn -> query($this->sql);
			//select语句返回的数据类型不是逻辑型
			if(is_bool($resultData)){
				if($resultData === true){
					echo '<br><br>';
					echo $queryName . ' successfuily!';
					return true;
					
				}else{
					echo '<br><br>';
					echo $this->conn -> error;
					return false;
				}
			}else{
				//如果返回值不是逻辑型，就直接返回返回值
				return $resultData;
			}
			
		}
		
	}
	
	$helper = new mysql_helper();
	$helper->sql_connect();
	$helper->sql_createDatabases('test1');
	$helper->sql_createTable('test1','user','id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,username varchar(30) not null,password varchar(30) not null');
	$helper->sql_insertData('user',"username,password","'test','123456'");
	$helper->sql_insertData('user',"username,password","'test','1234567'");
	$helper->sql_insertData('user',"username,password","'test','12345678'");
	$helper->sql_deleteData('user','id = 2');
	$resultData = $helper->sql_selectData('user','id,username,password');

	if($resultData->num_rows > 0){
		while($rowData = $resultData->fetch_assoc()){
			echo "<br><br> id: ". $rowData["id"]. " - username: ". $rowData["username"]. " - password: " . $rowData["password"];
		}
	}
	$helper->sql_dropDatabases('test1');
	$helper->sql_close();
?>
</body>
</html>