<?php
/**
 * Created by zed.
 */

namespace Tests\Unit;


use JYPHP\Core\Application;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        parent::setUp();
        $vDir = vfsStream::setup('base');
        $this->root = vfsStream::copyFromFileSystem(__DIR__.'/../testDir',$vDir);
    }

    public function testNew()
    {
        $app = new Application('vfs://base');
        $this->assertInstanceOf(Application::class,$app);
    }
}