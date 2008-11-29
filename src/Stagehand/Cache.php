<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5
 *
 * Copyright (c) 2008 KUBO Atsuhiro <iteman@users.sourceforge.net>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Stagehand_Cache
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    SVN: $Id$
 * @since      File available since Release 0.1.0
 */

namespace Stagehand;

use Stagehand\Cache\Exception\PEARException;

// {{{ Stagehand\Cache

/**
 * A simple cache manager.
 *
 * @package    Stagehand_Cache
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Cache
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private $_cache;

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ __construct()

    /**
     * Creates a Cache_Lite_File or Cache_Lite object.
     *
     * @param string $cacheDirectory
     * @param string $masterFile
     */
    public function __construct($cacheDirectory, $masterFile = null)
    {
        if (substr($cacheDirectory, -1) != '/') {
            $cacheDirectory .= '/';
        }

        if (!is_null($masterFile)) {
            $this->_cache = new \Cache_Lite_File(array('cacheDir' => $cacheDirectory,
                                                       'masterFile' => $masterFile,
                                                       'automaticSerialization' => true,
                                                       'errorHandlingAPIBreak' => true)
                                                 );
        } else {
            $this->_cache = new \Cache_Lite(array('cacheDir' => $cacheDirectory,
                                                  'automaticSerialization' => true,
                                                  'errorHandlingAPIBreak' => true)
                                            );
        }
    }

    // }}}
    // {{{ read()

    /**
     * Reads the cache for the given ID.
     *
     * @param string $id
     * @return mixed
     * @throws Stagehand\Cache\Exception\PEARException
     */
    public function read($id)
    {
        \PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $subject = $this->_cache->get($id);
        \PEAR::staticPopErrorHandling();
        if (\PEAR::isError($subject)) {
            throw new PEARException($subject);
        }

        if ($subject === false) {
            return null;
        }

        return $subject;
    }

    // }}}
    // {{{ write()

    /**
     * Writes the given object as a cache.
     *
     * @param mixed $subject
     * @throws Stagehand\Cache\Exception\PEARException
     */
    public function write($subject)
    {
        \PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $result = $this->_cache->save($subject);
        \PEAR::staticPopErrorHandling();
        if (\PEAR::isError($result)) {
            throw new PEARException($result);
        }
    }

    // }}}
    // {{{ remove()

    /**
     * Removes the cache for the given ID.
     *
     * @param string $id
     */
    public function remove($id)
    {
        \PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $this->_cache->remove($id);
        \PEAR::staticPopErrorHandling();
    }

    // }}}
    // {{{ clear()

    /**
     * Clears all caches in the cache directory.
     */
    public function clear()
    {
        \PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $this->_cache->clean();
        \PEAR::staticPopErrorHandling();
    }

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
