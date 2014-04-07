<?php

require_once dirname(__FILE__).'/../Pplog.php';

class PplogTest extends PHPUnit_Framework_TestCase
{
    public function testZapping()
    {
        $Pplog = new Pplog();
        $Pplog->zapping();

        $this->assertNotEmpty($Pplog->user_name);
    }

    public function testGetUserPoem()
    {

        $user_name  = '@'.$GLOBALS['user_name'];
        $title      = $GLOBALS['title'];
        $content    = str_replace('\n',"\n",$GLOBALS['content']);
        $created_at = $GLOBALS['created_at'];

        $Pplog = new Pplog();
        $Pplog->getUserPoem($user_name);

        $this->assertEquals($user_name,$Pplog->user_name);
        $this->assertEquals($title,$Pplog->title);
        $this->assertEquals($content,$Pplog->content);
        $this->assertEquals($created_at,$Pplog->created_at);

        $Pplog = new Pplog($user_name);
        $this->assertEquals($user_name,$Pplog->user_name);
        $this->assertEquals($title,$Pplog->title);
        $this->assertEquals($content,$Pplog->content);
        $this->assertEquals($created_at,$Pplog->created_at);
    }

    public function testGetPoem()
    {

        $user_name  = '@'.$GLOBALS['user_name'];
        $title      = $GLOBALS['title'];
        $content    = str_replace('\n',"\n",$GLOBALS['content']);
        $created_at = $GLOBALS['created_at'];

        $Pplog = new Pplog();
        $Pplog->getUserPoem($user_name);
        $poem = $Pplog->getPoem();

        $this->assertFalse(strpos($poem,$user_name) === false);
        $this->assertFalse(strpos($poem,$title) === false);
        $this->assertFalse(strpos($poem,$content) === false);
        $this->assertFalse(strpos($poem,$created_at) === false);
    }
}
