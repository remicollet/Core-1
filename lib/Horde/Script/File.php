<?php
/**
 * This class represents a javascript script file to output to the browser.
 *
 * Copyright 2012 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Michael Slusarz <slusarz@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Core
 *
 * @property string $app
 * @property string $file
 * @property string $full_path
 * @property string $hash  Hash value of this file.
 * @property integer $modified  Last modification time of the file.
 * @property string $path  Full filesystem path to script.
 * @property string $tag
 * @property string $tag_full
 * @property Horde_Url $url  URL to script.
 * @property Horde_Url $url_full  Full URL to script.
 */
class Horde_Script_File
{
    /** Priority constants. */
    const PRIORITY_VERYHIGH = 1; // Reserved for JS framework-level scripts
    const PRIORITY_HIGH = 2;
    const PRIORITY_NORMAL = 3;
    const PRIORITY_LOW = 4;
    const PRIORITY_VERYLOW = 5;

    /**
     * The cache group this file should be output in.
     *
     * @var string
     */
    public $cache = 'default';

    /**
     * Javascript variables that should be output to the page.
     *
     * @var array
     */
    public $jsvars = array();

    /**
     * Priority.
     *
     * @var integer
     */
    public $priority = self::PRIORITY_LOW;

    /**
     * Application.
     *
     * @var string
     */
    protected $_app;

    /**
     * Filename.
     *
     * @var string
     */
    protected $_file;

    /**
     * Adds a single javascript script to the output (if output has already
     * started), or to the list of script files to include in the output.
     *
     * @param string $file  The full javascript file name.
     * @param string $app   The application name. Defaults to the current
     *                      application.
     */
    public function __construct($file, $app = null)
    {
        $this->_app = is_null($app)
            ? $GLOBALS['registry']->getApp()
            : $app;
        $this->_file = $file;
    }

    /**
     */
    public function __get($name)
    {
        switch ($name) {
        case' app':
            return $this->_app;

        case 'file':
            return $this->_file;

        case 'full_path':
            return $this->path . $this->_file;

        case 'hash':
            return hash('sha1', $this->_app . "\0" . $this->_file);

        case 'modified':
            return filemtime($this->full_path);

        case 'path':
            return '/';

        case 'tag':
        case 'tag_full':
            return '<script type="text/javascript" src="' .
                (($name == 'tag') ? $this->url : $this->url_full) .
                '"></script>';

        case 'url':
        case 'url_full':
            return Horde::url('/' . $this->_file, ($name == 'url_full'), -1);
        }
    }

    /**
     */
    public function __toString()
    {
        return $this->tag;
    }

}
