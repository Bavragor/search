<?php
namespace Unit\Doctrine\Search\Mapping\Driver;

use Doctrine\Search\Mapping\Driver\AnnotationDriver;
use Doctrine\Search\Mapping\ClassMetadata;

/**
 * Test class for AnnotationDriver.
 * Generated by PHPUnit on 2011-12-13 at 08:34:04.
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Doctrine\Search\Mapping\Driver\AnnotationDriver
     */
    private $annotationDriver;

    /**
     * @var Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->reader = $this->getMock('Doctrine\\Common\\Annotations\\Reader');

        $this->classMetadata = $this->getMock('Doctrine\\Search\\Mapping\\ClassMetadata', array(), array(), '', false);

        $this->reflectionClass = $this->getMock('\ReflectionClass', array(), array(), '', false);

        $this->annotationDriver = new AnnotationDriver($this->reader);
    }

    /**
     * @todo Implement testRegisterAnnotationClasses().
     */
    public function testRegisterAnnotationClasses()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function testLoadMetadataForClass()
    {

        $this->reader->expects($this->once())
                         ->method('getClassAnnotations')
                         ->will($this->returnValue(array(0, new TestSearchable2(array()))));

        $this->reader->expects($this->any())
                                 ->method('getPropertyAnnotations')
                                 ->will($this->returnValue(array(0, new TestField(array()))));



        $classMetadata = new ClassMetadata('Unit\Doctrine\Search\Documents\BlogPost');

        $this->annotationDriver->loadMetadataForClass('Unit\Doctrine\Search\Documents\BlogPost', $classMetadata);

        $this->assertInstanceOf('Doctrine\Search\Mapping\ClassMetadata', $this->classMetadata);
        $this->assertEquals('Unit\Doctrine\Search\Documents\BlogPost', $classMetadata->getName());
        
    }

    /**
     * @expectedException Doctrine\Search\Exception\Driver\ClassIsNotAValidDocumentException
     */
    public function testLoadMetadataForClassExtractClassAnnotationsException()
    {

        $this->reader->expects($this->once())
                     ->method('getClassAnnotations')
                     ->will($this->returnValue(array()));

        $this->reflectionClass->expects($this->once())
                                ->method('getProperties')
                                ->will($this->returnValue(array()));

        $this->classMetadata->expects($this->once())
                            ->method('getReflectionClass')
                            ->will($this->returnValue($this->reflectionClass));

        $this->annotationDriver->loadMetadataForClass('Unit\Doctrine\Search\Documents\BlogPost', $this->classMetadata);
    }

    /**
     * @expectedException ReflectionException
     */
    public function testLoadMetadataForReflectionErrorClassNotFound()
    {
        $this->classMetadata->expects($this->once())
                            ->method('getReflectionClass')
                            ->will($this->returnValue(false));

        $this->annotationDriver->loadMetadataForClass(array(), $this->classMetadata);
    }

    /**
     * @expectedException Doctrine\Search\Exception\Driver\PropertyDoesNotExistsInMetadataException
     */
    public function testLoadMetadataForClassAddValuesToMetadata()
    {
        $this->reflectionClass->expects($this->once())
                              ->method('getProperties')
                              ->will($this->returnValue(array()));

        $this->reader->expects($this->once())
                     ->method('getClassAnnotations')
                     ->will($this->returnValue(array(0, new TestSearchable(array()))));

        $this->classMetadata->expects($this->once())
                            ->method('getReflectionClass')
                            ->will($this->returnValue($this->reflectionClass));

        $this->annotationDriver->loadMetadataForClass('Unit\Doctrine\Search\Documents\BlogPost', $this->classMetadata);
    }


    /**
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function testLoadMetadataForClassWrongParameterClassName()
    {
        $this->annotationDriver->loadMetadataForClass(new \StdClass(), $this->classMetadata);
    }

    /**
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function testLoadMetadataForClassWrongParameterClassMetadata()
    {
        $this->annotationDriver->loadMetadataForClass('test', new \StdClass());
    }
}

use Doctrine\Search\Mapping\Annotations\Searchable;

class TestSearchable extends Searchable
{
    public $typeNotDefined;
}


class TestSearchable2 extends Searchable {}

use Doctrine\Search\Mapping\Annotations\Field;

class TestField extends Field {}