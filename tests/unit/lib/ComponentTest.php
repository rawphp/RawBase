<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.3
 * 
 * @category  PHP
 * @package   RawPHP/RawBase/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawBase\Tests;

use RawPHP\RawBase\TestComponent;
use RawPHP\RawBase\Component;

/**
 * Component test class.
 * 
 * @category  PHP
 * @package   RawPHP/RawBase/Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ComponentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestService
     */
    protected $component    = NULL;
    
    private $_done          = FALSE;
    
    /**
     * Setup before each test.
     */
    protected function setUp()
    {
        $this->component = new TestComponent( );
        $this->component->init( array( 'debug' => TRUE ) );
        $this->_done = FALSE;
    }
    
    /**
     * Cleanup after each test.
     */
    protected function tearDown()
    {
        $this->_done = FALSE;
    }
    
    /**
     * Test adding an action.
     */
    public function testAddAction( )
    {
        $actions = $this->_addTestAction( );
        
        $this->assertEquals( 1, count( $actions[ self::ON_AFTER_RUN_ACTION ] ) );
        
        $this->assertEquals( 10, $actions[ self::ON_AFTER_RUN_ACTION ][ 0 ][ 'priority' ] );
    }
    
    /**
     * Test adding action with priority.
     */
    public function testAddActionWithPriority( )
    {
        $actions = $this->_addTestAction( NULL, NULL, 1 );
        
        $this->assertEquals( 1, count( $actions ) );
        
        $this->assertEquals( 1, $actions[ self::ON_AFTER_RUN_ACTION ][ 0 ][ 'priority' ] );
    }
    
    /**
     * Test adding actions and get them ordered by priority.
     */
    public function testAddThreeActionsWithPriorities( )
    {
        $action = 'test_action';
        
        $this->component->addAction( $action, array( $this, '' ), 10 );
        $this->component->addAction( $action, array( $this, '' ), 1 );
        $this->component->addAction( $action, array( $this, '' ), 5 );
        
        $this->assertEquals( 1, $this->component->actions[ $action ][ 0 ][ 'priority' ] );
        $this->assertEquals( 5, $this->component->actions[ $action ][ 1 ][ 'priority' ] );
        $this->assertEquals( 10, $this->component->actions[ $action ][ 2 ][ 'priority' ] );
    }
    
    /**
     * Test removing an action.
     */
    public function testRemoveAction( )
    {
        $callback = array( $this, 'action' );
        
        $actions = $this->_addTestAction( self::ON_AFTER_RUN_ACTION, $callback, 1 );
        
        $this->assertEquals( 1, count( $actions[ self::ON_AFTER_RUN_ACTION ] ) );
        
        $this->assertEquals( 1, $actions[ self::ON_AFTER_RUN_ACTION ][ 0 ][ 'priority' ] );
        
        $result = $this->component->removeAction( self::ON_AFTER_RUN_ACTION, $callback );
        
        $this->assertTrue( $result );
        
        $actions = $this->component->actions;
        
        $this->assertEquals( 0, count( $actions[ self::ON_AFTER_RUN_ACTION ] ) );
    }
    
    /**
     * Test removing an unknown/unset action.
     */
    public function testRemoveUnknownAction( )
    {
        $this->component->addAction( 'test_action', array( $this, '' ) );
        
        $this->assertFalse( $this->component->removeAction( 'unknown_action', array( $this, '' ) ) );
    }
    
    /**
     * Test running an action.
     */
    public function testRunAction( )
    {
        $this->_addTestAction( self::ON_AFTER_RUN_ACTION );
        
        $this->assertFalse( $this->_done );
        
        $this->component->doAction( self::ON_AFTER_RUN_ACTION );
        
        $this->assertTrue( $this->_done );
    }
    
    /**
     * Test adding a filter.
     */
    public function testAddFilter( )
    {
        $filters = $this->_addTestFilter( );
        
        $this->assertEquals( 1, count( $filters[ self::ON_CREATE_CONTROLLER_FILTER ] ) );
        
        $this->assertEquals( 10, $filters[ self::ON_CREATE_CONTROLLER_FILTER ][ 0 ][ 'priority' ] );
    }
    
    /**
     * Test adding a filter with priority.
     */
    public function testAddFilterWithPriority( )
    {
        $filters = $this->_addTestFilter( NULL, NULL, 1 );
        
        $this->assertEquals( 1, count( $filters[ self::ON_CREATE_CONTROLLER_FILTER ] ) );
        
        $this->assertEquals( 1, $filters[ self::ON_CREATE_CONTROLLER_FILTER ][ 0 ][ 'priority' ] );
    }
    
    /**
     * Test removing a filter.
     */
    public function testRemoveFilter( )
    {
        $callback = array( $this, 'filter' );
        
        $filters = $this->_addTestFilter( self::ON_CREATE_CONTROLLER_FILTER, $callback, 1 );
        
        $this->assertEquals( 1, count( $filters[ self::ON_CREATE_CONTROLLER_FILTER ] ) );
        
        $this->assertEquals( 1, $filters[ self::ON_CREATE_CONTROLLER_FILTER ][ 0 ][ 'priority' ] );
        
        $result = $this->component->removeFilter( self::ON_CREATE_CONTROLLER_FILTER, $callback );
        
        $this->assertTrue( $result );
        
        $filters = $this->component->filters;
        
        $this->assertEquals( 0, count( $filters[ self::ON_CREATE_CONTROLLER_FILTER ] ) );
    }
    
    /**
     * Test removing an unknown/unset action.
     */
    public function testRemoveUnknownFilter( )
    {
        $this->component->addFilter( 'test_filter', array( $this, '' ) );
        
        $this->assertFalse( $this->component->removeFilter( 'unknown_filter', array( $this, '' ) ) );
    }
    
    /**
     * Test running a filter.
     */
    public function testFilter( )
    {
        $original = 'original name';
        $expected = 'original name - filtered';
        
        $this->_addTestFilter( self::ON_CREATE_CONTROLLER_FILTER );
        
        $result = $this->component->filter( self::ON_CREATE_CONTROLLER_FILTER, $original );
        
        $this->assertEquals( $expected, $result );
    }
    
    /**
     * Test running a filter with two callbacks.
     */
    public function testFilterTwice( )
    {
        $original = 'original name';
        $expected = 'original name - filtered twice';
        
        $this->_addTestFilter( self::ON_CREATE_CONTROLLER_FILTER, NULL, 1 );
        $this->_addTestFilter( self::ON_CREATE_CONTROLLER_FILTER, array( $this, 'filterSecond' ), 2 );
        
        $result = $this->component->filter( self::ON_CREATE_CONTROLLER_FILTER, $original );
        
        $this->assertEquals( $expected, $result );
    }
    
    /**
     * Test running a filter that returns false.
     */
    public function testFilterReturnsFalseResultIsAccepted( )
    {
        $original = TRUE;
        
        $this->_addTestFilter( self::ON_INIT_RESULT_FILTER, array( $this, 'filterReturnsFalse' ) );
        
        $result = $this->component->filter( self::ON_INIT_RESULT_FILTER, $original );
        
        $this->assertFalse( $result );
    }
    
    /**
     * Test setting log with filter
     */
    public function testSettingLogWithFilter( )
    {
        $this->assertNull( $this->component->log );
        
        $this->component->addFilter( Component::ON_SET_LOG_FILTER, array( $this, 'setLogCallback' ) );
        
        $this->component->init( array( ) );
        
        $this->assertNotNull( $this->component->log );
    }
    
    /**
     * Test valid index.
     * 
     * @param mixed $array  array or string
     * @param int   $index  the position index
     * @param bool  $result TRUE or FALSE
     * 
     * @dataProvider indexDataProvider
     */
    public function testValidIndex( $array, $index, $result )
    {
        $this->assertEquals( $result, Component::validIndex( $index, $array ) );
    }
    
    /**
     * Data provider for testValidIndex method.
     * 
     * @return array the test data
     */
    public function indexDataProvider( )
    {
        return array( 
            array( array( 1, 2 ), 0, TRUE ),
            array( array( 1, 2 ), 1, TRUE ),
            array( array( 1, 2 ), 2, FALSE ),
            
            array( array( 1, 2 ), -1, FALSE ),
            
            array( 'string', 0, TRUE ),
            array( 'string', 5, TRUE ),
            array( 'string', 6, FALSE ),
            array( 'string', -1, FALSE ),
            
            array( new \stdClass(), 0, FALSE ),
        );
    }
    
    /**
     * Helper method to add action.
     * 
     * @param string $action   action name
     * @param array  $callback the callback array
     * @param int    $priority the action priority
     * 
     * @return array list of current actions
     */
    private function _addTestAction( $action = NULL, $callback = NULL, $priority = 10 )
    {
        if ( NULL == $action )
        {
            $action   = self::ON_AFTER_RUN_ACTION;
        }
        if ( NULL == $callback )
        {
            $callback = array( $this, 'action' );
        }
        
        $this->component->addAction( $action, $callback, $priority );
        
        return $this->component->actions;
    }
    
    /**
     * Helper method to add filter.
     * 
     * @param string $filter   filter name
     * @param array  $callback the callback array
     * @param int    $priority the filter priority
     * 
     * @return array list of current filters
     */
    private function _addTestFilter( $filter = NULL, $callback = NULL, $priority = 10 )
    {
        if ( NULL == $filter )
        {
            $filter   = self::ON_CREATE_CONTROLLER_FILTER;
        }
        if ( NULL == $callback )
        {
            $callback = array( $this, 'filter' );
        }
        
        $this->component->addFilter( $filter, $callback, $priority );
        
        return $this->component->filters;
    }
    
    /**
     * Helper test action callback method.
     */
    public function action()
    {
        $this->_done = TRUE;
    }
    
    /**
     * Helper test filter callback method.
     * 
     * @param string $value test string value
     * 
     * @return string filtered string
     */
    public function filter( $value )
    {
        return $value . ' - filtered';
    }
    
    /**
     * Helper test filter callback method.
     * 
     * @param string $value test string value
     * 
     * @return string filtered string
     */
    public function filterSecond( $value )
    {
        return $value . ' twice';
    }
    
    /**
     * Helper class to set a log on filter callback.
     * 
     * @param object $log the log which will always be NULL
     * 
     * @return \stdClass the test log object
     */
    public function setLogCallback( $log )
    {
        return new \stdClass();
    }
    
    /**
     * Filter always returns FALSE.
     * 
     * @param bool $value a boolean value
     * 
     * @return bool FALSE
     */
    public function filterReturnsFalse( $value )
    {
        return FALSE;
    }
    
    const ON_AFTER_RUN_ACTION           = 'on_after_run_action';
    
    const ON_CREATE_CONTROLLER_FILTER   = 'on_create_controller_filter';
    const ON_INIT_RESULT_FILTER         = 'on_init_result_filter';
}