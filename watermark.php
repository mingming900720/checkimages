<?php
/**
 * Created by PhpStorm.
 * User: zzugm
 * Date: 2015/4/26
 * Time: 14:07
 */

//Is the dir you want images add watermark
$dir = getcwd()."/images/";
//Is the watermark image
$watermarkpath = $dir.'stamp.png';

if(is_dir($dir) && $dh = opendir($dir)){
    $count = 0;
    while(($file = readdir($dh)) != false){
        if($file != '.' && $file !=  '..' && $file != 'stamp.png') {
            $filepath = $dir . $file;
            $newfilename1 = explode('.',$filepath);
            $newfilename = $newfilename1[0].'wm.jpg';
            if(addWaterMark($filepath,$watermarkpath,$newfilename)){
                echo "Add watermark for ".$file.' succeed!<br>';
                //delete the file after save the new watermark image(it can be chose)
                //delete($filepath);
            }else{
                echo "Sorry! add watermark is not succeed with unknown reason!";
            }
            $count++;

        }
    }

}

function addWaterMark($photodir,$stampdir,$newfilename)
{
// 加载水印以及要加水印的图像
    $stamp = imagecreatefrompng($stampdir);
    $im = imagecreatefromjpeg($photodir);
// 设置水印图像的外边距，并且获取水印图像的尺寸
    $marge_right = 10;
    $marge_bottom = 10;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);

    // 利用图像的宽度和水印的外边距计算位置，并且将水印复制到图像上
    imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    // 保存图像并释放内存
    //header('Content-type: image/png');
    if(imagejpeg($im,$newfilename) && imagedestroy($im)){
        return ture;
    }else{
        return false;
    }

}
//delete the image after new watermark image save in the same dir
function delete ($filename){
    if(unlink($filename)) {
        return true;
    }else{
        return false;
    }

}