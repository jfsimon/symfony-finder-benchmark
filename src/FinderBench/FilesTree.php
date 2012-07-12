<?php

namespace FinderBench;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class FilesTree
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
        $this->range = str_split(substr(self::NAME_CHARS, 0, min($size, strlen(self::NAME_CHARS))));
        $this->syst  = new Filesystem();
    }

    public function build($root = '', $depth = 0, $prefix = '')
    {
        $root = $root ?: $this->root;

        $this->syst->mkdir($root);
        $this->syst->touch(array_map(function ($char) use ($root, $prefix) {
            return $root.DIRECTORY_SEPARATOR.$prefix.$char.'.file';
        }, $this->range));

        if ($depth < $this->depth) {
            foreach ($this->range as $char) {
                $this->build($root.DIRECTORY_SEPARATOR.$prefix.$char, $depth+1, $prefix.$char);
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
