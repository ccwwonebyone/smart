<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
Vendor('PHPExcel.PHPExcel');

class ExcelController extends Controller {
	//导入全部表格
	public function excel() {
		$filepath = __NROOT__ . 'Public/files/weihu.xls';
		//createReaderForFile 为静态方法只能用 :: 创建阅读器
		//thinkphp载入第三方库  类名前面要加 \ 否则无法被正确识别
		$objreader = \PHPExcel_IOFactory::createReaderForFile($filepath);
		//载入阅读器 ******类变更为PHPExcel
		$phpexcel = $objreader->load($filepath);
		//统计工作簿的总共表数
		//$count = $phpexcel->getSheetCount();

		//获取表名
		$content = $phpexcel->getSheetNames();
		//获取最高列行的数据 返回数组
		//$con=$objWorksheet->getHighestRowAndColumn();
		//获取最高列数据
		//$columndata=$objWorksheet->getHighestDataColumn();
		//获取最高行数据
		//$rowdata=$objWorksheet->getHighestDataRow();
		$name = 'a';
		foreach ($content as $key => $value) {
			//设置默认的激活表
			$phpexcel->setActiveSheetIndex($key);
			//$phpexcel->setActiveSheetIndex(5);
			//获取目前表  ******类变更为Worksheet
			$objWorksheet = $phpexcel->getActiveSheet();
			//获取最高列
			$hcolumn = $objWorksheet->getHighestColumn();
			//获取最高行
			$hrow = $objWorksheet->getHighestRow();
			for ($j = 2; $j <= $hrow; $j++) {
				for ($k = 'A'; $k <= $hcolumn; $k++) {
					$cell = $objWorksheet->getCell("$k$j")->getValue();
					if ($cell instanceof \PHPExcel_RichText) //富文本转换字符串
					{
						$cell = $cell->__toString();
					}

					$con["gims_" . $k] = $cell;
				}
				$conall[] = $con;
			}
			$drop = "DROP TABLE IF EXISTS `smart`.`smart_$name`";
			M()->execute($drop);
			$sql .= "CREATE TABLE `smart`.`smart_$name` (`gims_id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',";
			for ($i = 'A'; $i <= $hcolumn; $i++) {
				$cell = $i . '1';
				$data = $objWorksheet->getCell($cell)->getValue();
				$sql .= " `gims_$i` CHAR(255) NOT NULL COMMENT '$data' ,";
			}
			$sql .= " PRIMARY KEY (`gims_id`)) ENGINE = MyISAM COMMENT = '$value';)";
			M()->execute($sql);
			$sql = '';
			$dbh = M($name);
			$dbh->addAll($conall);
			unset($con);
			unset($conall);
			$name++;
		}
		return 1;
	}
	//导出工作表
	public function exportexcel($where = 1, $excelname = '') {
		$tname = date("Y-m-d");
		$main = M("main_$tname");
		$res = $main->field('main_date,main_wo,main_add,main_tel,main_address,main_nu,main_ytime,main_ctime,main_cf,main_mark,main_status,main_res,main_staff,main_type,main_yres')->where($where)->select();
		//print_r($res);
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("づsんáηɡ年、悤悤惘惘")
			->setLastModifiedBy("づsんáηɡ年、悤悤惘惘")
			->setTitle("数据EXCEL导出")
			->setSubject("数据EXCEL导出")
			->setDescription("备份数据")
			->setKeywords("excel")
			->setCategory("result file");
		$num = 1;
		foreach ($res as $value) {
			$cell = 'A';
			foreach ($value as $key => $val) {
				if ($key == 'main_tel') {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($cell . $num, $val);
				} else {
					if ($key == 'main_ytime' || $key == 'main_ctime') {
						$val = date('Y/n/j G:i', strtotime($val));
					}
					if ($key == 'main_cf') {$val = '';}
					if ($key == 'main_res') {$val = '';}
					if ($key == 'main_status') {$val = '';}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell . $num, $val);
				}
				$cell++;
			}
			$num++;
		}
		$objPHPExcel->getActiveSheet()->setTitle('提单数据');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . "$tname$excelname" . '数据.xls');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	//导出5组工作表
	public function exportexcel5() {
		$tname = date("Y-m-d");
		require __NROOT__ . "Public/files/" . $tname . "_staconf.php";
		$where = "main_staff='{$staff['one']}' AND main_type='已派发'";
		self::exportexcel($where, '5组');
	}
	//导出8组工作表
	public function exportexcel8() {
		$tname = date("Y-m-d");
		require __NROOT__ . "Public/files/" . $tname . "_staconf.php";
		$where = "main_staff='{$staff['two']}' AND main_type='已派发'";
		self::exportexcel($where, '8组');
	}
	//展示所有的表名
	public function showsheet() {
		$filepath = __NROOT__ . 'Public/files/weihu.xls';
		//createReaderForFile 为静态方法只能用 :: 创建阅读器
		//thinkphp载入第三方库  类名前面要加 \ 否则无法被正确识别
		$objreader = \PHPExcel_IOFactory::createReaderForFile($filepath);
		//载入阅读器 ******类变更为PHPExcel
		$phpexcel = $objreader->load($filepath);
		//获取表名
		$content = $phpexcel->getSheetNames();
		$con = '';
		foreach ($content as $k => $value) {
			$con .= "<li><a href='#' class='sheet'>$k-$value</a></li>";
		}
		return $con;
	}
	/**
	 * 修改单个数据表
	 * @param  [int] $needsheet [表位于工作薄的第几张表]
	 * @return [int]            [如果更新完成将返回1]
	 */
	public function changesheet($needsheet) {
		$filepath = __NROOT__ . 'Public/files/weihu.xls';
		//createReaderForFile 为静态方法只能用 :: 创建阅读器
		//thinkphp载入第三方库  类名前面要加 \ 否则无法被正确识别
		$objreader = \PHPExcel_IOFactory::createReaderForFile($filepath);
		//载入阅读器 ******类变更为PHPExcel
		$phpexcel = $objreader->load($filepath);
		//获取表名
		$name = 'a';
		for ($i = 0; $i < $needsheet; $i++) {
			$name++;
		}
		//设置默认的激活表
		$phpexcel->setActiveSheetIndex($needsheet);
		//$phpexcel->setActiveSheetIndex(5);
		//获取目前表  ******类变更为Worksheet
		$objWorksheet = $phpexcel->getActiveSheet();
		//获取最高列
		$hcolumn = $objWorksheet->getHighestColumn();
		//获取最高行
		$hrow = $objWorksheet->getHighestRow();
		for ($j = 2; $j <= $hrow; $j++) {
			for ($k = 'A'; $k <= $hcolumn; $k++) {
				$cell = $objWorksheet->getCell("$k$j")->getValue();
				if ($cell instanceof \PHPExcel_RichText) //富文本转换字符串
				{
					$cell = $cell->__toString();
				}

				$con["gims_" . $k] = $cell;
			}
			$conall[] = $con;
		}
		//删除数据表
		$drop = "DROP TABLE IF EXISTS `smart`.`smart_$name`";
		M()->execute($drop);
		//创建数据表
		$sql .= "CREATE TABLE `smart`.`smart_$name` (`gims_id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',";
		for ($i = 'A'; $i <= $hcolumn; $i++) {
			$cell = $i . '1';
			$data = $objWorksheet->getCell($cell)->getValue();
			$sql .= " `gims_$i` CHAR(255) NOT NULL COMMENT '$data' ,";
		}
		$sql .= " PRIMARY KEY (`gims_id`)) ENGINE = MyISAM COMMENT = '$value';)";
		M()->execute($sql);
		$sql = '';
		//保存数据
		$dbh = M($name);
		if ($dbh->addAll($conall)) {
			$num = count($conall);
			return 1;
		}
		unset($con);
		unset($conall);
	}
