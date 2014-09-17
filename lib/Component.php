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
 * PHP version 5.4
 * 
 * @category  PHP
 * @package   RawPHP/RawBase
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawBase;

/**
 * Base class for all other classes in RawPHP.
 * 
 * This class contains a <code>$log</code> member variable that can
 * be any type of logging class. There are two ways to add a log:
 * 
 *  1) Set the log explicitly on the variable, or
 * 
 *  2) Hook onto the Component::ON_SET_LOG_FILTER and provide a callback
 *     that will assign a log instance on calling <code>init( )</code>.
 *     This means that the filter must be set before calling <code>init( )</code>
 * 
 * @category  PHP
 * @package   RawPHP/RawBase
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Component
{
    /**
     * @var array
     */
    public $config          = NULL;
    
    public $log             = NULL;
    
    /**
     * Registered actions for component.
     * 
     * @var array list of actions
     */
    public $actions          = array( );
    
    /**
     * Registered filters for component.
     * 
     * @var array list of filters
     */
    public $filters          = array( );
    
    /**
     * Initialises the component.
     * 
     * NOTE: All components SHOULD call <code>init()</code>, even without
     * passing a value.
     * 
     * @param array $config configuration array
     * 
     * @filter ON_SET_LOG_FILTER(1)
     * 
     * @action ON_COMPONENT_INIT_ACTION
     */
    public function init( $config = NULL )
    {
        $this->config = $config;
        
        $this->log = $this->filter( self::ON_SET_LOG_FILTER, NULL );
        
        $this->doAction( self::ON_COMPONENT_INIT_ACTION );
    }
    
    /**
     * Add a callback to execute on an action.
     * 
     * @param string $action   the action name
     * @param mixed  $callback the callback [ function name, array ]
     * @param int    $priority the callback priority
     */
    public function addAction( $action, $callback, $priority = 10 )
    {
        if ( !isset( $this->actions[ $action ] ) )
        {
            $this->actions[ $action ] = array();
        }
        
        $this->actions[ $action ][] = array( 
            'priority' => $priority,
            'callback' => $callback,
        );
        
        if ( 1 < count( $this->actions[ $action ] ) )
        {
            usort( $this->actions[ $action ], array( $this, '_sortByPriority' ) );
        }
    }
    
    /**
     * Removes a callback for an action.
     * 
     * @param string $action   the action name
     * @param mixed  $callback the callback [ function name, array ]
     * 
     * @return bool TRUE on success, FALSE on failure
     */
    public function removeAction( $action, $callback )
    {
        $i = 0;
        
        foreach( $this->actions as $key => $value )
        {
            if ( $action === $key )
            {
                foreach( $value as $a => $call )
                {
                    if ( get_class( $callback[ 0 ] ) === get_class( $call[ 'callback' ][ 0 ] ) )
                    {
                        if ( $callback[ 1 ] === $call[ 'callback' ][ 1 ] )
                        {
                            unset( $this->actions[ $key ][ $i ] );
                            
                            return TRUE;
                        }
                    }
                }
            }
            
            $i++;
        }
        
        return FALSE;
    }
    
    /**
     * Executes callbacks for an action.
     * 
     * @param string $action action name
     * @param array  $params callback parameters
     */
    public function doAction( $action, $params = array() )
    {
        if ( isset( $this->actions[ $action ] ) )
        {
            foreach( $this->actions[ $action ] as $callback )
            {
                call_user_func_array( $callback[ 'callback' ], $params );
            }
        }
    }
    
    /**
     * Add a callback to execute to filter content.
     * 
     * @param string $filter   the filter name
     * @param mixed  $callback the callback [ function name, array ]
     * @param int    $priority the callback priority
     */
    public function addFilter( $filter, $callback, $priority = 10 )
    {
        if ( !isset( $this->filters[ $filter ] ) )
        {
            $this->filters[ $filter ] = array();
        }
        
        $this->filters[ $filter ][] = array( 
            'priority' => $priority,
            'callback' => $callback,
        );
        
        if ( 1 < count( $this->filters[ $filter ] ) )
        {
            usort( $this->filters[ $filter ], array( $this, '_sortByPriority' ) );
        }
    }
    
    /**
     * Removes a callback for a filter.
     * 
     * @param string $filter   the filter name
     * @param mixed  $callback the callback [ function name, array ]
     * 
     * @return bool TRUE on success, FALSE on failure
     */
    public function removeFilter( $filter, $callback )
    {
        $i = 0;
        
        foreach( $this->filters as $key => $value )
        {
            if ( $filter === $key )
            {
                foreach( $value as $f => $call )
                {
                    if ( get_class( $callback[ 0 ] ) === get_class( $call[ 'callback' ][ 0 ] ) )
                    {
                        if ( $callback[ 1 ] === $call[ 'callback' ][ 1 ] )
                        {
                            unset( $this->filters[ $key ][ $i ] );
                            
                            return TRUE;
                        }
                    }
                }
            }
            
            $i++;
        }
        
        return FALSE;
    }
    
    /**
     * Executes callbacks for a filter and returns the result.
     * 
     * @param string $filter filter name
     * @param mixed  $params callback
     * 
     * @return mixed the filtered content
     */
    public function filter( $filter, $params )
    {
        $argList = func_get_args( );
        
        array_shift( $argList );
        
        if ( isset( $this->filters[ $filter ] ) )
        {
            foreach( $this->filters[ $filter ] as $callback )
            {
                $argList[ 0 ] = call_user_func_array( $callback[ 'callback' ], $argList );
            }
        }
        
        return $argList[ 0 ];
    }
    
    /**
     * Compares callback parameters.
     * 
     * @param array $a first callback
     * @param array $b second callback
     * 
     * @return int 0 if equal, 1 if $a is greater then $b, else -1
     */
    private function _sortByPriority( $a, $b )
    {
        if ( $a[ 'priority' ] > $b[ 'priority' ] )
        {
            return 1;
        }
        else
        {
            return -1;
        }
        
        return 0;
    }
    
    /**
     * Checks whether the value index position is valid.
     * 
     * Returns FALSE if an object or resource is passed in.
     * 
     * @param int   $index the array position to check
     * @param mixed $value array or string
     * 
     * @return bool TRUE if valid index, else FALSE
     */
    public static function validIndex( $index, $value )
    {
        if ( is_array( $value ) )
        {
            return ( count( $value ) > $index );
        }
        elseif ( is_string( $value ) )
        {
            return ( strlen( $value ) > $index );
        }
        
        return FALSE;
    }
    
    /**
     * Prints an array|object in formatted format.
     * 
     * @param mixed $array array or object to print
     */
    public static function arrayDump( $array )
    {
        echo '<pre>';
        print_r( $array );
        echo '</pre>';
    }
    
    /**
     * Prints an array|object in formatted format with types.
     * 
     * @param mixed $object array or object to print
     */
    public static function objectDump( $object )
    {
        echo '<pre>';
        var_dump( $object );
        echo '</pre>';
    }
    
    // actions
    const ON_COMPONENT_INIT_ACTION  = 'on_component_init_action';
    
    // filters
    const ON_SET_LOG_FILTER         = 'on_set_log_filter';
}
