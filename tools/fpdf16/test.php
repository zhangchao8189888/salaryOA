<?php

require('chinese-unicode.php');
iconv('UTF-8','GBK','中文');
$aa=$_REQUEST['mark'];
//echo $aa;
//exit;
 $pdf=new PDF_Chinese();
            $pdf->AddGBhwFont('simsun','宋体');
            $pdf->AddGBFont('simhei','黑体');
            $pdf->SetTopMargin(12);
            $pdf->SetLeftMargin(12);
            $pdf->SetRightMargin(10);
            $pdf->SetAutoPageBreak("on",8);

            $pdf->Open();
            $pdf->AddPage();

            $fontSize = 9;
            $Fonthight = 6;

            $pdf->SetFont('simhei','',18);
            $pdf->Cell(0,10,$aa,0,1,"C");
            $pdf->SetFont('simhei','',18);
            $pdf->Cell(0,10," 产 品 订 单 ",0,1,"C");
            /*$pdf->Image("3G_logo.jpg",10,5,40,15);*/
            
            $pdf->SetFont('simhei','',18);
            $pdf->Cell(0,10," ______________________________________________________",0,1,"C");

            $pdf->SetFont('simsun','',$fontSize);
            for($i=0;$i<30;$i++){$space1.=" ";}
            $pdf->Cell(0,$Fonthight,"PPB/XZ/QD/7.2.".date('m')."-".date('d')."-".date('Y')."                  订单日期：                       订单编号:      W",0,1);

            //客户信息
            $pdf->SetFont('simsun','',$fontSize);
            $pdf->Cell(150,$Fonthight,"订货单位:",1,0);
            $pdf->Cell(40,$Fonthight,"出 货 情 况",1,0);
            $pdf->Cell(0,$Fonthight,"服","L",1);
            
			$pdf->Cell(110,$Fonthight,"送货地址:",1,0);
            $pdf->Cell(40,$Fonthight,"送货安排：","L",0);
			$pdf->Cell(40,$Fonthight,"出库日期：","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);
           
            $pdf->Cell(110,$Fonthight,"备注：",1,0);
            $pdf->Cell(40,$Fonthight,"     月  日","L",0);
			$pdf->Cell(40,$Fonthight,"______年____月____日","RL",0);
			$pdf->Cell(0,$Fonthight,"服","L",1);
           
            $pdf->Cell(60,$Fonthight,"联系人：",1,0);
            $pdf->Cell(90,$Fonthight,"联系电话：",1,0);
			$pdf->Cell(40,$Fonthight,"产品完好无损，数量无误","RL",0);
			$pdf->Cell(0,$Fonthight,"服","L",1);
		   
		    $pdf->Cell(35,$Fonthight,"商品型号",1,0,"C");
            $pdf->Cell(35,$Fonthight,"规格",1,0,"C");
            $pdf->Cell(15,$Fonthight,"单位",1,0,"C");
            $pdf->Cell(15,$Fonthight,"数量",1,0,"C");
            $pdf->Cell(15,$Fonthight,"根数",1,0,"C");
			$pdf->Cell(35,$Fonthight,"备注",1,0,"C");
            $pdf->Cell(40,$Fonthight,"请客户验收签字：","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"_____________","RL",0,"C");
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
            $pdf->Cell(15,$Fonthight,"","L",0);
			$pdf->Cell(35,$Fonthight,"","L",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

			$pdf->Cell(35,$Fonthight,"","LB",0);
            $pdf->Cell(35,$Fonthight,"","LB",0);
            $pdf->Cell(15,$Fonthight,"","LB",0);
            $pdf->Cell(15,$Fonthight,"","LB",0);
            $pdf->Cell(15,$Fonthight,"","LB",0);
			$pdf->Cell(35,$Fonthight,"","LB",0);
            $pdf->Cell(40,$Fonthight,"","RL",0);
            $pdf->Cell(0,$Fonthight,"服","L",1);

            //$pdf->Cell(190,2,"",1,0);
            // $pdf->Cell(0,$Fonthight,"服","L",1);

            $pdf->Cell(150,$Fonthight,"提示 ： 1.本产品送达至货车可到达位置原地卸货，不负责搬运入户，敬请谅解。","L",0,"C");
			$pdf->Cell(40,$Fonthight,"(注:请将产品平置存放于","LT",0);
			$pdf->Cell(0,$Fonthight,"服","L",1);

            $pdf->Cell(150,$Fonthight,"2.本产品属于特殊定制产品，无质量问题不退换货，谢谢合作。","L",0,"C");
			$pdf->Cell(40,$Fonthight,"室内,并注意防损、防潮)","L",0);
			$pdf->Cell(0,$Fonthight,"服","L",1);
		   //服务商信息
            $pdf->Cell(150,$Fonthight,"公司地址：北京市朝阳区广渠路3号竞园51-D 电话：87212808/18/28 传真：87212738",1,0,"C");
			$pdf->Cell(40,$Fonthight,"","LB",0);
			$pdf->Cell(0,$Fonthight,"服","L",1);

            $pdf->Cell(0,10,"厂务:        库房:        司机:        车号:        操作组:        通知人:        经办人:",0,1);
           // $pdf->Cell(0,$Fonthight," ","L",1);

            $pdf->SetDisplayMode("real");
            $date_dir = date('Ymd');
            $pdf->Output();

?>
