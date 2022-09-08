<?php

namespace App\Traits;

use App\Helpers\Resizer;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File as FileBase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File as FileObj;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Attachable
{
    public static $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

    /**
     * @var mixed data is a local file name or an instance of an uploaded file,
     * objects of the UploadedFile class.
     */
    public $data = null;

    /**
     * @var array autoMimeTypes
     */
    protected $autoMimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $appends = ['url', 'path', 'extension'];
        $this->appends = array_merge($this->appends, $appends);
    }

    /**
     * fromPost creates a file object from a file an uploaded file
     *
     * @param  UploadedFile  $uploadedFile
     * @return $this
     */
    public function fromPost($uploadedFile)
    {
        if ($uploadedFile === null) {
            return;
        }

        $this->file_name = $uploadedFile->getClientOriginalName();
        $this->file_size = $uploadedFile->getSize();
        $this->content_type = $uploadedFile->getMimeType();
        $this->disk_name = $this->getDiskName();

        // getRealPath() can be empty for some environments (IIS)
        $realPath = empty(trim($uploadedFile->getRealPath()))
            ? $uploadedFile->getPath().DIRECTORY_SEPARATOR.$uploadedFile->getFileName()
            : $uploadedFile->getRealPath();

        $this->putFile($realPath, $this->disk_name);

        return $this;
    }

    /**
     * fromFile creates a file object from a file on the disk
     *
     * @param  string  $filePath
     * @param  string  $filename
     * @return $this
     */
    public function fromFile($filePath, $filename = null)
    {
        if ($filePath === null) {
            return;
        }

        $file = new FileObj($filePath);
        $this->file_name = empty($filename) ? $file->getFilename() : $filename;
        $this->file_size = $file->getSize();
        $this->content_type = $file->getMimeType();
        $this->disk_name = $this->getDiskName();

        $this->putFile($file->getRealPath(), $this->disk_name);

        return $this;
    }

    /**
     * fromData creates a file object from raw data
     *
     * @param  string  $data
     * @param  string  $filename
     */
    public function fromData($data, $filename)
    {
        if ($data === null) {
            return;
        }

        $tempName = str_replace('.', '', uniqid('', true)).'.tmp';
        $tempPath = storage_path('app/temp/'.$tempName);
        FileBase::put($tempPath, $data);

        $file = $this->fromFile($tempPath, basename($filename));
        FileBase::delete($tempPath);

        return $file;
    }

    /**
     * fromUrl creates a file object from url
     *
     * @param  string  $url
     * @param  string  $filename
     * @return self
     */
    public function fromUrl($url, $filename = null)
    {
        $data = Http::get($url);

        if ($data->status() !== 200) {
            throw new Exception(sprintf('Error getting file "%s", error code: %d', $url, $data->status()));
        }

        if (empty($filename)) {
            $filename = FileBase::basename($url);
        }

        return $this->fromData($data->body(), $filename);
    }

    /**
     * getUrlAttribute helper attribute for getPath
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if ($this->isLocalStorage()) {
            return asset('storage/app'.$this->getPath());
        } else {
            return $this->getDisk()->url($this->getPath());
        }
        // return url('storage/app' . $this->getPath());
    }

    /**
     * getPathAttribute helper attribute for getPath
     *
     * @return string
     */
    public function getPathAttribute()
    {
        return $this->getPath();
    }

    /**
     * getExtensionAttribute helper attribute for getExtension
     *
     * @return string
     */
    public function getExtensionAttribute()
    {
        return $this->getExtension();
    }

    /**
     * setDataAttribute used only when filling attributes
     */
    public function setDataAttribute($value)
    {
        $this->data = $value;
    }

    /**
     * getWidthAttribute helper attribute for get image width
     *
     * @return string
     */
    public function getWidthAttribute()
    {
        if ($this->isImage()) {
            $dimensions = $this->getImageDimensions();

            return $dimensions[0];
        }
    }

    /**
     * getHeightAttribute helper attribute for get image height
     *
     * @return string
     */
    public function getHeightAttribute()
    {
        if ($this->isImage()) {
            $dimensions = $this->getImageDimensions();

            return $dimensions[1];
        }
    }

    /**
     * getSizeAttribute helper attribute for file size in human format
     *
     * @return string
     */
    public function getSizeAttribute()
    {
        return $this->sizeToString();
    }

    /**
     * output the raw file contents
     *
     * @param  string  $disposition The Content-Disposition to set, defaults to inline
     * @param  bool  $returnResponse
     * @return Response|void
     */
    public function output($disposition = 'inline', $returnResponse = false)
    {
        $response = Response::make($this->getContents())->withHeaders([
            'Content-type' => $this->getContentType(),
            'Content-Disposition' => $disposition.'; filename="'.$this->file_name.'"',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0',
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $this->file_size,
        ]);

        if ($returnResponse) {
            return $response;
        }

        $response->sendHeaders();
        $response->sendContent();
    }

    // Getters

    public function getCacheKey($path = null)
    {
        if (empty($path)) {
            $path = $this->getDiskPath();
        }

        return 'database-file::'.$path;
    }

    public function getFilename()
    {
        return $this->file_name;
    }

    public function getExtension()
    {
        return FileBase::extension($this->file_name);
    }

    public function getLastModified($fileName = null)
    {
        return $this->storageCmd('lastModified', $this->getDiskPath($fileName));
    }

    public function getContentType()
    {
        if ($this->content_type !== null) {
            return $this->content_type;
        }

        $ext = $this->getExtension();
        if (isset($this->autoMimeTypes[$ext])) {
            return $this->content_type = $this->autoMimeTypes[$ext];
        }

        return null;
    }

    public function getContents($fileName = null)
    {
        return $this->storageCmd('get', $this->getDiskPath($fileName));
    }

    public function getPath($fileName = null)
    {
        if (empty($fileName)) {
            $fileName = $this->disk_name;
        }

        return $this->getPublicPath().$this->getPartitionDirectory().$fileName;
    }

    public function getLocalPath()
    {
        if ($this->isLocalStorage()) {
            return $this->getLocalRootPath().'/'.$this->getDiskPath();
        }

        $itemSignature = md5($this->getPath()).$this->getLastModified();

        $cachePath = $this->getLocalTempPath($itemSignature.'.'.$this->getExtension());

        if (! FileBase::exists($cachePath)) {
            $this->copyStorageToLocal($this->getDiskPath(), $cachePath);
        }

        return $cachePath;
    }

    public function getDiskPath($fileName = null)
    {
        if (empty($fileName)) {
            $fileName = $this->disk_name;
        }

        return $this->getStorageDirectory().$this->getPartitionDirectory().$fileName;
    }

    public function isPublic()
    {
        if (array_key_exists('is_public', $this->attributes)) {
            return $this->attributes['is_public'];
        }

        if (isset($this->is_public)) {
            return $this->is_public;
        }

        return true;
    }

    public function sizeToString()
    {
        return FileBase::sizeToString($this->file_size);
    }

    //
    // Events
    //

    /**
     * beforeSave check if new file data has been supplied
     * eg: $model->data = Input::file('something');
     */
    public function beforeSave()
    {
        /*
         * Process the data property
         */
        if ($this->data !== null) {
            if ($this->data instanceof UploadedFile) {
                $this->fromPost($this->data);
            } else {
                $this->fromFile($this->data);
            }

            $this->data = null;
        }
    }

    public function delete()
    {
        try {
            $this->deleteThumbs();
            $this->deleteFile();
        } catch (\Exception $e) {
            Log::error($e);

            return false;
        }

        return parent::delete();
    }

    //
    // Image handling
    //

    /**
     * isImage checks if the file extension is an image and returns true or false
     */
    public function isImage()
    {
        return in_array(strtolower($this->getExtension()), static::$imageExtensions);
    }

    /**
     * getImageDimensions
     *
     * @return array|bool
     */
    protected function getImageDimensions()
    {
        return getimagesize($this->getLocalPath());
    }

    /**
     * getThumb generates and returns a thumbnail path
     *
     * @param  int  $width
     * @param  int  $height
     * @param  array  $options [
     *     'mode' => 'auto',
     *     'offset' => [0, 0],
     *     'quality' => 90,
     *     'sharpen' => 0,
     *     'interlace' => false,
     *     'extension' => 'auto',
     * ]
     * @return string The URL to the generated thumbnail
     */
    public function getThumb($width, $height, $options = [])
    {
        if (! $this->isImage() || ! $this->hasFile($this->disk_name)) {
            return $this->getPath();
        }

        $width = (int) $width;
        $height = (int) $height;

        $options = $this->getDefaultThumbOptions($options);

        $thumbFile = $this->getThumbFilename($width, $height, $options);
        $thumbPath = $this->getDiskPath($thumbFile);
        $thumbPublic = $this->getPath($thumbFile);

        if (! $this->hasFile($thumbFile)) {
            try {
                if ($this->isLocalStorage()) {
                    $this->makeThumbLocal($thumbFile, $thumbPath, $width, $height, $options);
                } else {
                    $this->makeThumbStorage($thumbFile, $thumbPath, $width, $height, $options);
                }
            } catch (Exception $ex) {
                Log::error($ex);

                return '';
            }
        }

        return $thumbPublic;
    }

    public function getThumbUrl($width, $height, $options = [])
    {
        $path = $this->getThumb($width, $height, $options);
        if ($this->isLocalStorage()) {
            return asset($path);
        } else {
            return $this->getDisk()->url($path);
        }
    }

    /**
     * getThumbFilename generates a thumbnail filename
     *
     * @return string
     */
    public function getThumbFilename($width, $height, $options)
    {
        $options = $this->getDefaultThumbOptions($options);

        return 'thumb_'.$this->id.'_'.$width.'_'.$height.'_'.$options['offset'][0].'_'.$options['offset'][1].'_'.$options['mode'].'.'.$options['extension'];
    }

    /**
     * getDefaultThumbOptions returns the default thumbnail options
     *
     * @return array
     */
    protected function getDefaultThumbOptions($overrideOptions = [])
    {
        $defaultOptions = [
            'mode' => 'auto',
            'offset' => [0, 0],
            'quality' => 90,
            'sharpen' => 0,
            'interlace' => false,
            'extension' => 'auto',
        ];

        if (! is_array($overrideOptions)) {
            $overrideOptions = ['mode' => $overrideOptions];
        }

        $options = array_merge($defaultOptions, $overrideOptions);

        $options['mode'] = strtolower($options['mode']);

        if (strtolower($options['extension']) === 'auto') {
            $options['extension'] = strtolower($this->getExtension());
        }

        return $options;
    }

    /**
     * makeThumbLocal generates the thumbnail based on the local file system. This step
     * is necessary to simplify things and ensure the correct file permissions are given
     * to the local files.
     */
    protected function makeThumbLocal($thumbFile, $thumbPath, $width, $height, $options)
    {
        $rootPath = $this->getLocalRootPath();
        $filePath = $rootPath.'/'.$this->getDiskPath();
        $thumbPath = $rootPath.'/'.$thumbPath;

        /*
         * Generate thumbnail
         */
        Resizer::open($filePath)
            ->resize($width, $height, $options)
            ->save($thumbPath);

        FileBase::chmod($thumbPath);
    }

    /**
     * makeThumbStorage generates the thumbnail based on a remote storage engine
     */
    protected function makeThumbStorage($thumbFile, $thumbPath, $width, $height, $options)
    {
        $tempFile = $this->getLocalTempPath();
        $tempThumb = $this->getLocalTempPath($thumbFile);

        // Generate thumbnail
        $this->copyStorageToLocal($this->getDiskPath(), $tempFile);

        try {
            Resizer::open($tempFile)
                ->resize($width, $height, $options)
                ->save($tempThumb);
        } finally {
            FileBase::delete($tempFile);
        }

        // Publish to storage
        $success = $this->copyLocalToStorage($tempThumb, $thumbPath);

        // Clean up
        FileBase::delete($tempThumb);

        // Eagerly cache remote exists call
        if ($success) {
            Cache::forever($this->getCacheKey($thumbPath), true);
        }
    }

    /**
     * deleteThumbs deletes all thumbnails for this file
     */
    public function deleteThumbs()
    {
        $pattern = 'thumb_'.$this->id.'_';

        $directory = $this->getStorageDirectory().$this->getPartitionDirectory();
        $allFiles = $this->storageCmd('files', $directory);
        $collection = [];
        foreach ($allFiles as $file) {
            if (Str::startsWith(basename($file), $pattern)) {
                $collection[] = $file;
            }
        }

        // Delete the collection of files
        if (! empty($collection)) {
            if ($this->isLocalStorage()) {
                FileBase::delete($collection);
            } else {
                $this->getDisk()->delete($collection);

                foreach ($collection as $filePath) {
                    Cache::forget($this->getCacheKey($filePath));
                }
            }
        }
    }

    //
    // File handling
    //

    /**
     * getDiskName generates a disk name from the supplied file name
     */
    protected function getDiskName()
    {
        if ($this->disk_name !== null) {
            return $this->disk_name;
        }

        $ext = strtolower($this->getExtension());
        $name = str_replace('.', '', uniqid('', true));

        return $this->disk_name = ! empty($ext) ? $name.'.'.$ext : $name;
    }

    /**
     * getLocalTempPath returns a temporary local path to work from
     */
    protected function getLocalTempPath($path = null)
    {
        if (! $path) {
            return $this->getTempPath().'/'.md5($this->getDiskPath()).'.'.$this->getExtension();
        }

        return $this->getTempPath().'/'.$path;
    }

    /**
     * putFile saves a file
     *
     * @param  string  $sourcePath An absolute local path to a file name to read from.
     * @param  string  $destinationFileName A storage file name to save to.
     */
    protected function putFile($sourcePath, $destinationFileName = null)
    {
        if (! $destinationFileName) {
            $destinationFileName = $this->disk_name;
        }

        $destinationPath = $this->getStorageDirectory().$this->getPartitionDirectory();

        if (! $this->isLocalStorage()) {
            return $this->copyLocalToStorage($sourcePath, $destinationPath.$destinationFileName);
        }

        // Using local storage, tack on the root path and work locally
        // this will ensure the correct permissions are used.
        $destinationPath = $this->getLocalRootPath().'/'.$destinationPath;

        // Verify the directory exists, if not try to create it. If creation fails
        // because the directory was created by a concurrent process then proceed,
        // otherwise trigger the error.
        if (
            ! FileBase::isDirectory($destinationPath) &&
            ! FileBase::makeDirectory($destinationPath, 0755, true, true) &&
            ! FileBase::isDirectory($destinationPath)
        ) {
            if (($lastErr = error_get_last()) !== null) {
                trigger_error($lastErr['message'], E_USER_WARNING);
            }
        }

        return FileBase::copy($sourcePath, $destinationPath.$destinationFileName);
    }

    /**
     * deleteFile contents from storage device
     */
    protected function deleteFile($fileName = null)
    {
        if (! $fileName) {
            $fileName = $this->disk_name;
        }

        $directory = $this->getStorageDirectory().$this->getPartitionDirectory();
        $filePath = $directory.$fileName;

        if ($this->storageCmd('exists', $filePath)) {
            $this->storageCmd('delete', $filePath);
        }

        // Clear remote storage cache
        if (! $this->isLocalStorage()) {
            Cache::forget($this->getCacheKey($filePath));
        }

        $this->deleteEmptyDirectory($directory);
    }

    /**
     * hasFile checks file exists on storage device
     */
    protected function hasFile($fileName = null)
    {
        $filePath = $this->getDiskPath($fileName);

        if ($this->isLocalStorage()) {
            return $this->storageCmd('exists', $filePath);
        }

        // Cache remote storage results for performance increase
        $result = Cache::rememberForever($this->getCacheKey($filePath), function () use ($filePath) {
            return $this->storageCmd('exists', $filePath);
        });

        return $result;
    }

    /**
     * deleteEmptyDirectory checks if directory is empty then deletes it,
     * three levels up to match the partition directory.
     */
    protected function deleteEmptyDirectory($dir = null)
    {
        if (! $this->isDirectoryEmpty($dir)) {
            return;
        }

        $this->storageCmd('deleteDirectory', $dir);

        $dir = dirname($dir);
        if (! $this->isDirectoryEmpty($dir)) {
            return;
        }

        $this->storageCmd('deleteDirectory', $dir);

        $dir = dirname($dir);
        if (! $this->isDirectoryEmpty($dir)) {
            return;
        }

        $this->storageCmd('deleteDirectory', $dir);
    }

    /**
     * isDirectoryEmpty returns true if a directory contains no files
     */
    protected function isDirectoryEmpty($dir)
    {
        if (! $dir) {
            return null;
        }

        return count($this->storageCmd('allFiles', $dir)) === 0;
    }

    //
    // Storage interface
    //

    /**
     * storageCmd calls a method against File or Storage depending on local storage
     * This allows local storage outside the storage/app folder and is
     * also good for performance. For local storage, *every* argument
     * is prefixed with the local root path. Props to Laravel for
     * the unified interface.
     */
    protected function storageCmd()
    {
        $args = func_get_args();
        $command = array_shift($args);
        $result = null;

        if ($this->isLocalStorage()) {
            $interface = 'File';
            $path = $this->getLocalRootPath();
            $args = array_map(function ($value) use ($path) {
                return $path.'/'.$value;
            }, $args);

            $result = forward_static_call_array([$interface, $command], $args);
        } else {
            $result = call_user_func_array([$this->getDisk(), $command], $args);
        }

        return $result;
    }

    /**
     * copyStorageToLocal file
     */
    protected function copyStorageToLocal($storagePath, $localPath)
    {
        return FileBase::put($localPath, $this->getDisk()->get($storagePath));
    }

    /**
     * copyLocalToStorage file
     */
    protected function copyLocalToStorage($localPath, $storagePath)
    {
        return $this->isPublic()
        ? $this->getDisk()->put($storagePath, FileBase::get($localPath))
        : $this->getDisk()->put($storagePath, FileBase::get($localPath), 'public');
    }

    //
    // Configuration
    //

    /**
     * getStorageDirectory defines the internal storage path, override this method
     */
    public function getStorageDirectory()
    {
        $root = '/';
        $environment = App::environment();
        if (App::environment() != 'production') {
            $root .= $environment;
        }

        return $this->isPublic() ? $root.'/public/' : $root.'/storage/';
    }

    /**
     * getPublicPath returns the public address for the storage path
     */
    public function getPublicPath()
    {
        return $this->getStorageDirectory();
    }

    /**
     * getTempPath defines the internal working path, override this method
     */
    public function getTempPath()
    {
        $path = storage_path('app/temp/uploads');

        if (! FileBase::isDirectory($path)) {
            FileBase::makeDirectory($path, 0755, true, true);
        }

        return $path;
    }

    /**
     * getDisk returns the storage disk the file is stored on
     *
     * @return FilesystemAdapter
     */
    public function getDisk()
    {
        return Storage::disk();
    }

    /**
     * isLocalStorage returns true if the storage engine is local
     */
    protected function isLocalStorage()
    {
        return Storage::getDefaultDriver() === 'local';
    }

    /**
     * getPartitionDirectory generates a partition for the file
     * return /ABC/DE1/234 for an name of ABCDE1234.
     *
     * @param  Attachable  $attachable
     * @param  string  $styleName
     * @return mixed
     */
    protected function getPartitionDirectory()
    {
        return implode('/', array_slice(str_split($this->disk_name, 3), 0, 3)).'/';
    }

    /**
     * getLocalRootPath if working with local storage, determine the absolute local path
     */
    protected function getLocalRootPath()
    {
        return storage_path().'/app';
    }
}
