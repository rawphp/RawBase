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

use RawPHP\RawBase\Component;

/**
 * Base class for all models.
 * 
 * @category  PHP
 * @package   RawPHP/RawBase
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Model extends Component
{
    public $id = 0;
    
    /**
     * Model constructor.
     * 
     * @param array $config configuration array
     */
    public function __construct( $config = array() )
    {
        $this->init( $config );
    }
    
    /**
     * Initialises the model.
     * 
     * Do not call <code>parent::init()</code> on the model. It is already
     * being called by the constructor.
     * 
     * @param array $config configuration array
     * 
     * @action ON_MODEL_INIT_ACTION
     */
    public function init( $config = NULL )
    {
        parent::init( $config );
        
        if ( isset( $config[ 'id' ] ) )
        {
            $this->id = ( int )$config[ 'id' ];
        }
        
        $this->doAction( self::ON_MODEL_INIT_ACTION );
    }
    
    const ON_MODEL_INIT_ACTION = 'on_init_model_action';
}