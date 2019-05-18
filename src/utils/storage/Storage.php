<?php

namespace Odin\utils\storage;

use FilesystemIterator;
use Exception;

class Storage 
{
    public static function get(string $path, bool $lock = false)
    {
        if (self::isFile($path)) {
            return $lock ? self::sharedGet($path) : file_get_contents($path);
        }
        throw new Exception("File does not exist at path {$path}");
    }
    
    public static function sharedGet(string $path)
    {
        $contents = "";
        $handle = fopen($path, "rb");
        if($handle) {
            try {
                if(flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);
                    $contents = fread($handle, self::size($path) ?: 1);
                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }
        return $contents;
    }
    
    public static function getRequire(string $path)
    {
        if (self::isFile($path)) {
            return require $path;
        }
        throw new Exception("File does not exist at path {$path}");
    }
    
    public static function requireOnce(string $file)
    {
        require_once $file;
    }
    
    public static function replace(string $path, $content)
    {
        clearstatcache(true, $path);
        $path = realpath($path) ?: $path;
        $tempPath = tempnam(dirname($path), basename($path));
        chmod($tempPath, 0777 - umask());
        file_put_contents($tempPath, $content);
        rename($tempPath, $path);
    }
    
    public static function prepend(string $path, $data)
    {
        if (self::exists($path)) {
            return self::put($path, $data.self::get($path));
        }
        return self::put($path, $data);
    }
    
    public static function append(string $path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }
    
    public static function chmod(string $path, $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }
        return substr(sprintf('%o', fileperms($path)), -4);
    }
    
    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }
    
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;
        foreach ($paths as $path) {
            try {
                if (! @unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }
        return $success;
    }
    
    public static function move(string $path, string $target)
    {
        return rename($path, $target);
    }
    
    public static function copy(string $path, string $target)
    {
        return copy($path, $target);
    }
    
    public static function exists(string $path)
    {
        return file_exists($path);
    }
    
    public static function name(string $path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }
    
    public static function basename(string $path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }
    
    public static function dirname(string $path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }
    
    public static function extension(string $path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
    
    public static function type(string $path)
    {
        return filetype($path);
    }
    
    public static function mimeType(string $path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }
    
    public static function size(string $path)
    {
        return filesize($path);
    }
    
    public static function lastModified(string $path)
    {
        return filemtime($path);
    }
    
    public static function isReadable(string $path)
    {
        return is_readable($path);
    }
    
    public static function isWritable(string $path)
    {
        return is_writable($path);
    }
    
    public static function isDirectory(string $directory)
    {
        return is_dir($directory);
    }
    
    public static function isFile(string $file)
    {
        return is_file($file);
    }
    
    public static function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }
    
    public static function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }
        return mkdir($path, $mode, $recursive);
    }
    
    public static function moveDirectory(string $from, string $to, bool $overwrite = false)
    {
        if ($overwrite && self::isDirectory($to) && ! self::deleteDirectory($to)) {
            return false;
        }
        return @rename($from, $to) === true;
    }
    
    public static function deleteDirectory(string $directory, $preserve = false)
    {
        if (! self::isDirectory($directory)) {
            return false;
        }
        $items = new FilesystemIterator($directory);
        foreach ($items as $item) {
            if ($item->isDir() && ! $item->isLink()) {
                self::deleteDirectory($item->getPathname());
            }
            else {
                self::delete($item->getPathname());
            }
        }
        if (! $preserve) {
            @rmdir($directory);
        }
        return true;
    }
    
    public static function deleteDirectories($directory)
    {
        $allDirectories = self::directories($directory);
        if (! empty($allDirectories)) {
            foreach ($allDirectories as $directoryName) {
                self::deleteDirectory($directoryName);
            }
            return true;
        }
        return false;
    }
    
    public static function cleanDirectory($directory)
    {
        return self::deleteDirectory($directory, true);
    }
    
}
