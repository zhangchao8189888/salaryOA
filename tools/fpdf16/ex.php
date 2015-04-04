<?php
require('dbClass.php');
$aa=array(101,152);//页面大小
$height=7;//行高
$width=70;//列宽
$border=0;//边框
$id=$_GET['id'];
$db->query("update cool_chuku set dayin=1 where id in(".$id.")");
$sql = "select * from cool_chuku where id in(".$id.") order by id desc limit 0,1";
$result=$db->query($sql);
require('chinese.php');
$pdf=new PDF_Chinese('L','mm',$aa);//创建新的FPDF 对象，竖向放纸，单位为毫米，纸张大小A4
$pdf->AddGBFont();
$pdf->Open();
$pdf->SetFont('GB','B',10);
$pdf->SetTopMargin(3);
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(0.5);
while($row=$db->getarray($result))
{
	$sql_k = "select * from cool_kucun where id='".$row['kucunid']."' order by id desc";
	$result_k=$db->query($sql_k);
	$row_k=$db->getarray($result_k);
	$sql_c = "select * from cool_chuku_sq where id='".$row['chukuid']."' order by id desc";
	$result_c=$db->query($sql_c);
	$row_c=$db->getarray($result_c);
	$pdf->AddPage();
	$pdf->Cell($width,$height,'厂内标识传递标签(外质068A)',$border);
	$pdf->Cell($width,$height,'Internal Identifion Information Label(WZ068A)',$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'合格证编号：'.$row_k['hgcode'],$border);
	$pdf->Cell($width,$height,'Certifcate No：'.$row_k['hgcode'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'图号：'.$row_k['jiancode'],$border);
	$pdf->Cell($width,$height,'BWG No：'.$row_k['jiancode'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'炉批号：'.$row_k['lpcode'],$border);
	$pdf->Cell($width,$height,'Batch NO：'.$row_k['lpcode'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'柜号：'.$row_c['gongwei'].'盒号：'.$row_c['bid'],$border);
	$pdf->Cell($width,$height,'Cabinet：'.$row_c['gongwei'].'Bin ID：'.$row_c['bid'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'入库日期：'.$row_k['rktime'],$border);
	$pdf->Cell($width,$height,'Rcv. date：'.$row_k['rktime'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'发料日期：'.$row['fltime'],$border);
	$pdf->Cell($width,$height,'Rls. date：'.$row['fltime'],$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'发料员：'.get_vclassname('truename','cool_admin','id',$row['uid']).'发往单位：'.$row_c['gangwei'],$border);
	$pdf->Cell($width,$height,'Store manager：'.get_vclassname('truename','cool_admin','id',$row['uid']).'发往单位：'.$row_c['gangwei'],$border);
	$pdf->Ln($height);
	if($row_k['xishu']==1)
	{
		$sf=round($row['cksl']);
	}
	if($row_k['xishu']<>1)
	{
		$sf=round($row['cksl']*$row_k['xishu'],3)."公斤".",(".$row['cksl'].")个";
	}
	$pdf->Cell($width,$height,'实发：'.$sf,$border);
	$pdf->Cell($width,$height,'Rls. Qty：'.$sf,$border);
	$pdf->Ln($height);
	$pdf->Cell($width,$height,'排架号：'.$row_k['weizhi'],$border);
	$pdf->Cell($width,$height,'Location No：'.$row_k['weizhi'],$border);
	$pdf->Ln($height);
	if($row_k['xishu']==1)
	{
		$kc=$row['zsy']-$row['cksl'];	 		 
	}
	if($row1['xishu']<>1)
	{
		$kc=round(($row['zsy']-$row['cksl'])*$row_k['xishu'],3);
	}
	$pdf->Cell($width,$height,'库存数：'.$kc,$border);
	$pdf->Cell($width,$height,'Qty of scock：'.$kc,$border);
}
$pdf->Output();
?>
