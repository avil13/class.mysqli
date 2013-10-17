**Connect with DataBase**

```
$conf = array();
$conf['host'] = 'localhost';
$conf['user'] = 'root';
$conf['pass'] = '';
$conf['db'] = 'db_name';

$db = new DB($conf);
```

#Do query#
**get 1 Array elements from table**  
```
$res = $db->query("SELECT * FROM table")->fetch();
```
  
**get value of name**  
```
$res = $db->query("SELECT name FROM table WHERE id=2 ")->fetch('name');
```
  
**get all Array elements from table**  
```
$res = $db->query("SELECT * FROM table")->fetchAll(); 
```
  
#Insert and Update#


```
$data = array('id'=>NULL, 'key'=>'value');
```

**insert value in to the 'table'**  
```
$db->insert('table', $data); 
```

**get last updated *id* **  
```
$get_id = $db->id();
```

  
**update data where id = 12 in 'table'**  
```
$db->update('table', $data, $where = array('id'=>12)); 
```

#Other#

clean ' and \`   
```
$var = $db->clean('var'); 
```
