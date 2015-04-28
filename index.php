<?php
/**
 * Created by PhpStorm.
 * User: zzugm
 * Date: 2015/4/27
 * Time: 11:14
 */
ini_set('memory_limit','2048m');

$dir = getcwd() . '/images/';
$new_width = 6000;
$new_height = 4000;
$totalimage = imagecreatetruecolor($new_width, $new_height);
imagejpeg($totalimage,$dir.'totalimage.jpg');


/*差一些工作
现在只是能够完成一张的copy
但是如果多了就不行
原因不明
明天回家回来了
或者在家再继续探究

*/

$countwidth = 0;
$countheight = 0;
$resizedir = $dir;
if(is_dir($resizedir) && $dh = opendir($resizedir)){
    while((($file=readdir($dh)) != false) && ($countheight+600)<=$new_height){
        if($file!='.' && $file!= '..'){
            $filename = $dir.$file;
            list($oldwidth, $oldheight) = getimagesize($filename);
            if(($countwidth+$oldwidth) <= $new_width) {
                $image = @imagecreatefromjpeg($filename);
                //imagejpeg($image,getcwd().'/mingtest.jpg');
                imagecopyresampled($totalimage, $image, 0, 0, $countwidth, $countheight, $oldwidth, $oldheight, $oldwidth, $oldheight);
                $countwidth += $oldwidth;
                echo $countwidth.'-----------------'.$countheight.'<br>';
            }else{
                $countwidth = 0;
                $countheight+=$oldheight;
            }
        }
    }
    $newfilename = getcwd() . '/mingmingming.jpg';


    imagejpeg($totalimage,$newfilename);
    closedir($dh);
}

function reSize($dir){
    //设置统一的高度，宽度随高度自适应
    $height = 600;
    if(is_dir($dir) && $dh=opendir($dir)){
        while(($file=readdir($dh)) != false){
            if($file!='.' && $file!='..'){
                $filename  = $dir.$file;
                $newfilename = explode('.',$filename)[0].'resize.jpg';
                list($filewidth,$fileheight) = getimagesize($filename);
                $width = (int)($height*($filewidth/$fileheight));
                $newimage = imagecreatetruecolor($width,$height);
                $image = imagecreatefromjpeg($filename);
                imagecopyresampled($newimage, $image, 0, 0, 0, 0, $width, $height, $filewidth, $fileheight);
                imagejpeg($newimage,$newfilename);
                delete($filename);
                imagedestroy($image);
                imagedestroy($newimage);
            }
        }
    }
    return $dir;
}

function delete ($filename){
    if(unlink($filename)) {
        return true;
    }else{
        return false;
    }

}

function getRgbArray($dir)
{
    $filesdata = array();
    if (is_dir($dir) && $dh = opendir($dir)) {
        while (($file = readdir($dh)) != false) {
            if ($file != '.' && $file != '..') {
                $filename = $dir . $file;
                $filesdata[$file] = getAverageRgb($filename);
                echo $filesdata[$file]["countb"] . '<br>';
            }
        }
    }
    return $filesdata;
}



function getAverageRgb ($filename)
{
//调整RGB索引值的细度，越小越细，费得时间越多，消耗越大
    $num = 33;
    $im = imagecreatefromjpeg($filename);

    $countr = 0;
    $countg = 0;
    $countb = 0;

    $width = imagesx($im);
    $height = imagesy($im);

//获取索引值点的间隔
    $pointblankx = $width / $num;
    $pointblanky = $height / $num;
    for ($i = 0; $i < $width; $i += $pointblankx) {
        for ($j = 0; $j < $height; $j += $pointblanky) {
            $rgb = imagecolorat($im, $i, $j);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $countr += $r;
            $countg += $g;
            $countb += $b;
        }
    }
    $filedata = array(
        "countr" => $countr,
        "countg" => $countg,
        "countb" => $countb,
        "width"  => $width,
        "height" => $height,
    );
    return $filedata;

}