<?php
    error_reporting(0);
    require_once(app_path('Includes/excel/spreadsheet/Writer.php'));

    function convertEncoding($string) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    $xls = new Spreadsheet_Excel_Writer();
    $header = $xls->addFormat(array('Size' => 11));
    $header->setLocked();
    $header->setBold();
    $header->setFontFamily('Arial');
    $header->setAlign("center");
    $header->setAlign("vcenter");

    $subheader = $xls->addFormat(array('Size' => 10));
    $subheader->setLocked();
    $subheader->setFontFamily('Arial');
    $subheader->setAlign("center");
    $subheader->setAlign("vcenter");
 
    $subheaderB = $xls->addFormat(array('Size' => 10));
    $subheaderB->setLocked();
    $subheaderB->setFontFamily('Arial');
    $subheaderB->setAlign("center");
    $subheaderB->setAlign("vcenter");
    $subheaderB->setBold();
    $subheaderB->setBorder(1);
    $subheaderB->setFgColor('yellow');

    $subheaderNB = $xls->addFormat(array('Size' => 10));
    $subheaderNB->setLocked();
    $subheaderNB->setFontFamily('Arial');
    $subheaderNB->setAlign("left");
    $subheaderNB->setAlign("vcenter");
    $subheaderNB->setBold();

    $subheaderName = $xls->addFormat(array('Size' => 10));
    $subheaderName->setLocked();
    $subheaderName->setFontFamily('Arial');
    $subheaderName->setAlign("left");
    $subheaderName->setAlign("vcenter");
    $subheaderName->setBold();

    $normal = $xls->addFormat(array('Size' => 10));
    $normal->setFontFamily('Arial');
    $normal->setAlign("left");
    $normal->setAlign("vcenter");
    $normal->setTextWrap();
    $normal->setLocked();
    $normal->setBorder(1);
    
    $normalC = $xls->addFormat(array('Size' => 10));
    $normalC->setFontFamily('Arial');
    $normalC->setAlign("center");
    $normalC->setAlign("vcenter");
    $normalC->setTextWrap();
    $normalC->setLocked();
    $normalC->setBorder(1);
    
    $normalR = $xls->addFormat(array('Size' => 10));
    $normalR->setFontFamily('Arial');
    $normalR->setAlign("right");
    $normalR->setAlign("vcenter");
    $normalR->setTextWrap();
    $normalR->setBold();
    $normalR->setLocked();
    $normalR->setBorder(1);

    $sheet = $xls->addWorksheet($title);
    $xls->send($title.".xls");

    $fields = array(
        array('MemId',15),
        array('PbNo',15),
        array('Name',30),
        array('Branch',20),
        array('Date Registered',15),
        array('Registered By',15)
    );

    $c = $r = 0;
    foreach($fields as $fieldinfo):
        list($caption,$colwidth) = $fieldinfo;
        $sheet->setColumn($c,$c,$colwidth);
        $sheet->write($r,$c,$caption,$subheaderB);$c++;
    endforeach;
    $r++;

    foreach($memberList as $data){
        $c = 0;
        $sheet->writeString($r,$c,$data["memid"],$normalC);$c++;
        $sheet->writeString($r,$c,$data["pbno"],$normalC);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["name"]),$normal);$c++;
        $sheet->writeString($r,$c,$data["branch"],$normalC);$c++;
        $sheet->writeString($r,$c,$data["dateRegistered"],$normalC);$c++;
        $sheet->writeString($r,$c,convertEncoding($data["updatedBy"]),$normalC);$c++;
        $r++;
    }

    $xls->close();
    die;
?>
    