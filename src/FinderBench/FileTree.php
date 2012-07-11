<?php

namespace FinderBench;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class FileTree
{
    const NAME_CHARS = 'abcdefghijklmnopqrstuvwxyz0123456789';

    private $root;
    private $depth;
    private $range;
    private $syst;

    public function __construct($root, $size, $depth)
    {
        $this->root  = $root;
        $this->depth = min($depth, strlen(self::NAME_CHARS));
        $this->range = substr(self::NAME_CHARS, 0, min($size, strlen(self::NAME_CHARS)));
        $this->syst  = new Filesystem();
    }

    public function build($dir = '', $depth = 0, $prefix = '')
    {
        $this->syst->mkdir($this->root.DIRECTORY_SEPARATOR.$dir);
        $this->syst->touch(array_map(function ($char) use ($prefix) { return $prefix.$char; }, $this->range));

        if ($depth < $this->depth) {
            foreach ((array) $this->range as $char) {
                $this->build($dir.DIRECTORY_SEPARATOR.$prefix.$char, $depth+1, $prefix.$char);
            }
        }

        return $this;
    }

    public function remove()
    {
        $this->syst->remove($this->root);

        return $this;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getFilesCount()
    {
        $size = count($this->range);

        return $size + pow($size, $this->depth);
    }

    public function getDirsCount()
    {
        if (0 === $this->depth) {
            return 0;
        }

        $size = count($this->range);

        return $size + ($this->depth > 1 ? pow($size, $this->depth - 1) : 0);
    }
}
