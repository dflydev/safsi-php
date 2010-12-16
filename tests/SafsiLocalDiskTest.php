<?php

require_once(dirname(__FILE__) . '/AbstractSafsiTest.php');

require_once('safsi_core.php');

safsi_core_require_once('safsi_localdisk_FileSystem.php');

class SafsiLocalDiskTest extends AbstractSafsiTest {

    /**
     * Setup testing environment for each individual test.
     */
    public function setUp() {
    }

    /**
     * Simple test.
     */
    public function testSimple() {

        $root = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0'
        );

        $data = 'hello world.';

        // md5sum of 'hello world.'
        $checksum = '3c4292ae95be58e0c58e4e5511f09647';
        $contentLength = 12;

        $fs = new safsi_localdisk_FileSystem($root);

        $testFilename = 'simple.txt';

        $this->assertTrue(
            $fs->exists($testFilename),
            'Filename does not exist'
        );

        $file = $fs->read($testFilename);

        $this->assertEquals($checksum, $file->checksum(), 'Incorrect checksum');

        $this->assertEquals($data, $file->data(), 'Incorrect data');

        $this->assertEquals(
            $contentLength,
            $file->contentLength(),
            'Incorrect content length'
        );

    }

    /**
     * Test listing items.
     */
    public function testList() {

        $root = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0'
        );

        $fs = new safsi_localdisk_FileSystem($root);

        $listItems = $fs->listItems();

        $this->assertEquals(1, count($listItems), 'Incorrect number of items');

        $listItem = $listItems[0];

        $testFilename = 'simple.txt';

        $this->assertEquals(
            $testFilename,
            $listItem->basename(),
            'Incorrect file name'
        );

    }

    /**
     * Test reading to a resource stream.
     */
    public function testReadToStream() {

        $root = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0'
        );

        $testFilename = 'simple.txt';
        $data = 'hello world.';

        $fs = new safsi_localdisk_FileSystem($root);

        $tmp = tmpfile();
        $file = $fs->readStream($testFilename, $tmp);
        fseek($tmp, 0);
        $tmpData = fread($tmp, 1024);
        fclose($tmp);

        $this->assertEquals(null, $file->data(), 'Incorrect data');
        $this->assertEquals($data, $tmpData, 'Incorrect data');

    }

    /**
     * Test writing
     */
    public function testWrite() {

        $tempDir = $this->mkTempDir();

        $data = 'hello world.';
        $checksum = '3c4292ae95be58e0c58e4e5511f09647';
        $contentLength = 12;

        $testFilename = 'deep/path/write-test.txt';

        $fs = new safsi_localdisk_FileSystem($tempDir);

        $fs->write($testFilename, $data);

        $file = $fs->read($testFilename);

        $this->assertEquals($checksum, $file->checksum(), 'Incorrect checksum');

        $this->assertEquals($data, $file->data(), 'Incorrect data');
        $this->assertEquals(
            $contentLength,
            $file->contentLength(),
            'Incorrect content length'
        );

        $this->rmDir($tempDir);

    }

    /**
     * Test stream writing
     */
    public function testWriteStream() {

        $tempDir = $this->mkTempDir();

        $data = 'hello world.';
        $checksum = '3c4292ae95be58e0c58e4e5511f09647';
        $contentLength = 12;

        $testFilename = 'deep/path/write-test.txt';

        $source = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0/simple.txt'
        );

        $stream = fopen($source, 'rb');

        $fs = new safsi_localdisk_FileSystem($tempDir);

        $fs->writeStream($testFilename, $stream);

        fclose($stream);

        $file = $fs->read($testFilename);

        $this->assertEquals($checksum, $file->checksum(), 'Incorrect checksum');

        $this->assertEquals($data, $file->data(), 'Incorrect data');
        $this->assertEquals(
            $contentLength,
            $file->contentLength(),
            'Incorrect content length'
        );

        $this->rmDir($tempDir);

    }

    /**
     * Test path writing
     */
    public function testWriteFromPath() {

        $tempDir = $this->mkTempDir();

        $data = 'hello world.';
        $checksum = '3c4292ae95be58e0c58e4e5511f09647';
        $contentLength = 12;

        $testFilename = 'deep/path/write-test.txt';

        $source = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0/simple.txt'
        );

        $fs = new safsi_localdisk_FileSystem($tempDir);

        $fs->writeFromPath($testFilename, $source);

        $file = $fs->read($testFilename);

        $this->assertEquals($checksum, $file->checksum(), 'Incorrect checksum');

        $this->assertEquals($data, $file->data(), 'Incorrect data');
        $this->assertEquals(
            $contentLength,
            $file->contentLength(),
            'Incorrect content length'
        );

        $this->rmDir($tempDir);

    }

    /**
     * Test delete file
     */
    public function testDeleteFile() {

        $tempDir = $this->mkTempDir();

        $testFilename = 'deep/path/write-test.txt';

        $source = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0/simple.txt'
        );

        $fs = new safsi_localdisk_FileSystem($tempDir);

        $fs->writeFromPath($testFilename, $source);

        $this->assertTrue(
            $fs->exists($testFilename),
            'Filename does not exist'
        );

        $fs->deleteFile($testFilename);

        $this->assertFalse(
            $fs->exists($testFilename),
            'Filename exists'
        );

        $this->rmDir($tempDir);

    }


    /**
     * Test delete directory
     */
    public function testDeleteDirectory() {

        $tempDir = $this->mkTempDir();

        $testFilename = 'deep/path/write-test.txt';

        $source = $this->generatePathFromParts(
            dirname(__FILE__),
            'testData/safsiLocalDiskTest/0/simple.txt'
        );

        $fs = new safsi_localdisk_FileSystem($tempDir);

        $fs->writeFromPath($testFilename, $source);

        $this->assertTrue(
            $fs->exists($testFilename),
            'Filename does not exist'
        );

        $fs->deleteDirectory('deep');

        $this->assertFalse(
            $fs->exists($testFilename),
            'Filename exists'
        );

        $this->rmDir($tempDir);

    }


}

?>
