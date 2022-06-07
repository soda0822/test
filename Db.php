class Db
{
	private static $instance;
	private static $mysqli;
	private function __construct()
	{
		$config=config('DB_CONNECT');
		self::$mysqli=new mysqli($config['host'],$config['user'],$config['pass'],$config['dbname'],$config['port']);
		if(self::$mysqli->connect_error){
			exit('数据库连接失败：' . self::$mysqli->connect_error());
		}
		self::$mysqli->set_charset(config('DB_CHARSET'));
	}
	private function __clone(){}
	public static function getInstance()
	{
		return self::$instance ?: (self::$instance = new self());
	}
}

public function query($sql,$type='',array $data=[])
{
	if(!$stmt=self::$mysqli->prepare($sql)){
		exit("SQL[$sql]预处理失败：" . self::$mysqli->error);
	}
	if(data){
		$data=is_array(current($data)) ? $data : [$data];
		$params=array_shift($data);
		$args=[$type];
		foreach($params as &$args[]);
		call_user_func_array([$stmt,'bind_param'],$args);
	}
	if(!$stmt->execute()){
		exit('数据库操作失败：'.$stmt->error);
	}
	foreach ($data as $row){
		foreach($row as $k=>$v){
			$params[$k]=$v;
		}
		if(!$stmt->execute()){
			exit('数据库操作失败：' . $stmt->error);
		}
	}
	return $stmt;
	
}

$db=Db::getInstance();
$sql='UPADTE test SET name=? WHERE id=?';
 $db->query($sql,'si',['test',123]);
 $db->query($sql,'si',[['test',123],['test02',456]]);
 
 SELECT*FROM fun_user;
 SELECT*FROM __USER__;
 
 $sql=preg_replace_callback('/__([A-Z0-9_-]+)__/sU',function($match){
	 return '`' . config('DB_PREFIX') . strtolower($match[1]) . '`';
 },$sql);
 
 public function fetchAll($sql,$type='',array $data=[])
 {
	 return $this->query($sql,$type,$data)->get_result()->fetch_all(MYSQLI_ASSOC);
 }
 
 public function fetchRow($sql,$type='',array $data=[])
 {
	 return $this->query($sql,$type,$data)->get_result()->fetch_assoc();
 }
 
 public function execute($sql,$type='',array $data=[])
 {
 	 $stmt=$this->query($sql,$type,$data)
	 return(strtoupper(substr(trim($sql),0,6))=='INSERT')?
	 $stmt->insert_id : $stmt->affected_rows;
 }
 
 public function select($table,$fields,$type='',array $data=[],$method='fetchAll')
 {
	 $fields=str_replace(',',',',$fields);
	 $where=implode('AND',self::buildFields(array_keys($data)));
	 $limit=($method=='fetchRow') ? 'LIMIT 1' :'';
	 return $this->$method("SELECT $fields FROM $table WHERE $where $limit",$type,$data);
 }
 
 private static function bulidFields(array $fields)
 {
	 return array_map(function($v){ return "$v=?"; }, $fields);
 }
 
 $db=Db::getInstance();
 $data=$db->select('__USER__','name,password','i',['id'=>1]);
 
 public function find($table,$fields,$type='',array $data=[])
 {
	 return $this->select($table,$fields,$type,$data,'fechRow');
 }
 
 public function value($table,$fields,$type='',array $data=[])
 {
 	 return $this->find($table,$fields,$type,$data,'fechRow')[$field];
 }
 
 public function insert($table,$type,array $data)
 {
	 $fields=self::arrayFields($data);
	 $sql="INSERT INTO $table SET" . implode(',',self::bulidFields($fields));
	 return $this->execute($sql,$type,$data);
 }
 
 private static function arrayFields($data);
 {
	 $row = current($data);
	 return array_keys(is_array($row) ? $row : $data);
 }
 
 $db=Db::getInstance();
 $id=$db->insert('__USER__','is',['id'=>1,'name'=>'test']);
 
 public function update($table,$type,array $data,$where='id')
 {
	 $where=exlode(',',$where);
	 $fields=array_diff(self::arrayFields($data),$where);
	 return $this->execute("UPDATE $table SET" . implode(',',
	 self::buildFields($fields)).'WHERE' . implode('AND',
	 self::buildFields($where)),$type,$data);
 }
 
 public function delete($table,$type, array $data)
 {
	 $fields=implode('AND',self::buildFields(self::arrayFields($data)));
	 return $this->execute("DELETE FROM $table WHERE $fields", $type ,$data);
 }
 
 $db=Db::getInstance();
 $data=['email'=>'a@b.com','id'=>'1','name'=>'test'];
 $db->update('__USER__','sis',$data,'id,name');
 $db->delete('__USER__','i',['id'=>1]);