//上传表格数据 得到想要的数据
	public function getdata() {
		$filepath = __NROOT__ . 'Public/files/data.xls';
		//createReaderForFile 为静态方法只能用 :: 创建阅读器
		//thinkphp载入第三方库  类名前面要加 \ 否则无法被正确识别
		$objreader = \PHPExcel_IOFactory::createReaderForFile($filepath);
		//载入阅读器 ******类变更为PHPExcel
		$phpexcel = $objreader->load($filepath);
		//设置默认的激活表
		$phpexcel->setActiveSheetIndex(0);
		//$phpexcel->setActiveSheetIndex(5);
		//获取目前表  ******类变更为Worksheet
		$objWorksheet = $phpexcel->getActiveSheet();
		//获取最高行
		$hrow = $objWorksheet->getHighestRow();
		for ($j = 2; $j <= $hrow; $j++) {
			$data['wo'] = $objWorksheet->getCell('A' . $j)->getValue();
			$data['tel1'] = $objWorksheet->getCell('B' . $j)->getValue();
			$data['address'] = $objWorksheet->getCell('H' . $j)->getValue();
			$data['ytime'] = $objWorksheet->getCell('K' . $j)->getValue();
			$data['tel2'] = $objWorksheet->getCell('O' . $j)->getValue();
			$data['reason'] = $objWorksheet->getCell('P' . $j)->getValue();
			$data['bck'] = $objWorksheet->getCell('Q' . $j)->getValue();
			foreach ($data as $key => $cell) {
				if ($cell instanceof \PHPExcel_RichText) {
					//富文本转换字符串
					$data[$key] = $cell->__toString();
				}
			}
			$needdata[] = $data;
		}
		return $needdata;
	}
}