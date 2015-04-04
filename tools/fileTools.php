<?php
/**
*本类为文件操作类，实现了文件的建立，写入，删除，修改，复制，移动，创建目录，删除目录
* 列出目录里的文件等功能，路径后面别忘了加"/"
* 
* @author 路人郝
* @copyright myself
* @link [url=http://www.phpr.cn]www.phpr.cn[/url]
* 
*/
class fileoperate
{
var $path;// 文件路径
var $name;//文件名
var $result;//对文件操作后的结果

/**
* 本方法用来在path目录下创建name文件
*
* @param string path
* @param string name
*/ 
function creat_file($path,$name)//建立文件
{ 
$filename=$path.$name;
if (file_exists($filename))
{
echo "文件已经存在，请换个文件名";
}
else 
{
if (file_exists($path))
{
touch($tname);
rename($name,$filename);
echo "文件建立成功 </br>";
}
else{
echo "目录不存在，请检查";
}
}
}

/**
* 本方法用来写文件，向path路径下name文件写入content内容，bool为写入选项，值为1时
* 接着文件原内容下继续写入，值为2时写入后的文件只有本次content内容
*
* @param string_type path
* @param string_type name
* @param string_type content
* @param bool_type bool
*/
function write_file($path,$name,$content,$bool) //写文件
{
$filename=$path.$name;
if ($bool==1) {
if (is_writable($filename)) {
$handle=fopen($filename,'a');
if (!$handle) {
echo "文件不能打开或文件不存在";
exit;
}
$result=fwrite($handle,$content);
if (!$result) {
echo "文件写入失败";
}
echo "文件写入成功";
fclose($handle);
}
else echo "文件不存在";
}
if ($bool==2) {
if (!file_exists($filename)) {
$this->creat_file($path,$name);
$handle=fopen($filename,'a');
if (fwrite($handle,$content));
echo "文件写入成功";

}
else {
unlink($filename);
$this->creat_file($path,$name);
$this->write_file($path,$name,$content,1);
echo "文件修改成功";
}
}

}

/**
* 本方法删除path路径下name文件
*
* @param string_type path
* @param string_type name
*/
function del_file($path,$name){ //删除文件
$filename=$path.$name;
$mess="";
    echo $filename;
if (!file_exists($filename)) {
$mess= "文件不存在，请确认路径是否正确";
}
else {
if (unlink($filename)){
$mess= "文件删除成功";
}
else $mess= "文件删除失败";
}
return $mess;
}

/**
* 本方法用来修改path目录里name文件中的内容（可视）
*
* @param string_type path
* @param string_type name
*/
function modi_file($path,$name){ //文件修改
$filename=$path.$name;
$handle=fopen($filename,'r+');
$content=file_get_contents($filename);
echo "<form id='form1' name='form1' action='modi_file.php' method='post'>";
echo "<textarea name=content rows='10'>content</textarea>文件内容";
echo "<p>";
echo "<input type='text' name='filename' value=filename />文件路径<p>";
echo "<input name=ss type=submit value=修改文件内容 />";
echo "</form>";
}

/**
* 本方法用来复制name文件从spath到dpath
*
* @param string name
* @param string spath
* @param string dpath
*/
function copy_file($name,$spath,$dpath) //文件复制
{
$filename=$spath.$name;
if (file_exists($filename)) {
$handle=fopen($filename,'a');
copy($filename,$dpath.$name);
if (file_exists($dpath.$name))
echo "文件复制成功";
else echo "文件复制失败";
}
else echo "文件不存在";
}

/**
* 本方法把name文件从spath移动到path路径
*
* @param string_type path
* @param string_type dirname
* @param string_type dpath
*/
function move_file($name,$spath,$dpath) //移动文件
{
$filename=$spath.$name;
if (file_exists($filename)) {
$result=rename($filename,$dpath.$name);
if ($result==false or !file_exists($dpath))
echo "文件移动失败或目的目录不存在";
else 
echo "文件移动成功";
}
else {
echo "文件不存在，无法移动";
}
}

/**
* 本方法把filename文件重命名为newname文件
*
* @param string_type filename
* @param string_type newname
*/
function rename_file($filename,$newname) { //文件或目录更名
$path=pathinfo($filename);
$dir=$path['dirname']; //得到文件路径
$newfilename=$dir."/".$newname;
if (file_exists($filename)) { //判断文件是否存在
$result=rename($filename,$newfilename);
if ($result==true)
return  "文件更名成功";
else 
return "文件更名失败";
}
else 
return "文件不存在"; 
}

/**
* 本方法用来列出目录里的文件或目录switch为1时按字母顺序列出所有目录和文件
* switch为2则只列出目录，switch为3时，只列出文件名
*
* @param string_type path
* @param int_type switch
*/
function list_filename($path,$switch) //列出文件和目录名
{
$files=array();
$j=0;
if (file_exists($path)) {
$dir=scandir($path);
if ($switch==1){ //如果switch为1则按字母顺序列出所有目录和文件
for ($i=0;$i<=count($dir);$i++)
{
if ($dir[$i]!="." && $dir[$i]!=".."&&$dir[$i]!="") 
{
	$files[$j]=$dir[$i];
    //echo $dir[$i]."<br>";
    $j++;
}
}
}
if ($switch==2) //switch为2则只列出目录
{
for ($i=0;$i<=count($dir);$i++)
{
$x=is_dir($path.$dir[$i]);
if ($dir[$i]!="." && $dir[$i]!=".." && $x==true&&$dir[$i]!="")
{
    $files[$j]=$dir[$i];
    //echo $dir[$i]."<br>";
    $j++;
}
}
}
if ($switch==3) //switch为3时，只列出文件名
{
for ($i=0;$i<=count($dir);$i++)
{
$x=is_dir($path.$dir[i]);
if ($dir[$i]!="." && $dir[$i]!=".." && $x==false&&$dir[$i]!="")
{
    $files[$j]=$dir[$i];
   // echo $dir[$i]."<br>";
    $j++;
}
}
} 
}else{
	echo "路径不存在";
}
return $files;
}

/**
* 本方法在path目录下创建名为dirname的目录
*
* @param string_type path
* @param string_type dirname
*/
function creat_dir($path,$dirname){ //创建目录
if (file_exists($path)) {
$result=mkdir($path.dirname);
if ($result)
echo "目录建立成功";
else 
echo "目录建立失败";
}
else 
echo "路径不存在，请重新输入";
}

/**
* 本方法删除pathname目录，包括该目录下所有的文件及子目录
*
* @param string_type pathname
*/
function del_dir($pathname){ //删除目录及目录里所有的文件夹和文件
if (!is_dir($pathname))
{exit("你输入的不是一个目录，请检查") ;}
$handle=opendir($pathname);
while (($fileordir=readdir($handle)) !== false) {
if ($fileordir!="." && $fileordir!="..") {
is_dir("pathname/fileordir")?
$this->del_dir("pathname/fileordir"):
unlink("pathname/fileordir");
}
}
if (readdir($handle) == false)
{
closedir($handle);
rmdir($pathname);
}
}
}
?>